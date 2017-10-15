<?php

namespace App\Tasks\Test;

use App\Tasks\Task;
use App\Thrift\Clients\BaiduClient;

class BaiduTask extends Task
{

    public function tiebaSignAction()
    {
        $client = BaiduClient::getInstance(['port' => 52100]);
        $res = $client->tiebaSign(env('BAIDU_USS'), '桃园丶龙玉箫');
        dump($res);
    }

}

