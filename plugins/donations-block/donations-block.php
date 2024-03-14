<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://about.me/bharatkambariya
 * @since             2.1.0
 * @package           Donation_Block
 *
 * @wordpress-plugin
 * Plugin Name:       Donation Block For PayPal
 * Plugin URI:        https://wordpress.org/plugins/donations-block/
 * Description:       Create your own PayPal block as many as you want as per your need in simple way.
 * Version:           2.1.0
 * Author:            bharatkambariya
 * Author URI:        https://profiles.wordpress.org/bharatkambariya/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       donations-block
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
define( 'DONATIONS_BLOCK_VERSION', '2.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-donations-block-activator.php
 */
function activate_donations_block() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-donations-block-activator.php';
	Donations_Block_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-donations-block-deactivator.php
 */
function deactivate_donations_block() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-donations-block-deactivator.php';
	Donations_Block_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_donations_block' );
register_deactivation_hook( __FILE__, 'deactivate_donations_block' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-donations-block.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.1.0
 */
function run_donations_block() {

	$plugin = new Donations_Block();
	$plugin->run();

}
run_donations_block();
