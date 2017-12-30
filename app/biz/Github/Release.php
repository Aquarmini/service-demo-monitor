<?php
// +----------------------------------------------------------------------
// | Release.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Biz\Github;

use App\Common\Enums\RedisKey;
use App\Utils\Curl;
use App\Utils\Log;
use App\Utils\Redis;
use Xin\Traits\Common\InstanceTrait;
use App\Common\Api\Github\Release as ReleaseClient;

class Release
{
    use InstanceTrait;

    public function isRelease($owner, $repo)
    {
        $result = ReleaseClient::getInstance()->latest($owner, $repo);
        if (isset($result['tag_name']) && isset($result['id'])) {
            $id = $result['id'];
            $tag = $result['tag_name'];
            $key = sprintf(RedisKey::GITHUB_RELEASE_LATEST, $owner, $repo);
            $now = Redis::get($key) ?? 0;
            if ($now < $result['id']) {
                // 发布新的release版本
                Log::info('release:owner=' . $owner . ',repo=' . $repo . ',tag=' . $tag);
                $now = date('Y-m-d H:i:s');
                $params = [
                    "msgtype" => "markdown",
                    "markdown" => [
                        "title" => "收到Github的Release通知",
                        "text" => "### 收到Github的Release通知\n" .
                            "> {$owner}/{$repo} 发布了新的Release版本{$tag}\n\n" .
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
                    Redis::set($key, $id);
                }
                return true;
            }
        }
        return false;
    }
}
