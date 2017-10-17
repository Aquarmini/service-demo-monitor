<?php

namespace App\Logics\Github;

use App\Logics\Base;

class Follow extends Base
{
    /**
     * @desc   我关注的
     * @author limx
     * @param $token
     */
    public static function followers($token)
    {
        $api = '/users/limingxinleo/received_events/public';
        $data = [
            'page' => 1,
            'per_page' => 5,
        ];

        return Curl::httpGet($api, $data, $token);
    }

    /**
     * @desc   关注我的
     * @author limx
     * @param $token
     */
    public static function following($token)
    {

    }
}

