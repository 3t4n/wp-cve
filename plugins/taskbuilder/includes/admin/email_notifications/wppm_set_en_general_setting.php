<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wppmfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

// From Name
$from_name = isset($_POST) && isset($_POST['wppm_en_from_name']) ? sanitize_text_field($_POST['wppm_en_from_name']) : '';
update_option('wppm_en_from_name',$from_name);

// From Email
$from_email = isset($_POST) && isset($_POST['wppm_en_from_email']) ? sanitize_text_field($_POST['wppm_en_from_email']) : '';
update_option('wppm_en_from_email',$from_email);

// Block emails
$ignore_emails = isset($_POST) && isset($_POST['wppm_en_ignore_emails']) ? explode("\n", ($_POST['wppm_en_ignore_emails'])) : array();
$ignore_emails = $wppmfunction->sanitize_array($ignore_emails);
update_option('wppm_en_ignore_emails',$ignore_emails);

do_action('wppm_set_en_gerneral_settings');

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','taskbuilder').'" }';
