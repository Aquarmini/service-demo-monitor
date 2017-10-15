<?php

namespace App\Logics\Github;

use App\Logics\Base;
use App\Models\CommitsLog;

class Commits extends Base
{
    /**
     * @desc   获取commit日志
     * @author limx
     * @param $username
     * @param $btime
     * @param $etime
     */
    public static function getCommitsLogs($username, $btime, $etime)
    {
        $res = CommitsLog::find([
            'conditions' => 'username = ?0 AND created_at > ?1 AND created_at < ?2',
            'bind' => [$username, $btime, $etime],
        ]);
        $result = [];
        foreach ($res as $item) {
            $obj = new \Xin\Thrift\MonitorService\Co();
            $obj->id = $item->id;
            $obj->username = $item->username;
            $obj->commits = $item->commits;
            $result[] = $obj;
        }
        return $result;
    }
}

