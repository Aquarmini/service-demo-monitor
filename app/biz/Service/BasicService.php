<?php
// +----------------------------------------------------------------------
// | BasicService.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Biz\Service;

use Xin\Swoole\Rpc\Handler\HanderInterface;
use Xin\Traits\Common\InstanceTrait;

class BasicService implements HanderInterface
{
    use InstanceTrait;

    public function version()
    {
        return di('config')->version;
    }
}
