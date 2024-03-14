<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://codewrangler.io
 * @since             1.0.0
 * @package           CW_Site_Announcements
 *
 * @wordpress-plugin
 * Plugin Name:       Site Announcements
 * Plugin URI:        https://codewrangler.io
 * Description:       Site Announcements allows you to broadcast site-wide messages to your visitors
 * Version:           1.0.4
 * Author:            CodeWrangler, Inc.
 * Author URI:        https://codewrangler.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cw-site-announcements
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cw-site-announcements-activator.php
 */
function activate_cw_site_announcements() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cw-site-announcements-activator.php';
	CW_Site_Announcements_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cw-site-announcements-deactivator.php
 */
function deactivate_cw_site_announcements() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cw-site-announcements-deactivator.php';
	CW_Site_Announcements_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cw_site_announcements' );
register_deactivation_hook( __FILE__, 'deactivate_cw_site_announcements' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cw-site-announcements.php';

/**
 * The class for the announcement object
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cw-announcement.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cw_site_announcements() {

	$plugin = new CW_Site_Announcements();
	$plugin->run();

}
run_cw_site_announcements();
