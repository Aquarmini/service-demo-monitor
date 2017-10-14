<?php
// +----------------------------------------------------------------------
// | Github.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace Githb\Thrift;

use App\Logics\Github\Commits;
use \UnitTestCase;

/**
 * Class UnitTest
 */
class GithubTest extends UnitTestCase
{
    public function testCommitsLog()
    {
        $btime = date('Y-m-d');
        $etime = date('Y-m-d', time() + 3600 * 24);

        $logs = Commits::getCommitsLogs('limingxinleo', $btime, $etime);
        $this->assertTrue(is_array($logs));
    }
}