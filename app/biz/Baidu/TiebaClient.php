<?php

namespace App\Biz\Baidu;

use App\Core\Support\CacheBase;
use App\Models\BaiduTieba;
use App\Utils\Curl;
use App\Utils\Log;
use App\Utils\Redis;
use Yi\Baidu\Application;

class TiebaClient extends CacheBase
{
    public static $_instance = [];

    public static function getInstance($bdUss, $nickName)
    {
        $key = md5($bdUss . '|' . $nickName);
        if (isset(static::$_instance[$key]) && static::$_instance[$key] instanceof TiebaClient) {
            return static::$_instance[$key];
        }

        return static::$_instance[$key] = new Application([
            'bduss' => $bdUss,
            'nickname' => $nickName
        ]);
    }

    /**
     * @desc   签到
     * @author limx
     */
    public static function sign($bdUss, $nickName)
    {
        $tiebas = static::tiebas($bdUss, $nickName);
        $redis_key = 'tieba:sign:' . date('Ymd');
        $message = '';
        // $length = 50; // 一次签到贴吧数
        foreach ($tiebas as $tieba) {
            $fid = $tieba->tieba->fid;
            $name = $tieba->tieba->name;
            $avatar = $tieba->avatar;
            $favo_type = $tieba->favo_type;
            $cur_score = $tieba->cur_score;
            $level_id = $tieba->level_id;
            $level_name = $tieba->level_name;
            $levelup_score = $tieba->levelup_score;
            $slogan = $tieba->slogan;


            Log::info("贴吧：{$name}");
            $model = BaiduTieba::findFirst([
                'conditions' => 'nickname = ?0 AND fid=?1',
                'bind' => [$nickName, $fid],
            ]);
            if (empty($model)) {
                $model = new BaiduTieba();
            }
            $model->fid = $fid;
            $model->nickname = $nickName;
            $model->name = $name;
            $model->avatar = $avatar;
            $model->favo_type = $favo_type;
            $model->cur_score = $cur_score;
            $model->level_id = $level_id;
            $model->level_name = $level_name;
            $model->levelup_score = $levelup_score;
            $model->slogan = $slogan;
            $model->save();

            if (!Redis::sismember($redis_key, $fid)) {
                // 贴吧签到
                $res = $tieba->sign();
                $message .= "> 贴吧：{$name}";
                if ($res['no'] == 0 || $res['no'] == 1101) {
                    $message .= " 签到成功！\n\n";
                    Redis::sadd($redis_key, $fid);
                } else {
                    $message .= " 签到失败！\n\n";
                }
            }
        }

        if (strlen($message) > 0) {
            Redis::expire($redis_key, 3600 * 24);
            $params = [
                "msgtype" => "markdown",
                "markdown" => [
                    "title" => "Baidu贴吧签到",
                    "text" => "### 收到Baidu贴吧签到事件推送 \n" . $message,
                ],
                "at" => [
                    "atMobiles" => [
                        "18678017521",
                    ],
                    "isAtAll" => false
                ]
            ];

            $url = env('DING_TALK');
            $result = Curl::json($url, $params);
        }

        return true;
    }

    public static function tiebas($bdUss, $nickName)
    {
        $client = static::getInstance($bdUss, $nickName);
        $tiebas = $client->user->flushTiebas();

        return $tiebas;
    }
}

