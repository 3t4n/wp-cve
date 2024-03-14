<?php
require 'header.php';
$plgName = EzGA::getPlgName();
$plgSlug = EzGA::getPlgMode();
if ($plgSlug == 'google-adsense-ultra') {
  $plgSlug = 'google-adsense';
}
$plgPrice = EzGA::$plgPrice;
require_once '../lib/Ftp.php';
$ftp = new Ftp();
require_once 'Updater.php';
$updater = new Updater($plgSlug);
?>
<div>
  <ul class="breadcrumb">
    <li>
      <a href="#">Home</a>
    </li>
    <li>
      <a href="#">Update <?php echo $plgName; ?></a>
    </li>
  </ul>
</div>
<style type="text/css">
  label{width:100px;}
</style>
<?php
insertAlerts(11);
openBox("Update or Upgrade Your Product", "plus", 11, "<p>It is easy to update your application. Once you have downloaded an update package, please use the Browse button in the <b>Upload Your Zip File</b> section. When you have selected the zip file to upload, the updater will take care of the rest.</p>"
        . "<p>If you have purchased a <a href='#' class='goPro'>Pro upgrade</a>, the process is identical. Just browse and upload the zip file."
        . "<p>In some installations, you may need to provide FTP details for the updater to work. If needed, you will be prompted for the credentials. Contact your hosting provider or system admin for details.</p>"
        . "<p><a class='showDiag btn-sm btn-primary'>Show Diagnostics</a></p>");
?>
<div class="clearfix">&nbsp;</div>
<?php
if (EzGA::$isPro) {
  $localVersion = $updater->getLocalVersion();
  $remoteVersion = $updater->getRemoteVersion();
  $toolTip = $updater->getUpdateText();
  if ($updater->isOld()) {
    ?>
    <div class="col-md-3 col-sm-3 col-xs-6 update">
      <a data-toggle="tooltip" title="<?php echo $toolTip; ?>" class="well top-block update" href="#">
        <i class="glyphicon glyphicon-hand-up red"></i>
        <div><?php echo $plgName; ?> V<?php echo $localVersion; ?></div>
        <div>Update to V<?php echo $remoteVersion; ?></div>
        <span class="notification red"><?php echo "V$remoteVersion"; ?></span>
      </a>
    </div>
    <?php
  }
  else {
    ?>
    <div class="col-md-3 col-sm-3 col-xs-6">
      <a data-toggle="tooltip" title="<?php echo $toolTip; ?>" class="well top-block" href="#">
        <i class="glyphicon glyphicon-thumbs-up red"></i>
        <div><?php echo $plgName; ?> V<?php echo $localVersion; ?></div>
        <div>Your version is up-to-date</div>
      </a>
    </div>
    <?php
  }
}
else {
  $plgUrl = admin_url('plugins.php');
  ?>
  <div class="col-md-3 col-sm-3 col-xs-6">
    <a data-toggle="tooltip" title="Please keep your plugin regularly updated for security, both yours and that of your readers." class="well top-block endpoint" href="<?php echo $plgUrl; ?>" target="_parent">
      <i class="glyphicon glyphicon-hand-up red"></i>
      <div>Check for Updates</div>
      <div>Please visit your plugins page.</div>
    </a>
  </div>
  <?php
}
if (EzGA::$isPro) {
  ?>
  <div class="col-md-3 col-sm-3 col-xs-6">
    <a data-toggle="tooltip" title="See other premium WordPress plugins and PHP programs by the same author." class="well top-block" href="http://www.thulasidas.com/render" target="_blank">
      <i class="glyphicon glyphicon-star green"></i>
      <div>Other Plugins and Programs</div>
      <div>From the author</div>
    </a>
  </div>
  <?php
}
else {
  $basePlgName = str_replace(" Lite", "", $plgName);
  ?>
  <div class="col-md-3 col-sm-3 col-xs-6 goPro" data-product="<?php echo $plgSlug; ?>">
    <a data-toggle="tooltip" title="Get the Pro version of <?php echo $basePlgName; ?> for only $<?php echo $plgPrice[$plgSlug]; ?>. Tons of extra features. Instant download." class="well top-block goPro" href="http://buy.thulasidas.com/<?php echo $plgSlug; ?>">
      <i class="glyphicon glyphicon-shopping-cart red"></i>
      <div>Get <?php echo $basePlgName; ?> Pro</div>
      <div>$<?php echo $plgPrice[$plgSlug]; ?>. Instant Download</div>
      <span class="notification red">Pro</span>
    </a>
  </div>
  <?php
}
?>
<div class="col-md-3 col-sm-3 col-xs-6">
  <a data-toggle="tooltip" title="Check out the author's blog for more information about the author. It has links to his credentials as well." class="well top-block" href="http://www.thulasidas.com/" target="_blank">
    <i class="glyphicon glyphicon-user blue"></i>
    <div>Author Profile</div>
    <div>CV from Author's blog</div>
  </a>
</div>
<div class="col-md-3 col-sm-3 col-xs-6">
  <a data-toggle="tooltip" title="If you need support with this application, please visit our support portal and raise a ticket for $0.95. The Pro version (or any purchase from us) comes with free support for a short period." class="well top-block" href="http://support.thulasidas.com/" target="_blank">
    <i class="glyphicon glyphicon-envelope yellow"></i>
    <div>Contact</div>
    <div>Enquiries and Support</div>
  </a>
</div>
<div class="clearfix"></div>
<hr>
<div id="updateDiv">
  <h4>Upload Your Upgrade/Update</h4>
  <table class="table table-striped table-bordered responsive">
    <tbody>
      <tr>
        <td>Zip File</td>
        <td style='width:70%'><a id='file' class='red' data-name='file' data-type='file'  data-mode='inline' data-validator='notNull'><input id='fileinput' type='file' class='file' multiple=true data-show-preview='false' data-show-upload='false'> </a></td>
        <td class='center-text'><a style='font-size:1.5em' data-content='Browse to the zip file you have downloaded either for update, or Pro upgrade. Once selected, you will be asked to confirm the update, and the rest will be taken care of.' data-help='' data-toggle='popover' data-placement='left' data-trigger='hover' title='Your Digital Product' ><i class='glyphicon glyphicon-question-sign blue'></i></a></td>
      </tr>
    </tbody>
  </table>
</div>
<div class="clearfix"></div>
<div class="center red" id="loading" style="display:none;font-size:1.3em;width:100%"><i class="fa fa-spinner fa-spin"></i> Working! Please wait...</div>
<hr>
<?php
echo $ftp->printForm();
closeBox();
?>
<div id="diag" style="display:none">
  <pre>
<h3>Diagnostics</h3>
  <p><a class='showDiag btn-sm btn-primary'>Hide Diagnostics</a></p><br><?php echo $updater->getDiagnostics(); ?></pre>
</div>
<script>
  $(document).ready(function () {
    var file;
    function ajaxUpload(_file) {
      var data = new FormData();
      data.append('file', _file);
      $.ajax({
        url: 'ajax/update.php',
        type: 'POST',
        dataType: 'json',
        data: data,
        processData: false,
        contentType: false,
        success: function (response) {
          $("#loading").hide();
          showSuccess(response.success);
          flashWarning(response.warning);
        },
        error: function (a) {
          $("#loading").hide();
          showError(a.responseText);
        }
      });
    }
    $("#fileinput").on('change', function (event) {
      file = event.target.files[0];
      if (file) {
        bootbox.confirm("<p>Are you sure you want to upload <code>" + file.name + "</code> to update/upgrade your <b><?php echo $plgName; ?></b> plugin? The update process is designed to be safe, but it will replace your existing files and may modify your database tables.</p><p class='red'> <em>Keeping a backup of your files and database is highly recommended.</em></p>", function (result) {
          if (result) {
            $("#updateDiv").hide();
            $("#loading").fadeIn();
            ajaxUpload(file);
          }
          else {
            flashWarning("File not uploaded. Browse again to upload a new file to update or upgrade your <b><?php echo $plgName; ?></b> plugin.");
            $("#loading").hide();
            $("#updateDiv").fadeIn();
          }
        });
      }
    });
    $('.update').click(function (e) {
      e.preventDefault();
      var url = 'http://buy.thulasidas.com/update.php';
      var title = "Check for Updates";
      var w = 1024;
      var h = 728;
      return ezPopUp(url, title, w, h);
    });
    $('body').on('click', ".showDiag", function (e) {
      e.preventDefault();
      $("#diag").toggle();
    });
  });
</script>

<?php
require 'footer.php';
