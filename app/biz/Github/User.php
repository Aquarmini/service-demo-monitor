<?php

namespace App\Biz\Github;

use App\Core\Support\CacheBase;
use App\Utils\Curl;

class User extends CacheBase
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

