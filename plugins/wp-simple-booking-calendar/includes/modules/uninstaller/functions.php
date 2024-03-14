<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Adds a new tab to the Settings page of the plugin
 *
 * @param array $tabs
 *
 * @return $tabs
 *
 */
function wpsbc_submenu_page_settings_tabs_uninstaller( $tabs ) {

	$tabs['uninstaller'] = __( 'Uninstaller', 'wp-simple-booking-calendar' );

	return $tabs;

}
add_filter( 'wpsbc_submenu_page_settings_tabs', 'wpsbc_submenu_page_settings_tabs_uninstaller', 100 );


/**
 * Adds the HTML for the Uninstaller tab
 *
 */
function wpsbc_submenu_page_settings_tab_uninstaller() {

	include 'views/view-uninstaller.php';

}
add_action( 'wpsbc_submenu_page_settings_tab_uninstaller', 'wpsbc_submenu_page_settings_tab_uninstaller' );


/**
 * Action that uninstalls the plugin
 *
 */
function wpsbc_action_uninstall_plugin() {

	// Verify for nonce
	if( empty( $_GET['wpsbc_token'] ) || ! wp_verify_nonce( $_GET['wpsbc_token'], 'wpsbc_uninstall_plugin' ) )
		return;

	/**
	 * Drop db tables
	 *
	 */
	global $wpdb;

	$registered_tables = wp_simple_booking_calendar()->db;

	foreach( $registered_tables as $table )
		$wpdb->query( "DROP TABLE IF EXISTS {$table->table_name}" );

	/**
	 * Remove options
	 *
	 */
	delete_option( 'wpsbc_version' );
	delete_option( 'wpsbc_first_activation' );
	delete_option( 'wpsbc_upgrade_8_0_0' );
	delete_option( 'wpsbc_upgrade_8_0_0_skipped' );
	delete_option( 'wpsbc_serial_key' );
	delete_option( 'wpsbc_registered_website_id' );


	/**
	 * Deactivate the plugin and redirect to Plugins
	 *
	 */
    deactivate_plugins( WPSBC_BASENAME );
    
    wp_redirect( admin_url( 'plugins.php' ) );
    exit;

}
add_action( 'wpsbc_action_uninstall_plugin', 'wpsbc_action_uninstall_plugin' );