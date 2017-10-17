<?php

namespace App\Jobs;

use App\Jobs\Contract\JobInterface;
use App\Logics\Github\Events;
use App\Utils\Curl;
use App\Utils\Log;
use App\Utils\Redis;

class GithubReceivedEventJob implements JobInterface
{

    public $token;

    public $username;

    public function __construct($username, $token)
    {
        $this->token = $token;
        $this->username = $username;
    }

    public function handle()
    {
        $token = $this->token;
        return Events::sendReceivedEvent($token);
    }

}

