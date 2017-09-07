<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class MainController extends BaseController
{

    private $host = "https://www.ptt.cc";

    public function __invoke()
    {
        $beautyArray = array();

        $httpGetText = file_get_contents("https://www.ptt.cc/bbs/Beauty/index.html");

//        $myfile = fopen("index.txt", "w");
//        fwrite($myfile, $httpGetText);
//        fclose($myfile);
        // 取得所有標題及網頁連結
        $getAllPattern = "/(<a href=).+/";

        if (count(preg_match_all($getAllPattern, $httpGetText, $matches)) > 0)
        {
            // 取得個別標題及網頁連結
            foreach ($matches[0]as $match)
            {
                $pageLinkPattern = '/(?<=href=").+.html(?=">)/';
                $titlePattern = "";

                if (str_contains($match, "<span"))
                {
                    $titlePattern = '/(?<=">).+(?=<span)/';
                }
                else
                {
                    $titlePattern = '/(?<=">).+(?=<\/a>)/';
                }

                if (preg_match($pageLinkPattern, $match, $link) && preg_match($titlePattern, $match, $title))
                {
                    // 進入標題並取得圖片連結
                    $pageLink = $this->host . $link[0];
                    $httpGetText = file_get_contents($pageLink);

                    $imgPattern = '/(?<=<a href=")http.+(?:.jpg|.png)(?=")/';

                    if (preg_match_all($imgPattern, $httpGetText, $imgLinks) > 0)
                    {
                        // 組成陣列，key：標題，value：圖片連結
                        array_push($beautyArray, [$title[0] => $imgLinks[0]]);
                    }
                }
            }
        }


        // get
//        $key = key($linkArray[0]);
//        $value = $linkArray[0][$key];
// 取得上一頁tag
        // .+(上頁</a>)

        $jsonCode = json_encode($beautyArray);
        return $jsonCode;
    }

}
