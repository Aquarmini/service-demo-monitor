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
        Follow::followers($this->username);
    }
}

