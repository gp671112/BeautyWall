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
                            $("#loading").toggleClass("loadingDone").toggleClass("loadingActive");
                        }
                    }).done(function (response) {

                        // 巡覽response物件
                        for (var i = response.length - 1; i >= 0; i--) {
                            var oneBeauty = response[i];
                            // 單個物件，取得key(string)及value(array)
                            for (var key in oneBeauty) {
                                beautyArray[key] = oneBeauty[key];
                                $("#mainTable").append("<tr class='beautyRow' role='button' ='hello'><td>" + key + "</td><td>" + oneBeauty[key].length + "</td></tr>");
                                $("#mainTable").children().last()
                                        .attr("data-powertip", "<img src='" + beautyArray[key][0] + "' width=300>")
                                        .bind("click", rowClick)
                                        .powerTip({followMouse: true});
                            }
                        }

                        // 檢查文件高度，遞迴
                        if ($(document).height() < 1500) {
                            ajaxFlag = true;
                            request();
                        }

                        $("#loading").toggleClass("loadingActive").toggleClass("loadingDone");
                        ajaxFlag = true;
                    });
                }
            }

            function rowClick() {
                var key = getChooseRowKey($(this));
                var oneBeauty = beautyArray[key];

                $("#imgBox").children("a").remove();

                for (var i = 0; i < oneBeauty.length; i++) {
                    $("#imgBox").append("<a href='" + oneBeauty[i] + "' data-lightbox='lightbox'></a>");
                }

                $("#imgBox").children().first().click();
            }

            function getChooseRowKey(sender) {
                return sender.children("td").html();
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
                    </tr>
                </thead>
                <tbody id="mainTable"></tbody>
            </table>

            <!--loading-->
            <img id="loading" class="center-block loadingDone" src="../resources/assets/loading.gif" width="100px" />
        </div>

        <!--row click-->
        <div id="imgBox"></div>
    </body>
</html>


