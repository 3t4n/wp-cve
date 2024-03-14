<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://acewebx.com/
 * @since             1.0.0
 * @package           Ace_Woo_Ajax_Cart_Count
 *
 * @wordpress-plugin
 * Plugin Name:       Ace Woo Ajax Cart Count
 * Plugin URI:        ace-woo-ajax-cart-count
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.3.9
 * Author:            AceWebX Team
 * Author URI:        https://acewebx.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ace-woo-ajax-cart-count
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
define( 'ACE_WOO_AJAX_CART_COUNT_VERSION', '1.3.9' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ace-woo-ajax-cart-count-activator.php
 */
function activate_ace_woo_ajax_cart_count() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ace-woo-ajax-cart-count-activator.php';
	Ace_Woo_Ajax_Cart_Count_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ace-woo-ajax-cart-count-deactivator.php
 */
function deactivate_ace_woo_ajax_cart_count() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ace-woo-ajax-cart-count-deactivator.php';
	Ace_Woo_Ajax_Cart_Count_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ace_woo_ajax_cart_count' );
register_deactivation_hook( __FILE__, 'deactivate_ace_woo_ajax_cart_count' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ace-woo-ajax-cart-count.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ace_woo_ajax_cart_count() {

	$plugin = new Ace_Woo_Ajax_Cart_Count();
	$plugin->run();

}
run_ace_woo_ajax_cart_count();
