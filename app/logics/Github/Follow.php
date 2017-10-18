<?php

namespace App\Logics\Github;

use App\Logics\Base;
use App\Models\Followers;
use App\Utils\Curl;
use App\Utils\Log;

class Follow extends Base
{
    /**
     * @desc   我关注的
     * @author limx
     * @param $token
     */
    public static function followers($username, $page = 1, $per_page = 20, $token = null)
    {
        $api = "/users/{$username}/followers";
        $data = [
            'page' => $page,
            'per_page' => $per_page,
        ];

        $res = Curl::httpGet($api, $data, $token);
        foreach ($res as $item) {
            Log::info('followers:' . $item['login']);
            $user = Followers::findFirst([
                'conditions' => 'username = ?0 AND  login = ?1',
                'bind' => [$username, $item['login']],
            ]);
            if (empty($user)) {
                $user = new Followers();
                $user->username = $username;
                $user->uid = $item['id'];
                $user->avatar_url = $item['avatar_url'];
                $user->login = $item['login'];
                $user->save();
            }
        }
        return $res;
    }

    public static function getFollowers($username)
    {
        return Followers::find([
            'conditions' => 'username = ?0',
            'bind' => [$username],
        ]);
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

