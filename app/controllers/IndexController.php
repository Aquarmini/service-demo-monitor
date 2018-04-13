<?php
// +----------------------------------------------------------------------
// | 默认控制器 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
namespace App\Controllers;

use App\Common\Enums\ErrorCode;
use App\Common\Exceptions\BizException;
use App\Core\System;
use App\Utils\Response;

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
        if ($this->request->isPost()) {
            $message = di('configCenter')->get('msg')->welcome;
            return Response::success([
                'version' => System::getInstance()->version(),
                'message' => $message,
            ]);
        }
        $this->view->version = System::getInstance()->version();
        return $this->view->render('index', 'index');
    }

    public function exceptionAction()
    {
        throw new BizException(ErrorCode::$ENUM_SYSTEM_ERROR);
    }
}
