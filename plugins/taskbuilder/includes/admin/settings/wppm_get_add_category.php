<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}
ob_start();
?>
<div class="form-group">
  <label for="wppm_cat_name"><?php echo esc_html_e('Category Name','taskbuilder');?></label>
  <p class="help-block"><?php echo esc_html_e('Insert category name. Please make sure category name you are entering should not already exist.','taskbuilder');?></p>
  <input id="wppm_cat_name" class="form-control" name="wppm_cat_name" value="" />
</div>
<?php 
$body = ob_get_clean();
ob_start();
?>
<button type="button" class="btn wppm_popup_close" onclick="wppm_modal_close();"><?php echo esc_html_e('Close','taskbuilder');?></button>
<button type="button" class="btn wppm_popup_action" onclick="wppm_set_add_category();"><?php echo esc_html_e('Submit','taskbuilder');?></button>
<?php 
$footer = ob_get_clean();
$output = array(
  'body'   => $body,
  'footer' => $footer
);
echo json_encode($output);
