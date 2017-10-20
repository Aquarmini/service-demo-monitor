<?php

namespace App\Tasks\Test;

use App\Jobs\GithubReceivedEventJob;
use App\Logics\Github\Events;
use App\Logics\Github\Commits;
use App\Logics\Github\Follow;
use App\Logics\Github\User;
use App\Tasks\Task;
use App\Thrift\Clients\GithubClient;
use App\Utils\Queue;
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

    public function searchAction()
    {
        $committer = 'limingxinleo';
        $date = date('Y-m-d');
        $token = env('RECEIVED_EVENTS_TOKEN');
        $res = Commits::search($committer, $date, $token);
        dd($res);
    }

    public function countAction()
    {
        $committer = 'limingxinleo';
        $date = date('Y-m-d');
        $token = env('RECEIVED_EVENTS_TOKEN');
        $res = Commits::count($committer, $date, $token);
        dd($res);
    }

    public function sendCommitsAction()
    {
        $committer = 'limingxinleo';
        $token = env('RECEIVED_EVENTS_TOKEN');
        $res = Commits::send($committer, $token);
        dd($res);
    }

    public function receivedAction()
    {
        $token = env('RECEIVED_EVENTS_TOKEN');
        $committer = 'limingxinleo';
        $res = Events::sendReceivedEvent($committer, $token);
        dd($res);
    }

    public function followersAction()
    {
        $committer = 'laruence';
        $res = Follow::followers($committer);
        // $res = Follow::getFollowers($committer)->toArray();
        dd($res);
    }


    public function followingAction()
    {
        $committer = 'limingxinleo';
        $res = Follow::following($committer);
        // $res = Follow::getFollowers($committer)->toArray();
        dd($res);
    }

    public function profileAction()
    {
        $username = 'limingxinleo';
        $token = env('RECEIVED_EVENTS_TOKEN');

        // $res = User::profile($username, $token);

        $client = GithubClient::getInstance(['port' => 52100]);
        $res = $client->userProfile($username, $token);

        dd($res);

    }

}

