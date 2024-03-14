<?php
/**
 * Plugin Name:     Increase Product Variation Limit For WooCommerce
 * Plugin URI:      https://maticpogladic.com/increase-product-variation-limit-for-woocommerce
 * Description:     Adds settings to increase the product variation limit in WooComerce.
 * Author:          Matic Pogladič
 * Author URI:      https://maticpogladic.com
 * Text Domain:     increase-product-variation-limit-for-woocommerce
 * Version:         1.0
 *
 * @package         Increase_Product_Variation_Limit_For_Woocommerce
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'IPVL_VERSION', '1.0' );
define( 'IPVL_PREFIX', 'ipvl_' );
define( 'IPVL_NAME', 'Increase Product Variation Limit For WooCommerce' );
define( 'IPVL_MIN_PHP_VER', '5.6' );
define( 'IPVL_MIN_WP_VER', '4.2' );
define( 'IPVL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'IPVL_PLUGIN_PATH', dirname( __FILE__ ) );

require plugin_dir_path( __FILE__ ) . 'includes/plugin.php';

if ( ! function_exists( 'ipvl_load_plugin' ) ) {

	function ipvl_load_plugin() {

		$plugin = increase_product_variation_limit();

		if ( $plugin::check() ) {
			$plugin->init();
		}
	}

	add_action( 'plugins_loaded', 'ipvl_load_plugin', 8 );
}
