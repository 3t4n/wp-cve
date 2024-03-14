<?php
if ( ! defined( 'ABSPATH' ) || ! class_exists( 'GtbBootstrapSettingsPage', false ) ) exit;
include_once(dirname(__FILE__).'/../formfields.php');
global $gtb_options;
?>
<div class="wrap page-width">
   <h3><?php _e('Extra information',GUTENBERGBOOTSTRAP_SLUG)?></h3>

<div class="section-part extra">
   <h3><?php _e('Active Bootstrap Blocks Core Modules',GUTENBERGBOOTSTRAP_SLUG);?></h3>
   <ul>
<?php
$modules = GutenbergBootstrap::getModules();
foreach($modules as $module):
   $icon = $module->licensed?'dashicons-products"':'dashicons-admin-plugins'; 
?>
   <li><span class="dashicons-before <?=$icon?>"></span><?=$module->name?> (<?=$module->version?>)</li>
<?php endforeach; ?>
   </ul>
</div>
<?php require dirname( __FILE__ ) . '/get-pro-box.php'; ?>
</div>
