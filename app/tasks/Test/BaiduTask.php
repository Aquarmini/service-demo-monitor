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

    public function tiebasAction()
    {
        $client = BaiduClient::getInstance(['host' => 'my.server.host', 'port' => 52100]);
        $res = $client->myTiebas(env('BAIDU_USS'), '桃园丶龙玉箫');
        dump($res);
    }

    public function tiebaAction()
    {
        $client = BaiduClient::getInstance(['host' => 'my.server.host', 'port' => 52100]);
        $res = $client->tieba(env('BAIDU_USS'), '桃园丶龙玉箫', '上海');
        dump($res);
    }

    public function tiebaListAction()
    {
        $client = BaiduClient::getInstance(['host' => 'my.server.host', 'port' => 52100]);
        $res = $client->tiebaList('桃园丶龙玉箫');
        dump($res);
    }

}

