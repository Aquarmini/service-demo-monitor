<?php
// +----------------------------------------------------------------------
// | Curl.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Utils;

use App\Core\Exception\HttpException;

class Curl
{
    public static function json($url, $params)
    {

        $body = json_encode($params);

        $ch = curl_init();
        // 设置抓取的url
        curl_setopt($ch, CURLOPT_URL, $url);
        // 启用时会将头文件的信息作为数据流输出。
        curl_setopt($ch, CURLOPT_HEADER, false);
        // 启用时将获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        // 设置访问 方法
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        // 设置POST BODY
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        // 设置JSON HEADER
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($body),
        ]);

        //执行命令
        $result = curl_exec($ch);
        if ($result === false) {
            throw new HttpException(curl_error($ch));
        }
        //关闭URL请求
        curl_close($ch);
        return json_decode($result, true);
    }

    public static function httpGet($api, $params, $token)
    {
        $url = 'https://api.github.com';

        $body = http_build_query($params);

        $url = $url . $api . '?' . $body;

        Log::info('CURL:' . $url);

        $ch = curl_init();
        // 设置抓取的url
        curl_setopt($ch, CURLOPT_URL, $url);
        // 启用时会将头文件的信息作为数据流输出。
        curl_setopt($ch, CURLOPT_HEADER, false);
        // 启用时将获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        // 设置JSON HEADER
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: token {$token}",
            "User-Agent: DingTalk Github App",
            "Accept: application/vnd.github.cloak-preview",
        ]);

        //执行命令
        $result = curl_exec($ch);

        if ($result === false) {
            throw new HttpException(curl_error($ch));
        }
        //关闭URL请求
        curl_close($ch);
        return json_decode($result, true);
    }


}