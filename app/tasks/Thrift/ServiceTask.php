<?php

namespace App\Tasks\Thrift;

use App\Core\Cli\Task\Socket;
use App\Thrift\Services\AppHandler;
use App\Thrift\Services\BaiduHandler;
use App\Thrift\Services\GithubHandler;
use App\Utils\Log;
use App\Utils\Redis;
use Xin\Phalcon\Logger\Sys;
use Xin\Thrift\MonitorService\BaiduProcessor;
use Xin\Thrift\MonitorService\GithubProcessor;
use App\Utils\Register\Sign;
use Phalcon\Logger\AdapterInterface;
use Phalcon\Text;
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

    protected $port = 52100;

    protected $host = 'my.server.host';

    protected $processor;

    public function onConstruct()
    {
        $this->port = $this->config->thrift->service->port;
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
        parent::beforeServerStart($server);
        $config = $this->getConfig();
        if ($this->option('daemonize')) {
            $config['daemonize'] = true;
        }

        // 重置参数
        $server->set($config);
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

    protected function getConfig()
    {
        $config = $this->config->thrift->service->config;
        return $config->toArray();
    }
}
