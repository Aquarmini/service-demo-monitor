<?php
// +----------------------------------------------------------------------
// | Router 服务 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Core\Services\Mvc;

use App\Core\Services\ServiceProviderInterface;
use Phalcon\Config;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Router as MvcRouter;
use Xin\Phalcon\Router\Mvc\Router as XMvcRouter;

class Router implements ServiceProviderInterface
{
    public function register(FactoryDefault $di, Config $config)
    {
        $di->setShared('router', function () use ($config) {
            $router = new MvcRouter(false);
            // $router = new XMvcRouter(false);
            $dir = $config->application->configDir . 'routes';
            foreach (glob($dir . '/*.php') as $item) {
                include_once $item;
            }
            return $router;
        });
    }

}