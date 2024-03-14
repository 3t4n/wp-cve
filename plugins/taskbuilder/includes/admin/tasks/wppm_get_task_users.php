<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
$wppm_users_role = get_option('wppm_user_role');
$task_id  = isset($_POST['task_id']) ? intval(sanitize_text_field($_POST['task_id'])) : '' ;
$proj_id = isset($_POST['proj_id']) ? intval(sanitize_text_field($_POST['proj_id'])) : '' ;
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_permission('assign_task_users',$task_id))) {exit;}
$task = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wppm_task where id = $task_id" );
$project_data = $wppmfunction->get_project($task->project);
if(!empty($project_data['users'])){
	$proj_users = explode(',',$project_data['users']);
}
if($task->users != null){
	$users = explode(",",$task->users);
}
ob_start();
?>
<form id="frm_get_task_users">
	<div id="wppm_get_task_users">
		<input type="text" id="wppm_task_user_name" class="wppm_task_user_name form-control regi_user_autocomplete ui-autocomplete-input" name="task_user_name" autocomplete="off" placeholder="<?php echo esc_attr__('Search User...','taskbuilder');?>">
		<div class="wppm_filter_display_container" id="wppm_task_users_display_container">
		<?php 
			if(!empty($users)){
				foreach($users as $user){ 
					if( (!empty($proj_users)) && in_array($user,$proj_users)){
						?>
						<div id="wppm_task_user_display_container_<?php echo esc_attr($user)?>" class="row wppm_task_user_display_container">
							<?php
							$userdata = get_userdata( $user ); ?>
							<div class="flex-container col-sm-4">
								<span class="wppm_filter_display_text">
									<?php echo esc_html($userdata->display_name); ?>
									<input type="hidden" name="user_names[]" value="<?php echo esc_attr($user) ?>">
								</span>
							</div>
							<div class="col-sm-4 wppm_delete_user_icon">
								<span onclick="wppm_remove_task_user_filter(<?php echo esc_attr($user)?>);"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/trash.svg'); ?>" alt="delete"></span>
							</div>
						</div>
					<?php } ?>
			<?php } 
			} else{
				?><span id="wppm_assign_to_none_task"><?php echo esc_html_e('None','taskbuilder');?> </span><?php
			} ?>
		</div>
		<input type="hidden" name="action" value="wppm_set_task_users" />
		<input type="hidden" id="wppm_task_id" name="task_id" value="<?php echo esc_attr($task_id) ?>" />
	</div>
</form>
<script>
jQuery(document).ready(function(){
	jQuery("input[name='task_user_name']").keypress(function(e) {
		//Enter key
		if (e.which == 13) {
			return false;
		}
	});
	jQuery( ".wppm_task_user_name" ).autocomplete({
		minLength: 1,
		appendTo: jQuery('.wppm_task_user_name').parent(),
		source: function( request, response ) {
			var term = request.term;
			request = {
				action: 'wppm_filter_autocomplete',
				term : term,
				field : 'task_user_name',
				task_id:<?php echo esc_attr($task_id) ?>,
				proj_id:<?php echo esc_attr($proj_id) ?>
			}
			jQuery.getJSON( wppm_admin.ajax_url, request, function( data, status, xhr ) {
				response(data);
			});
		},
		select: function (event, ui) {
			var html_str = '<div id="wppm_task_user_display_container_'+ui.item.user_id+'" class="row wppm_task_user_display_container">'
								+'<div class="flex-container col-sm-4">'
									+'<span class="wppm_filter_display_text">'
										+ui.item.label
										+'<input type="hidden" name="user_names[]" value="'+ui.item.user_id+'">'
									+'</span>'
								+'</div>'
								+'<div class="col-sm-4 wppm_delete_user_icon">'
									+'<span onclick="wppm_remove_task_user_filter('+ui.item.user_id+');"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/trash.svg'); ?>" alt="delete"></span>'
								+'</div>'	
							+'</div>';
			jQuery('#wppm_assign_to_none_task').hide();
			jQuery('#wppm_get_task_users #wppm_task_users_display_container').append(html_str);
			jQuery(this).val(''); return false;
		}
	}).focus(function() {
		jQuery(this).autocomplete("search", "");
	});

});
</script>
<?php
$body = ob_get_clean();
ob_start();
?>
<button type="button" class="btn wppm_popup_close" onclick="wppm_modal_close();"><?php echo esc_html_e('Close','taskbuilder');?></button>
<button type="button" class="btn wppm_popup_action" onclick="wppm_set_task_users(<?php echo esc_attr($proj_id) ?>);"><?php echo esc_html_e('Save','taskbuilder');?></button>

<?php
$footer = ob_get_clean();

$output = array(
    'body'      => $body,
    'footer'    => $footer
);

echo json_encode($output);