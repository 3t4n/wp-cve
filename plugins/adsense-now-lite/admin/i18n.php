<?php
require 'header.php';
?>
<div>
  <ul class="breadcrumb">
    <li>
      <a href="#">Home</a>
    </li>
    <li>
      <a href="#">Languages</a>
    </li>
  </ul>
</div>

<?php
openBox("Languages and Internationlization", "globe");
$plgName = "ez-adsense";
?>
<p>This plugin can display its admin pages in your language using machine translation by Google<sup>&reg;</sup>.</p>
<?php
$show_google_translate = array('name' => __('Enable Google Translation of Admin Pages?', 'easy-adsenser'),
    'help' => __('This plugin can display its admin pages using Google Translate. If you would like to see the pages in your language, please enable this option. You will then see a language selector near the top right corner where you can choose your language.', 'easy-adsenser'),
    'type' => 'checkbox',
    'reload' => true,
    'value' => false);
?>
<div  class="col-md-9" ><table class="table table-striped table-bordered responsive">
    <thead>
      <tr>
        <th style="width:50%;min-width:150px">Option</th>
        <th style="width:55%;min-width:80px">Value</th>
        <th class="center-text" style="width:15%;min-width:50px">Help</th>
      </tr>
    </thead>
    <tbody>
      <?php
      echo EzGA::renderOption('show_google_translate', $show_google_translate);
      ?>
    </tbody>
  </table>
</div>
<div class="clearfix"></div>
<?php
closeBox();
include 'promo.php';
?>
<script>
  var xeditHandler = 'ajax/options.php';
  var xparams = {};
</script>
<?php
require 'footer.php';
