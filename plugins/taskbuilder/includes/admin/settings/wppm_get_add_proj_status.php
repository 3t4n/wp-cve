<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}
ob_start();
?>
<div class="form-group">
  <label for="wppm_proj_status_name"><?php echo esc_html_e('Status Name','taskbuilder');?></label>
  <p class="help-block"><?php echo esc_html_e('Insert status name. Please make sure status name you are entering should not already exist.','taskbuilder');?></p>
  <input id="wppm_proj_status_name" class="form-control" name="wppm_proj_status_name" value="" />
</div>
<div class="form-group">
  <label for="wppm_proj_status_color"><?php echo esc_html_e('Color','taskbuilder');?></label>
  <p class="help-block"><?php echo esc_html_e('Text color of status.','taskbuilder');?></p>
  <input id="wppm_proj_status_color" class="wppm_color_picker" name="wppm_status_color" value="#ffffff" />
</div>
<div class="form-group">
  <label for="wppm_proj_status_bg_color"><?php echo esc_html_e('Background Color','taskbuilder');?></label>
  <p class="help-block"><?php echo esc_html_e('Background color of status.','taskbuilder');?></p>
  <input id="wppm_proj_status_bg_color" class="wppm_color_picker" name="wppm_status_bg_color" value="#1E90FF" />
</div>
<script>
  jQuery(document).ready(function(){
      jQuery('.wppm_color_picker').wpColorPicker();
  });
</script>
<?php 
$body = ob_get_clean();
ob_start();
?>
<button type="button" class="btn wppm_popup_close" onclick="wppm_modal_close();"><?php echo esc_html_e('Close','taskbuilder');?></button>
<button type="button" class="btn wppm_popup_action" onclick="wppm_set_add_proj_status();"><?php echo esc_html_e('Submit','taskbuilder');?></button>
<?php 
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);

echo json_encode($output);
