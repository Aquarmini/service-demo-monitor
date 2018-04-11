<?php
// +----------------------------------------------------------------------
// | RpcLoggerHandler.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Common\Logger\Rpc;

use Exception;
use Xin\Phalcon\Logger\Factory;
use Xin\Swoole\Rpc\LoggerInterface;
use Xin\Traits\Common\InstanceTrait;

class LoggerHandler implements LoggerInterface
{
    use InstanceTrait;
    /** @var  Factory */
    private $factory;

    public function __construct()
    {
        $this->factory = di('logger');
    }

    public function info($request, $response)
    {
        $logger = $this->factory->getLogger('swoole-service');
        $message = json_encode(
            [
                'request' => $request,
                'response' => $response,
            ],
            JSON_UNESCAPED_UNICODE
        );
        return $logger->info($message);
    }

    public function error($request, $response, Exception $ex)
    {
        $logger = $this->factory->getLogger('swoole-service');
        $message = json_encode(
            [
                'request' => $request,
                'response' => $response,
            ],
            JSON_UNESCAPED_UNICODE
        );
        $logger->error($message);

        $logger = $this->factory->getLogger('swoole-service-error');
        $message = $ex->getMessage() . ' code:' . $ex->getCode() . ' in ' . $ex->getFile() . ' line ' . $ex->getLine() . PHP_EOL . $ex->getTraceAsString();
        $logger->error($message);
    }
}
