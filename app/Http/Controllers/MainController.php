<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use DateTime;
use App\lib\MyDb;

class MainController extends BaseController
{

    public function __invoke($page = 0)
    {
        $myDb = new MyDb;
        $host = "https://www.ptt.cc";
        $beautyArray = [];

        $httpGetText = file_get_contents($host . "/bbs/Beauty/index.html");

        $pagePattern = '/(?<=<a class="btn wide" href="\/bbs\/Beauty\/index).+(?=.html">&lsaquo; 上頁<\/a>)/';
        preg_match($pagePattern, $httpGetText, $lastPageIndex);

        if ($page <= 0)
        {
            $page = $lastPageIndex[0];
        }
        elseif ($page > 0)
        {
            $page = $lastPageIndex[0] - $page;
        }

        $httpGetText = file_get_contents($host . "/bbs/Beauty/index" . $page . ".html");

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
                    $pageLink = $host . $linkMatchs[0];
                    $date = new DateTime($dateMatchs[0]); // string to datetime
                    $imgLinks = [];

                    $titleId = $myDb->getTitleId($title, $pageLink, $date);

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
                        $httpGetText = file_get_contents($pageLink);

                        $imgPattern = '/(?<=<a href=")http.+(?:.jpg|.png)(?=")/';
                        preg_match_all($imgPattern, $httpGetText, $imgLinkMatchs);

                        // 若該頁無圖片，則為空陣列
                        $imgLinks = $imgLinkMatchs[0];

                        $myDb->saveList($title, $pageLink, $date, $imgLinks);
                    }

                    if (count($imgLinks) > 0 && $imgLinks[0] != null)
                    {
                        // 組成物件
                        $beautyArray[$pageLink] = ["title" => $title, "imgLinks" => $imgLinks];
                    }
                }
            }
        }

        $jsonCode = json_encode($beautyArray);
        return $jsonCode;
    }

}
