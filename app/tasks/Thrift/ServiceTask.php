<?php

namespace App\Tasks\Thrift;

use App\Core\Cli\Task\Socket;
use App\Thrift\Clients\RegisterClient;
use App\Thrift\Services\AppHandler;
use App\Thrift\Services\BaiduHandler;
use App\Thrift\Services\GithubHandler;
use App\Utils\Log;
use App\Utils\Redis;
use Xin\Phalcon\Logger\Sys;
use Xin\Thrift\MonitorService\BaiduProcessor;
use Xin\Thrift\MonitorService\GithubProcessor;
use App\Utils\Register\Sign;
use limx\Support\Str;
use Phalcon\Logger\AdapterInterface;
use Xin\Phalcon\Cli\Traits\Input;
use Xin\Thrift\MicroService\AppProcessor;
use swoole_server;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\TMultiplexedProcessor;
use Thrift\Transport\TMemoryBuffer;
use swoole_process;
use swoole_client;
use Xin\Thrift\Register\ServiceInfo;

class ServiceTask extends Socket
{
    use Input;

    protected $config = [
        'pid_file' => ROOT_PATH . '/service.pid',
        'daemonize' => false,
        // 'worker_num' => 4, // cpu核数1-4倍比较合理 不写则为cpu核数
        'max_request' => 500, // 每个worker进程最大处理请求次数
    ];

    protected $port = 52100;

    protected $host = 'my.server.host';

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

    /**
     * @desc   服务注册
     * @author limx
     * @param swoole_server $server
     * @param               $name
     */
    protected function registryHeartbeat(swoole_server $server, $name)
    {
        $worker = new swoole_process(function (swoole_process $worker) use ($name) {
            $config = di('config')->thrift;
            $client = RegisterClient::getInstance([
                'host' => $config->register->host,
                'port' => $config->register->port,
            ]);
            /** @var AdapterInterface $logger */
            $logger = di('logger')->getLogger('heart', Sys::LOG_ADAPTER_FILE, ['dir' => 'system']);
            swoole_timer_tick(5000, function () use ($client, $logger, $name, $config) {
                $service = new ServiceInfo();
                $service->name = $name;
                $service->host = $this->host;
                $service->port = $this->port;
                $service->nonce = Str::random(16);
                $service->isService = true;
                $service->sign = Sign::sign(Sign::serviceInfoToArray($service));

                $result = $client->heartbeat($service);

                if ($result->success === false) {
                    $logger->error($result->message);
                    return;
                }

                if (!isset($result->services)) {
                    $logger->error("服务列表为空！");
                    return;
                }

                foreach ($result->services as $key => $item) {
                    $serviceJson = json_encode(Sign::serviceInfoToArray($item));
                    $logger->info($serviceJson);
                    Redis::hset($config->service->listKey, $key, $serviceJson);
                }

            });
        });

        $server->addProcess($worker);
    }

    protected function beforeServerStart(swoole_server $server)
    {
        parent::beforeServerStart($server);

        if ($this->option('daemonize')) {
            $this->config['daemonize'] = true;
        }

        // 重置参数
        $server->set($this->config);

        $isOpen = di('config')->thrift->register->open;
        if ($isOpen) {
            static::registryHeartbeat($server, 'github');
            static::registryHeartbeat($server, 'baidu');
            // $this->registryHeartbeat($server, 'app');
        }
    }


    public function workerStart(swoole_server $serv, $workerId)
    {
        // dump(get_included_files()); // 查看不能被平滑重启的文件

        $this->processor = new TMultiplexedProcessor();
        $handler = new AppHandler();
        $this->processor->registerProcessor('app', new AppProcessor($handler));
        $handler = new GithubHandler();
        $this->processor->registerProcessor('github', new GithubProcessor($handler));
        $handler = new BaiduHandler();
        $this->processor->registerProcessor('baidu', new BaiduProcessor($handler));

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

