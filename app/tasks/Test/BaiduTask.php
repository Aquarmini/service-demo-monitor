<?php

namespace App\Tasks\Test;

use App\Tasks\Task;
use App\Thrift\Clients\BaiduClient;

class BaiduTask extends Task
{

    public function tiebaSignAction()
    {
        $client = BaiduClient::getInstance();
        $res = $client->tiebaSign(env('BAIDU_USS'), '桃园丶龙玉箫');
        dump($res);
    }

    public function tiebasAction()
    {
        $client = BaiduClient::getInstance();
        $res = $client->myTiebas(env('BAIDU_USS'), '桃园丶龙玉箫');
        dump($res);
    }

    public function tiebaAction()
    {
        $client = BaiduClient::getInstance();
        $res = $client->tieba(env('BAIDU_USS'), '桃园丶龙玉箫', '上海');
        dump($res);
    }

    public function tiebaListAction()
    {
        $client = BaiduClient::getInstance();
        $res = $client->tiebaList('桃园丶龙玉箫');
        dump($res);
    }

}

