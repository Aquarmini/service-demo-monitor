<?php
// +----------------------------------------------------------------------
// | Lv.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Biz\Pages;

use App\Jobs\SendEmailJob;
use App\Utils\Curl;
use App\Utils\Log;
use App\Utils\Queue;
use App\Utils\Redis;
use Xin\Traits\Common\InstanceTrait;

class Lv
{
    use InstanceTrait;

    public $goods = [];

    public $emails = [];

    public function bindGoods($url)
    {
        $this->goods[] = $url;
        return $this;
    }

    public function bindEmails($name, $email)
    {
        $this->emails[] = [
            'name' => $name,
            'email' => $email,
        ];
        return $this;
    }

    public function all()
    {
        foreach ($this->goods as $goods) {
            $this->one($goods);
        }
    }

    public function one($url)
    {
        $key = 'lv:' . md5($url);
        $name = $key;
        $sku = '';
        preg_match('/\#(.*)$/', $url, $result);
        if (isset($result[1])) {
            $sku = $result[1];
        }

        $res = $this->httpGet($url);
        $res = preg_replace('/ |\n/', '', $res);
        preg_match('/data-sku="(.*)"/U', $res, $result);

        if (isset($result[1])) {
            if (empty($sku)) {
                $sku = $result[1];
            }
            $result = $this->httpGet($this->getIsStockUrl($sku));
            $result = json_decode($result, true);

            preg_match('/\<h1itemprop="name"\>(.*)\<\/h1\>/', $res, $r);
            if (isset($r[1])) {
                $name = $r[1];
            }

            if (isset($result[$sku]) && $result[$sku]['inStock']) {
                // 有货

                if (Redis::get($key) == 0) {
                    // 发送邮件
                    $redis = [
                        'key' => $key,
                        'val' => 1,
                    ];
                    $content = [
                        'title' => "{$name}有货了！！",
                        'content' => "购买地址{$url}",
                    ];
                    Queue::push(new SendEmailJob($redis, $this->emails, $content));
                }
            } else {
                // 无货
                if (Redis::get($key) == 1) {
                    // 发送邮件
                    $redis = [
                        'key' => $key,
                        'val' => 0,
                    ];
                    $content = [
                        'title' => "{$name}没货了！！",
                        'content' => "购买地址{$url}",
                    ];
                    Queue::push(new SendEmailJob($redis, $this->emails, $content));
                }
            }
        }
    }

    public function getIsStockUrl($sku)
    {
        $time = time();
        $url = sprintf('https://secure.louisvuitton.cn/ajaxsecure/getStockLevel.jsp?storeLang=zhs-cn&pageType=product&skuIdList=%s&null&_=%s', $sku, $time);
        return $url;
    }

    public function httpGet($url)
    {
        /** @var Factory $facotry */
        $facotry = di('logger');
        $logger = $facotry->getLogger('http');
        $logger->debug('CURL:' . $url);

        $ch = curl_init();
        // 设置抓取的url
        curl_setopt($ch, CURLOPT_URL, $url);
        // 启用时会将头文件的信息作为数据流输出。
        curl_setopt($ch, CURLOPT_HEADER, false);
        // 启用时将获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        //执行命令
        $result = curl_exec($ch);

        if ($result === false) {
            throw new \Exception(curl_error($ch));
        }
        //关闭URL请求
        curl_close($ch);
        return $result;
    }
}
