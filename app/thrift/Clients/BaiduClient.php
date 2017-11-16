<?php

namespace App\Thrift\Clients;

use App\Thrift\Client;

class BaiduClient extends Client
{
    protected $host = 'my.server.host';

    protected $port = '52100';

    protected $service = 'baidu';

    protected $clientName = \Xin\Thrift\MonitorService\BaiduClient::class;

    protected $recvTimeoutMilliseconds = 50;

    protected $sendTimeoutMilliseconds;

    /**
     * @desc
     * @author limx
     * @param array $config
     * @return \Xin\Thrift\MonitorService\BaiduClient
     */
    public static function getInstance($config = [])
    {
        return parent::getInstance($config);
    }

}

