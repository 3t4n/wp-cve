<?php
/**
 * WP to Hootsuite WordPress Plugin.
 *
 * @package WP_To_Hootsuite
 * @author WP Zinc
 *
 * @wordpress-plugin
 * Plugin Name: WP to Hootsuite
 * Plugin URI: http://www.wpzinc.com/plugins/wordpress-to-hootsuite-pro
 * Version: 1.5.4
 * Author: WP Zinc
 * Author URI: http://www.wpzinc.com
 * Description: Send WordPress Pages, Posts or Custom Post Types to your Hootsuite (hootsuite.com) account for scheduled publishing to social networks.
 * Text Domain: wp-to-hootsuite
 */

// Bail if Plugin is alread loaded.
if ( class_exists( 'WP_To_Hootsuite' ) ) {
	return;
}

// Define Plugin version and build date.
define( 'WP_TO_HOOTSUITE_PLUGIN_VERSION', '1.5.4' );
define( 'WP_TO_HOOTSUITE_PLUGIN_BUILD_DATE', '2023-11-17 18:00:00' );

// Define Plugin paths.
define( 'WP_TO_HOOTSUITE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_TO_HOOTSUITE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Define the autoloader for this Plugin
 *
 * @since   3.4.7
 *
 * @param   string $class_name     The class to load.
 */
function WP_To_Hootsuite_Autoloader( $class_name ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName

	// Define the required start of the class name.
	$class_start_name = 'WP_To_Social_Pro';

	// Get the number of parts the class start name has.
	$class_parts_count = count( explode( '_', $class_start_name ) );

	// Break the class name into an array.
	$class_path = explode( '_', $class_name );

	// Bail if it's not a minimum length (i.e. doesn't potentially have WP_To_Social_Pro).
	if ( count( $class_path ) < $class_parts_count ) {
		return;
	}

	// Build the base class path for this class.
	$base_class_path = '';
	for ( $i = 0; $i < $class_parts_count; $i++ ) {
		$base_class_path .= $class_path[ $i ] . '_';
	}
	$base_class_path = trim( $base_class_path, '_' );

	// Bail if the first parts don't match what we expect.
	if ( $base_class_path !== $class_start_name ) {
		return;
	}

	// Define the file name.
	$file_name = 'class-' . str_replace( '_', '-', strtolower( $class_name ) ) . '.php';

	// Define the paths to search for the file.
	$include_paths = array(
		WP_TO_HOOTSUITE_PLUGIN_PATH . 'lib/includes',
		WP_TO_HOOTSUITE_PLUGIN_PATH . 'includes',
	);

	// Iterate through the include paths to find the file.
	foreach ( $include_paths as $path ) {
		if ( file_exists( $path . '/' . $file_name ) ) {
			require_once $path . '/' . $file_name;
			return;
		}
	}

}
spl_autoload_register( 'wp_to_hootsuite_autoloader' );

// Load Activation, Cron and Deactivation functions.
require_once WP_TO_HOOTSUITE_PLUGIN_PATH . 'includes/activation.php';
require_once WP_TO_HOOTSUITE_PLUGIN_PATH . 'includes/cron.php';
require_once WP_TO_HOOTSUITE_PLUGIN_PATH . 'includes/deactivation.php';
register_activation_hook( __FILE__, 'wp_to_hootsuite_activate' );
if ( version_compare( get_bloginfo( 'version' ), '5.1', '>=' ) ) {
	add_action( 'wp_insert_site', 'wp_to_hootsuite_activate_new_site' );
} else {
	add_action( 'wpmu_new_blog', 'wp_to_hootsuite_activate_new_site' );
}
add_action( 'activate_blog', 'wp_to_hootsuite_activate_new_site' );
register_deactivation_hook( __FILE__, 'wp_to_hootsuite_deactivate' );

/**
 * Main function to return Plugin instance.
 *
 * @since   3.8.1
 */
function WP_To_Hootsuite() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName

	return WP_To_Hootsuite::get_instance();

}

// Finally, initialize the Plugin.
require_once WP_TO_HOOTSUITE_PLUGIN_PATH . 'includes/class-wp-to-hootsuite.php';
$wp_to_hootsuite = WP_To_Hootsuite();
