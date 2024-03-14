<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
$task_id  = isset($_POST['task_id']) ? intval(sanitize_text_field($_POST['task_id'])) : '' ;
$proj_id = isset($_POST['proj_id']) ? intval(sanitize_text_field($_POST['proj_id'])) : '' ;
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_permission('change_status',$task_id))) {exit;}
$task_data = $wppmfunction->get_task($task_id);
$statuses = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wppm_task_statuses");
$status_id   	= $task_data['status'];
$priority_id 	= $task_data['priority'];
ob_start();
?>
<form id="frm_get_task_change_status" method="post">
	<div class="form-group">
		<label class="wppm_edit_task_status_label" for="wppm_default_task_status"><?php echo esc_html_e('Task Status','taskbuilder');?></label>
		<select class="form-control" name="wppm_status">
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
	<?php do_action('wppm_after_edit_change_task_status',$task_id);?>
  <input type="hidden" name="action" value="wppm_set_change_task_status" />
  <input type="hidden" id="wppm_task_id" name="task_id" value="<?php echo htmlentities(esc_attr($task_id)) ?>" />
	

</form>
<?php
$body = ob_get_clean();

ob_start();
?>
<button type="button" class="btn wppm_popup_close" onclick="wppm_modal_close();"><?php echo esc_html_e('Close','taskbuilder');?></button>
<button type="button" class="btn wppm_popup_action" onclick="wppm_set_change_task_status(<?php echo htmlentities(esc_attr($task_id))?>,<?php echo esc_attr($proj_id) ?> );"><?php echo esc_html_e('Save','taskbuilder');?></button>
<?php
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);

echo json_encode($output);
