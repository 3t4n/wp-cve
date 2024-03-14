<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

$hsas_errors 		= array();
$hsas_success 		= '';
$hsas_error_found 	= FALSE;

$hsas_role_content 	= "";
$hsas_role_setting 	= "";

$form = array(
	'hsas_role_content' => '',
	'hsas_role_setting'	=> ''
);

if (isset($_POST['hsas_form_submit']) && $_POST['hsas_form_submit'] == 'yes') {
	$hsas_roles = get_option('horizontal-scrolling-roles', 'norecord');
	if($hsas_roles == 'norecord' || $hsas_roles == "") {
		add_option('horizontal-scrolling-roles', 'norecord');
	}
	
	check_admin_referer('hsas_rolhsas_add');
	$form['hsas_role_content'] 	= isset($_POST['hsas_role_content']) ? sanitize_text_field($_POST['hsas_role_content']) : '';
	$form['hsas_role_setting'] 	= isset($_POST['hsas_role_content']) ? sanitize_text_field($_POST['hsas_role_content']) : '';
	
	if ($hsas_error_found == FALSE) {
		update_option('horizontal-scrolling-roles', $form );		
		$form = array(
			'hsas_role_content' 	=> '',
			'hsas_role_setting'		=> ''
		);
		$hsas_success = __('Roles was successfully updated.', 'email-posts-to-subscribers');
	}
}

$hsas_roles = get_option('horizontal-scrolling-roles', 'norecord');
if($hsas_roles <> 'norecord' && $hsas_roles <> "") {
	$hsas_role_content 	= isset( $hsas_roles['hsas_role_content'] ) ? $hsas_roles['hsas_role_content'] : 'manage_options';
	$hsas_role_setting 	= isset( $hsas_roles['hsas_role_setting'] ) ? $hsas_roles['hsas_role_setting'] : 'manage_options';
}

?>

<div class="wrap">
	<h3><?php _e('Edit Permission', 'horizontal-scrolling-announcements'); ?></h3>
	<?php
	if ($hsas_error_found == TRUE && isset($hsas_errors[0]) == TRUE) {
		?><div class="error fade"><p><strong><?php echo $hsas_errors[0]; ?></strong></p></div><?php
	}
	if ($hsas_error_found == FALSE && strlen($hsas_success) > 0) {
		?><div class="updated fade"><p><strong><?php echo $hsas_success; ?></strong></p></div><?php
	}
	?>
	<div class="tool-box">
		<form name="form_roles" method="post" action="#">
      	<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"> 
						<label>
							<strong><?php _e('Plugin Permission', 'horizontal-scrolling-announcements'); ?></strong>
							<p class="description"><?php _e('Select user role to access plugin Menu. Only Admin user can change this value.', 'horizontal-scrolling-announcements'); ?></p>
						</label>
					</th>
					<td>
					<select name="hsas_role_content" id="hsas_role_content">
						<option value='manage_options' <?php if($hsas_role_content == 'manage_options') { echo "selected='selected'" ; } ?>>Administrator Only</option>
						<option value='edit_others_pages' <?php if($hsas_role_content == 'edit_others_pages') { echo "selected='selected'" ; } ?>>Administrator/Editor</option>
						<option value='edit_posts' <?php if($hsas_role_content == 'edit_posts') { echo "selected='selected'" ; } ?>>Administrator/Editor/Author/Contributor</option>
					</select>
					</td>
				</tr>
			</tbody>
		</table>
      <input type="hidden" name="hsas_form_submit" value="yes"/>
      <p class="submit">
		<?php if(current_user_can('administrator')) { ?>
		<input name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'horizontal-scrolling-announcements'); ?>" type="submit" />
		<?php } ?>
		<input name="cancel" id="cancel" class="button button-primary" value="<?php _e('Back', 'horizontal-scrolling-announcements'); ?>" type="button" onclick="_hsas_redirect()" />
		<input name="help" id="help" class="button button-primary" value="<?php _e('Help', 'horizontal-scrolling-announcements'); ?>" type="button" onclick="_hsas_help()" />
	</p>
	  <?php wp_nonce_field('hsas_rolhsas_add'); ?>
    </form>
	</div>
</div>