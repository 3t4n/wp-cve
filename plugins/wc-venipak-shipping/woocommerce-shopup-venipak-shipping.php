<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://shopup.lt/venipak
 * @since             1.0.0
 * @package           Woocommerce_Shopup_Venipak_Shipping
 *
 * @wordpress-plugin
 * Plugin Name:       Shipping with Venipak for WooCommerce
 * Description:       Venipak delivery method plugin for WooCommerce. Delivery via courier and pickup points.
 * Version:           1.20.0
 * Author:            ShopUp
 * Author URI:        https://shopup.lt/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-shopup-venipak-shipping
 * Domain Path:       /languages/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// this is for plugin name and desc poedit auto detection
$plugin_name = __( 'Shipping with Venipak for WooCommerce', 'woocommerce-shopup-venipak-shipping' );
$plugin_description = __('Venipak delivery method plugin for WooCommerce. Delivery via courier and pickup points.', 'woocommerce-shopup-venipak-shipping' );

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WOOCOMMERCE_SHOPUP_VENIPAK_SHIPPING_VERSION', '1.20.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-shopup-venipak-shipping-activator.php
 */
function activate_woocommerce_shopup_venipak_shipping() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-shopup-venipak-shipping-activator.php';
	Woocommerce_Shopup_Venipak_Shipping_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-shopup-venipak-shipping-deactivator.php
 */
function deactivate_woocommerce_shopup_venipak_shipping() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-shopup-venipak-shipping-deactivator.php';
	Woocommerce_Shopup_Venipak_Shipping_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_shopup_venipak_shipping' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_shopup_venipak_shipping' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-shopup-venipak-shipping.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_shopup_venipak_shipping() {

	$plugin = new Woocommerce_Shopup_Venipak_Shipping();
	$plugin->run();

}
run_woocommerce_shopup_venipak_shipping();
