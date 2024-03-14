<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user,$wppmfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}
$wppm_public_projects_permission = isset($_POST) && isset(($_POST['wppm_public_projects_permission'])) ? sanitize_text_field($_POST['wppm_public_projects_permission']) : '0';
update_option('wppm_public_projects_permission',$wppm_public_projects_permission);
do_action('wppm_set_advanced_settings');

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','taskbuilder').'" }';