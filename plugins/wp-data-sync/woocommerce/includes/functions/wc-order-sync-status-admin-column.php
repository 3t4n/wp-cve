<?php
/**
 * WooCommerce Order Sync Status Admin Column
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\Woo;

use WP_DataSync\App\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add order sync status admin column.
 *
 * @param array $columns
 */

add_filter( 'woocommerce_shop_order_list_table_columns', function( $columns ) {

	if ( Settings::is_checked( 'wp_data_sync_show_order_sync_status_admin_column' ) ) {
		$columns['wpds_sync_status'] = __( 'Sync Status', 'wp-data-sync' );
	}

	return $columns;

}, 99 );

/**
 * Display contents of the order sync status admin column.
 *
 * @param string $column
 * @param int    $order_id
 */

add_action( 'woocommerce_shop_order_list_table_custom_column', function( $column, $order_id ) {

	if ( 'wpds_sync_status' === $column ) {

        $order  = wc_get_order( $order_id );
        $synced = $order->get_meta( WCDSYNC_ORDER_SYNC_STATUS );

		if ( $synced ) {

			if ( 'no' !== $value ) {
				printf( '<span class="wpds-order-export synced">%s</span>', esc_html( '&#10003;' ) );

				return;
			}

		}

		printf( '<span class="wpds-order-export">%s</span>', esc_html( '&#10005;' ) );

	}

}, 10, 2 );
