<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Includes the files needed for the 
 *
 */
function wpsbc_include_files_update_checker() {

	// Get legend admin dir path
	$dir_path = plugin_dir_path( __FILE__ );

	// Include actions file
	if( file_exists( $dir_path . 'functions-actions-update-checker.php' ) )
		include $dir_path . 'functions-actions-update-checker.php';

	// Include update checker class
	if( file_exists( $dir_path . 'class-update-checker.php' ) )
		include $dir_path . 'class-update-checker.php';

}
add_action( 'wpsbc_include_files', 'wpsbc_include_files_update_checker' );


/**
 * Initializes the update checker
 *
 */
function wpsbc_init_plugin_update_cheker() {

	$serial_key = get_option( 'wpsbc_serial_key', '' );
	$website_id = get_option( 'wpsbc_registered_website_id', '' );

	if( empty( $serial_key ) || empty( $website_id ) )
		return;

	$url_args = array(
		'request'      => 'get_update',
		'product_slug' => 'wp-simple-booking-calendar-premium',
		'serial_key'   => $serial_key
	);
	
	$update_checker = new WPSBC_PluginUpdateChecker( add_query_arg( $url_args, 'https://www.wpsimplebookingcalendar.com/u/' ), WPSBC_FILE, 'wp-simple-booking-calendar-premium', 24 );

}
add_action( 'plugins_loaded', 'wpsbc_init_plugin_update_cheker' );


/**
 * Adds a new tab to the Settings page of the plugin
 *
 * @param array $tabs
 *
 * @return $tabs
 *
 */
function wpsbc_submenu_page_settings_tabs_register_website( $tabs ) {

	$tabs['register_website'] = __( 'Register Website', 'wp-simple-booking-calendar' );

	return $tabs;

}
add_filter( 'wpsbc_submenu_page_settings_tabs', 'wpsbc_submenu_page_settings_tabs_register_website', 50 );


/**
 * Adds the HTML for the Register Version tab
 *
 */
function wpsbc_submenu_page_settings_tab_register_website() {

	include 'views/view-register-website.php';

}
add_action( 'wpsbc_submenu_page_settings_tab_register_website', 'wpsbc_submenu_page_settings_tab_register_website' );


/**
 * Registers the admin notices needed for the register/deregister website functionality
 *
 */
function wpsbc_register_admin_notices_update_checker() {

	if( empty( $_GET['wpsbc_message'] ) )
		return;

	/**
	 * Register website notices
	 *
	 */
	wpsbc_admin_notices()->register_notice( 'register_website_general_error', '<p>' . __( 'Something went wrong. Could not complete the operation.', 'wp-simple-booking-calendar' ) . '</p>', 'error' );

	wpsbc_admin_notices()->register_notice( 'register_website_serial_key_missing', '<p>' . __( 'Please provide a serial key.', 'wp-simple-booking-calendar' ) . '</p>', 'error' );

	wpsbc_admin_notices()->register_notice( 'register_website_response_error', '<p>' . __( 'Something went wrong. Could not connect to our server to register your website.', 'wp-simple-booking-calendar' ) . '</p>', 'error' );

	wpsbc_admin_notices()->register_notice( 'register_website_already_registered', '<p>' . sprintf( __( 'This website is already registered with the provided serial key. Please log into %syour account on our website%s to view all your registered websites.', 'wp-simple-booking-calendar' ), '<a href="https://www.wpsimplebookingcalendar.com/account/" target="_blank">', '</a>' ) . '</p>', 'error' );

	wpsbc_admin_notices()->register_notice( 'register_website_serial_expired', '<p>' . sprintf( __( 'The provided serial key is either invalid or expired. You cannot register a website with an invalid or expired serial key. %sPlease visit our website to set up or renew your WP Simple Booking Calendar license%s.', 'wp-simple-booking-calendar' ), '<a href="https://www.wpsimplebookingcalendar.com/" target="_blank">', '</a>' ) . '</p>', 'error' );

	wpsbc_admin_notices()->register_notice( 'register_website_maximum_websites', '<p>' . sprintf( __( 'The maximum number of websites have been registered for this serial key. To upgrade your license, %splease visit our website%s.', 'wp-simple-booking-calendar' ), '<a href="https://www.wpsimplebookingcalendar.com/" target="_blank">', '</a>' ) . '</p>', 'error' );

	wpsbc_admin_notices()->register_notice( 'register_website_success', '<p>' . sprintf( __( 'Website successfully registered. To view all your registered websites, please check %syour account page on our website%s.', 'wp-simple-booking-calendar' ), '<a href="https://www.wpsimplebookingcalendar.com/account/" target="_blank">', '</a>' ) . '</p>' );	

	/**
	 * Deregister website notices
	 *
	 */
	wpsbc_admin_notices()->register_notice( 'deregister_website_general_error', '<p>' . __( 'Something went wrong. Could not complete the operation.', 'wp-simple-booking-calendar' ) . '</p>', 'error' );

	wpsbc_admin_notices()->register_notice( 'deregister_website_serial_key_missing', '<p>' . __( 'Please provide a serial key.', 'wp-simple-booking-calendar' ) . '</p>', 'error' );

	wpsbc_admin_notices()->register_notice( 'deregister_website_response_error', '<p>' . __( 'Something went wrong. Could not connect to our server to deregister your website.', 'wp-simple-booking-calendar' ) . '</p>', 'error' );

	wpsbc_admin_notices()->register_notice( 'deregister_website_success', '<p>' . __( 'Website successfully deregistered.', 'wp-simple-booking-calendar' ) . '</p>' );

	// Check for updates
	wpsbc_admin_notices()->register_notice( 'check_for_updates_success', '<p>' . sprintf( __( 'Please visit the %sPlugins page%s to check if a new update is available for WP Simple Booking Calendar.', 'wp-simple-booking-calendar' ), '<a href="' . admin_url( 'plugins.php' ) . '">', '</a>' ) . '</p>' );

}
add_action( 'admin_init', 'wpsbc_register_admin_notices_update_checker' );

