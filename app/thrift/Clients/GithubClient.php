<?php

namespace App\Thrift\Clients;

use App\Thrift\Client;
use Xin\Thrift\MonitorService\GithubClient as GithubServiceClient;

class GithubClient extends Client
{
    protected $host = 'my.server.host';

    protected $port = '52100';

    protected $service = 'github';

    protected $clientName = GithubServiceClient::class;

    protected $recvTimeoutMilliseconds = 50;

    protected $sendTimeoutMilliseconds;

    /**
     * @desc
     * @author limx
     * @param array $config
     * @return GithubServiceClient
     */
    public static function getInstance($config = [])
    {
        return parent::getInstance($config);
    }

}

