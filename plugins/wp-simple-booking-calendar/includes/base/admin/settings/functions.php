<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Includes the files needed for the Settings admin area
 *
 */
function wpsbc_include_files_admin_settings() {

	// Get legend admin dir path
	$dir_path = plugin_dir_path( __FILE__ );

	// Include submenu page
	if( file_exists( $dir_path . 'class-submenu-page-settings.php' ) )
		include $dir_path . 'class-submenu-page-settings.php';

}
add_action( 'wpsbc_include_files', 'wpsbc_include_files_admin_settings' );


/**
 * Register the Settings admin submenu page
 *
 */
function wpsbc_register_submenu_page_settings( $submenu_pages ) {

	if( ! is_array( $submenu_pages ) )
		return $submenu_pages;

	$submenu_pages['settings'] = array(
		'class_name' => 'WPSBC_Submenu_Page_Settings',
		'data' 		 => array(
			'page_title' => __( 'Settings', 'wp-simple-booking-calendar' ),
			'menu_title' => __( 'Settings', 'wp-simple-booking-calendar' ),
			'capability' => apply_filters( 'wpsbc_submenu_page_capability_settings', 'manage_options' ),
			'menu_slug'  => 'wpsbc-settings'
		)
	);

	return $submenu_pages;

}
add_filter( 'wpsbc_register_submenu_page', 'wpsbc_register_submenu_page_settings', 50 );