<?php
/**
 * Include order item.
 *
 * Filter to determine if an order item can be included in order sync.
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\Woo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @param bool           $include
 * @param \WC_Order_Item $item
 * @param \WC_Order      $order
 *
 * @since 1.10.1
 */
add_filter( 'wp_data_sync_include_order_item', function( $include, $item, $order ) {

	if ( $cat_ids = get_option( 'wp_data_sync_order_allowed_product_cats', [] ) ) {

		if ( in_array( '-1', $cat_ids ) ) {
			return true;
		}

		if ( $product_id = $item->get_product_id() ) {

			foreach ( $cat_ids as $cat_id ) {

				if ( has_term( $cat_id, 'product_cat', $product_id ) ) {
					return true;
				}

			}

		}

		return false;

	}

	return $include;

}, 10, 3 );
