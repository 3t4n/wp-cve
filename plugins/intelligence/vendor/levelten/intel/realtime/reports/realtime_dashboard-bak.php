<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Page title</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

    <script src="../../../../TimelineJS/compiled/js/storyjs-embed.js"></script>
    <script src="../../../../TimelineJS/compiled/js/timeline.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <script src="js/rtd.setup.js"></script>
    <script src="js/realtime_dashboard_model.js"></script>
    <script>
        google.load("visualization", "1", {packages:["corechart", 'table']});
        google.setOnLoadCallback(visualizationLoaded);
        function visualizationLoaded() {
          visualizationLoaded = true;
        }
        jQuery(document).ready(function(){
            doResize();
            rtdModel = new rtDashboardModel('rtdModel');
            rtdModel.pole();
            rtdView = new rtDashboardView('rtdView');
            rtdView.pole();
        });
        function doResize() {
          // set main div to height of window
          $('#dashboard').height($(window).height());
            jQuery('[class*="row-md-"]').each(function(index) {
              var classes = $(this).attr('class');
              classes = classes.split(' ');
              var rowCount = 12;
              for(var j in classes) {
                if (classes[j].substring(0, 7) == 'row-md-') {
                  var e = classes[j].split('-');
                  rowCount = parseInt(e[2]);
                  break;
                }
              }
              var parentHeight = $(this).parent().height();
              var height = parentHeight * rowCount / 12;
              $(this).height(height);
              // set pane heights
              $('.pane', this).each(function () {
                var paneHeight = $(this).parent().height();
                var panePadding = 2 * (parseInt($(this).css('margin')) + parseInt($(this).css('border-width')));
                $(this).height(paneHeight - panePadding);
              });
              $('.chart', this).each(function () {
                var paneHeight = $(this).parent().height();
                var panePadding = 2 * (parseInt($(this).css('margin')) + parseInt($(this).css('border-width')));
                $(this).height(paneHeight - panePadding);
              });
            });

        }
        jQuery(window).resize(doResize);


    </script>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Dancing+Script|Antic+Slab' rel='stylesheet' type='text/css'>
    <link href='css/rtd-dark.css' rel='stylesheet' type='text/css'>
</head>
<body>
<div id="dashboard">
  <!-- top half -->
  <div id="row-top" class="row row-md-6">

    <!-- top left quarter -->
    <div class="pane-container col-md-6 row-md-12">
      <?php include "includes/rtd.main-screen.php" ?>
    </div>
    <!-- end top left quarter -->

    <!-- top right quarter -->
    <div class="pane-container col-md-6 row-md-12">
      <?php include "includes/rtd.pages-refs-screen.php" ?>
    </div>
    <!-- end top right quarter -->

  </div>
  <!-- end top half -->

  <!-- bottom half -->
  <div id="row-bottom" class="row row-md-6">
    <!-- bottom left quarter -->
    <div class="pane-container col-md-6 row-md-12">
      <?php include "includes/rtd.visitors-screen.php" ?>
    </div>
    <!-- end bottom left quarter -->

    <!-- bottom right quarter -->
    <div class="pane-container col-md-6 row-md-12">
      <?php include "includes/rtd.visitors-screen.php" ?>
    </div>
    <!-- end bottom right quarter -->

  </div>
  <!-- end bottom half -->
</div>
</body>
</html>