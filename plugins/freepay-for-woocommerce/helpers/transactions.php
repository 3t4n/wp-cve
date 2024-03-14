<?php

/**
 * Returns the proper transaction instance type
 *
 * @param WC_Order $order
 *
 * @return WC_FreePay_API_Payment|WC_FreePay_API_Subscription
 */
function woo_freepay_get_transaction_instance_by_order( $order ) {

	$order = woo_freepay_get_order( $order );

	// Instantiate a new transaction
	$api_transaction = new WC_FreePay_API_Payment();

	// If the order is a subscripion or an attempt of updating the payment method
	if ( ! WC_FreePay_Subscription_Utils::cart_contains_switches() && (
			WC_FreePay_Order_Utils::contains_wcs_subscription($order) ||
			WC_FreePay_Order_Utils::contains_wps_sfw_subscription($order) ||
			WC_FreePay_Order_Utils::is_request_to_change_payment() )
		) {
		// Instantiate a subscription transaction instead of a payment transaction
		$api_transaction = new WC_FreePay_API_Subscription();
	}

	return $api_transaction;
}



/**
 * Returns an existing payment link if available or creates a new one.
 *
 * @param WC_Order $order
 *
 * @param bool $force_update
 *
 * @return string
 * @throws FreePay_API_Exception
 */
function woo_freepay_create_payment_link( $order ) {

	$order = woo_freepay_get_order( $order );

	if ( ! $order->needs_payment() && ! WC_FreePay_Order_Utils::is_request_to_change_payment() ) {
		throw new \Exception( __( 'Order does not need payment', 'freepay-for-woocommerce' ) );
	}

	$transaction = woo_freepay_get_transaction_instance_by_order( $order );

	$link = $transaction->create_link( $order );

	if ( WC_FreePay_Helper::is_url( $link ) ) {
		WC_FreePay_Payment_Utils::set_payment_link( $order, $link );
		$payment_link = $link;
	}

	return $payment_link;
}

/**
 * Get WC_Order.
 *
 * @param mixed $order
 *
 * @return WC_Order
 */
function woo_freepay_get_order( $order ) {
	if ( ! is_object( $order ) ) {
		$order = wc_get_order( $order );
	}
	else if ( $order instanceof WP_Post ) {
		$order = wc_get_order( $order->ID );
	}

	return $order;
}

/**
 * Get WC_Subscription.
 *
 * @param mixed $order
 *
 * @return WC_Subscription
 */
function woo_freepay_get_subscription( $subscription ) {
	if ( ! function_exists( 'wcs_get_subscription' ) ) {
		return null;
	}

	if ( ! is_object( $subscription ) ) {
		$subscription = wcs_get_subscription( $subscription );
	}
	else if ( $subscription instanceof WP_Post ) {
		$subscription = wcs_get_subscription( $subscription->ID );
	}

	return $subscription;
}

function is_current_admin_screen( $screen_ids ) {
	$screen = get_current_screen();

	return $screen && in_array( $screen->id, $screen_ids, true );
}

function get_edit_order_screen_id() {
	return WC_FreePay_Helper::is_HPOS_enabled() ? wc_get_page_screen_id( 'shop-order' ) : 'shop_order';
}

function get_edit_subscription_screen_id() {
	return WC_FreePay_Helper::is_HPOS_enabled() && function_exists( 'wcs_get_page_screen_id' ) ? wcs_get_page_screen_id( 'shop-subscription' ) : 'shop_subscription';
}