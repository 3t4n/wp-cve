<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
$wppm_users_role = get_option('wppm_user_role');
$proj_id  = isset($_POST['proj_id']) ? sanitize_text_field($_POST['proj_id']) : '' ;
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_project_permission('assign_project_users',$proj_id))) {exit;}
$project = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wppm_project where id = $proj_id" );
if(!empty($project->users)){
	$users = explode(",",$project->users);
}
ob_start();
?>
<form id="frm_get_project_users">
	<div id="wppm_get_users">
		<input type="text" id="wppm_user_name" class="wppm_user_name form-control regi_user_autocomplete ui-autocomplete-input" name="user_name" autocomplete="off" placeholder="Search User">
		<div class="wppm_filter_display_container" id="wppm_proj_users_display_container">
			<?php if(!empty($users)){
				foreach($users as $user){ ?>
					<div id="wppm_user_display_container_<?php echo esc_attr($user)?>" class="row wppm_user_display_container">
						<?php
						$userdata = get_userdata( $user );
						$project_user = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_project_users WHERE proj_id = '$proj_id' AND user_id = '$user'");
						?>
						<div class="flex-container col-sm-4">
							<span class="wppm_filter_display_text">
								<?php  echo esc_html_e($userdata->display_name,'taskbuilder'); ?>
								<input type="hidden" name="user_names[]" value="<?php echo esc_attr($user) ?>">
							</span>
						</div>
						<div class="col-sm-4 wppm_user_role">
							<select size="sm" class="form-control" id="wppm_select_user_role_<?php echo esc_attr($user) ?>" name="wppm_select_user_role_<?php echo esc_attr($user) ?>">
							<?php
							if(!empty($wppm_users_role)){ 
								foreach($wppm_users_role as $key=>$role){
									if(!empty($role)){
										foreach($role as $k=>$val){
											$selected = (!empty($project_user) && $key == $project_user->role_id) ? 'selected="selected"' : '';
											?><option <?php echo $selected?> value="<?php echo esc_attr($key) ?>"><?php echo esc_html_e($role['label'],'taskbuilder') ?></option><?php
										}
									}
								}
							}
							?>
							</select>
						</div>
						<div class="col-sm-4 wppm_delete_user_icon">
							<span onclick="wppm_remove_filter(<?php echo esc_attr($user)?>);"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/trash.svg'); ?>" alt="delete"></span>
						</div><?php
						?>
					</div>
			<?php }
			} else{
				?><span class="wppm_project_users_not_assign_label"><?php echo esc_html_e('None','wp_projects');?></span><?php
			}
			?> 
		</div>
		<input type="hidden" name="action" value="wppm_set_project_users" />
		<input type="hidden" id="wppm_proj_id" name="proj_id" value="<?php echo esc_attr($proj_id) ?>" />
	</div>
</form>
<script>
	jQuery(document).ready(function(){
		jQuery("input[name='user_name']").keypress(function(e) {
			//Enter key
			if (e.which == 13) {
				return false;
			}
		});
		
		jQuery( ".wppm_user_name" ).autocomplete({
			minLength: 1,
			appendTo: jQuery('.wppm_user_name').parent(),
			source: function( request, response ) {
				var term = request.term;
				request = {
					action: 'wppm_filter_autocomplete',
					term : term,
					field : 'users_name',
				}
				jQuery.getJSON( wppm_admin.ajax_url, request, function( data, status, xhr ) {
					response(data);
				});
			},
			select: function (event, ui) {
				var html_str = '<div id="wppm_user_display_container_'+ui.item.user_id+'" class="row wppm_user_display_container">'
									+'<div class="flex-container col-sm-4">'
										+'<span class="wppm_filter_display_text">'
											+ui.item.label
											+'<input type="hidden" name="user_names[]" value="'+ui.item.user_id+'">'
										+'</span>'
									+'</div>'
									+'<div class="col-sm-4 wppm_user_role">'
											+'<select size="sm" class="form-control" id="wppm_select_user_role_'+ui.item.user_id+'" name="wppm_select_user_role_'+ui.item.user_id+'">'+
											<?php 
												if(!empty($wppm_users_role)){
													foreach($wppm_users_role as $key=>$role){
														if(!empty($role)){
															foreach($role as $k=>$val){
															?>'<option value="<?php echo esc_attr($key) ?>"><?php echo esc_html_e($role['label'],'taskbuilder') ?></option>'+<?php
															}
														}
													}
												}
											?>
											'</select>'
									+'</div>'
									+'<div class="col-sm-4 wppm_delete_user_icon">'
										+'<span onclick="wppm_remove_filter('+ui.item.user_id+');"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/trash.svg'); ?>" alt="delete"></span>'
									+'</div>'	
								+'</div>';
				jQuery('.wppm_project_users_not_assign_label').hide();
				jQuery('#wppm_get_users #wppm_proj_users_display_container').append(html_str);
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
<button type="button" class="btn wppm_popup_action" onclick="wppm_set_project_users();"><?php echo esc_html_e('Save','taskbuilder');?></button>
<?php
$footer = ob_get_clean();
$output = array(
    'body'      => $body,
    'footer'    => $footer
);
echo json_encode($output);