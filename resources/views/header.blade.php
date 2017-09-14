<!--JQuery-->
<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>

<!--BootStrap3-->
<link rel="stylesheet" href="css/bootstrap-3.3.7-dist/css/bootstrap.css"/>
<link rel="stylesheet" href="css/bootstrap-3.3.7-dist/css/bootstrap-theme.css"/>
<script type="text/javascript" src="css/bootstrap-3.3.7-dist/js/bootstrap.js"></script>

<script type="text/javascript">
    $(function () {
        var pagePathName = document.location.pathname;

        console.log(pagePathName);

        if (pagePathName.indexOf("main") >= 0) {
            $("#naviMain").addClass("active");
        } else if (pagePathName.indexOf("about") >= 0) {
            $("#naviAbout").addClass("active");
        }
    });
</script>