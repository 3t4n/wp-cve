<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://freelancertestbd.blogspot.com
 * @since             1.0.0
 * @package           Custom_Dashboard
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Dashboard
 * Plugin URI:        https://wordpress.org/plugins/custom-dashboard
 * Description:       Make your wordpress dashboard more style and customize by use this plug-in.
 * Version:           1.0.0
 * Author:            Dipto Paul
 * Author URI:        http://freelancertestbd.blogspot.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       custom-dashboard
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-custom-dashboard-activator.php
 */
function activate_custom_dashboard() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-dashboard-activator.php';
	Custom_Dashboard_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-custom-dashboard-deactivator.php
 */
function deactivate_custom_dashboard() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-dashboard-deactivator.php';
	Custom_Dashboard_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_custom_dashboard' );
register_deactivation_hook( __FILE__, 'deactivate_custom_dashboard' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-custom-dashboard.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_custom_dashboard() {

	$plugin = new Custom_Dashboard();
	$plugin->run();

}
run_custom_dashboard();
