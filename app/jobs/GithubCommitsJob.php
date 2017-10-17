<?php

namespace App\Jobs;

use App\Jobs\Contract\JobInterface;
use App\Logics\Github\Commits;
use App\Models\CommitsLog;
use App\Utils\Curl;
use App\Utils\Log;
use App\Utils\Redis;

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

