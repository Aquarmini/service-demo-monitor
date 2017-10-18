<?php

namespace App\Jobs;

use App\Jobs\Contract\JobInterface;
use App\Logics\Github\Commits;

class GithubFollowingCommitsJob implements JobInterface
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
        $username = $this->username;
        $token = $this->token;

        return Commits::sendFollowingCommits($username, $token);
    }
}

