<?php

namespace App\Logics\Github;

use App\Logics\Base;
use App\Utils\Curl;
use App\Utils\Log;
use App\Utils\Redis;

class Events extends Base
{
    public static function received($username, $token)
    {
        $api = "/users/{$username}/received_events/public";
        $data = [
            'page' => 1,
            'per_page' => 5,
        ];

        return Curl::httpGet($api, $data, $token);
    }

    public static function sendReceivedEvent($username, $token)
    {
        $redis_key = 'github:received:events:%s';
        $res = static::received($username, $token);
        foreach ($res as $item) {
            $id = $item['id'];
            Log::info('receivedEvents:id=' . $id);
            $key = sprintf($redis_key, $id);
            if (!Redis::exists($key)) {
                // 没有提示过的事件
                $actor_login = $item['actor']['login'];
                $actor_avator = $item['actor']['avatar_url'];
                $repo_name = $item['repo']['name'];
                $repo_api_url = $item['repo']['url'];
                $repo_url = 'https://github.com/' . $repo_name;
                $created_at = $item['created_at'];
                $type = $item['type'];

                $params = [
                    "msgtype" => "markdown",
                    "markdown" => [
                        "title" => "收到Github的新事件推送",
                        "text" => "### 收到Github的新事件推送" .
                            "> ![]({$actor_avator})\n" .
                            "> {$actor_login}有一个[{$repo_name}]({$repo_url})类型为{$type}的事件消息\n\n" .
                            "> 时间：{$created_at}\n"
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
                    Redis::set($key, $id);
                    Redis::expire($key, 3600 * 24 * 7);
                }
            }
        }
        return true;
    }
}

