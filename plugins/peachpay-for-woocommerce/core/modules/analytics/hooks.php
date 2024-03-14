<?php
/**
 * PeachPay Stripe integration hooks.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'peachpay_analytics_initialize' );

// Begin detecting cart hooks.
add_action( 'woocommerce_add_to_cart', 'peachpay_analytics_update_cart' );
add_action( 'woocommerce_cart_item_removed', 'peachpay_analytics_update_cart' );
add_action( 'woocommerce_cart_item_set_quantity', 'peachpay_analytics_update_cart' );

// Billing and email information hook.
add_action( 'wc_ajax_update_order_review', 'peachpay_analytics_wc_ajax_update_billing' );
add_action( 'peachpay_analytics_update_email', 'peachpay_analytics_update_email' );

// Update to only necessary information and leave the rest to WooCommerce.
add_action( 'woocommerce_order_status_changed', 'peachpay_analytics_order_changed', 20, 3 );

// Error states
add_action( 'woocommerce_order_refunded', 'peachpay_analytics_order_refunded', 20, 2 );

// Listener for analytics queries
add_action( 'wp_ajax_pp-analytics-query', 'wp_ajax_query_analytics' );
