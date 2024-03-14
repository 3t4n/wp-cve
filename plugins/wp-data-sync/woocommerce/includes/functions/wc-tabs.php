<?php
/**
 * Admin Tabs
 *
 * Add admin tabs to the WP Data Sync admin page.
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\Woo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_data_sync_admin_tabs', function( $tabs ) {

	$tabs = array_merge( $tabs, [
		'woocommerce' => [
			'label' => __( 'WC Products', 'wp-data-sync' )
		],
		'orders' => [
			'label' => __( 'WC Orders', 'wp-data-sync' )
		]
	] );

	return $tabs;

} );