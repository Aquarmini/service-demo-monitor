<?php
// +----------------------------------------------------------------------
// | BasicClient.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Common\Clients\Rpc;

use Xin\Swoole\Rpc\Client\Client;

/**
 * Class BasicClient
 * @package App\Common\Clients\Rpc
 * @method version
 */
class BasicClient extends Client
{
    public function __construct()
    {
        $service = 'test';
        $rpc = get_rpc_config($service);

        $this->service = $service;
        $this->port = $rpc->port;
        $this->host = $rpc->host;

        parent::__construct();
    }
}
