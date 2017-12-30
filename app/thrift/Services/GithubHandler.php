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
use App\Jobs\GithubFollowersJob;
use App\Jobs\GithubFollowingCommitsJob;
use App\Jobs\GithubFollowingJob;
use App\Jobs\GithubReceivedEventJob;
use App\Biz\Github\Commits;
use App\Biz\Github\User;
use App\Jobs\GithubReleaseEventJob;
use Xin\Thrift\MonitorService\UserProfile;
use App\Utils\Queue;
use App\Utils\Redis;
use Xin\Thrift\MonitorService\GithubIf;

class GithubHandler extends Handler implements GithubIf
{
    public function receivedEvents($username, $token)
    {
        Queue::push(new GithubReceivedEventJob($username, $token));
        return true;
    }

    public function commits($committer, $token)
    {
        Queue::push(new GithubCommitsJob($committer, $token));
        return true;
    }

    public function commitsLog($committer, $btime, $etime)
    {
        $btime = date('Y-m-d H:i:s', $btime);
        $etime = date('Y-m-d H:i:s', $etime);
        return Commits::getCommitsLogs($committer, $btime, $etime);
    }

    public function updateFollowers($username, $token)
    {
        Queue::push(new GithubFollowersJob($username, $token));
        return true;
    }

    public function updateFollowing($username, $token)
    {
        Queue::push(new GithubFollowingJob($username, $token));
        return true;
    }

    public function followingCommits($username, $token)
    {
        Queue::push(new GithubFollowingCommitsJob($username, $token));
        return true;
    }

    public function userProfile($username, $token)
    {
        if (empty($username)) {
            return null;
        }

        $redis_key = 'monitor:github:user:' . $username;
        if ($user = Redis::get($redis_key)) {
            return unserialize($user);
        }

        $user = User::profile($username, $token);
        $profile = new UserProfile($user);

        Redis::set($redis_key, serialize($profile));
        Redis::expire($redis_key, 3600);

        return $profile;
    }

    public function release($owner, $repo)
    {
        return Queue::push(new GithubReleaseEventJob($owner, $repo));
    }
}
