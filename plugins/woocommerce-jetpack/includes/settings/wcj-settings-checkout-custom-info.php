<?php
/**
 * Booster for WooCommerce - Settings - Checkout Custom Info
 *
 * @version 7.0.0
 * @since   2.8.0
 * @author  Pluggabl LLC.
 * @todo    [dev] clean up
 * @package Booster_For_WooCommerce/settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$settings     = array(
	array(
		'id'   => 'checkout_custom_info_options',
		'type' => 'sectionend',
	),
	array(
		'id'      => 'checkout_custom_info_options',
		'type'    => 'tab_ids',
		'tab_ids' => array(
			'checkout_custom_info_general_options_tab' => __( 'General Options', 'woocommerce-jetpack' ),
		),
	),
	array(
		'id'   => 'checkout_custom_info_general_options_tab',
		'type' => 'tab_start',
	),
	array(
		'title' => __( 'Checkout Custom Info Blocks', 'woocommerce-jetpack' ),
		'type'  => 'title',
		'id'    => 'wcj_checkout_custom_info_blocks_options',
	),
	array(
		'title'             => __( 'Total Blocks', 'woocommerce-jetpack' ),
		'id'                => 'wcj_checkout_custom_info_total_number',
		'default'           => 1,
		'type'              => 'custom_number',
		'desc'              => apply_filters( 'booster_message', '', 'desc' ),
		'custom_attributes' => apply_filters( 'booster_message', '', 'readonly' ),
	),
	array(
		'type' => 'sectionend',
		'id'   => 'wcj_checkout_custom_info_blocks_options',
	),
);
$total_number = apply_filters( 'booster_option', 1, wcj_get_option( 'wcj_checkout_custom_info_total_number', 1 ) );
for ( $i = 1; $i <= $total_number; $i++ ) {
	$settings = array_merge(
		$settings,
		array(
			array(
				'title' => __( 'Info Block', 'woocommerce-jetpack' ) . ' #' . $i,
				'type'  => 'title',
				'id'    => 'wcj_checkout_custom_info_options_' . $i,
			),
			array(
				'title'   => __( 'Content', 'woocommerce-jetpack' ),
				'id'      => 'wcj_checkout_custom_info_content_' . $i,
				'default' => '[wcj_cart_items_total_weight before="Total weight: " after=" kg"]',
				'type'    => 'textarea',
				'css'     => 'width:100%;height:200px;',
			),
			array(
				'title'   => __( 'Position', 'woocommerce-jetpack' ),
				'id'      => 'wcj_checkout_custom_info_hook_' . $i,
				'default' => 'woocommerce_checkout_after_order_review',
				'type'    => 'select',
				'options' => array(

					'woocommerce_before_checkout_form'  => __( 'Before checkout form', 'woocommerce-jetpack' ),
					'woocommerce_checkout_before_customer_details' => __( 'Before customer details', 'woocommerce-jetpack' ),
					'woocommerce_checkout_billing'      => __( 'Billing', 'woocommerce-jetpack' ),
					'woocommerce_checkout_shipping'     => __( 'Shipping', 'woocommerce-jetpack' ),
					'woocommerce_checkout_after_customer_details' => __( 'After customer details', 'woocommerce-jetpack' ),
					'woocommerce_checkout_before_order_review' => __( 'Before order review', 'woocommerce-jetpack' ),
					'woocommerce_checkout_order_review' => __( 'Order review', 'woocommerce-jetpack' ),
					'woocommerce_checkout_after_order_review' => __( 'After order review', 'woocommerce-jetpack' ),
					'woocommerce_after_checkout_form'   => __( 'After checkout form', 'woocommerce-jetpack' ),
					'woocommerce_thankyou'              => __( 'Order Received (Thank You) page', 'woocommerce-jetpack' ),
				),
			),
			array(
				'title'   => __( 'Position Order (i.e. Priority)', 'woocommerce-jetpack' ),
				'id'      => 'wcj_checkout_custom_info_priority_' . $i,
				'default' => 10,
				'type'    => 'number',
			),
			array(
				'id'   => 'wcj_checkout_custom_info_options_' . $i,
				'type' => 'sectionend',
			),
		)
	);
}
$settings = array_merge(
	$settings,
	array(
		array(
			'id'   => 'checkout_custom_info_general_options_tab',
			'type' => 'tab_end',
		),
	)
);
return $settings;
