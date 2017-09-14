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

                        // 巡覽response物件
                        for (var i = response.length - 1; i >= 0; i--) {
                            var oneBeauty = response[i];
                            // 單個物件，取得key(string)及value(array)
                            for (var key in oneBeauty) {
                                beautyArray[key] = oneBeauty[key];
                                $("#mainTable").append("<tr class='beautyRow' role='button'><td>" + key + "</td><td>" + oneBeauty[key].length + "</td></tr>");
                            }
                        }

                        // 檢查文件高度，若小於一頁，則遞迴呼叫
                        if ($(document).height() < 1500) {
                            ajaxFlag = true;
                            request();
                        }

                        $("#loading").removeClass("loadingActive").addClass("loadingDone");

                        $(".beautyRow").unbind("click", rowClick);
                        $(".beautyRow").unbind({mouseenter: rowMouseenter, mouseleave: rowMouseleave});
                        $(".beautyRow").bind("click", rowClick);
                        $(".beautyRow").bind({mouseenter: rowMouseenter, mouseleave: rowMouseleave});

                        ajaxFlag = true;
                    });
                }
            }

            function rowClick() {
                console.log("rowClick()");
                var key = getChooseRowKey($(this));
                var oneBeauty = beautyArray[key];

                $(".carousel-inner").children("div,img").remove();
                for (var i = oneBeauty.length - 1; i >= 0; i--) {

                    var carouselItem = "<div class='item";
                    if (i === 0) {
                        carouselItem += " active";
                    }

                    var height = $(window).height() - 200;
                    carouselItem += "'><img class='img-responsive center-block' src='" + oneBeauty[i] + "' style='max-height: " + height + "px'></div>";
                    $(".carousel-inner").prepend(carouselItem);
                }

                $("#modalTitle").html(key);
                $(".modal").modal('show');
            }

            // hover in
            function rowMouseenter(e) {
                var key = getChooseRowKey($(this));

                // 取第一個值為預覽圖
                $("#tooltipImg").attr("src", beautyArray[key][0]);
                // 超出視窗時調整位置
                var topPostion = e.pageY + 10;
                if ((topPostion + 300) > $(window).height()) {
                    topPostion = topPostion - 300;
                }

                $("#tooltip").css({"top": topPostion + "px", "left": e.pageX + 10 + "px", "position": "absolute"});
                $("#tooltip").show();
            }

            // hover out
            function rowMouseleave() {
                $("#tooltipImg").attr("src", "");
                $("#tooltip").hide();
            }

            function getChooseRowKey(sender) {
                return sender.children("td").html();
            }

            // page ready
            $(function () {

                request();
                $("#myCarousel").carousel('pause');
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

            #tooltip {
                display: none; 
                max-width: 300px;
            }

            #tooltipImg {
                max-height: 100%;
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
            <img id="loading" class="center-block" src="../resources/assets/loading.gif" width="100px" />
        </div>

        <!--滑鼠圖片-->
        <div id="tooltip" class="panel panel-default">
            <div class="panel-body" >
                <img id="tooltipImg" class="img-responsive">
            </div>
        </div>

        <!--row click-->
        <div class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 id="modalTitle" class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <div id="myCarousel" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner" role="listbox"  >
                                <div class='item active'></div>
                                <div class='item'></div>
                                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                </a>
                                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>


