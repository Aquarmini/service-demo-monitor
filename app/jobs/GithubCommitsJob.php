<?php

namespace App\Jobs;

use App\Jobs\Contract\JobInterface;
use App\Utils\Curl;
use App\Utils\Log;
use App\Utils\Redis;

class GithubCommitsJob implements JobInterface
{
    public $committer;

    public $token;

    public function __construct($committer, $token)
    {
        $this->committer = $committer;
        $this->token = $token;
    }

    public function handle()
    {
        $committer = $this->committer;
        $token = $this->token;

        Log::info('commits:committer=' . $committer);

        $date = date('Y-m-d');
        $redis_key = sprintf('github:commits:count:%s:%s', $committer, $date);

        $api = '/search/commits';
        $params = [
            'q' => "committer-date:>={$date} committer:{$committer}",
            'sort' => 'committer-date',
        ];

        $res = Curl::httpGet($api, $params, $token);
        if (!isset($res['total_count'])) {
            return false;
        }

        $current_count = Redis::get($redis_key) ?? 0;
        if ($res['total_count'] <= $current_count) {
            return false;
        }

        $count = $res['total_count'];
        Log::info('commits:committer=' . $committer . ',count=' . $count);
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
            return true;
        }

        return false;
    }
}

