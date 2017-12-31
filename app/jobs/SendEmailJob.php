<?php

namespace App\Jobs;

use App\Jobs\Contract\JobInterface;
use App\Utils\Redis;
use Xin\Thrift\Notice\Email;
use App\Support\Email\Email as EmailSupport;

class SendEmailJob implements JobInterface
{
    public $redis;

    public $emails;

    public $content;

    public function __construct(array $redis, array $emails, array $content)
    {
        $this->redis = $redis;
        $this->emails = $emails;
        $this->content = $content;
        dump($this->emails);
    }

    public function handle()
    {
        $client = EmailSupport::getInstance();
        foreach ($this->emails as $item) {
            $client->addTarget($item['email'], $item['name']);
        }
        if ($client->send($this->content['title'], $this->content['content'])) {
            // 发送成功记录
            Redis::set($this->redis['key'], $this->redis['val']);
        }
        return false;
    }

}

