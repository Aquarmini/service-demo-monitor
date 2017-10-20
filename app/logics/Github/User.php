<?php

namespace App\Logics\Github;

use App\Logics\Base;
use App\Utils\Curl;

class User extends Base
{
    /**
     * @desc   获取Github用户信息
     * @author limx
     * @param $username
     * @param $token
     */
    public static function profile($username, $token)
    {
        $api = "/users/{$username}";
        return Curl::httpGet($api, [], $token);
    }
}

