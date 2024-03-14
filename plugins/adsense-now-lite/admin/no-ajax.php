<?php
$plgMode = EZWP::getPlgMode();
$plgName = EZWP::getPlgName();
$pluginURL = admin_url('plugins.php');
$downloadURL = '';
if ($isPro) {
  $downloadURL = "http://buy.thulasidas.com/update.php' class='popup";
}
else {
  $plgModeTrimmed = str_replace('-ultra', '', $plgMode);
  $downloadURL = "http://buy.thulasidas.com/lite/$plgModeTrimmed-lite-no-AJAX.zip";
}
if (empty($downloadURL)) {
  return;
}
?>
<div style='padding:5px;margin:0;background-color:#fdd;display:none' id="adBlocked">
  This plugin loads its admin pages in an iFrame, which may look like an ad to some browser-side ad blockers. Looks like your browser is preventing the admin pages from being displayed. If you are running AdBlock or similar extensions, please disable it for your blog domain, and see if the admin page appears.
</div>
<div style='padding:5px;margin:0;background-color:#fdd' id="noAjax">
  <h3>Go Back to Non-AJAX Version<br>
    <small>Problems with the fancy AJAX interface?</small>
  </h3>
  You can always go back to the non-AJAX version if you are not happy with this slick new admin page, or if the plugin doesn't work as expected (ads not appearing on home/front page/posts, or any other kind of strange behavior). The AJAX version stores its options completely independent of the non-AJAX version. Your non-AJAX options are intact and untouched. Here are the steps to revert to the non-AJAX version:
  <ul style="list-style: circle inside none">
    <li>
      <a href='<?php echo $pluginURL; ?>' id='plugins' class='popup-long' target="_blank">Deactivate and delete</a> this plugin.
    </li>
    <li>
      <a href='<?php echo $downloadURL; ?>' target="_blank">Download</a> the non-AJAX version of the plugin.
    </li>
    <li>
      <a href='<?php echo $installImg; ?>' id='uploadHelp' target="_blank">Upload and activate</a> the downloaded non-AJAX version.
    </li>
    <li>
      Find and click on the the <strong><?php echo $plgName; ?></strong> menu item under your <strong>Settings</strong> menu, and you should see the non-AJAX version.
    </li>
  </ul>
  <strong class='red'>Please remember never to update the plugin if you are using the non-AJAX version, or it will get overwritten by the AJAX version. It's easy to recover though - just follow the above instructions again.</strong>
</div>
<div class="clearfix"></div>
<script>
  jQuery("#uploadHelp").click(function (e) {
    if (typeof bootbox !== "undefined") {
      e.preventDefault();
      bootbox.alert({title: 'How to Upload and Install a Plugin Zip file', message: '<p>To install follow steps 1 through 4 in the pictures below. Go to the admin page of your blog, click on "Plugins" on your sidebar menu, select "Add New" and click on "Upload". On some installations, you may be prompted to provide your FTP credentials.</p><br><img src="img/install.png" alt="Installation Help" style="max-width:80%;" class="center-block"/>'});
    }
  });
</script>

