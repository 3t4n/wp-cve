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
 * Endpoint which updates an arrangement for the currently-logged-in user. It is separated out like this so it can be unit tested.
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_update_arrangement_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_update_arrangement'] ) ) {
		return false;
	}

	$endpoint_result = church_tithe_wp_update_arrangement_handler();

	echo wp_json_encode( $endpoint_result );
	die();
}
add_action( 'init', 'church_tithe_wp_update_arrangement_endpoint' );

/**
 * Update a single arrangement from the frontend.
 *
 * @access   public
 * @since    1.0.0
 * @return   array
 */
function church_tithe_wp_update_arrangement_handler() {

	// Verify the nonce.
	if ( ! isset( $_POST['church_tithe_wp_update_arrangement_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_update_arrangement_nonce'] ) ), 'church_tithe_wp_update_arrangement_nonce' ) ) {
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

	$church_tithe_wp_arrangement_id = absint( $_POST['church_tithe_wp_arrangement_id'] );

	$arrangement = new Church_Tithe_WP_Arrangement( $church_tithe_wp_arrangement_id );

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

	// If we should update the payment method.
	if ( isset( $_POST['church_tithe_wp_stripe_payment_method_id'] ) ) {
		$payment_method_id = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_stripe_payment_method_id'] ) );
		$stripe_customer   = church_tithe_wp_get_stripe_customer( $user->user_email );

		// Attach the payment method to the customer at Stripe.
		$s = new Church_Tithe_WP_Stripe(
			array(
				'url'    => 'https://api.stripe.com/v1/payment_methods/' . $payment_method_id . '/attach',
				'fields' => array(
					'customer' => $stripe_customer['id'],
				),
			)
		);

		// Execute the call to Stripe.
		$payment_method_attached_to_customer = $s->call();

		// If ther ewas a problem attaching the payment method to the customer...
		if ( isset( $payment_method_attached_to_customer['error'] ) ) {

			return array(
				'success'    => false,
				'error_code' => 'unable_to_attach_payment_method_to_customer_at_stripe',
				'details'    => $payment_method_attached_to_customer['error'],
			);

		}

		// Attach this payment method to the Stripe subscription so it is used in the future.
		$s = new Church_Tithe_WP_Stripe(
			array(
				'idempotency_key' => 'update_payment_method_for_subscription' . $payment_method_id . '_' . $arrangement->id,
				'url'             => 'https://api.stripe.com/v1/subscriptions/' . $arrangement->gateway_subscription_id,
				'fields'          => array(
					'default_payment_method' => $payment_method_id,
				),
			)
		);

		// Execute the call to Stripe.
		$stripe_subscription = $s->call();

		// If ther ewas a problem attaching the payment method to the customer...
		if ( isset( $stripe_subscription['error'] ) || ! isset( $stripe_subscription['default_payment_method'] ) ) {

			return array(
				'success'    => false,
				'error_code' => 'unable_to_attach_payment_method_to_customer_at_stripe',
				'details'    => $stripe_subscription['error'],
			);

		}

		// Get the data for this payment_method from Stripe.
		$s = new Church_Tithe_WP_Stripe_Get(
			array(
				'url' => 'https://api.stripe.com/v1/payment_methods/' . $stripe_subscription['default_payment_method'],
			)
		);

		// Execute the call to Stripe.
		$payment_method = $s->call();

	}

	return array(
		'success'          => true,
		'arrangement_info' => church_tithe_wp_arrangement_info_format_for_endpoint( $arrangement ),
		'payment_method'   => $payment_method,
	);

}
