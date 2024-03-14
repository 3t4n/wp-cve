<?php
/**
 * Plugin Name: Distance Rate Shipping For WooCommerce
 * Plugin URI: https://wordpress.org/plugins/distance-rate-shipping-for-woocommerce
 * Description:Distance Shipping for WooCommerce add functionality of calculating shipping fees based on distance between store and customer.
 * Version: 1.0.0 
 * Requires at least: 5.4
 * Requires PHP: 5.4
 * Author: tusharknovator
 * Author URI: https://knovator.com/wordpress-plugin-development/
 * Text Domain: distance-rate-shipping-for-woocommerce
 * Domain Path: /languages
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * This condition make sure plugin files are not accessed direclty.
 * If they're accessed directly, plguin make sure to exit and terminate program.
 */ 
if(!defined('ABSPATH')){
    exit;
} 
if(!defined('WPINC')){
    exit;
}

register_activation_hook( __FILE__, 'distance_rate_shipping_activation_hook_callback' );
function distance_rate_shipping_activation_hook_callback(){
    // include activator class
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-distance-rate-shipping-activator.php';
    $plugin_activator = new Distance_Rate_Shipping_Activator();
    $plugin_activator->activate();
}

register_deactivation_hook( __FILE__, 'distance_rate_shipping_deactivation_hook_callback' );
function distance_rate_shipping_deactivation_hook_callback(){
    // include deactivator class
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-distance-rate-shipping-deactivator.php';
    $plugin_deactivator = new Distance_Rate_Shipping_Deactivator();
    $plugin_deactivator->deactivate();
}

/**
 * The core plugin class that is used to define internationalization,
 * Backend hooks, and fronend hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-distance-rate-shipping.php';

function run_distance_rate_shipping(){
    $distance_rate_shipping = new Distance_Rate_Shipping();
    $distance_rate_shipping->run();
}
run_distance_rate_shipping();