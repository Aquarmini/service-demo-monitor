<?php

namespace App\Tasks\Test;

use App\Tasks\Task;
use App\Thrift\Clients\GithubClient;
use App\Utils\Redis;

class GithubTask extends Task
{

    public function commitsAction()
    {
        $client = GithubClient::getInstance(['port' => 52100]);
        $client->commits('limingxinleo', env('RECEIVED_EVENTS_TOKEN'));
    }

    public function eventsAction()
    {
        $service = Redis::hget(env('REGISTRY_SERVICE'), env('REGISTRY_SERVICE'));
        $service = json_decode($service);

        $client = GithubClient::getInstance(['ip' => $service->ip, 'port' => $service->port]);

        $client->receivedEvents(env('RECEIVED_EVENTS_TOKEN'));
    }

}

