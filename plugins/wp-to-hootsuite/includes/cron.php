<?php
/**
 * Defines functions that are called by WordPress' Cron.
 *
 * @package WP_To_Hootsuite
 * @author WP Zinc
 */

/**
 * Define the WP Cron function to perform the log cleanup
 *
 * @since   3.9.8
 */
function wp_to_hootsuite_log_cleanup_cron() {

	// Initialise Plugin.
	$wp_to_hootsuite = WP_To_Hootsuite::get_instance();
	$wp_to_hootsuite->initialize();

	// Call CRON Log Cleanup function.
	$wp_to_hootsuite->get_class( 'cron' )->log_cleanup();

	// Shutdown.
	unset( $wp_to_hootsuite );

}
add_action( 'wp_to_hootsuite_log_cleanup_cron', 'wp_to_hootsuite_log_cleanup_cron' );
