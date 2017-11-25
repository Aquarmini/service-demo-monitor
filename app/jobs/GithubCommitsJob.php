<?php

namespace App\Jobs;

use App\Jobs\Contract\JobInterface;
use App\Biz\Github\Commits;

class GithubCommitsJob implements JobInterface
{
    public $committer;

    public $token;

    public function __construct($committer, $token)
    {
        $this->committer = $committer;
        $this->token = $token;
    }

    public function handle()
    {
        $committer = $this->committer;
        $token = $this->token;

        return Commits::send($committer, $token);
    }
}

