<?php

namespace App\Jobs;

use App\Jobs\Contract\JobInterface;
use App\Logics\Github\Follow;

class GithubFollowersJob implements JobInterface
{
    public $username;


    public function __construct($username)
    {
        $this->username = $username;
    }

    public function handle()
    {
        $continue = true;
        $page = 1;
        while ($continue) {
            $res = Follow::followers($this->username, $page);
            if (count($res) == 0) {
                $continue = false;
            }
            $page++;
        }
    }
}

