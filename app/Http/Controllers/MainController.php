<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use DateTime;
use App\lib\MyDb;

class MainController extends BaseController
{

    private $host = "https://www.ptt.cc";

    public function __invoke()
    {
        $myDb = new MyDb;
        $beautyArray = array();

        $httpGetText = file_get_contents("https://www.ptt.cc/bbs/Beauty/index.html");

//        $myfile = fopen("index.txt", "w");
//        fwrite($myfile, $httpGetText);
//        fclose($myfile);
        // 取得所有標題及網頁連結
        $getAllPattern = "/(<a href=).+\s.+\s.+\s.+\s.+\s/";

        if (count(preg_match_all($getAllPattern, $httpGetText, $matches)) > 0)
        {
            // 取得個別標題及網頁連結
            foreach ($matches[0] as $match)
            {
                $pageLinkPattern = '/(?<=href=").+.html(?=">)/';
                $datePattern = '/(?<=<div class="date">).+(?=<\/div>)/';
                $titlePattern = "";

                // PTT特殊符號
                if (str_contains($match, "<span"))
                {
                    $titlePattern = '/(?<=">).+(?=<span)/';
                }
                else
                {
                    $titlePattern = '/(?<=">).+(?=<\/a>)/';
                }

                if (preg_match($pageLinkPattern, $match, $linkMatchs) && preg_match($titlePattern, $match, $titleMatchs) &&
                        preg_match($datePattern, $match, $dateMatchs))
                {
                    // 與資料庫比對日期與標題，若相符，則由資料庫取得連結
                    // 若不相符則進入網頁取得連結，取得後存入資料庫
                    $title = $titleMatchs[0];
                    $date = new DateTime($dateMatchs[0]); // string to datetime
                    $imgLinks = [];

                    $titleId = $myDb->getTitleId($title, $date);

                    if ($titleId > 0)
                    {
                        $imgLinksFromDb = $myDb->getImgLinks($titleId);

                        // 組成array
                        foreach ($imgLinksFromDb as $imgLink)
                        {
                            array_push($imgLinks, $imgLink->link);
                        }
                    }
                    else
                    {
                        // 進入標題並取得圖片連結
                        $pageLink = $this->host . $linkMatchs[0];
                        $httpGetText = file_get_contents($pageLink);

                        $imgPattern = '/(?<=<a href=")http.+(?:.jpg|.png)(?=")/';
                        preg_match_all($imgPattern, $httpGetText, $imgLinkMatchs);

                        // 若該頁無圖片，則為空陣列
                        $imgLinks = $imgLinkMatchs[0];

                        $myDb->saveList($title, $date, $imgLinks);
                    }
                    // key：標題，value：圖片連結
                    array_push($beautyArray, [$title => $imgLinks]);
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
