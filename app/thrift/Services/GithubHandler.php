<?php
// +----------------------------------------------------------------------
// | AppHandler.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Thrift\Services;

use App\Jobs\GithubCommitsJob;
use App\Utils\Queue;
use GithubService\GithubIf;

class GithubHandler extends Handler implements GithubIf
{
    public function receivedEvents($token)
    {
        return false;
    }

    public function commits($committer, $token)
    {
        Queue::push(new GithubCommitsJob($committer, $token));
        return true;
    }
}