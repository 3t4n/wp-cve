<?php
$plgName = EzGA::getPlgName();
$plgSlug = EzGA::getSlug();
$plgModeName = EzGA::$pluginModes[$plgSlug];
$plgMode = EzGA::getPlgMode();
$isUltra = $plgMode == 'google-adsense-ultra';
?>
<div class="col-lg-8 col-sm-12">
  <p>
    Welcome to <strong><?php echo $plgName; ?></strong>, one of the most popular AdSense plugins ever.
    <?php
    if ($isUltra) {
      ?>This plugin can operate as <strong>AdSense Now!</strong> (basic mode), <strong>Google AdSense</strong> (default) or <strong>Easy AdSense</strong> (advanced mode).
      <?php
    }
    ?>
  </p>
  <h4>Quick Start</h4>
  <ul>
    <?php
    if ($isUltra) {
      $choices = EzGA::$pluginModes;
      unset($choices['ajax-adsense']);
      $plugin_slug = array('name' => __('Plugin Mode', 'easy-common'),
          'value' => EzGA::$pluginModes[$plgSlug],
          'help' => __('Ads EZ Plugin for Google AdSense can operate as any one of the three plugins in the list. For instance, if you have been using Easy AdSense, you can choose to make this plugin work like Easy AdSense. The options you have saved in that plugin will be migrated and you will see them in the familiar interface.', 'easy-common'),
          'type' => 'select',
          'options' => $choices);
      ?>
      <li>
        Select <strong>Plugin Mode</strong>. It is currently <?php echo EzGA::renderOptionValue('plugin_slug', $plugin_slug); ?>. (Click on it to change it).
      </li>
      <?php
    }
    ?>
    <li>Go to the <a href='<?php echo $plgSlug; ?>-admin.php'>admin page</a> and enter your ad code and other details.</li>
    <li>If you have the <strong><a href="#" class='goPro' data-product='<?php echo $plgSlug; ?>'>Pro version</a></strong> of <?php echo $plgName; ?>, set up the <a href='pro.php'>Pro features</a>.</li>
    <li>Take this <strong><a class="restart" href="#">tour</a></strong> any time you would like to go through the application features again.</li>
  </ul>
  <h4>WordPress and Shortcodes</h4>
  <p>If you are using the <strong><a href="#" class='goPro'>Pro version</a></strong> of <?php echo $plgName; ?>, you can use <a href='http://codex.wordpress.org/Shortcode' target='_blank' class='popup-long'>shortcodes</a> to place your ads on your posts and pages. Use the shortcode <code>[adsense]</code> or <code>[ezadsense]</code> to place your ads exactly where you need them on any posts. You can set up intelligent shortcode priority schemes on the <a href='pro.php'>Pro page</a>.</p>

  <h4>Context-Aware Help</h4>
  <p>
    Every option on the plugin admin and pro pages has a popover help bubble. You just need to hover over the field to get a clear and concise description of what the option does and how to set it up.
  </p>
  <p>
    The admin and the pro pages also have generous help near the top, which can be expanded by clicking on a clearly marked button. For further support and assistance, please use the channels on the Contact Author panel next to this panel. If you need further assistance, please see the <a href='#' id='showSupportChannels'>support channels</a> available.
  </p>
</div>
<div class="col-lg-4 col-sm-12">
  <?php
  require_once 'support.php';
  ?>
</div>
<div class="clearfix"></div>
<hr />
<p class="center-text">
  <a class="btn btn-primary center-text restart" href="#" data-toggle='tooltip' title='Start or restart the tour any time' id='restart'><i class="glyphicon glyphicon-globe icon-white"></i>&nbsp; Start Tour</a>

  <a href='<?php echo $plgSlug; ?>-admin.php' class="btn btn-warning" data-toggle='tooltip' title="<p>Set up the plugin options and enter your AdSense code and details. You can also click on the <strong><?php echo $plgModeName; ?></strong> tab above.</p>"><i class='glyphicon glyphicon-cog'></i> Setup Plugin</a>

  <a href='#' id='suspendAds' class="btn btn-danger" data-toggle='tooltip' title="<p>Pause ad serving.</p>"><i class='glyphicon glyphicon-pause'></i> Suspend Ads</a>

  <a href='#' id='resumeAds' style='display:none' class="btn btn-success" data-toggle='tooltip' title="<p>Resume ad serving.</p>"><i class='glyphicon glyphicon-play'></i> Resume Ads</a>

  <a href='#' id='migrateOptions' class="btn btn-success" data-toggle='tooltip' title="<p>This version of the plugin uses a new option model. If you used an older version before, your options are automatically imported when you activate the plugin. If you find them missing, please click this button to import them again. Note that your modified options are never overwritten by the migration process; so it is safe to run it again.</p>"><i class='glyphicon glyphicon-import'></i> Import Options</a>

  <a class="btn btn-primary center-text showFeatures" href="#" data-toggle='tooltip' title='Show the features of this plugin and its Pro version'><i class="glyphicon glyphicon-thumbs-up icon-white"></i>&nbsp; Show Features</a>

  <a class="btn btn-warning center-text showKillAjax" href="#" data-toggle='tooltip' title='If you need to, you can get the old non-AJAX version of the plugin. Click to find out how.'><i class="glyphicon glyphicon-info-sign icon-white"></i>&nbsp; Show AJAX Info</a>
</p>
<script>
  $(document).ready(function () {
    $("#suspendAds").click(function () {
      suspendAds('suspend');
    });
    $("#resumeAds").click(function () {
      suspendAds('resume');
    });
    $("#migrateOptions").click(function (e) {
      e.preventDefault();
      var data = {};
      data.action = 'migrate';
      $.ajax({url: 'ajax/optionset.php',
        type: 'POST',
        data: data,
        success: function (a) {
          flashSuccess(a);
        },
        error: function (a) {
          showError(a.responseText);
        }});
    });
    if (!$('.tour').length && typeof (tour) === 'undefined') {
      var tour = new Tour({backdrop: true, backdropPadding: 20,
        onShow: function (t) {
          var current = t._current;
          var toShow = t._steps[current].element;
          $(toShow).parent('ul').parent().siblings('.accordion').find('ul').slideUp();
          $(toShow).parent('ul').slideDown();
        }});
      tour.addStep({
        element: "#dashboard",
        placement: "right",
        title: "Dashboard",
        content: "<strong>Welcome to <?php echo $plgName; ?></strong><br> When you first visit your <?php echo $plgName; ?> Admin interface, you will find yourself in the Dashboard. Depending on the version of our plugin, you may see informational messages, statistics etc. on this page."
      });
      tour.addStep({
        element: "#tour",
        placement: "right",
        title: "Tour",
        content: "This page is the starting point of your tour. You can always come here to relaunch the tour, if you wish."
      });
      tour.addStep({// The first on ul unroll is ignored. Bug in BootstrapTour?
        element: "#google-adsense",
        placement: "right",
        title: "Manage Google AdSense",
        content: "This is the plugin admin interface to enter ad codes, specify alignment, colors etc. It is a drop-down menu with all your <strong>Option Sets</strong> (including the ones for mobile phones and tablets). You can edit any one of them and switch to it as the active one."
      });
      tour.addStep({// The first on ul unroll is ignored. Bug in BootstrapTour?
        element: "#easy-adsense",
        placement: "right",
        title: "Manage Easy AdSense",
        content: "This is the plugin admin interface to enter ad codes, specify alignment, colors etc. It is a drop-down menu with all your <strong>Option Sets</strong> (including the ones for mobile phones and tablets). You can edit any one of them and switch to it as the active one."
      });
      tour.addStep({// The first on ul unroll is ignored. Bug in BootstrapTour?
        element: "#adsense-now",
        placement: "right",
        title: "Manage AdSense Now!",
        content: "This is the plugin admin interface to enter ad codes, specify alignment, colors etc. It is a drop-down menu with all your <strong>Option Sets</strong> (including the ones for mobile phones and tablets). You can edit any one of them and switch to it as the active one."
      });
      tour.addStep({
        element: "#goPro",
        placement: "right",
        title: "Upgrade Your App to Pro",
        content: "To unlock the full potential of this Plugin, you may want to purchase the Pro version. You will get a link to download it instantly. It costs only a few dollars and adds tons of features. Click here to buy it now!"
      });
      tour.addStep({
        element: "#pro",
        placement: "right",
        title: "Edit Pro Options",
        content: "The Pro version gives you a safety feature to help minimize the risk of your AdSense account getting banned, mobile support, per category/post control on ads etc."
      });
      tour.addStep({
        element: "#options",
        placement: "right",
        title: "Plugin Configuration",
        content: "Set up the plugin admin page options, and other advanced options here."
      });
      tour.addStep({
        element: "#stats",
        placement: "right",
        title: "Ad Serving Statistics",
        content: "<p class='red'>This is an optional paid feature.</p><p>Here you can see how your ads are being served, and their performance."
      });
      tour.addStep({
        element: ".col-lg-4",
        placement: "left",
        title: "Support Channels",
        content: "If you need further support with Welcome to <?php echo $plgName; ?>, use one of these support channels."
      });
      tour.addStep({
        orphan: true,
        placement: "right",
        title: "Done",
        content: "<p>You now know the <?php echo $plgName; ?> Plugin interface. Congratulations!</p>"
      });
    }
    $(".restart").click(function (e) {
      e.preventDefault();
      tour.restart();
    });
    $(".showFeatures").click(function (e) {
      e.preventDefault();
      $("#features").toggle();
      if ($("#features").is(":visible")) {
        $(this).html('<i class="glyphicon glyphicon-thumbs-up icon-white"></i>&nbsp; Hide Features</a>');
      }
      else {
        $(this).html('<i class="glyphicon glyphicon-thumbs-up icon-white"></i>&nbsp; Show Features</a>');
      }
    });
    $(".showKillAjax").click(function (e) {
      e.preventDefault();
      $("#killAjax").toggle();
      if ($("#killAjax").is(":visible")) {
        $(this).html('<i class="glyphicon glyphicon-thumbs-up icon-white"></i>&nbsp; Hide AJAX Info');
      }
      else {
        $(this).html('<i class="glyphicon glyphicon-thumbs-up icon-white"></i>&nbsp; Show AJAX Info');
      }
    });
  });
</script>
