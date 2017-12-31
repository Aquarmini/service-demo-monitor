<?php
// +----------------------------------------------------------------------
// | APP ENV [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
return [
    'project-name' => 'limx-phalcon-project',
    // 定时执行的脚本
    'cron-tasks' => [
        // ['task' => 'System\\Clear', 'action' => 'view', 'params' => ['yes'], 'schedule' => ['dailyAt', [2, 0]]],
    ],
    'error-code' => [
        500 => '服务器错误！',
    ],

    'email' => [
        'email' => env('SEND_EMAIL'),
        'password' => env('SEND_EMAIL_PASSWORD'),
        'name' => env('SEND_EMAIL_NAME', '邮件代理服务器'),
        'host' => env('SEND_EMAIL_HOST'),
        'port' => env('SEND_EMAIL_PORT'),
    ],
];
