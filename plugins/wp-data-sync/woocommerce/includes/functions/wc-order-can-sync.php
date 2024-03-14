<?php
/**
 * Can Sync Order
 *
 * Filter to determine if an order can be synced.
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
 * @param bool  $include
 * @param array $order_data
 *
 * @since 1.10.1
 *
 * @return bool
 */

add_filter( 'wp_data_sync_can_sync_order', function( $can_sync, $order_data ) {

	if ( Settings::is_checked( 'wp_data_sync_order_require_valid_product' ) && empty( $order_data['items'] ) ) {
		return false;
	}

	return $can_sync;

}, 10, 2 );
