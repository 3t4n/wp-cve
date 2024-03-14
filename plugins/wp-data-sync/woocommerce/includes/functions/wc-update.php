<?php
/**
 * WooCommerce Update
 *
 * Run functions on WC integration update.
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\Woo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'WP_DataSync\Woo\wc_update' );
add_action( 'admin_init', 'WP_DataSync\Woo\wc_update' );

/**
 * Run plugin update functions.
 */

function wc_update() {

	if ( WCDSYNC_VERSION !== get_option( 'WCDSYNC_VERSION' ) ) {

		WC_Product_Sells::create_table();

		update_product_sells_settings();

		update_option( 'WCDSYNC_VERSION', WCDSYNC_VERSION );

	}

}

/**
 * Update product sells settings.
 *
 * Update the setting names since WooCommerce changed the meta keys.
 *
 * @since 2.1.9
 */

function update_product_sells_settings() {

	if ( $value = get_option( 'wp_data_sync_process_up_sells' ) ) {
		update_option( 'wp_data_sync_process__upsell_ids', $value );
	}

	if ( $value = get_option( 'wp_data_sync_process_cross_sells' ) ) {
		update_option( 'wp_data_sync_process__crosssell_ids', $value );
	}

}
