<?php

namespace App\Jobs;

use App\Jobs\Contract\JobInterface;
use App\Logics\Github\Follow;
use App\Utils\Redis;

class GithubFollowersJob implements JobInterface
{
    public $username;
    public $token;

    public function __construct($username, $token)
    {
        $this->username = $username;

        $this->token = $token;
    }

    public function handle()
    {
        $continue = true;

        $redis_key = "followers:" . $this->username;
        $page = Redis::get($redis_key) ?? 1;
        $page = intval($page);
        if ($page <= 0) $page = 1;
        while ($continue) {
            $res = Follow::followers($this->username, $page, 20, $this->token);
            if (count($res) == 0) {
                Redis::del($redis_key);
                break;
            }
            $page++;
            Redis::set($redis_key, $page);
        }
    }
}

