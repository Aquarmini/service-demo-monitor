<?php

namespace App\Jobs;

use App\Jobs\Contract\JobInterface;
use App\Biz\Baidu\TiebaClient;

class BaiduTiebaSignJob implements JobInterface
{
    public $bdUss;

    public $nickName;

    public function __construct($bdUss, $nickName)
    {
        $this->bdUss = $bdUss;
        $this->nickName = $nickName;
    }

    public function handle()
    {
        TiebaClient::sign($this->bdUss, $this->nickName);
    }
}

