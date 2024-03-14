<?php
global $wp_version;
use Login_With_AJAX\Admin_Notices;
use Login_With_AJAX\Admin_Notice;

$lwa_data = get_option('lwa_data');
$lwa_data_defaults = array (
	'legacy' => false,
	'template' => 'minimalistic',
	'rememberme' => '2',
	'ajaxify' => 1,
	'login_redirect' => '%LASTURL%',
	'logout_redirect' => '%LASTURL%',
	'2FA' =>
		array (
			'enabled' => 0,
			'when' => '2',
			'days' => '30',
			'methods' =>
				array (
					'totp' => '1',
					'email' => '1',
					'backup' => '1',
				),
			'default' => '0',
			'setup_show' => '0',
			'grace_mode' => '1',
			'grace_user_days' => '7',
		),
	'integrate' => array(
		'buddypress' => 1,
		'events-manager' => 1,
		'woocommerce' => 1,
	),
);

if( !is_array($lwa_data) ) $lwa_data = array();
//no DB changes necessary

include_once('admin/notices/admin-notices.php');
Admin_Notices::$option_name = 'lwa_data';
Admin_Notices::$option_notices_name = 'lwa_admin_notices';

//add notices and upgrade logic
if( !get_option('lwa_version') ){
	// add welcome message
	$message = esc_html__("You have installed Login With AJAX 5.0! Check out our % for options and documentation links, also look out for the new Login With AJAX blocks on the widget and page builders.", 'login-with-ajax');
	$settings_url = '<a href="'. admin_url('options-general.php?page=login-with-ajax') .'">'. esc_html__("settings page", 'login-with-ajax') .'</a>';
	$message = sprintf($message, $settings_url);
	
	$Admin_Notice = new Admin_Notice('v4-upgrade', 'info', $message, 'all' );
	Admin_Notices::add( $Admin_Notice );
	add_option('lwa_data', $lwa_data_defaults);
} else {
	// add some defaults that may not have been set
	
	if( version_compare( get_option('lwa_version',0), '4.0', '<' ) ){
		// 4.0 Upgrade
		$lwa_data['legacy'] = true;
		$lwa_data['rememberme'] = 1;
		$lwa_data['template_color'] = array('H'=>220, 'S' => 87, 'L' => 59);
		$lwa_data['notification_subject'] = str_replace('%PASSWORD%', '%PASSWORDURL%', $lwa_data['notification_subject']);
		$lwa_data['notification_message'] = str_replace('%PASSWORD%', '%PASSWORDURL%', $lwa_data['notification_message']);
		$lwa_data['ajaxify'] = array('wp_login' => true);
		
		$message = '<strong>' .esc_html__('You have upgraded to Login With AJAX 4.0!', 'login-with-ajax'). '</strong></p><p>';
		$message .= esc_html__('We have completely revamped our templates as well as adding Gutenberg support. You are currently on a backwards-compatible legacy mode which you can disable from the %s and upgrade to our new templates.', 'login-with-ajax') .'</p><p>';
		$message .= esc_html__('Check out our %s and also look out for the new Login With AJAX blocks on the widget and page builders.', 'login-with-ajax');
		$settings_url = '<a href="'. admin_url('options-general.php?page=login-with-ajax') .'">'. esc_html__("settings page", 'login-with-ajax') .'</a>';
		$message = sprintf($message, $settings_url, $settings_url);
		
		$Admin_Notice = new Admin_Notice('v4-upgrade', 'info', $message, 'all' );
		Admin_Notices::add( $Admin_Notice );
		
		
		$message = '<strong>' .esc_html__('Welcome to the redesigned Login With AJAX settings page!', 'login-with-ajax'). '</strong></p><p>';
		$message .= esc_html__('You are currently on a backwards-compatible legacy mode. You can disable via the checkbox below and save your settings, you will then be able to choose from our new templates.', 'login-with-ajax') .'</p><p>';
		$message = sprintf($message, $settings_url, $settings_url);
		
		$Admin_Notice = new Admin_Notice('v4-legacy', 'info', $message, 'settings' );
		Admin_Notices::add( $Admin_Notice );
		
		update_option('lwa_data', $lwa_data);
	}
	if( version_compare( get_option('lwa_version',0), '4.2', '<' ) ){
		$new_defaults = array(
			'ajaxify' => !empty($lwa_data['ajaxify']),
			'2FA' =>
				array (
					'enabled' => 0,
					'when' => '2',
					'days' => '30',
					'methods' =>
						array (
							'totp' => '1',
							'email' => '1',
							'backup' => '1',
						),
					'default' => '0',
					'setup_show' => '0',
					'grace_mode' => '1',
					'grace_user_days' => '7',
				),
			'integrate' => array(
				'buddypress' => 1,
				'events-manager' => 1,
				'woocommerce' => 1,
			),
		);
		$lwa_data = array_merge( $new_defaults, $lwa_data );
		update_option('lwa_data', $lwa_data);
		
		$message = '<strong>' .esc_html__('You have upgraded to Login With AJAX 4.2!', 'login-with-ajax'). '</strong></p><p>';
		$message .= esc_html__('We have added a completely new feature, 2FA authentication!', 'login-with-ajax') .'</p><p>';
		$message .= esc_html__('Check out our %s and also look out for the new Login With AJAX blocks on the widget and page builders.', 'login-with-ajax');
		$settings_url = '<a href="'. admin_url('options-general.php?page=login-with-ajax') .'">'. esc_html__("settings page", 'login-with-ajax') .'</a>';
		$message = sprintf($message, $settings_url, $settings_url);
		
		$Admin_Notice = new Admin_Notice('v4.2-upgrade', 'info', $message, 'all' );
		Admin_Notices::add( $Admin_Notice );
	}
}

$current_version = get_option('lwa_version',0);
$data = get_site_option('lwa_admin_notices');
if( empty($current_version) || !isset($data['admin-modals']) ){ // if admin-modals isn't set, it was never added before
	if( empty($data['admin-modals']) ) $data['admin-modals'] = array();
	if( !is_array($data['admin-modals']) ) $data['admin-modals'] = array();
	$data['admin-modals']['review-nudge'] = time() + (DAY_IN_SECONDS * 14);
	update_site_option('lwa_admin_notices', $data);
}
// temp promo
if( time() < 1709078400 &&  version_compare($current_version, '4.2', '<')  ) {
	if( empty($data['admin-modals']) ) $data['admin-modals'] = array();
	$data['admin-modals']['promo-popup'] = true;
	update_site_option('lwa_admin_notices', $data);
}

update_option('lwa_version', LOGIN_WITH_AJAX_VERSION);