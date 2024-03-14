<?php require 'header.php'; ?>
<div>
  <ul class="breadcrumb">
    <li>
      <a href="#">Home</a>
    </li>
    <li>
      <a href="#">Configuration</a>
    </li>
  </ul>
</div>

<?php
openBox("Other Options", "th-list", 11, "The table below is editable. You can click on the option values and enter new values.  Hover over the <i class='glyphicon glyphicon-question-sign blue'></i> <b>Help</b> button on the row for quick info on what that option does.");
?>
<p>This feature is available in the <a href="#" class="goPro">Pro version</a> of this plugin, which allows you to set a few global options:</p>
<ul>
  <li><strong>Suspend All Ads</strong>: Check this to temporarily suspend all ad serving. This will set a global option <code>adsense-suspend-ads</code> which can be used even in theme files to prevent ads from appearing, or for other formatting based on ads.</li>
  <li><strong>Select Admin Theme</strong>: If you are not crazy about the default color scheme of the admin pages, you can change it here.</li>
  <li><strong>Enable Breadcrumbs</strong>: On the plugin admin page, you can have breadcrumbs so that you can see where you are. This feature is of questionable value on an admin page, and is disabled by default.</li>
  <li><strong>Enable Statistics</strong>: (Optional Paid Module required) If you wish to track your ad statistics, please check enable it here. Although designed to be lightweight, please note that statistics collection may impact your server performance depending on the kind of hosting you have. If you feel that your server cannot handle statistics, disable it here.</li>
</ul>
<?php
if (file_exists("options-advanced.php")) {
  require_once "options-advanced.php";
}
else {
  ?>
  <hr>
  <h4>Screenshot of the Options Screen the <a href="#" class="goPro">Pro</a> Version</h4>
  <?php
  showScreenshot(3);
}
?>
<div class="clearfix"></div>
<?php
closeBox();
include 'promo.php';
require 'footer.php';
