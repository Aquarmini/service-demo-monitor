<?php

namespace App\Tasks\Thrift;

use App\Core\Cli\Task\Socket;
use App\Thrift\Services\AppHandler;
use App\Thrift\Services\GithubHandler;
use App\Utils\Log;
use App\Utils\Redis;
use Xin\Phalcon\Logger\Sys;
use Xin\Thrift\GithubService\GithubProcessor;
use Xin\Thrift\MicroService\AppProcessor;
use swoole_server;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\TMultiplexedProcessor;
use Thrift\Transport\TMemoryBuffer;
use swoole_process;
use swoole_client;

class ServiceTask extends Socket
{

    protected $config = [
        'pid_file' => ROOT_PATH . '/service.pid',
        'daemonize' => true,
        'max_request' => 500, // 每个worker进程最大处理请求次数
    ];

    protected $processor;

    public function onConstruct()
    {
        $this->port = env('SERVICE_PORT');
    }

    protected function events()
    {
        return [
            'receive' => [$this, 'receive'],
            'WorkerStart' => [$this, 'workerStart'],
        ];
    }

    protected function beforeServerStart(swoole_server $server)
    {
        parent::beforeServerStart($server); // TODO: Change the autogenerated stub

        $worker = new swoole_process(function (swoole_process $worker) {
            $client = new swoole_client(SWOOLE_SOCK_TCP);
            if (!$client->connect(env('REGISTRY_IP'), env('REGISTRY_PORT'), -1)) {
                exit("connect failed. Error: {$client->errCode}\n");
            }
            $logger = di('logger')->getLogger('heart', Sys::LOG_ADAPTER_FILE, ['dir' => 'system']);
            swoole_timer_tick(5000, function () use ($client, $logger) {
                $service = env('REGISTRY_SERVICE', 'github');
                $data = [
                    'service' => $service,
                    'ip' => env('SERVICE_IP'),
                    'port' => env('SERVICE_PORT'),
                    'nonce' => time(),
                    'register' => true,
                    'sign' => 'xxx',
                ];

                $client->send(json_encode($data));
                $result = $client->recv();
                $logger->info($result);

                $result = json_decode($result, true);
                if ($result['success']) {
                    foreach ($result['services'] as $key => $item) {
                        Redis::hset($service, $key, json_encode($item));
                    }
                }
            });
        });

        $server->addProcess($worker);
    }


    public function workerStart(swoole_server $serv, $workerId)
    {
        // dump(get_included_files()); // 查看不能被平滑重启的文件

        $this->processor = new TMultiplexedProcessor();
        $handler = new AppHandler();
        $this->processor->registerProcessor('app', new AppProcessor($handler));
        $handler = new GithubHandler();
        $this->processor->registerProcessor('github', new GithubProcessor($handler));

    }

    public function receive(swoole_server $server, $fd, $reactor_id, $data)
    {
        $transport = new TMemoryBuffer($data);
        $protocol = new TBinaryProtocol($transport);
        $transport->open();
        $this->processor->process($protocol, $protocol);
        $server->send($fd, $transport->getBuffer());
        $transport->close();
    }
}

