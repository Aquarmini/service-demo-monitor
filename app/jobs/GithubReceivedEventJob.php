<?php

namespace App\Jobs;

use App\Jobs\Contract\JobInterface;
use App\Logics\Github\Events;

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
        $username = $this->username;

        return Events::sendReceivedEvent($username, $token);
    }

}

