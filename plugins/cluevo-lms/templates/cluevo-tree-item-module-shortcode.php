<?php
$curItem = cluevo_get_the_lms_page();
$module = null;
do_action('cluevo_enqueue_module_scripts');
$module = null;
if ($curItem->module_id >= 0) {
  $module = cluevo_get_module($curItem->module_id);
} else {
  $module = -1;
}
if ( cluevo_user_has_item_access_level() ) { ?>
<?php if (!empty($module)) { ?>
  <div class="cluevo-module-container">
    <?php if (is_object($module)) do_action('cluevo_display_module', [ "item" => $curItem, "module" => $module ] ); ?>
  </div>
<?php } else { cluevo_display_notice(__("Notice", "cluevo"), __("This module does not seem to exist.", "cluevo"), 'error'); }?>
<?php 
} else { cluevo_display_notice(__("Notice", "cluevo"), __("You do not have the required permissions to access this page.", "cluevo"), 'error'); }?>
