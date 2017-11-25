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
use App\Biz\Baidu\TiebaClient;
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
        $tieba = \App\Models\BaiduTieba::findFirst([
            'conditions' => 'nickname=?0 AND name=?1',
            'bind' => [$nickName, $name]
        ]);

        $result = new BaiduTieba();

        $result->id = $tieba->id;
        $result->nickname = $tieba->nickname;
        $result->fid = $tieba->fid;
        $result->slogan = $tieba->slogan;
        $result->avatar = $tieba->avatar;
        $result->name = $tieba->name;
        $result->levelupScore = $tieba->levelup_score;
        $result->levelId = $tieba->level_id;
        $result->levelName = $tieba->level_name;
        $result->curScore = $tieba->cur_score;

        return $result;
    }

    public function tiebaList($nickName)
    {
        $res = \App\Models\BaiduTieba::find([
            'conditions' => 'nickname=?0',
            'bind' => [$nickName]
        ]);

        $result = [];
        foreach ($res as $tieba) {
            $item = new BaiduTieba();

            $item->id = $tieba->id;
            $item->nickname = $tieba->nickname;
            $item->fid = $tieba->fid;
            $item->slogan = $tieba->slogan;
            $item->avatar = $tieba->avatar;
            $item->name = $tieba->name;
            $item->levelupScore = $tieba->levelup_score;
            $item->levelId = $tieba->level_id;
            $item->levelName = $tieba->level_name;
            $item->curScore = $tieba->cur_score;
            $result[] = $item;
        }

        return $result;
    }


}