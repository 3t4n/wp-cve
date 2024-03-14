<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user,$wpdb;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$priority_id = isset($_POST) && isset($_POST['priority_id']) ? intval(sanitize_text_field($_POST['priority_id'])) : 0;
if (!$priority_id) {exit;}

$priority = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_task_priorities WHERE id = $priority_id ");
ob_start();
?>
<div class="form-group">
  <label for="wppm_edit_priority_name"><?php echo esc_html_e('Priority Name','taskbuilder');?></label>
  <p class="help-block"><?php echo esc_html_e('Insert priority name. Please make sure priority name you are entering should not already exist.','taskbuilder');?></p>
  <input id="wppm_edit_priority_name" class="form-control" name="wppm_edit_priority_name" value="<?php echo esc_attr($priority->name)?>" />
</div>
<div class="form-group">
  <label for="wppm_edit_priority_color"><?php echo esc_html_e('Color','taskbuilder');?></label>
  <p class="help-block"><?php echo esc_html_e('Text color of priority.','taskbuilder');?></p>
  <input id="wppm_edit_priority_color" class="wppm_edit_color_picker" name="wppm_edit_priority_color" value="<?php echo esc_attr($priority->color)?>" />
</div>
<div class="form-group">
  <label for="wppm_edit_priority_bg_color"><?php echo esc_html_e('Background Color','taskbuilder');?></label>
  <p class="help-block"><?php echo esc_html_e('Background color of priority.','taskbuilder');?></p>
  <input id="wppm_edit_priority_bg_color" class="wppm_edit_color_picker" name="wppm_edit_priority_bg_color" value="<?php echo esc_attr($priority->bg_color)?>" />
</div>
<script>
  jQuery(document).ready(function(){
      jQuery('.wppm_edit_color_picker').wpColorPicker();
  });
</script>
<?php 
$body = ob_get_clean();
ob_start();
?>
<button type="button" class="btn wppm_popup_close" onclick="wppm_modal_close();"><?php echo esc_html_e('Close','taskbuilder');?></button>
<button type="button" class="btn wppm_popup_action" onclick="wppm_set_edit_priority(<?php echo htmlentities(esc_attr($priority_id))?>);"><?php echo esc_html_e('Submit','taskbuilder');?></button>
<?php 
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);

echo json_encode($output);