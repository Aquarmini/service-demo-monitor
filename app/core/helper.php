<?php
// +----------------------------------------------------------------------
// | 助手函数 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
if (!function_exists('get_rpc_config')) {
    /**
     * @desc   获取微服务配置
     * @author limx
     * @param $service 服务名
     * @return mixed
     */
    function get_rpc_config($service)
    {
        $env = di('config')->env;
        $rpc = di('configCenter')->get('rpc_clients');
        if (!isset($rpc->$env) || !isset($rpc->$env->$service)) {
            throw new \Exception('RPC CLIENT 配置不存在');
        }

        return $rpc->$env->$service;
    }
}
