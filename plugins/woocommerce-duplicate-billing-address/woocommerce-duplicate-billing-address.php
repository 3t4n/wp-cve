<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://eversionsystems.com/
 * @since             1.0.0
 * @package           Woocommerce_Duplicate_Billing_Address
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Duplicate Billing Address
 * Plugin URI:        https://wordpress.org/plugins/woocommerce-duplicate-billing-address
 * Description:       Adds a checkbox in the edit user profile dashboard page to duplicate the billing to shipping address in WooCommerce.
 * Version:           1.16
 * Author:            Andrew Schultz
 * Author URI:        https://eversionsystems.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-duplicate-billing-address
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-duplicate-billing-address-activator.php
 */
function activate_woocommerce_duplicate_billing_address() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-duplicate-billing-address-activator.php';
	Woocommerce_Duplicate_Billing_Address_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-duplicate-billing-address-deactivator.php
 */
function deactivate_woocommerce_duplicate_billing_address() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-duplicate-billing-address-deactivator.php';
	Woocommerce_Duplicate_Billing_Address_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_duplicate_billing_address' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_duplicate_billing_address' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-duplicate-billing-address.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_duplicate_billing_address() {

	$plugin = new Woocommerce_Duplicate_Billing_Address();
	$plugin->run();

}
run_woocommerce_duplicate_billing_address();

// Ensure WooCommerce plugin is activated
new WPS_Extend_Plugin( 'woocommerce/woocommerce.php', __FILE__, '2.0', 'woocommerce-duplicate-billing-address' );
