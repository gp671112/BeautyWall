<?php

namespace App\lib;

use Illuminate\Support\Facades\DB;

class MyDb
{

    // 以標題ID取得圖片連結
    function getImgLinks($titleId)
    {
        $result = DB::table("title")
                ->select("link.link")
                ->leftJoin("link", "title.id", "=", "link.titleId")
                ->where("title.id", "=", $titleId)
                ->get();

        return $result;
    }

    // 以標題及日期取得ID
    function getTitleId($title = "", $date = null)
    {
        $result = DB::table("title")
                ->select("id")
                ->where([["title", "=", $title], ["date", "=", $date]])
                ->get()
                ->first();

        return ($result == null) ? 0 : $result->id;
    }

    // insert一個PTT主題，包括標題、日期、圖片連結
    function saveList($title = "", $date = null, $links = [])
    {
        $id = DB::table("title")
                ->insertGetId(["title" => $title, "date" => $date]);

        foreach ($links as $link)
        {
            DB::table("link")->insert(["link" => $link, "titleId" => $id]);
        }
    }

}
