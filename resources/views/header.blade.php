<!--JQuery-->
<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>

<!--lightbox2-->
<script type="text/javascript" src="js/lightbox2/js/lightbox.js"></script>
<link rel="stylesheet" href="js/lightbox2/css/lightbox.css" />

<!--powertip-->
<script type="text/javascript" src="js/jquery.powertip-1.3.0/jquery.powertip.js"></script>
<link rel="stylesheet" href="js/jquery.powertip-1.3.0/css/jquery.powertip.css" />

<!--BootStrap3-->
<link rel="stylesheet" href="css/bootstrap-3.3.7-dist/css/bootstrap.css"/>
<link rel="stylesheet" href="css/bootstrap-3.3.7-dist/css/bootstrap-theme.css"/>
<script type="text/javascript" src="css/bootstrap-3.3.7-dist/js/bootstrap.js"></script>

<script type="text/javascript">
    $(function () {
        var pagePathName = document.location.pathname;

        if (pagePathName.indexOf("main") >= 0) {
            $("#naviMain").addClass("active");
        } else if (pagePathName.indexOf("about") >= 0) {
            $("#naviAbout").addClass("active");
        }
    });
</script>