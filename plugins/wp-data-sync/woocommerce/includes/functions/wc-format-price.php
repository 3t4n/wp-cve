<?php
/**
 * WooCommerce Price
 *
 * Format WooCommerce prices before insert.
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\Woo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'wp_data_sync__regular_price_value', 'WP_DataSync\Woo\format_woocommerce_price', 10, 1 );
add_filter( 'wp_data_sync__sale_price_value', 'WP_DataSync\Woo\format_woocommerce_price', 10, 1 );
add_filter( 'wp_data_sync__price_value', 'WP_DataSync\Woo\format_woocommerce_price', 10, 1 );

/**
 * Format decimal for WooCommerce prices.
 *
 * @param $price
 *
 * @return float|string
 */

function format_woocommerce_price( $price ) {
	return wc_format_decimal( $price );
}
