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
            ->bindSku('M41465', 'POCHETTE METIS 手袋', 'http://www.louisvuitton.cn/zhs-cn/products/pochette-metis-monogram-reverse-canvas-015383')
            ->bindSku('M40780', 'POCHETTE METIS 手袋', 'http://www.louisvuitton.cn/zhs-cn/products/pochette-metis-monogram-006115')
            ->bindSku('M44071', 'POCHETTE METIS 手袋', 'http://www.louisvuitton.cn/zhs-cn/products/pochette-metis-monogram-empreinte-nvprod630173v#M44071')
            ->bindSku('M47542', '26号盥洗袋', 'https://www.louisvuitton.cn/zhs-cn/products/toiletry-pouch-26-monogram-000767#M47542')
            ->bindGoods('M43589', 'SQUARE 手袋', 'https://www.louisvuitton.cn/zhs-cn/products/square-bag-nvprod750010v')
            ->bindEmails(env('LV_LIMX_NAME'), env('LV_LIMX_EMAIL'))
            ->bindEmails(env('LV_AGNES_NAME'), env('LV_AGNES_EMAIL'))
            // ->bindEmails(env('LV_XXX01_NAME'), env('LV_XXX01_EMAIL'))
            ->all();
    }
}
