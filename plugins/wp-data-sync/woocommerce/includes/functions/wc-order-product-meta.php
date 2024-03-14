<?php
/**
 * WooCommerce Order - Product Meta
 *
 * @since   2.7.12
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\Woo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @param array      $order_item
 * @param int        $product_id
 * @param WC_Product $product
 *
 * @return array
 */

add_filter( 'wp_data_sync_order_items_product', function( $order_item, $product_id ) {

    $meta = get_post_meta( $product_id );

    foreach ( $meta as $key => $value ) {
        $order_item['meta_data'][ $key ] = maybe_unserialize( reset( $value ) );
    }


    return $order_item;

}, 10, 3 );
