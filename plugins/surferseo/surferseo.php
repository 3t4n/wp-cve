<?php
/**
 * Plugin Name: Surfer
 * Plugin URI: https://wordpress.org/plugins/surferseo/
 * Description: Create content that ranks with Surfer in WordPress
 * Version: 1.4.1.440
 * Author: Surfer
 * Author URI: https://surferseo.com
 * License: GPLv2 or late
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: surferseo
 * Domain Path: /languages
 * Requires at least: 5.7
 * Test up to: 6.4.3
 * Requires PHP: 7.4
 *
 * @package SurferSEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'SURFER_VERSION' ) ) {
	define( 'SURFER_VERSION', '1.4.1.440' );
}

if ( ! defined( 'SURFER_PLUGIN_FILE' ) ) {
	define( 'SURFER_PLUGIN_FILE', __FILE__ );
}

use SurferSEO\Surferseo;

if ( ! class_exists( 'Surferseo' ) ) {
	require_once __DIR__ . '/includes/class-surferseo.php';
	$surferseo = Surferseo::get_instance();
}


if ( ! ( function_exists( 'Surfer' ) ) ) {
	/**
	 * Returns the main instance of Surferseo
	 *
	 * @return Surferseo
	 */
	function Surfer() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		return Surferseo::get_instance();
	}
}

register_uninstall_hook( __FILE__, 'surferseo_uninstall_hook' );

/**
 * Clears after uninstall.
 */
function surferseo_uninstall_hook() {
	wp_cache_flush();

	// Delete all SurferSEO options (keep only connection details).
	delete_option( 'surfer_notification_dismissals' );

	delete_transient( 'surfer_tracking_first_enabled' );
	delete_transient( 'surfer_gsc_weekly_report_email_sent' );
	delete_transient( 'surfer_connection_token' );

	// Clear crons.
	wp_clear_scheduled_hook( 'surfer_gather_available_locations' );
	wp_clear_scheduled_hook( 'surfer_gather_posts_traffic' );
	wp_clear_scheduled_hook( 'surfer_gather_position_monitor_data' );
	wp_clear_scheduled_hook( 'surfer_gather_drop_monitor_data' );
}
