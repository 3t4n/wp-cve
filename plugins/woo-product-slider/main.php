<?php
/**
 * Plugin Name:     Product Slider for WooCommerce
 * Plugin URI:      https://wooproductslider.io/pricing/?ref=1
 * Description:     Slide your WooCommerce Products in a tidy and professional slider or carousel with an easy-to-use and intuitive Shortcode Generator. Highly customizable and No coding required!
 * Version:         2.7.0
 * Author:          ShapedPlugin LLC
 * Author URI:      https://shapedplugin.com/
 * License:         GPLv3
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 * Requires at least: 5.0
 * Requires PHP: 5.6
 * WC requires at least: 4.5
 * WC tested up to: 8.5.2
 * Text Domain:     woo-product-slider
 * Domain Path:     /languages
 *
 * @package         Woo_Product_Slider
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
require_once __DIR__ . '/vendor/autoload.php';
if ( ! defined( 'SP_WPS_NAME' ) ) {
	define( 'SP_WPS_NAME', 'woo-product-slider' );
}
if ( ! defined( 'SP_WPS_VERSION' ) ) {
	define( 'SP_WPS_VERSION', '2.7.0' );
}
if ( ! defined( 'SP_WPS_PATH' ) ) {
	define( 'SP_WPS_PATH', plugin_dir_path( __FILE__ ) . 'src/' );
}
if ( ! defined( 'SP_WPS_URL' ) ) {
	define( 'SP_WPS_URL', plugin_dir_url( __FILE__ ) . 'src/' );
}
if ( ! defined( 'SP_WPS_BASENAME' ) ) {
	define( 'SP_WPS_BASENAME', plugin_basename( __FILE__ ) );
}

/**
 * Pro version check.
 *
 * @return boolean
 */
function is_woo_product_slider_pro() {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
	if ( ! ( is_plugin_active( 'woo-product-slider-pro/woo-product-slider-pro.php' ) || is_plugin_active_for_network( 'woo-product-slider-pro/woo-product-slider-pro.php' ) ) ) {
		return true;
	}
}

if ( ! function_exists( 'woo_product_slider' ) ) {
	/**
	 * Shortcode converter function
	 *
	 * @param  int $id shortcode id.
	 * @return void
	 */
	function woo_product_slider( $id ) {
		echo do_shortcode( '[woo_product_slider id="' . $id . '"]' );
	}
}

// Declare that the plugin is compatible with WooCommerce High-Performance order storage feature.
add_action(
	'before_woocommerce_init',
	function() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);

/**
 * Returns the main instance.
 *
 * @since 2.0
 * @return WooProductSlider
 */
function sp_woo_product_slider() {
	return ShapedPlugin\WooProductSlider\Includes\WooProductSlider::instance();
}

if ( is_woo_product_slider_pro() ) {
	sp_woo_product_slider();
}
