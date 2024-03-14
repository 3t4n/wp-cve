<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
$project_id  = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '' ;
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_project_permission('change_project_status',$project_id))) {exit;}
$project_data = $wppmfunction->get_project($project_id);
$statuses = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wppm_project_statuses");
$status_id   	= $project_data['status'];
ob_start();
?>
<form id="frm_get_project_change_status" method="post">
	<div class="form-group">
		<label for="wppm_project_status" class="wppm_project_status"><?php echo esc_html_e('Project Status','taskbuilder');?></label>
		<select class="form-control" name="wppm_project_status">
			<?php
			if(!empty($statuses)){
				foreach ( $statuses as $status ) :
					$selected = $status_id == $status->id ? 'selected="selected"' : '';
					echo '<option '.esc_attr($selected).' value="'.esc_attr($status->id).'">'.esc_html($status->name).'</option>';
				endforeach;
			}
			?>
		</select>
	</div>
	<?php do_action('wppm_after_edit_change_project_status',$project_id);?>
  <input type="hidden" name="action" value="wppm_set_change_project_status" />
  <input type="hidden" id="wppm_project_id" name="project_id" value="<?php echo htmlentities(esc_attr($project_id)); ?>" />
	

</form>
<?php
$body = ob_get_clean();

ob_start();
?>
<button type="button" class="btn wppm_popup_close" onclick="wppm_modal_close();"><?php echo esc_html_e('Close','taskbuilder');?></button>
<button type="button" class="btn wppm_popup_action" onclick="wppm_set_change_project_status(<?php echo htmlentities(esc_attr($project_id))?>);"><?php echo esc_html_e('Save','taskbuilder');?></button>
<?php
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);

echo json_encode($output);
