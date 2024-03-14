<?php
/**
 * Product Attribute Clear Cache
 *
 * Clear the product attribute cache..
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\Woo;

use WC_Cache_Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Clear WooCommerce product attribute cache.
 *
 * @param int $product_id
 * @param array $product_attributes
 */

add_action( 'wp_data_sync_attributes', function( $product_id, $product_attributes ) {

	if ( empty( $product_attributes ) ) {
		return;
	}

	delete_transient( 'wc_attribute_taxonomies' );
	WC_Cache_Helper::invalidate_cache_group( 'woocommerce-attributes' );

}, 10, 2 );