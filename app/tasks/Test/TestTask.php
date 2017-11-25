<?php

namespace App\Tasks\Test;

use App\Common\Enums\ErrorCode;
use App\Tasks\Task;

class TestTask extends Task
{

    public function mainAction()
    {
        dd(ErrorCode::getMessage(ErrorCode::$ENUM_SYSTEM_ERROR));
    }

}

