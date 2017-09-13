<!DOCTYPE html>
<html>
    <head>
        <title>表特牆</title>
        <meta charset="UTF-8">

        <!--JQuery-->
        <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>

        <!--BootStrap3-->
        <link rel="stylesheet" href="css/bootstrap-3.3.7-dist/css/bootstrap.css"/>
        <link rel="stylesheet" href="css/bootstrap-3.3.7-dist/css/bootstrap-theme.css"/>
        <script type="text/javascript" src="css/bootstrap-3.3.7-dist/js/bootstrap.js"></script>

        <script>

            var beautyArray = {};
            var pageCount = 0;

            function request(page) {

                var timeCount = new Date();
                $.ajax({
                    method: "GET",
                    url: "request/" + page,
                    dataType: "json",
                    beforeSend: function () {
                        timeCount.getTime();
                    }
                }).done(function (response) {
                    console.log("ajax done");
                    timeCount = new Date().getTime() - timeCount;
                    console.log("used " + timeCount + " ms");
                    // 巡覽response物件
                    for (var i = response.length - 1; i >= 0; i--) {
                        var oneBeauty = response[i];
                        // 單個物件，取得key(string)及value(array)
                        for (var key in oneBeauty) {
                            beautyArray[key] = oneBeauty[key];
                            $("#mainTable").append("<tr class='row1'><td>" + key + "</td></tr>");
                        }
                    }

                    // TODO: 此處可能會重複bind事件
                    $(".row1").bind("click", rowClick);
                    $(".row1").bind({mouseenter: rowMouseenter, mouseleave: rowMouseleave});
                });
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

            function rowMouseleave() {
//                console.log("rowMouseleave()");

                $("#tooltipImg").attr("src", "");
                $("#tooltip").hide();
            }

            function getChooseRowKey(sender) {
                return sender.children("td").html();
            }

            $(function () {
                console.log("page is ready");
                request(pageCount);
                request(++pageCount);

                $("#myCarousel").carousel('pause');

                // test
//                $("th").bind({click: rowClick, mouseenter: rowMouseenter, mouseleave: rowMouseleave});
            }); // ready end

            $(window).scroll(function () {

                // 若無捲軸，此值為0
                var scrollTop = Math.ceil($(window).scrollTop());
                var topLeft = $(document).height() - $(window).height();

                if (topLeft === scrollTop) {
                    request(++pageCount);
                }
            });

        </script>
    </head>
    <body>
        <div class="col-md-offset-2 col-md-8">
            <table class="table">
                <thead>
                    <tr>
                        <th>標題</th>
                    </tr>
                </thead>
                <tbody id="mainTable"></tbody>
            </table>
        </div>

        <!--滑鼠圖片-->
        <div id="tooltip" class="panel panel-default" style="display: none; max-width: 300px">
            <div class="panel-body" >
                <img id="tooltipImg" class="img-responsive" style="max-height: 100%">
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


