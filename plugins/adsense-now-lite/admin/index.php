<?php require 'header.php'; ?>
<div>
  <ul class="breadcrumb">
    <li>
      <a href="#">Home</a>
    </li>
    <li>
      <a href="#">Dashboard</a>
    </li>
  </ul>
</div>
<?php
insertAlerts();
openBox("Quick Start and Tour", "home", 12);
require 'tour.php';
closeBox();
?>
<div id="features" style="display: none">
  <?php
  openBox("Features and Benefits", "thumbs-up", 12);
  require_once "$plgMode-intro.php";
  closeBox();
  ?>
</div>
<div id="killAjax" style="display: none">
  <?php
  openBox("AJAX Issues", "thumbs-up", 12);
  $isPro = EzGA::$isPro;
  $installImg = "img/install.png";
  require_once "no-ajax.php";
  closeBox();
  ?>
</div>
<?php
require 'promo.php';
if (file_exists('demo-box.php')) {
  include 'demo-box.php';
}
require 'footer.php';
