<?php
// +----------------------------------------------------------------------
// | Handler.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Core\Exception;

use App\Common\Enums\ErrorCode;
use App\Common\Exceptions\ExceptionInterface;
use App\Utils\Response;
use Exception;
use ErrorException;
use Phalcon\DI\FactoryDefault;
use Xin\Phalcon\Logger\Sys;

class Handler
{
    public static $_instance;

    public $di;

    public $logger;

    public $errorLogger;

    private function __construct()
    {
        $this->di = FactoryDefault::getDefault();
        $this->logger = di('logger')->getLogger('exception', Sys::LOG_ADAPTER_FILE);
        $this->errorLogger = di('logger')->getLogger('error', Sys::LOG_ADAPTER_FILE);
    }

    public static function getInstance()
    {
        if (isset(static::$_instance) && static::$_instance instanceof Handler) {
            return static::$_instance;
        }
        return static::$_instance = new static();
    }

    /**
     * @desc   捕获Http模式 异常
     * @author limx
     * @param Exception $ex
     */
    public function render(Exception $ex)
    {
        $code = ErrorCode::$ENUM_SYSTEM_ERROR;
        $message = 'Sorry, 服务器内部错误';
        $msg = $ex->getMessage() . ' code:' . $ex->getCode() . ' in ' . $ex->getFile() . ' line ' . $ex->getLine() . PHP_EOL . $ex->getTraceAsString();

        if ($ex instanceof ExceptionInterface) {
            // 业务异常
            $this->logger->error($msg);
            $code = $ex->getErrorCode();
            $message = $ex->getMessage();
        } else {
            $this->errorLogger->error($msg);
            if (env('APP_DEBUG', false)) {
                $message = $ex->getMessage();
            }
        }

        Response::fail($code, $message)->send();
        exit(255);
    }

    /**
     * @desc   捕获Cli模式 异常
     * @author limx
     * @param Exception $ex
     */
    public function renderForConsole(Exception $ex)
    {
        $msg = $ex->getMessage() . ' code:' . $ex->getCode() . ' in ' . $ex->getFile() . ' line ' . $ex->getLine() . PHP_EOL . $ex->getTraceAsString();
        if ($ex instanceof ExceptionInterface) {
            // 业务异常
            $this->logger->error($msg);
        } else {
            $this->errorLogger->error($msg);
        }
        echo $msg;
        exit(255);
    }
}
