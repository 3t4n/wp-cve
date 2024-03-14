<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
$task_id  = isset($_POST['task_id']) ? intval(sanitize_text_field($_POST['task_id'])) : '' ;
$proj_id = isset($_POST['proj_id']) ? intval(sanitize_text_field($_POST['proj_id'])) : '' ;
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_permission('change_raised_by',$task_id))) {exit;}
$task_data = $wppmfunction->get_task($task_id);
$task_creator = get_userdata($task_data['created_by']);
ob_start();
?>
<form id="frm_get_task_creator" method="post">
	<div class="row" style="padding-left:2px;">
		<div class="col-sm-12">
    	    <label class="wppm_ct_field_label" for="user_name"><?php echo esc_html_e('Task Creator Name','taskbuilder') ?> </label>
			<input type="text" id="user_name" class="form-control wppm_regi_user_autocomplete ui-autocomplete-input" name="customer_name" autocomplete="off" value="<?php echo htmlentities(stripcslashes(esc_attr($task_creator->display_name)))?>">
   	    </div>
    </div>
    <input type="hidden" name="action" value="wppm_set_change_raised_by" />
    <input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr($task_creator->ID) ?>">
    <input type="hidden" name="task_id" value="<?php echo htmlentities(esc_attr($task_id)) ?>" />
</form>
<script>
	jQuery(document).ready(function(){
  	jQuery( ".wppm_regi_user_autocomplete" ).autocomplete({
        minLength: 1,
        appendTo: jQuery('.wppm_regi_user_autocomplete').parent(),
        source: function( request, response ) {
            var term = request.term;
            request = {
                action : 'wppm_filter_autocomplete',
                term : term,
                field : 'users_name'
            }
            jQuery.getJSON( wppm_admin.ajax_url, request, function( data, status, xhr ) {
                response(data);
            });	
			},
        minLength: 2,
            select: function (event, ui) {
                jQuery('#user_name').val(ui.item.value);
                jQuery('#user_id').val(ui.item.user_id);
            }
		});
	
	});
</script>
<?php 
$body = ob_get_clean();
ob_start();
?>
<button type="button" class="btn wppm_popup_close" onclick="wppm_modal_close();"><?php echo esc_html_e('Close','taskbuilder');?></button>
<button type="button" class="btn wppm_popup_action" onclick="wppm_set_change_raised_by(<?php echo esc_attr($task_id) ?>,<?php echo esc_attr($proj_id) ?>);"><?php echo esc_html_e('Save','taskbuilder');?></button>
<?php 
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);

echo json_encode($output);