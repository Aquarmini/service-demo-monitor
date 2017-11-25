<?php

namespace App\Biz\Github;

use App\Core\Support\CacheBase;
use App\Models\CommitsLog;
use App\Models\Followers;
use App\Utils\Curl;
use App\Utils\Log;
use App\Utils\Redis;

class Commits extends CacheBase
{
    /**
     * @desc   获取commit日志
     * @author limx
     * @param $username
     * @param $btime
     * @param $etime
     */
    public static function getCommitsLogs($username, $btime, $etime)
    {
        $res = CommitsLog::find([
            'conditions' => 'username = ?0 AND created_at > ?1 AND created_at < ?2',
            'bind' => [$username, $btime, $etime],
        ]);
        $result = [];
        foreach ($res as $item) {
            $obj = new \Xin\Thrift\MonitorService\CommitsLog();
            $obj->id = $item->id;
            $obj->username = $item->username;
            $obj->commits = $item->commits;
            $result[] = $obj;
        }
        return $result;
    }

    /**
     * @desc   获取commits
     * @author limx
     * @param $committer
     * @param $date
     * @param $token
     */
    public static function search($committer, $date, $token)
    {
        $api = '/search/commits';
        $params = [
            'q' => "committer-date:>={$date} committer:{$committer}",
            'sort' => 'committer-date',
        ];

        $res = Curl::httpGet($api, $params, $token);

        return $res;
    }

    /**
     * @desc   获取commit次数
     * @author limx
     * @param $committer
     * @param $date
     * @param $token
     * @return int
     */
    public static function count($committer, $date, $token)
    {
        $res = static::search($committer, $date, $token);
        $count = 0;
        if (isset($res['total_count'])) {
            $count = $res['total_count'];
        }
        Log::info('commits:committer=' . $committer . ',count=' . $count);
        return $count;
    }

    /**
     * @desc   当commit增加，推送消息
     * @author limx
     */
    public static function send($committer, $token)
    {

        $date = date('Y-m-d', time() - 8 * 3600);
        $redis_key = sprintf('github:commits:count:%s:%s', $committer, $date);

        $count = static::count($committer, $date, $token);

        $current_count = Redis::get($redis_key) ?? 0;
        $current_count = intval($current_count);
        if ($count <= $current_count) {
            return false;
        }

        // 检测到commits有所增加 发送钉钉消息
        Log::info('commits:committer=' . $committer . ',count=' . $current_count . '->' . $count);
        $now = date('Y-m-d H:i:s');
        $params = [
            "msgtype" => "markdown",
            "markdown" => [
                "title" => "收到Github的新事件推送",
                "text" => "### 收到Github的新事件推送\n" .
                    "> 今日{$committer}当前commit提交数为 {$count}\n\n" .
                    "> 时间：{$now}\n"
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
        if ($result['errcode'] == 0 && $result['errmsg'] == "ok") {
            Redis::set($redis_key, $count);
            Redis::expire($redis_key, 3600 * 24);

            $model = new CommitsLog();
            $model->commits = $count;
            $model->username = $committer;
            return $model->save();
        }

        return false;
    }

    public static function sendFollowingCommits($username, $token)
    {
        $follows = Followers::find([
            'conditions' => 'login=?0',
            'bind' => [$username],
        ]);

        $result = [];
        $date = date('Y-m-d', time() - 8 * 3600);
        foreach ($follows as $committer) {
            $redis_key = sprintf('github:commits:count:%s:%s', $committer->username, $date);
            $current_count = Redis::get($redis_key) ?? 0;
            $current_count = intval($current_count);
            $count = static::count($committer->username, $date, $token);
            if ($count <= $current_count) {
                continue;
            }

            $item = [];
            $item['username'] = $committer->username;
            $item['count'] = $count;

            $result[] = $item;
        }

        if (count($result) === 0) {
            return true;
        }

        $message = '';
        foreach ($result as $item) {
            $committer = $item['username'];
            $count = $item['count'];

            // 检测到commits有所增加 发送钉钉消息
            Log::info('commits:committer=' . $committer . ',count=' . $count);
            $message .= "> 今日{$committer}当前commit提交数为 {$count}\n\n";
        }

        $now = date('Y-m-d H:i:s');
        $params = [
            "msgtype" => "markdown",
            "markdown" => [
                "title" => "收到Github的新事件推送",
                "text" => "### 收到Github的新事件推送\n" .
                    $message .
                    "> 时间：{$now}\n"
            ],
            "at" => [
                "atMobiles" => [
                    "18678017521",
                ],
                "isAtAll" => false
            ]
        ];

        $url = env('DING_TALK');
        $res = Curl::json($url, $params);
        if ($res['errcode'] == 0 && $res['errmsg'] == "ok") {
            foreach ($result as $item) {
                $redis_key = sprintf('github:commits:count:%s:%s', $item['username'], $date);
                Redis::set($redis_key, $item['count']);
                Redis::expire($redis_key, 3600 * 24);
                $model = new CommitsLog();
                $model->commits = $count;
                $model->username = $committer;
                $model->save();

            }
            return true;
        }

        return true;
    }


}

