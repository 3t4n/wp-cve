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
 * Endpoint which handles the Get Payment Intent endpoint. It is separated out like this so it can be unit tested.
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_get_payment_intent_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_get_payment_intent'] ) ) {
		return false;
	}

	$endpoint_result = church_tithe_wp_get_payment_intent_handler();

	echo wp_json_encode( $endpoint_result );
	die();
}
add_action( 'init', 'church_tithe_wp_get_payment_intent_endpoint' );

/**
 * Callback function which fires after a Stripe Token is successfuly created
 *
 * @access   public
 * @since    1.0.0
 * @return   array
 */
function church_tithe_wp_get_payment_intent_handler() {

	// If there is required data that is missing.
	if (
		! isset( $_POST['church_tithe_wp_stripe_payment_method_id'] ) || empty( $_POST['church_tithe_wp_stripe_payment_method_id'] ) ||
		! isset( $_POST['church_tithe_wp_amount'] ) || empty( $_POST['church_tithe_wp_amount'] ) ||
		! isset( $_POST['church_tithe_wp_name'] ) || empty( $_POST['church_tithe_wp_name'] ) ||
		! isset( $_POST['church_tithe_wp_email'] ) || empty( $_POST['church_tithe_wp_email'] ) ||
		! isset( $_POST['church_tithe_wp_currency'] ) || empty( $_POST['church_tithe_wp_currency'] ) ||
		! isset( $_POST['church_tithe_wp_method'] ) || empty( $_POST['church_tithe_wp_method'] ) ||
		! isset( $_POST['church_tithe_wp_page_url'] ) || empty( $_POST['church_tithe_wp_page_url'] ) ||
		! isset( $_POST['church_tithe_wp_recurring_value'] ) || empty( $_POST['church_tithe_wp_recurring_value'] )
	) {

		return array(
			'success'    => false,
			'error_code' => 'values_missing',
			'details'    => 'Values were missing. Please try again.',
		);

	}

	// Sanitize all incoming values
	$church_tithe_wp_stripe_payment_method_id = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_stripe_payment_method_id'] ) );
	$church_tithe_wp_amount                   = intval( wp_unslash( $_POST['church_tithe_wp_amount'] ) );
	$church_tithe_wp_name                     = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_name'] ) );
	$church_tithe_wp_email                    = sanitize_email( wp_unslash( $_POST['church_tithe_wp_email'] ) );
	$church_tithe_wp_currency                 = strtolower( sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_currency'] ) ) );
	$church_tithe_wp_method                   = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_method'] ) );
	$church_tithe_wp_page_url                 = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_page_url'] ) );
	$church_tithe_wp_recurring_value          = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_recurring_value'] ) );
	$default_settings                         = get_option( 'church_tithe_wp_settings' );

	// Create/Get a WordPress User to correspond with this customer. They don't need to log in, or even know this account exists.
	// Rather, we simply use it to store meta about them, like their customer ID from Stripe.
	// This lets us do subsequent calls to update this customer in the future.
	if ( is_user_logged_in() ) {
		$user = wp_get_current_user();
	} else {
		$user = get_user_by( 'email', $church_tithe_wp_email );
	}

	// Default this value to false, and we'll override it later if the user doesn't exist.
	$create_a_user_for_this_email = false;

	// Create/Get a WordPress User to correspond with this customer. They don't need to log in, or even know this account exists.
	// Rather, we simply use it to store meta about them, like their customer ID from Stripe.
	// This lets us do subsequent calls to update this customer in the future.
	if ( is_user_logged_in() ) {
		$user = wp_get_current_user();
	} else {
		$user = get_user_by( 'email', $church_tithe_wp_email );
	}

	// If we need to create a new WP user for this email.
	if ( ! $user ) {

		// Create Stripe customer and use email to identify them in stripe.
		$s = new Church_Tithe_WP_Stripe(
			array(
				'url'    => 'https://api.stripe.com/v1/customers',
				'fields' => array(
					'email'          => $church_tithe_wp_email,
					'payment_method' => $church_tithe_wp_stripe_payment_method_id,
				),
			)
		);

		// Execute the call to Stripe.
		$stripe_customer = $s->call();

		if ( isset( $stripe_customer['error'] ) ) {
			return array(
				'success'    => false,
				'error_code' => 'unable_to_create_customer_for_that_email',
				'details'    => 'That email was not accepted.',
			);
		}

		// Make a note to create a WP user for this email, but wait until after Stripe Radar Validates them.
		$create_a_user_for_this_email = true;

		// If a WP User already exists for this email.
	} else {

		$user_id = $user->ID;

		// A user already exists for this email, so check if they have a Stripe customer ID already. If not, one will be created for them.
		$stripe_customer = church_tithe_wp_get_stripe_customer( $user->user_email );

	}

	// Make sure the current payment method is attached to the customer in question.
	$s = new Church_Tithe_WP_Stripe(
		array(
			'url'    => 'https://api.stripe.com/v1/payment_methods/' . $church_tithe_wp_stripe_payment_method_id . '/attach',
			'fields' => array(
				'customer' => $stripe_customer['id'],
			),
		)
	);

	// Execute the call to Stripe.
	$payment_method_attached_to_customer = $s->call();

	if ( isset( $payment_method_attached_to_customer['error'] ) ) {

		// If the card was declined.
		if (
			isset( $payment_method_attached_to_customer['error']['type'] ) &&
			'card_error' === $payment_method_attached_to_customer['error']['type']
		) {
				return array(
					'success'    => false,
					'error_code' => 'card_error',
					'details'    => isset( $payment_method_attached_to_customer['error']['message'] ) ? $payment_method_attached_to_customer['error']['message'] : __( 'Something is wrong with your card.', 'church-tithe-wp' ),
				);
		} else {
			// Log this error, but move on, since this isn't pivotal to accepting a payment.
			$log = new Church_Tithe_WP_Log();
			$log->create(
				array(
					'log_data' => wp_json_encode( $payment_method_attached_to_customer ),
				)
			);
		}
	}

	// Set this payment method as the default payment method for all invoice (subscription) payments.
	$s = new Church_Tithe_WP_Stripe(
		array(
			'url'    => 'https://api.stripe.com/v1/customers/' . $stripe_customer['id'],
			'fields' => array(
				'invoice_settings' => array(
					'default_payment_method' => $church_tithe_wp_stripe_payment_method_id,
				),
			),
		)
	);

	// Execute the call to Stripe.
	$stripe_customer = $s->call();

	if ( isset( $stripe_customer['error'] ) ) {

		// Email the admin to let them know about this error. They probably should know about this one since it's something possibly new at the Stripe API.
		$admin_email = get_bloginfo( 'admin_email' );
		// translators: The url of this website.
		$subject   = sprintf( __( 'A Payment has failed on %s.', 'church-tithe-wp' ), get_bloginfo( 'url' ) );
		$body      = __( 'Please email support@churchtithewp.com with the following information for assistance.', 'church-tithe-wp' ) . ' ' . wp_json_encode( $stripe_customer['error'] ) . "\n" . __( 'Data in request:', 'church-tithe-wp' ) . "\n" . wp_json_encode( $s->fields );
		$mail_sent = wp_mail( $admin_email, $subject, $body );

		return array(
			'success'    => false,
			'error_code' => 'unable_to_set_default_payment_method',
			'details'    => 'Something went wrong.',
		);
	}

	// If the currency being used for this payment does not match the currency from Stripe.
	if ( ! empty( $stripe_customer['currency'] ) && $church_tithe_wp_currency !== $stripe_customer['currency'] ) {

		return array(
			'success'    => false,
			'error_code' => 'currency_mismatch',
			// translators: Name of the currency required.
			'details'    => sprintf( __( '%s Currency required. Please try again.', 'church-tithe-wp' ), strtoupper( $stripe_customer['currency'] ) ),
		);

	}

	/*
	// Send a call to Stripe to generate a SetupIntent.
	$s = new Church_Tithe_WP_Stripe(
		array(
			'url'    => 'https://api.stripe.com/v1/setup_intents',
			'fields' => array(
				'confirm'              => 'true',
				'customer'             => $stripe_customer['id'],
				'payment_method_types' => array( 'card' ),
				'payment_method'       => $church_tithe_wp_stripe_payment_method_id,
			),
		)
	);

	// Execute the call to Stripe.
	$setup_intent = $s->call();

	*/

	// Send a call to Stripe to generate a PaymentIntent.
	$s = new Church_Tithe_WP_Stripe(
		array(
			'url'    => 'https://api.stripe.com/v1/payment_intents',
			'fields' => array(
				'description'            => church_tithe_wp_get_saved_setting( $default_settings, 'tithe_form_title', get_bloginfo( 'name' ) ),
				'amount'                 => $church_tithe_wp_amount,
				'currency'               => $church_tithe_wp_currency,
				'customer'               => $stripe_customer['id'],
				'payment_method_types'   => array( 'card' ),
				'payment_method'         => $church_tithe_wp_stripe_payment_method_id,
				'save_payment_method'    => 'true',
				'statement_descriptor'   => church_tithe_wp_statement_descriptor(),
				'application_fee_amount' => absint( $church_tithe_wp_amount * .01 ),
			),
		)
	);

	// Execute the call to Stripe.
	$payment_intent = $s->call();

	if ( isset( $payment_intent['error'] ) ) {

		// If the amount is too low.
		if ( 1 > $church_tithe_wp_amount || ( isset( $payment_intent['error']['code'] ) && 'amount_too_small' === $payment_intent['error']['code'] ) ) {
			$details    = $payment_intent['error']['message'];
			$error_code = 'amount_too_low';
		} else {

			// If we don't know why it failed.
			$details    = __( 'Unable to create payment at this time. Please try again', 'church-tithe-wp' );
			$error_code = 'unknown';

			// Email the admin to let them know about this error. They probably should know about this one since it's something possibly new at the Stripe API.
			$admin_email = get_bloginfo( 'admin_email' );
			// translators: The url of this website.
			$subject   = sprintf( __( 'A Payment Intent has failed on %s.', 'church-tithe-wp' ), get_bloginfo( 'url' ) );
			$body      = __( 'Please email support@churchtithewp.com with the following information for assistance.', 'church-tithe-wp' ) . ' ' . wp_json_encode( $payment_intent['error'] ) . "\n" . __( 'Data in request:', 'church-tithe-wp' ) . "\n" . wp_json_encode( $s->fields );
			$mail_sent = wp_mail( $admin_email, $subject, $body );
		}

		return array(
			'success'    => false,
			'type'       => 'Payment Intent',
			'error_code' => $error_code,
			'details'    => $details,
		);

	}

	// The payment_intent was successful. This means we have confidence that the email entered actually belongs to this person, since Stripe Radar validated it.
	if ( $create_a_user_for_this_email ) {

		// Create a WP account for this email.
		$user_id = wp_create_user( $church_tithe_wp_email, wp_generate_password(), $church_tithe_wp_email );

		// If a user was not able to be generated, return why.
		if ( is_wp_error( $user_id ) ) {

			return array(
				'success'    => false,
				'error_code' => 'unable_to_create_user',
				'details'    => $user_id->get_error_message(),
			);

		} else {

			$user = get_user_by( 'id', $user_id );

			// Store the customer ID as user meta for this user.
			$meta_key           = church_tithe_wp_stripe_get_customer_key();
			$stripe_customer_id = update_user_meta( $user->ID, $meta_key, $stripe_customer['id'] );
		}
	}

	// Update the user account for this email. This ensures they get a proper name attached.
	update_user_meta( $user_id, 'church_tithe_wp_name', $church_tithe_wp_name );

	// Set up the recurring variables based on the incoming data.
	if ( 'never' !== $church_tithe_wp_recurring_value ) {

		$recurring_status = 'active';

		if ( 'weekly' === $church_tithe_wp_recurring_value ) {
			$interval_count = 1;
			$interval       = 'week';
		}

		if ( 'monthly' === $church_tithe_wp_recurring_value ) {
			$interval_count = 1;
			$interval       = 'month';
		}

		if ( 'yearly' === $church_tithe_wp_recurring_value ) {
			$interval_count = 1;
			$interval       = 'year';
		}
	} else {

		$recurring_status = 'off';
		$interval_count   = null;
		$interval         = null;

	}

	// Create a new tithe entry in the Church Tithe WP "transactions" table, which automatically creates an arrangement as well.
	$transaction                 = new Church_Tithe_WP_Transaction();
	$transaction_data            = array(
		'user_id'                 => $user_id,
		'type'                    => 'active' === $recurring_status ? 'initial' : 'single',
		'method'                  => $church_tithe_wp_method,
		'page_url'                => $church_tithe_wp_page_url,
		'charged_amount'          => $church_tithe_wp_amount,
		'charged_currency'        => $church_tithe_wp_currency,
		'payment_intent_id'       => $payment_intent['id'],
		'statement_descriptor'    => church_tithe_wp_statement_descriptor(),
		'is_live_mode'            => church_tithe_wp_stripe_is_live_mode(),

		// This data will be used to automatically generate a corresponding arrangement, because the type is "initial" (above).
		'initial_amount'          => $church_tithe_wp_amount,
		'renewal_amount'          => $church_tithe_wp_amount,
		'interval_count'          => $interval_count,
		'interval_string'         => $interval,
		'recurring_status'        => $recurring_status,
		'gateway_subscription_id' => null, // This will be filled when the webhook comes in. See payment_intent.succeeded.php.
	);
	$transaction_creation_result = $transaction->create( $transaction_data );

	return array(
		'success'          => true,
		'session_id'       => church_tithe_wp_create_payment_session( $user_id, $transaction->id ),
		'user_id'          => $user_id,
		'client_secret'    => $payment_intent['client_secret'],
		'response'         => $payment_intent,
		'transaction_info' => church_tithe_wp_transaction_info_format_for_endpoint( $transaction ),
	);

}
