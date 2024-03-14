<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$cat_id = isset($_POST) && isset($_POST['cat_id']) ? intval(sanitize_text_field($_POST['cat_id'])) : 0;
if (!$cat_id) {exit;}

$category = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_project_categories WHERE id = $cat_id ");
ob_start();
?>
<div class="form-group">
  <label for="wppm_edit_cat_name"><?php echo esc_html_e('Category Name','taskbuilder');?></label>
  <p class="help-block"><?php echo esc_html_e('Insert category name.','taskbuilder');?></p>
  <input id="wppm_edit_cat_name" class="form-control" name="wppm_edit_cat_name" value="<?php echo esc_html_e($category->name,'taskbuilder');?>" />
</div>
<?php 
$body = ob_get_clean();
ob_start();
?>
<button type="button" class="btn wppm_popup_close" onclick="wppm_modal_close();"><?php echo esc_html_e('Close','taskbuilder');?></button>
<button type="button" class="btn wppm_popup_action" onclick="wppm_set_edit_category(<?php echo htmlentities(esc_attr($cat_id))?>);"><?php echo esc_html_e('Save Changes','taskbuilder');?></button>
<?php 
$footer = ob_get_clean();
$output = array(
  'body'   => $body,
  'footer' => $footer
);
echo json_encode($output);