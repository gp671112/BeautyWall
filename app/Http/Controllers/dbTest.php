<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class dbTest extends BaseController
{

    public function __invoke()
    {
        $db1 = new \App\lib\MyDb();

        return $db1->getTitleId("[正妹] 豊田 萌絵", new \DateTime("9/7"));
//        return '<tr class="row1"><td>123</td></tr>';
    }

}
