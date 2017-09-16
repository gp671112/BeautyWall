<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title>關於本網站</title>

        @component('header')
        @endcomponent

    </head>
    <body>
        <div class="col-md-offset-2 col-md-8">

            @component('navi')
            @endcomponent

            <h3>表特牆</h3>
            <p>作者：Sean (aaa90025@gmail.com)</p>
            <hr />

            <h3>簡介</h3>
            <p>網站使用Laveral為基礎，以PHP網路爬蟲(web crawler)對<a target="_blank" href="https://www.ptt.cc/bbs/beauty/index.html">PTT表特版</a>網頁進行分析與資料擷取，
                接著組成列表供使用者預覽及查看圖片。</p>
            <hr />

            <h3>結構分析</h3>
            <p>當使用者載入主頁時，Laveral route只單純返回主頁的View，document ready時發出AJAX請求列表內容。</p>                
            <br />

            <p>Controller接收到請求，帶有一個參數為欲查詢的頁碼，
                當Controller確認頁碼後，對目標發出HTTP GET取得HTML文字檔，以正則表達式(Regular Expression)對內容分析，取得每頁標題及圖片連結。</p>
            <br />

            <p>分析後的資料存入資料庫，當下次有同樣的請求時，以資料庫直接取得資料，避免重複crawl，加快資料獲取速度，而後以JSON格式回傳。</p>
            <br />

            <p>前端接收到後處理JSON字串為JS物件，並將資訊加入HTML元素，當滑鼠移動或點擊到標題時，以JQuery事件動態繫結，顯示預覽圖或大圖。</p>
            <br />

            <p>當scroll bar移動到底部時，會再次發出AJAX請求，以lazy loading方式過場，將所得結果不斷加入下方元素，達到連續頁面的目標。</p>
            <hr />

            <h3>問題研討</h3>
            <p>Q: 有些圖片看不見？</p>
            <p>A: 圖片皆為連結，某些圖床不支援外部直接存取，故有看不見的問題。評估解決辦法: 取得每張圖片的Http head Content-Type，確認為有效的MIME image類型。</p>
            <br />

            <p>Q: 為何需要資料庫？</p>
            <p>A: 經計算，爬一個主題的時間約為1~3秒，瓶頸皆在對目標發出HTTP GET取得HTML文字這段工作，假設一頁的主題有20個，可能耗費30秒以上。</p>
            <br />

            <p>Q: 網頁載入很久？</p>
            <p>A: 承上個問題，當目標主題未列入資料庫索引時，需重新取得每個標題及連結而耗費大量時間。
                評估解決辦法: 排程對目標網頁進行資料擷取並列入資料庫索引。</p>
            <hr />

            <h3>開發環境及工具</h3>
            <ul>
                <li><p>Apache 2.4.17</p></li>
                <li><p>MySQL 5.0.11</p></li>
                <li><p>PHP 5.6.23</p></li>
                <li><p>Laveral 5.4</p></li>
                <li><p>JQuery 3.2.1</p></li>
                <li><p>JQuery Lightbox2</p></li>
                <li><p>JQuery Powertip 1.3.0</p></li>
                <li><p>Bootstrap 3.3.7</p></li>
                <li><p>NetBeans IDE 8.2</p></li>
            </ul>
        </div>
    </body>
</html>