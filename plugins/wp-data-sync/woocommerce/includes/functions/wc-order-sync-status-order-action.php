<?php
/**
 * WooCommerce Order Sync Status Order Actions
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
 * Add the Remove the order sync status action
 *
 * @param array $actions
 */

add_filter( 'woocommerce_order_actions', function( $actions ) {

	global $theorder;

    $synced = $theorder->get_meta( WCDSYNC_ORDER_SYNC_STATUS );

	if ( ! empty( $synced ) ) {
		$actions['wpds_order_sync_status'] = __( 'Reset order sync status', 'wp-data-sync' );
	}

	return $actions;

} );

/**
 * Remove the order sync status and add an order note.
 *
 * @param \WC_Order $order
 */

add_action( 'woocommerce_order_action_wpds_order_sync_status', function ( \WC_Order $order ) {

	$order->add_order_note( __( 'Manually removed order sync status.', 'wp-data-sync' ) );
    $order->delete_meta_data( WCDSYNC_ORDER_SYNC_STATUS );
    $order->save();

} );
