<?php
/*
* share folder popup
*/

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}
?>

<div class="upfp-popup share_folder_popup upfp-hidden">
	<div class="upf_inner">
		
		<h4><?php echo __("Share This Folder with others", "user-private-files"); ?></h4>
		
		<span class="closePopup close_share_folder_popup">X</span>
		
		<?php
		$curr_user_id = get_current_user_id();
		if( user_can( $curr_user_id, 'administrator' ) ){
		?>
		
			<p><input type="radio" name="fldr_share_type" id="fldrSingleShare" checked><label for="fldrSingleShare"><?php echo __("Single User", "user-private-files"); ?></label></p>
			<form id="upf_allow_folder_access_frm">
				<input type="text" placeholder="<?php echo __("Email Address or Username", "user-private-files"); ?>" id="fldr_allowed_usr_mail" required>
				<select class="upfp_share_acs_lvl" required>
					<option value="lmtd"><?php echo __("View Only", "user-private-files"); ?></option>
					<option value="full"><?php echo __("Full Access", "user-private-files"); ?></option>
				</select>
				<input type="submit" value="<?php echo __("Allow Access", "user-private-files"); ?>">
			</form>
			
			
			<p><input type="radio" name="fldr_share_type" id="fldrRoleShare"><label for="fldrRoleShare"><?php echo __("Users with Role", "user-private-files"); ?></label></p>
			<form id="upf_allow_folder_access_frm_to_role" style="display: none;">
				<?php 
				global $wp_roles;
				$all_roles = $wp_roles->roles;
				$editable_roles = apply_filters('editable_roles', $all_roles);
				$options = '';
				foreach($editable_roles as $key => $val){
					$options .= '<option value="'.$key.'">'.$val['name'].'</option>';
				}
				?>
				
				<select id="fldr_allowed_role" required>
					<?php echo $options; ?>
				</select>
				<select class="upfp_share_acs_lvl" required>
					<option value="lmtd"><?php echo __("View Only", "user-private-files"); ?></option>
					<option value="full"><?php echo __("Full Access", "user-private-files"); ?></option>
				</select>
				<input type="submit" value="<?php echo __("Allow Access", "user-private-files"); ?>">
			</form>
			
			<p><input type="radio" name="fldr_share_type" id="fldrAllShare"><label for="fldrAllShare"><?php echo __("All Users", "user-private-files"); ?></label></p>
			<form id="upf_allow_folder_access_frm_all" style="display: none;">
				<select class="upfp_share_acs_lvl" required>
					<option value="lmtd"><?php echo __("View Only", "user-private-files"); ?></option>
					<option value="full"><?php echo __("Full Access", "user-private-files"); ?></option>
				</select>
				<input type="submit" value="<?php echo __("Allow Access", "user-private-files"); ?>">
			</form>
			
		<?php 
		} 
		else{
		?>
		
			<form id="upf_allow_folder_access_frm">
			
				<input type="email" placeholder="<?php echo __("Email Address", "user-private-files"); ?>" id="fldr_allowed_usr_mail" required>
				<select class="upfp_share_acs_lvl" required>
					<option value="lmtd"><?php echo __("View Only", "user-private-files"); ?></option>
					<option value="full"><?php echo __("Full Access", "user-private-files"); ?></option>
				</select>
				
				<input type="submit" value="<?php echo __("Allow Access", "user-private-files"); ?>">
				
			</form>
			
		<?php } ?>
		
		<p class="upfp-error" style="display: none;"></p>
		
	</div>
</div>