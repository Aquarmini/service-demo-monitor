<?php

namespace App\Tasks\Test;

use App\Biz\Pages\Lv;
use App\Tasks\Task;
use Xin\Cli\Color;

class LvTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  Lv监控脚本') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run test:lv@[action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  buy                         是否可以购买', Color::FG_GREEN) . PHP_EOL;
    }

    public function buyAction()
    {
        $result = Lv::getInstance()
            ->bindGoods('http://www.louisvuitton.cn/zhs-cn/products/pochette-metis-monogram-reverse-canvas-015383')
            ->bindGoods('http://www.louisvuitton.cn/zhs-cn/products/pochette-metis-monogram-006115')
            ->bindGoods('http://www.louisvuitton.cn/zhs-cn/products/dandy-briefcase-pm-epi-014551')
            ->bindGoods('http://www.louisvuitton.cn/zhs-cn/products/pochette-metis-monogram-empreinte-nvprod630173v#M44071')
            ->bindEmails(env('LV_LIMX_NAME'), env('LV_LIMX_EMAIL'))
            ->bindEmails(env('LV_AGNES_NAME'), env('LV_AGNES_EMAIL'))
            ->all();
    }
}
