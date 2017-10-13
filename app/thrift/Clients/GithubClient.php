<?php

namespace App\Thrift\Clients;

use App\Thrift\Client;

class GithubClient extends Client
{
    protected $host = '127.0.0.1';

    protected $port = '10086';

    protected $service = 'github';

    protected $clientName = \GithubService\GithubClient::class;

}

