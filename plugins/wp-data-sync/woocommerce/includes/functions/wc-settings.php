<?php
/**
 * Settings
 *
 * Plugin settings
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\Woo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'wp_data_sync_settings', function( $settings, $_settings ) {

	$settings = array_merge( $settings, [
		'woocommerce' => [
			[
				'key' 		=> 'wp_data_sync_product_visibility',
				'label'		=> __( 'Default Product Visibility', 'wp-data-sync' ),
				'callback'  => 'input',
				'args'      => [
					'sanitize_callback' => 'sanitize_text_field',
					'basename'          => 'select',
					'selected'          => get_option( 'wp_data_sync_product_visibility' ),
					'name'              => 'wp_data_sync_product_visibility',
					'class'             => 'product-visibility widefat',
					'values'            => [
						'visible'                                  => __( 'Shop and search results', 'woocommerce' ),
						'exclude-from-search'                      => __( 'Shop only', 'woocommerce' ),
						'exclude-from-catalog'                     => __( 'Search results only', 'woocommerce' ),
						'exclude-from-catalog,exclude-from-search' => __( 'Hidden', 'woocommerce' ),
						'featured'                                 => __( 'Featured', 'woocommerce' )
					]
				]
			],
			[
				'key' 		=> 'wp_data_sync_use_current_product_visibility',
				'label'		=> __( 'Use Current Product Visibility', 'wp-data-sync' ),
				'callback'  => 'input',
				'args'      => [
					'sanitize_callback' => 'sanitize_text_field',
					'basename'          => 'checkbox',
					'type'		        => '',
					'class'		        => '',
					'placeholder'       => '',
					'info'              => __( 'Use the current product visibility if the product visibility is already set. This will prevent DataSync from updating the current product visibility on existing products.', 'wp-data-sync' )
				]
			],
			[
				'key' 		=> 'wp_data_sync_process__crosssell_ids',
				'label'		=> __( 'Process Cross Sells', 'wp-data-sync' ),
				'callback'  => 'input',
				'args'      => [
					'sanitize_callback' => 'sanitize_text_field',
					'basename'          => 'checkbox',
					'type'		        => '',
					'class'		        => '',
					'placeholder'       => '',
					'info'              => __( 'This relates the IDs from your data source with the IDs from your website. Please note, if the related product does not exist, this system will relate the product when it is created in the data sync.', 'wp-data-sync' )
				]
			],
			[
				'key' 		=> 'wp_data_sync_process__upsell_ids',
				'label'		=> __( 'Process Up Sells', 'wp-data-sync' ),
				'callback'  => 'input',
				'args'      => [
					'sanitize_callback' => 'sanitize_text_field',
					'basename'          => 'checkbox',
					'type'		        => '',
					'class'		        => '',
					'placeholder'       => '',
					'info'              => __( 'This relates the IDs from your data source with the IDs from your website. Please note, if the related product does not exist, this system will relate the product when it is created in the data sync.', 'wp-data-sync' )
				]
			]
		],
		'orders' => [
			[
				'key' 		=> 'wp_data_sync_order_sync_allowed',
				'label'		=> __( 'Allow Order Sync', 'wp-data-sync' ),
				'callback'  => 'input',
				'args'      => [
					'sanitize_callback' => 'sanitize_text_field',
					'basename'          => 'checkbox',
					'tyoe'              => '',
					'class'             => 'sync-orders',
					'placeholder'       => '',
					'info'              => __( 'Allow order details to sync with the WP Data Sync API.', 'wp-data-sync' )
				]
			],
			[
				'key' 		=> 'wp_data_sync_allowed_order_status',
				'label'		=> __( 'Allowed Order Status', 'wp-data-sync' ),
				'callback'  => 'input',
				'args'      => [
					'sanitize_callback' => [ $_settings, 'sanitize_array' ],
					'basename'          => 'select-multiple',
					'name'              => 'wp_data_sync_allowed_order_status',
					'type'		        => '',
					'class'		        => 'wc-enhanced-select regular-text',
					'placeholder'       => '',
					'selected'          => get_option( 'wp_data_sync_allowed_order_status', [] ),
					'options'           => apply_filters( 'wp_data_sync_allowed_order_status', [
						'wc-pending'    => __( 'Pending', 'woocommerce' ),
						'wc-processing' => __( 'Processing', 'woocommerce' ),
						'wc-on-hold'    => __( 'On Hold', 'woocommerce' ),
						'wc-completed'  => __( 'Completed', 'woocommerce' ),
						'wc-refunded'   => __( 'Refunded', 'woocommerce' )
					] )
				]
			],
			[
				'key' 		=> 'wp_data_sync_order_allowed_product_cats',
				'label'		=> __( 'Allowed Product Categories', 'wp-data-sync' ),
				'callback'  => 'input',
				'args'      => [
					'sanitize_callback' => [ $_settings, 'sanitize_array' ],
					'basename'          => 'select-multiple',
					'name'              => 'wp_data_sync_order_allowed_product_cats',
					'type'		        => '',
					'class'		        => 'wc-enhanced-select regular-text',
					'placeholder'       => '',
					'selected'          => get_option( 'wp_data_sync_order_allowed_product_cats', [] ),
					'info'              => __( 'Include products with selected categories in order sync.', 'wp-data-sync' ),
					'options'           => get_product_category_options_array()
				]
			],
			[
				'key' 		=> 'wp_data_sync_order_require_valid_product',
				'label'		=> __( 'Require Valid Product', 'wp-data-sync' ),
				'callback'  => 'input',
				'args'      => [
					'sanitize_callback' => 'sanitize_text_field',
					'basename'          => 'checkbox',
					'tyoe'              => '',
					'class'             => 'sync-order-without-valid-product',
					'placeholder'       => '',
					'info'              => __( 'Require a valid poroduct in the order.', 'wp-data-sync' )
				]
			],
			[
				'key' 		=> 'wp_data_sync_show_order_sync_status_admin_column',
				'label'		=> __( 'Show Order Sync Status Admin Column', 'wp-data-sync' ),
				'callback'  => 'input',
				'args'      => [
					'sanitize_callback' => 'sanitize_text_field',
					'basename'          => 'checkbox',
					'tyoe'              => '',
					'class'             => 'show-admin-column',
					'placeholder'       => '',
					'info'              => __( 'Show admin column for order export status on Orders list.', 'wp-data-sync' )
				]
			],
		]
	] );

	return $settings;

}, 1, 2 );
