<?php
// +----------------------------------------------------------------------
// | EmailValidator.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Support\Email;

use App\Core\Validation\Validator;
use Phalcon\Validation\Validator\PresenceOf;

class EmailValidator extends Validator
{
    public function initialize()
    {
        $this->add([
            'email',
            'password',
            'name',
            'host',
            'port'
        ], new PresenceOf([
            'messages' => 'field :field is required'
        ]));
    }
}