<?php
// +----------------------------------------------------------------------
// | AppHandler.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Thrift\Services;

use App\Jobs\BaiduTiebaSignJob;
use App\Logics\Baidu\TiebaClient;
use App\Utils\Queue;
use Xin\Thrift\MonitorService\BaiduIf;
use Xin\Thrift\MonitorService\BaiduTieba;

class BaiduHandler extends Handler implements BaiduIf
{
    public function tiebaSign($bdUss, $nickName)
    {
        Queue::push(new BaiduTiebaSignJob($bdUss, $nickName));
        return true;
    }

    public function myTiebas($bdUss, $nickName)
    {
        $client = TiebaClient::getInstance($bdUss, $nickName);
        $res = $client->user->flushTiebas();
        $result = [];
        foreach ($res as $item) {
            $obj = new BaiduTieba();
            $obj->fid = $item->tieba->fid;
            $obj->nickname = $nickName;
            $obj->name = $item->tieba->name;
            $obj->avatar = $item->avatar;
            $obj->curScore = $item->cur_score;
            $obj->levelId = $item->level_id;
            $obj->levelName = $item->level_name;
            $obj->levelupScore = $item->levelup_score;
            $obj->slogan = $item->slogan;

            $result[] = $obj;
        }
        return $result;
    }

    public function tieba($bdUss, $nickName, $name)
    {
        return new BaiduTieba();
    }
}