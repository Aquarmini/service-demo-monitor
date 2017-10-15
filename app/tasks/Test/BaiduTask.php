<?php

namespace App\Tasks\Test;

use App\Logics\Baidu\TiebaClient;
use App\Tasks\Task;
use App\Thrift\Clients\BaiduClient;
use Xin\Cli\Color;

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
        $client = TiebaClient::getInstance(env('BAIDU_USS'), '桃园丶龙玉箫');
        $res = $client->user->flushTiebas();

        foreach ($res as $item) {
            echo Color::colorize(($item->tieba->fid), Color::FG_GREEN) . PHP_EOL;
            echo Color::colorize(($item->tieba->name), Color::FG_GREEN) . PHP_EOL;
            echo Color::colorize(($item->avatar), Color::FG_GREEN) . PHP_EOL;
            echo Color::colorize(($item->cur_score), Color::FG_GREEN) . PHP_EOL;
            echo Color::colorize(($item->favo_type), Color::FG_GREEN) . PHP_EOL;
            echo Color::colorize(($item->level_id), Color::FG_GREEN) . PHP_EOL;
            echo Color::colorize(($item->level_name), Color::FG_GREEN) . PHP_EOL;
            echo Color::colorize(($item->levelup_score), Color::FG_GREEN) . PHP_EOL;
            echo Color::colorize(($item->slogan), Color::FG_GREEN) . PHP_EOL;
            exit;
        }
    }

}

