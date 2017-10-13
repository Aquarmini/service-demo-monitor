<?php

namespace App\Tasks\Test;

use App\Tasks\Task;
use App\Thrift\Clients\GithubClient;

class GithubTask extends Task
{

    public function commitsAction()
    {
        $client = GithubClient::getInstance(['port' => 52100]);
        $client->commits('limingxinleo', env('RECEIVED_EVENTS_TOKEN'));
    }

    public function eventsAction()
    {
        $client = GithubClient::getInstance(['port' => 52100]);
        $client->receivedEvents(env('RECEIVED_EVENTS_TOKEN'));
    }

}

