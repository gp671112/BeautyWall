<!DOCTYPE html>
<html>
    <head>
        <title>表特牆</title>
        <meta charset="UTF-8">

        @component('header')
        @endcomponent

        <script>

            var beautyArray = {};
            var pageCount = 0;
            var ajaxFlag = true;

            function request() {

                if (ajaxFlag) {

                    $.ajax({
                        method: "GET",
                        url: "request/" + pageCount++,
                        dataType: "json",
                        beforeSend: function () {
                            ajaxFlag = false;
                            $("#loading").removeClass("loadingDone").addClass("loadingActive");
                        }
                    }).done(function (response) {

                        // 巡覽response
                        for (var key in response) {
                            var oneBeauty = response[key];

                            var trHtml = "<tr role='button'>";
                            trHtml += "<td>" + oneBeauty["title"] + "</td>";
                            trHtml += "<td>" + oneBeauty["imgLinks"].length + "</td>";
                            trHtml += "<td><a class='btn btn-default btn-xs' target='_blank' href='" + key + "'>連結</a></td>";
                            trHtml += "</tr>";

                            $("#mainTable").append(trHtml);
                            $("#mainTable").children().last()
                                    .attr("data-powertip", "<img src='" + oneBeauty["imgLinks"][0] + "' width=300>")
                                    .bind("click", rowClick)
                                    .powerTip({followMouse: true});
                        }

                        // 檢查文件高度，遞迴
                        if ($(document).height() < 1500) {
                            ajaxFlag = true;
                            request();
                        }

                        $.extend(beautyArray, beautyArray, response);
                        $("#loading").removeClass("loadingActive").addClass("loadingDone");
                        ajaxFlag = true;
                    });
                }
            }

            function rowClick() {
                var key = $(this).find("td>a").attr("href");
                var oneBeauty = beautyArray[key];
                var imgLinks = oneBeauty["imgLinks"];

                $("#imgBox").children("a").remove();

                for (var i = 0; i < imgLinks.length; i++) {
                    $("#imgBox").append("<a href='" + imgLinks[i] + "' data-lightbox='lightbox'></a>");
                }

                $("#imgBox").children().first().click();
            }

            // page ready
            $(function () {
                lightbox.option({
                    'wrapAround': true
                });

                request();
            });

            // scroll event
            $(window).scroll(function () {
                var scrollTop = Math.ceil($(window).scrollTop());
                var topLeft = $(document).height() - $(window).height() - 300;
                if (topLeft <= scrollTop) {
                    request();
                }
            });
        </script>

        <style type="text/css">

            .loadingActive {
                display: block;
            }

            .loadingDone {
                display: none;
            }
        </style>
    </head>
    <body>
        <div class="col-md-offset-2 col-md-8">

            @component('navi')
            @endcomponent

            <!--table-->
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>標題</th>
                        <th>照片張數</th>
                        <th>原文連結</th>
                    </tr>
                </thead>
                <tbody id="mainTable"></tbody>
            </table>

            <!--loading-->
            <img id="loading" class="center-block loadingDone" src="img/loading.gif" width="100px" />
        </div>

        <!--row click-->
        <div id="imgBox"></div>
    </body>
</html>


