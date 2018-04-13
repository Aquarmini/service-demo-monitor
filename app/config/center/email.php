<?php
// +----------------------------------------------------------------------
// | email.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
return [
    'email' => env('SEND_EMAIL'),
    'password' => env('SEND_EMAIL_PASSWORD'),
    'name' => env('SEND_EMAIL_NAME', '邮件代理服务器'),
    'host' => env('SEND_EMAIL_HOST'),
    'port' => env('SEND_EMAIL_PORT'),
];