<?php require 'header.php'; ?>
<div>
  <ul class="breadcrumb">
    <li>
      <a href="#">Home</a>
    </li>
    <li>
      <a href="#">Migrate Data</a>
    </li>
  </ul>
</div>

<?php
openBox("Migrate Old Options", "user", 12);
?>
<p>This version of the Ads EZ Plugin for AdSense uses a new data/option model. When the plugin is activated, all the options from previous versions of Easy AdSense, Google AdSense, AdSense Now, Easy Ads, Easy Chitika etc. are detected and copied over to the new options model. If you have made further changes in the old plugin options and would like to migrate them as well, you can do so here.</p>
<p>Note that clicking on the Migrate button does not overwrite existing (already migrated) options. It will only migrate new options created (for instance, by switching to a new theme and running a previous version of one of the afore-mentioned plugins).</p>
<div><a href="?migrate" class="btn btn-primary" title="Migrate " data-toggle="tooltip">Migrate Now</a> </div>
<div class="clearfix">&nbsp;</div>
<?php
if (isset($_REQUEST['migrate'])) {
  require_once 'Migrator.php';
  $migrator = new Migrator();
  $migrator->migrate(true);
}
closeBox();
require 'promo.php';
require 'footer.php';
