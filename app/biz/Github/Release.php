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
            $key = sprintf(RedisKey::GITHUB_RELEASE_LATEST, $owner, $repo);
            $now = Redis::get($key) ?? 0;
            if ($now < $result['id']) {
                // 发布新的release版本
                Redis::set($key, $id);

                return true;
            }
        }
        return false;
    }
}