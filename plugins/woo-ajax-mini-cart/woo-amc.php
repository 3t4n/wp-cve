<?php

/**
 * Plugin Name:       Woocommerce Ajax Mini Cart
 * Plugin URI:        http://inconver.com/ajax-mini-cart/
 * Description:       Woocommerce ajax mini cart
 * Version:           1.0.2
 * Author:            Inconver
 * Author URI:        http://inconver.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-ajax-mini-cart
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'WAMC_INCONVER_VERSION', '1.0.2' );

/**
 * The code that runs during plugin activation.
 */
function activate_woocommerce_ajax_mini_cart() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-amc-activator.php';
    WooAmcActivator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_woocommerce_ajax_mini_cart() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-amc-deactivator.php';
    WooAmcDeactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_ajax_mini_cart' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_ajax_mini_cart' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-amc.php';

/**
 * Begins execution of the plugin.
 */
function run_woocommerce_ajax_mini_cart() {

	$plugin = new WooAmc();
	$plugin->run();

}
run_woocommerce_ajax_mini_cart();
