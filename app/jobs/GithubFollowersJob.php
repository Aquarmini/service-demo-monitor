<?php

namespace App\Jobs;

use App\Jobs\Contract\JobInterface;
use App\Logics\Github\Follow;

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
        $page = 1;
        while ($continue) {
            $res = Follow::followers($this->username, $page, 20, $this->token);
            if (count($res) == 0) {
                $continue = false;
            }
            $page++;
        }
    }
}

