<?php

namespace App\Logics\Baidu;

use App\Logics\Base;
use App\Utils\Curl;
use App\Utils\Log;
use App\Utils\Redis;
use Yi\Baidu\Application;

class TiebaClient extends Base
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
        $client = static::getInstance($bdUss, $nickName);

        $tiebas = $client->user->flushTiebas();
        $redis_key = 'tieba:sign:' . date('Ymd');
        $message = '';
        // $length = 50; // 一次签到贴吧数
        foreach ($tiebas as $tieba) {
            Log::info("贴吧：{$tieba->tieba->name}");
            if (!Redis::sismember($redis_key, $tieba->tieba->fid)) {
                // 贴吧签到
                $res = $tieba->sign();
                $message .= "> 贴吧：{$tieba->tieba->name}";
                if ($res['no'] == 0 || $res['no'] == 1101) {
                    $message .= " 签到成功！\n\n";
                    Redis::sadd($redis_key, $tieba->tieba->fid);
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
}

