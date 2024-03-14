<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $current_user,$wppmfunction;
$task_id     = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '' ;

if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_permission('clone_task',$task_id))) {
 exit;
}
$task_name     = $wppmfunction->get_task_fields($task_id,'task_name');
ob_start();

?>
<form id="frm_edit_clone_task_name">
    <div class="form-group">
      <input type="text" id="task_name"class="form-control" name="task_name" value="<?php echo esc_attr($task_name); ?>"/>
    </div>
    <input type="hidden" name="action" value="wppm_set_clone_task" />
    <input type="hidden" name="task_id" value="<?php echo esc_attr($task_id) ?>" />
    <input type="hidden" name="_ajax_nonce" value="<?php echo wp_create_nonce('set_clone_task')?>">
</form>

<?php

$body = ob_get_clean();

ob_start();

?>
<button type="button" class="btn wppm_popup_close" onclick="wppm_modal_close();"><?php esc_html_e('Cancel','taskbuilder');?></button>
<button type="button" class="btn wppm_popup_action" onclick="wppm_set_clone_task();"><?php esc_html_e('Save Changes','taskbuilder');?></button>
<?php

$footer = ob_get_clean();

$response = array(
    'body'      => $body,
    'footer'    => $footer
);

echo json_encode($response);
?>