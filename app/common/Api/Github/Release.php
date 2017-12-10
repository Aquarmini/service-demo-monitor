<?php
// +----------------------------------------------------------------------
// | Release.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Common\Api\Github;

use App\Utils\Curl;
use Xin\Traits\Common\InstanceTrait;

class Release
{
    use InstanceTrait;

    public function latest($owner, $repo)
    {
        $route = sprintf('/repos/%s/%s/releases/latest', $owner, $repo);

        $res = Curl::httpGet($route, []);

        return $res;
    }
}