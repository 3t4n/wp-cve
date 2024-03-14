<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.tplugins.com/shop
 * @since             1.0.0
 * @package           TP_Show_Product_Images_on_Checkout_Page
 *
 * @wordpress-plugin
 * Plugin Name:       TP Show Product Images on Checkout Page for WooCommerce
 * Plugin URI:        www.tplugins.com
 * Description:       Display Product Images on Checkout Page.
 * Version:           1.0.1
 * Author:            TP Plugins
 * Author URI:        www.tplugins.com/shop
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tp-show-product-images-on-checkout-page
 * Domain Path:       /languages
 * WC requires at least: 4.6
 * WC tested up to: 8.2.1
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
define('TPSPICP_VERSION', '1.0.1');
define('TPSPICP_PLUGIN_NAME', 'TP Show Product Images on Checkout Page for WooCommerce');
define('TPSPICP_PLUGIN_MENU_NAME', 'TP Show Product Images on Checkout');
define('TPSPICP_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('TPSPICP_PLUGIN_HOME', 'https://www.tplugins.com/');
define('TPSPICP_PLUGIN_SLUG', 'tp-show-product-images-on-checkout-page');

//require plugin_dir_path( __FILE__ ) . 'includes/class-tp-recently-viewed-products-slider-for-woocommerce.php';
require plugin_dir_path( __FILE__ ) . 'admin/tp-show-product-images-on-checkout-page-admin.php';
require plugin_dir_path( __FILE__ ) . 'public/tp-show-product-images-on-checkout-page-public.php';