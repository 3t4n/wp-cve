<?php

/**
*
* Admin notices.
* @version 3.0.0
*
**/

defined( 'ABSPATH' ) || exit;

/**
*
* @author Credits to https://catapultthemes.com/wordpress-plugin-update-hook-upgrader_process_complete/
* This function runs when WordPress completes its upgrade process
* It iterates through each plugin updated to see if ours is included
* @param array - $upgrader_object
* @param array - $options
*
*/
add_action( 'upgrader_process_complete', 'wceb_upgrade_completed', 10, 2 );

function wceb_upgrade_completed( $upgrader_object, $options ) {

	// The path to our plugin's main file
	$our_plugin = plugin_basename( __FILE__ );
	
	// If an update has taken place and the updated type is plugins and the plugins element exists
	if ( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {

		// Iterate through the plugins being updated and check if ours is there
		foreach ( $options['plugins'] as $plugin ) {

			if ( $plugin == $our_plugin ) {

				// Set a transient to record that our plugin has just been updated
				set_transient( 'wceb_updated', 1 );

			}

		}

	}

}

/**
*
* Display notices
*
*/
add_action( 'admin_notices', 'wceb_display_admin_notices' );

function wceb_display_admin_notices() {

	// Check the transient to see if we've just activated the plugin
	// This notice shouldn't display to anyone who has just updated this plugin
	if ( get_transient( 'wceb_activated' ) ) {

		// Delete the transient so we don't keep displaying the activation message
		delete_transient( 'wceb_activated' );

	}

	// Check the transient to see if we've just updated the plugin
	// This notice shouldn't display to anyone who has just installed the plugin for the first time
	if ( get_transient( 'wceb_updated' ) ) {

		// Delete the transient so we don't keep displaying the activation message
		delete_transient( 'wceb_updated' );

	}

	// Show a notice to update database if necessary
	if ( get_option( 'easy_booking_db_version' ) !== wceb_get_db_version() ) {
		include_once( 'views/notices/html-wceb-notice-update-database.php' );
	}

	// Show a notice to upgrade to PRO version (if addons are installed)
	if ( function_exists( 'wceb_addons_installed' ) && true === wceb_addons_installed() && get_option( 'easy_booking_display_notice_pro_upgrade' ) !== '1' ) {
		include_once( 'views/notices/html-wceb-notice-upgrade-to-pro.php' );
	}

	// Show another notice to remind people to upgrade to PRO version (if addons are installed)
	if ( function_exists( 'wceb_addons_installed' ) && true === wceb_addons_installed() && get_option( 'easy_booking_display_notice_pro_upgrade_reminder' ) !== '1' ) {
		include_once( 'views/notices/html-wceb-notice-upgrade-to-pro-reminder.php' );
	}

}