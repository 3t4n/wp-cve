<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.simb.co
 * @since             1.0.0
 * @package           Wp_Manychat
 *
 * @wordpress-plugin
 * Plugin Name:       Block Editor for ManyChat
 * Description:       Easily add Manychat widgets to your site
 * Version:           1.0.5
 * Author:            SimBCo
 * Author URI:        https://www.simb.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-manychat
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
define( 'WP_MANYCHAT_VERSION', '1.0.5' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-manychat-activator.php
 */
function activate_wp_manychat() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-manychat-activator.php';
	Wp_Manychat_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-manychat-deactivator.php
 */
function deactivate_wp_manychat() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-manychat-deactivator.php';
	Wp_Manychat_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_manychat' );
register_deactivation_hook( __FILE__, 'deactivate_wp_manychat' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-manychat.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_manychat() {

	$plugin = new Wp_Manychat();
	$plugin->run();

}
run_wp_manychat();

include_once plugin_dir_path( __FILE__ ) . 'divi-manychat/divi-manychat.php';
