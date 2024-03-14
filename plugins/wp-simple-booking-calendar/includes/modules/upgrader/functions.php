<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Includes the files needed for the Settings admin area
 *
 */
function wpsbc_include_files_upgrader() {

	// Get legend admin dir path
	$dir_path = plugin_dir_path( __FILE__ );

	// Include submenu page
	if( file_exists( $dir_path . 'class-submenu-page-upgrader.php' ) )
		include $dir_path . 'class-submenu-page-upgrader.php';

	// Include actions
	if( file_exists( $dir_path . 'functions-actions-upgrader.php' ) )
		include $dir_path . 'functions-actions-upgrader.php';

	// Include AJAX actions
	if( file_exists( $dir_path . 'functions-actions-ajax-upgrader.php' ) )
		include $dir_path . 'functions-actions-ajax-upgrader.php';

}
add_action( 'wpsbc_include_files', 'wpsbc_include_files_upgrader' );


/**
 * Registers the upgrader submenu page if an upgrade is needed
 * Deregisters all other pages if an upgraded is needed
 *
 * @param array $submenu_pages
 *
 * @return array
 *
 */
function wpsbc_register_submenu_page_upgrader( $submenu_pages ) {

	if( ! is_array( $submenu_pages ) )
		return $submenu_pages;

	// Check is there is a need for an upgrade
	if( false === wpsbc_process_upgrade_from() )
		return $submenu_pages;

	// Remove all registered pages
	$submenu_pages = array();

	// Add the welcome page
	$submenu_pages['upgrader'] = array(
		'class_name' => 'WPSBC_Submenu_Page_Upgrader',
		'data' 		 => array(
			'page_title' => __( 'Welcome', 'wp-simple-booking-calendar' ),
			'menu_title' => __( 'Welcome', 'wp-simple-booking-calendar' ),
			'capability' => apply_filters( 'wpsbc_submenu_page_capability_upgrader', 'manage_options' ),
			'menu_slug'  => 'wpsbc-upgrader'
		)
	);

	return $submenu_pages;

}
add_filter( 'wpsbc_register_submenu_page', 'wpsbc_register_submenu_page_upgrader', 1000 );


/**
 * Returns a string detailing from which plugin the upgrade should be made
 *
 * @return mixed false|string (string values can be "old_premium" and "free")
 *
 */
function wpsbc_process_upgrade_from() {

	/**
	 * Check to see if the upgrade has been skipped
	 *
	 */
	$upgrade_skipped = get_option( 'wpsbc_upgrade_8_0_0_skipped' );

	if( false !== $upgrade_skipped )
		return false;

	/**
	 * Check to see if the upgrade has already been made
	 *
	 */
	$upgrade_done = get_option( 'wpsbc_upgrade_8_0_0' );

	if( false !== $upgrade_done )
		return false;

	/**
	 * Check to see if the upgrade should be made from the old premium version
	 *
	 */
	$premium_db_version = get_option( 'wpsbc_db_version' );

	if( false !== $premium_db_version )
		return 'old_premium';

	/**
	 * Check to see if the upgrade should be made from the free version
	 *
	 */
	$free_version = get_option( 'wp-simple-booking-calendar-options' );

	if( false !== $free_version )
		return 'free';
	
	return false;

}


/**
 * Function ported from the previous version of the plugin. Should not be used in any circumstances
 * outside of the plugin
 *
 * @access private
 *
 * @param string $str
 *
 * @return string
 *
 */
function _wpsbc_replace_custom( $str ) {
    return str_replace( 
        array(
            '--AMP--',
            '--DOUBLEQUOTE--',
            '--QUOTE--',
            '--LT--',
            '--GT--'
        ),
        array(
            '&',
            '"',
            "'",
            '<',
            '>'
        ),
        $str );
}