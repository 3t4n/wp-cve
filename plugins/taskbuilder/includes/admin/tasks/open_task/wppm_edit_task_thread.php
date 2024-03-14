<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $wpdb,$wppmfunction,$current_user;
$comment_id = isset($_POST['comment_id']) ? intval(sanitize_text_field($_POST['comment_id'])) : '' ;
$task_id  = isset($_POST['task_id']) ? intval(sanitize_text_field($_POST['task_id'])) : '' ;
$proj_id  = isset($_POST['proj_id']) ? intval(sanitize_text_field($_POST['proj_id'])) : '' ;
$task_data = $wppmfunction->get_task($task_id);
$task_comment = $wppmfunction->get_task_comment($comment_id);
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || ($wppmfunction->has_permission('change_task_details',$task_id)) || ($current_user->ID == $task_comment['created_by']))) {exit;}
ob_start();
?>
<form id="frm_edit_task_thread" method="post">
	<div>
        <textarea id="wppm_edit_task_thread_editor">
        <?php echo stripslashes(htmlspecialchars_decode(esc_textarea($task_comment['body']),ENT_QUOTES))?>
        </textarea>
	</div>
  <input type="hidden" name="action" value="wppm_set_edit_task_thread" />
  <input type="hidden" id="wppm_task_id" name="task_id" value="<?php echo htmlentities(esc_attr($task_id)) ?>" />
  <input type="hidden" id="wppm_comment_id" name="comment_id" value="<?php echo htmlentities(esc_attr($comment_id)) ?>" />
</form>
<script>
jQuery( document ).ready( function( jQuery ) {
    tinymce.remove();
    tinymce.init({ 
        selector:'#wppm_edit_task_thread_editor',
        body_id: 'wppm_edit_task_thread_editor',
        directionality : '<?php //echo 'rtl'; ?>',
        menubar: false,
        statusbar: false,
        height : '200',
        width  : '100%', 
        plugins: [
            'lists link image directionality'
        ],
        image_advtab: true,
        toolbar: 'bold italic underline blockquote | alignleft aligncenter alignright | bullist numlist | rtl | link image',
        branding: false,
        autoresize_bottom_margin: 20,
        browser_spellcheck : true,
        relative_urls : false,
        remove_script_host : false,
        convert_urls : true,
        setup: function (editor) {
        }
    });
});
<?php
$body = ob_get_clean();

ob_start();
?>
<button type="button" class="btn wppm_popup_close" onclick="wppm_modal_close();"><?php echo esc_html_e('Close','taskbuilder');?></button>
<button type="button" class="btn wppm_popup_action" onclick="wppm_set_edit_task_thread(<?php echo htmlentities(esc_attr($task_id))?>,<?php echo esc_attr($proj_id) ?>);"><?php echo esc_html_e('Save','taskbuilder');?></button>
<?php
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);

echo json_encode($output);