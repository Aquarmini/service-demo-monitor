<?php
// +----------------------------------------------------------------------
// | AppHandler.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Thrift\Services;

use App\Jobs\BaiduTiebaSignJob;
use App\Utils\Queue;
use Xin\Thrift\MonitorService\BaiduIf;

class BaiduHandler extends Handler implements BaiduIf
{
    public function tiebaSign($bdUss, $nickName)
    {
        Queue::push(new BaiduTiebaSignJob($bdUss, $nickName));
        return true;
    }

}