<h4>Play with a Demo</h4>
If you would like to play with the admin interface without messing up your installation, <a href="http://demo.thulasidas.com/google-adsense" title='Visit the demo site to play with the admin interface' data-toggle='tooltip' target="_blank">please visit the demo site</a>.
<div id='supportChannels'>
  <h4>Need Support?</h4>
  <ul>
    <?php
    $plgSlug = EzGA::getSlug();
    $verbose = array('name' => __('Diagnostic Comments', 'easy-adsenser'),
        'type' => 'checkbox',
        'help' => __('The content filter in the plugin can add diagnostic comments to the HTML code, which could have security implications. Please turn it on only if you need to contact the plugin author, or track down some bugs.', 'easy-adsenser'),
        'value' => 0);
    $verboseBox = '<div id="verboseBox" class="col-md-12" style="display:none">
    <table class="table table-striped table-bordered responsive">
      <thead>
        <tr>
          <th style="width:50%;min-width:150px">Option</th>
          <th style="width:55%;min-width:80px">Value</th>
          <th class="center-text" style="width:15%;min-width:50px">Help</th>
        </tr>
      </thead>
      <tbody>' .
            EzGA::renderOption('verbose', $verbose) .
            '</tbody>
    </table>
    </div>';
    ?>
    <li>Please check the carefully prepared <a href="http://www.thulasidas.com/plugins/<?php echo $plgSlug; ?>#faq" class="popup-long" title='Your question or issue may be already answered or resolved in the FAQ' data-toggle='tooltip'> Plugin FAQ</a> for answers.</li>
    <?php
    if (EzGA::$isPro) {
      ?>
      <li>The Pro version comes with a short <a href='http://support.thulasidas.com/open.php' class='popup btn-xs btn-success' title='Open a support ticket if you have trouble with your Pro version. It is free during the download link expiry time.' data-toggle='tooltip'>Free Support</a>.</li>
      <?php
    }
    else {
      ?>
      <li>For the lite version, you may be able to get support from the <a href='https://wordpress.org/support/plugin/<?php echo $plgSlug; ?>-lite' class='popup' title='WordPress forums have community support for this plugin' data-toggle='tooltip'>WordPress support forum</a>.</li>
      <li class="text-success bg-success">Visit the <a href='http://buy.thulasidas.com/update.php' class='popup btn-xs btn-success' title='If you purchased the Pro version of this plugin, but did not get an automated email or a download page, please click here to find it.' data-toggle='tooltip'>Product Delivery Portal</a> to download the Pro version you have purchased.</li>
      <?php
    }
    ?>
    <li>For preferential support and free updates, you can purchase a <a href='http://buy.thulasidas.com/support' class='popup btn-xs btn-info' title='Support contract costs only $4.95 a month, and you can cancel anytime. Free updates upon request, and support for all the products from the author.' data-toggle='tooltip'>Support Contract</a>.</li>
    <li>For one-off support issues, you can raise a one-time paid <a href='http://buy.thulasidas.com/ezsupport' class='popup btn-xs btn-primary' title='Support ticket costs $0.95 and lasts for 72 hours' data-toggle='tooltip'>Support Ticket</a> for prompt support.</li>
    <li>Please turn on <a href='#' id="verbose" class='btn-xs btn-warning' title='Click to check the status of Dignostic Comments in your post HTML. Do not leave the diagnostic comments on unless needed for troubleshooting. It increases the bandwindth usage, and gives out some information about your filesystem.' data-toggle='tooltip'>Diagnostic Comments</a> and include a link to your blog URL when you contact the plugin author.</li>
  </ul>
  <?php
  echo $verboseBox;
  ?>
</div>
<h4>Happy with this Plugin?</h4>
Please leave a short review and rate it at <a href="https://wordpress.org/plugins/<?php echo $plgSlug; ?>-lite/" class="popup-long" title='Please help the author and other users by leaving a short review for this plugin and by rating it' data-toggle='tooltip'>WordPress</a>. Thanks!
<?php
$show_google_translate = array('name' => __('Enable Google Translation of Admin Pages?', 'easy-adsenser'),
    'help' => '',
    'type' => 'checkbox',
    'reload' => true,
    'value' => !empty(EzGA::$options['show_google_translate']));
?>
<div class="text-success bg-success" style="margin-top:5px;padding:5px">
  <h4>Admin Pages in your <a href='i18n.php'>Language</a>  <a style="font-size:1.1em;float:right" data-content="This application can display its admin pages using Google<sup>®</sup> Translate. If you would like to see the pages in your language, please enable this option. You will then see a language selector near the top right corner where you can choose your language." data-help="" data-toggle="popover" data-placement="left" data-trigger="hover" title="" data-original-title="Enable Translation?"><i class="glyphicon glyphicon-question-sign blue"></i></a>
</h4>
  Enable machine tranaslation by Google?
  <?php echo EzGA::renderOptionValue('show_google_translate', $show_google_translate); ?>
</div>
<div class="clearfix"></div>
<script>
  var xeditHandler = 'ajax/options.php';
  var xparams = {};
  $('#verbose').click(function (e) {
    e.preventDefault();
    $("#verboseBox").toggle();
  });
  $(document).ready(function () {
    $("#showSupportChannels").click(function (e) {
      e.preventDefault();
      var bg = $("#supportChannels").css("backgroundColor");
      var fg = $("#supportChannels").css("color");
      $("#supportChannels").css({backgroundColor: "yellow", color: "black"});
      setTimeout(function () {
        $("#supportChannels").css({backgroundColor: bg, color: fg});
      }, 500);
    });
    setTimeout(function () {
      $(".xeditReload").editable('option', 'success', function () {
        ezReload();
      });
    }, 1500);
  });
</script>