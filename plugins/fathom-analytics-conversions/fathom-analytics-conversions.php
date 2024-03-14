<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.fathomconversions.com
 * @since             1.0
 * @package           Fathom_Analytics_Conversions
 *
 * @wordpress-plugin
 * Plugin Name:       Fathom Analytics Conversions
 * Plugin URI:        https://www.fathomconversions.com
 * Description:       Easily add event conversions in WordPress plugins to Fathom Analytics
 * Version:           1.0.12
 * Author:            SixFive Pty Ltd
 * Author URI:        https://www.sixfive.com.au
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       fathom-analytics-conversions
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'FATHOM_ANALYTICS_CONVERSIONS_VERSION', '1.0.12' );
define( 'FAC4WP_PATH', plugin_dir_path( __FILE__ ) );

global $fac4wp_plugin_url, $fac4wp_plugin_basename;
$fac4wp_plugin_url      = plugin_dir_url( __FILE__ );
$fac4wp_plugin_basename = plugin_basename( __FILE__ );

// Load the autoloader.
require __DIR__ . '/includes/Autoloader.php';

Autoloader::init();

/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_fathom_analytics_conversions() {

	if ( ! class_exists( 'Appsero\Client' ) ) {
		require_once __DIR__ . '/appsero/src/Client.php';
	}

	$client = new Appsero\Client( 'df35d7c2-8939-4676-ba5d-395f499225c4', 'Fathom Analytics Conversions', __FILE__ );

	// Active insights
	$client->insights()->init();

}

appsero_init_tracker_fathom_analytics_conversions();

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-fathom-analytics-conversions-activator.php
 */
function activate_fathom_analytics_conversions() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fathom-analytics-conversions-activator.php';
	Fathom_Analytics_Conversions_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-fathom-analytics-conversions-deactivator.php
 */
function deactivate_fathom_analytics_conversions() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fathom-analytics-conversions-deactivator.php';
	Fathom_Analytics_Conversions_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_fathom_analytics_conversions' );
register_deactivation_hook( __FILE__, 'deactivate_fathom_analytics_conversions' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-fathom-analytics-conversions.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_fathom_analytics_conversions() {

	$plugin = new Fathom_Analytics_Conversions();
	$plugin->run();

}

run_fathom_analytics_conversions();
