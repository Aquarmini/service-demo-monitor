<?php
// +----------------------------------------------------------------------
// | 默认控制器 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
namespace App\Controllers;

use App\Logics\System;

class IndexController extends Controller
{
    /**
     * @desc
     * @author limx
     * @return bool|\Phalcon\Mvc\View
     * @Middleware('auth')
     */
    public function indexAction()
    {
        $this->view->version = (new System())->version();
        return $this->view->render('index', 'index');
    }

    // public function serverAction()
    // {
    //     $this->thrift->handle(
    //         \App\Logics\Thrift\App::class,
    //         \MicroService\SystemProcessor::class
    //     );
    // }
}