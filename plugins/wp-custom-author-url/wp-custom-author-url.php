<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://chrisjallen.com
 * @since             1.0.0
 * @package           Wp_Custom_Author_Url
 *
 * @wordpress-plugin
 * Plugin Name:       WP Custom Author URL
 * Description:       Choose a custom URL for your author links, instead of the standard WordPress author page.
 * Version:           1.0.5
 * Author:            Chris Allen
 * Author URI:        https://chrisjallen.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-custom-author-url
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
define( 'WP_CUSTOM_AUTHOR_URL_VERSION', '1.0.5' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-custom-author-url-activator.php
 */
function activate_wp_custom_author_url() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-custom-author-url-activator.php';
	Wp_Custom_Author_Url_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-custom-author-url-deactivator.php
 */
function deactivate_wp_custom_author_url() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-custom-author-url-deactivator.php';
	Wp_Custom_Author_Url_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_custom_author_url' );
register_deactivation_hook( __FILE__, 'deactivate_wp_custom_author_url' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-custom-author-url.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_custom_author_url() {

	$plugin = new Wp_Custom_Author_Url();
	$plugin->run();

}
run_wp_custom_author_url();
