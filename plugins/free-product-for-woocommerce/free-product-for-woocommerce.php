<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://nitin247.com
 * @since             1.0.0
 * @package           Free_Product_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       FREE Product for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/free-product-for-woocommerce/
 * Description:       Display FREE if Price is Zero or Empty.
 * Version:           1.1
 * Author:            Nitin Prakash
 * Author URI:        https://nitin247.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       free-product-for-woocommerce
 * Domain Path:       /languages
 * Requires PHP:      7.4
 * WC requires at least: 8.0
 * WC tested up to: 8.6
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'FREE_PRODUCT_FOR_WOOCOMMERCE_VERSION', '1.1' );
define( 'FREE_PRODUCT_FOR_WOOCOMMERCE_DIR', plugin_dir_path( __FILE__ ) );

add_filter( 'woocommerce_get_price_html', 'fpfw_price_free_zero', 9999, 2 );

function fpfw_price_free_zero( $price, $product ) {

	$free_price_txt = __( 'FREE', 'free-product-for-woocommerce' );

	if ( $product->is_type( 'variable' ) ) {

		$prices    = $product->get_variation_prices( true );
		$min_price = current( $prices['price'] );
		if ( 0 === absint( $min_price ) ) {
			$max_price     = end( $prices['price'] );
			$min_reg_price = current( $prices['regular_price'] );
			$max_reg_price = end( $prices['regular_price'] );
			if ( $min_price !== $max_price ) {
				$price  = wc_format_price_range( $free_price_txt, $max_price );
				$price .= $product->get_price_suffix();
			} elseif ( $product->is_on_sale() && $min_reg_price === $max_reg_price ) {
				$price  = wc_format_sale_price( wc_price( $max_reg_price ), $free_price_txt );
				$price .= $product->get_price_suffix();
			} else {
				$price = $free_price_txt;
			}
		}
	} elseif ( 0 === absint( $product->get_price() ) ) {
		$price = '<span class="woocommerce-Price-amount amount">' . $free_price_txt . '</span>';
	}
	return $price;
}

