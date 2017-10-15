<?php

namespace App\Thrift\Clients;

use App\Thrift\Client;

class BaiduClient extends Client
{
    protected $host = '127.0.0.1';

    protected $port = '10086';

    protected $service = 'baidu';

    protected $clientName = \Xin\Thrift\MonitorService\BaiduClient::class;

}

