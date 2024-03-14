<?php
/**
 * Church Tithe WP
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/Church Tithe WP
 * @copyright   Copyright (c) 2018, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Endpoint which gets a Stripe subscription's default payment method.
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_get_arrangement_payment_method_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_get_arrangement_payment_method_endpoint'] ) ) {
		return false;
	}

	$endpoint_result = church_tithe_wp_get_arrangement_payment_method_handler();

	echo wp_json_encode( $endpoint_result );
	die();
}
add_action( 'init', 'church_tithe_wp_get_arrangement_payment_method_endpoint' );

/**
 * Endpoint which gets a Stripe subscription's default payment method.
 *
 * @access   public
 * @since    1.0.0
 * @return   array
 */
function church_tithe_wp_get_arrangement_payment_method_handler() {

	// Verify the nonce.
	if ( ! isset( $_POST['church_tithe_wp_get_arrangement_payment_method_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_get_arrangement_payment_method_nonce'] ) ), 'church_tithe_wp_get_arrangement_payment_method_nonce' ) ) {
		return array(
			'success'    => false,
			'error_code' => 'nonce_failed',
			'details'    => 'Nonce failed.',
		);
	}

	$user = wp_get_current_user();

	// If no current user was found.
	if ( ! $user->ID ) {
		return array(
			'success'    => false,
			'error_code' => 'not_logged_in',
		);
	}

	// If json_decode failed, the JSON is invalid.
	if ( ! is_array( $_POST ) || ! isset( $_POST['church_tithe_wp_arrangement_id'] ) ) {
		return array(
			'success'    => false,
			'error_code' => 'invalid_params',
			'details'    => 'Invalid params',
		);
	}

	$arrangement = absint( $_POST['church_tithe_wp_arrangement_id'] );

	$arrangement = new Church_Tithe_WP_Arrangement( $arrangement );

	if ( 0 === $arrangement->id ) {
		return array(
			'success'    => false,
			'error_code' => 'no_matching_arrangement_found',
			'details'    => 'No Plan found with that ID',
		);
	}

	if ( absint( $user->ID ) !== absint( $arrangement->user_id ) ) {
		return array(
			'success'    => false,
			'error_code' => 'invalid_user',
			'details'    => 'Invalid user' . $user->ID . '-' . $arrangement->user_id,
		);
	}

	// Get the data for this subscription from Stripe.
	$s = new Church_Tithe_WP_Stripe_Get(
		array(
			'url' => 'https://api.stripe.com/v1/subscriptions/' . $arrangement->gateway_subscription_id,
		)
	);

	// Execute the call to Stripe.
	$stripe_subscription = $s->call();

	// If no payment method is attached to this subscription at Stripe, get the customer's default card.
	if ( ! isset( $stripe_subscription['default_payment_method'] ) || empty( $stripe_subscription['default_payment_method'] ) ) {

		if ( ! isset( $stripe_subscription['customer'] ) ) {
			return array(
				'success'    => false,
				'error_code' => 'unable_to_get_payment_method',
				'details'    => $stripe_subscription,
			);
		}

		// Get the data for this subscription from Stripe.
		$s = new Church_Tithe_WP_Stripe_Get(
			array(
				'url' => 'https://api.stripe.com/v1/customers/' . $stripe_subscription['customer'],
			)
		);

		// Execute the call to Stripe.
		$stripe_customer = $s->call();

		if ( isset( $stripe_customer['error'] ) ) {
			return array(
				'success'    => false,
				'error_code' => 'unable_to_get_payment_method',
				'details'    => $stripe_customer['error'],
			);
		}

		// Get the data for this payment_method from Stripe.
		$s = new Church_Tithe_WP_Stripe_Get(
			array(
				'url' => 'https://api.stripe.com/v1/payment_methods/' . $stripe_customer['invoice_settings']['default_payment_method'],
			)
		);

		// Execute the call to Stripe.
		$payment_method = $s->call();

		if ( isset( $payment_method['error'] ) ) {
			return array(
				'success'    => false,
				'error_code' => 'unable_to_get_payment_method',
				'details'    => $payment_method['error'],
			);
		}
	} else {

		// Get the data for this payment_method from Stripe.
		$s = new Church_Tithe_WP_Stripe_Get(
			array(
				'url' => 'https://api.stripe.com/v1/payment_methods/' . $stripe_subscription['default_payment_method'],
			)
		);

		// Execute the call to Stripe.
		$payment_method = $s->call();

		if ( isset( $payment_method['error'] ) ) {
			return array(
				'success'    => false,
				'error_code' => 'unable_to_get_payment_method',
				'details'    => $payment_method['error'],
			);
		}
	}

	return array(
		'success'             => true,
		'payment_method_data' => $payment_method,
	);

}
