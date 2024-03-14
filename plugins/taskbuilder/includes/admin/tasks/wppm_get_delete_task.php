<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;
$id = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
ob_start();
?>
<form id="frm_delete_task">
    <div class="form-group">
        <p><?php echo esc_html_e('Are you sure to delete this task?','taskbuilder');?></p>
    </div>
    <input type="hidden" name="action" value="wppm_set_delete_task" />
    <input type="hidden" name="task_id" value="<?php echo htmlentities(esc_attr($id))?>" />
</form>
<?php
$body = ob_get_clean();
ob_start();
?>
<button type="button" class="btn wppm_popup_close"  onclick="wppm_modal_close();"><?php echo esc_html_e('Cancel','taskbuilder');?></button>
<button type="button" class="btn wppm_popup_action" onclick="wppm_set_delete_task();"><?php echo esc_html_e('Confirm','taskbuilder');?></button>
<?php
$footer = ob_get_clean();
$response = array(
    'body'      => $body,
    'footer'    => $footer
);
echo json_encode($response);
