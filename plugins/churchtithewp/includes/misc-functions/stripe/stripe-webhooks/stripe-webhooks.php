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
 * Once Stripe Connect has been connected, make a call to Stripe to set up the Webhook.
 * This is currently not working on Stripe's end.
 *
 * @since    1.0.0
 * @see      https://stripe.com/docs/webhooks/signatures
 * @return   bool
 */
function church_tithe_wp_stripe_webhook_valid() {

	// Get the data sent from Stripe in the body.
	$headers = getallheaders();

	if ( ! isset( $headers['Stripe-Signature'] ) ) {
		return false;
	}

	// Break the Stripe Signature Header into parts.
	$stripe_signature_parts = explode( ',', $headers['Stripe-Signature'] );

	// Create a key->value array containing the Stripe Signature parts.
	foreach ( $stripe_signature_parts as $stripe_signature_part ) {
		$key_value                                     = explode( '=', $stripe_signature_part );
		$stripe_signature_keys_values[ $key_value[0] ] = $key_value[1];
	}

	// If the stripe webhook did not contain a "t" paramater for the timestamp, fail the webhook.
	if ( ! isset( $stripe_signature_keys_values['t'] ) ) {
		return false;
	}

	// Grab the body from the webhook.
	$content = trim( file_get_contents( 'php://input' ) );

	// Create a payload string using the body of the webhook.
	$signed_payload = $stripe_signature_keys_values['t'] . '.' . $content;

	// Decode the JSON for the webhook.
	$webhook_data = json_decode( $content, true );

	$saved_settings = get_option( 'church_tithe_wp_settings' );

	if ( $webhook_data['livemode'] ) {
		$signing_secret = church_tithe_wp_get_saved_setting( $saved_settings, 'stripe_webhook_signing_secret_live_mode' );
	} else {
		$signing_secret = church_tithe_wp_get_saved_setting( $saved_settings, 'stripe_webhook_signing_secret_test_mode' );
	}

	$local_signature = hash_hmac( 'sha256', $signed_payload, $signing_secret );

	// If the created-local signature matches the one sent by Stripe.
	if ( hash_equals( $stripe_signature_keys_values['v1'], $local_signature ) ) {

		// Check if this signature is less than 1 second old.
		if ( $stripe_signature_keys_values['t'] > ( time() - 1000 ) ) {
			return true;
		}
	}

	return false;

}

/**
 * Handle the Stripe Webhooks. This makes things like refunds get reflected and handled on the WordPress side
 * Webhook address to set up in your Stripe account: https://yourdomainname.com/?church_tithe_wp_stripe_webhook
 * Set up webhooks here: https://dashboard.stripe.com/account/webhooks
 *
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_stripe_webhook_handler() {

	if ( ! isset( $_GET['church_tithe_wp_stripe_webhook'] ) ) {
		return false;
	}

	// Let's verify that this webhook actually came from Stripe.
	if ( ! church_tithe_wp_stripe_webhook_valid() ) {
		echo esc_textarea( __( 'Stripe signature failed', 'church-tithe-wp' ) );
		die();
	}

	$content      = trim( file_get_contents( 'php://input' ) );
	$webhook_data = json_decode( $content, true );

	// Set up the default action description.
	$action_description = __( 'No action taken by Church Tithe WP', 'church-tithe-wp' );

	// Handle the differetn relevant webhooks.
	switch ( $webhook_data['type'] ) {

		// A Payment Intent was successfully charged.
		case 'payment_intent.succeeded':
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/stripe/stripe-webhooks/payment_intent.succeeded.php';
			$action_description = church_tithe_wp_stripe_webhook_payment_intent_succeeded( $webhook_data );
			break;

		// A charge was successful.
		case 'charge.succeeded':
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/stripe/stripe-webhooks/charge.succeeded.php';
			$action_description = church_tithe_wp_stripe_webhook_charge_succeeded( $webhook_data );
			break;

		// A charge was refunded.
		case 'charge.refunded':
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/stripe/stripe-webhooks/charge.refunded.php';
			$action_description = church_tithe_wp_stripe_webhook_charge_refunded( $webhook_data );
			break;

		// A subscription is going to be renewed.
		case 'invoice.upcoming':
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/stripe/stripe-webhooks/invoice.upcoming.php';
			$action_description = church_tithe_wp_stripe_webhook_invoice_upcoming( $webhook_data );
			break;

		// This is a successful renewal payment for a subscription.
		case 'invoice.payment_succeeded':
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/stripe/stripe-webhooks/invoice.payment_succeeded.php';
			$action_description = church_tithe_wp_stripe_webhook_invoice_payment_succeeded( $webhook_data );
			break;

		// A renewal payment failed for a subscription.
		case 'invoice.payment_failed':
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/stripe/stripe-webhooks/invoice.payment_failed.php';
			$action_description = church_tithe_wp_stripe_webhook_invoice_payment_failed( $webhook_data );
			break;

		case 'customer.subscription.deleted':
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/stripe/stripe-webhooks/customer.subscription.deleted.php';
			$action_description = church_tithe_wp_stripe_webhook_customer_subscription_deleted( $webhook_data );
			break;

		default:
			break;

	}

	echo esc_textarea( __( 'Webhook received by Church Tithe WP.', 'church-tithe-wp' ) . ' ' );
	echo wp_json_encode( $action_description );
	die();

}
add_action( 'init', 'church_tithe_wp_stripe_webhook_handler' );
