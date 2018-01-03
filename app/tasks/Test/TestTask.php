<?php

namespace App\Tasks\Test;

use App\Common\Enums\ErrorCode;
use App\Tasks\Task;

class TestTask extends Task
{
    public function mainAction()
    {
        $url = 'http://www.louisvuitton.cn/zhs-cn/products/pochette-metis-monogram-empreinte-nvprod630173v#M44071';
        preg_match('/\#(.*)$/', $url, $result);
        if (!isset($result[1])) {
            echo 1;
        }
    }
}
