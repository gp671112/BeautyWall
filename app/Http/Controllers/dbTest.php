<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class dbTest extends BaseController
{

    public function __invoke()
    {
        $db1 = new \App\lib\MyDb();

        return $db1->getImgLinks(23);
    }

}
