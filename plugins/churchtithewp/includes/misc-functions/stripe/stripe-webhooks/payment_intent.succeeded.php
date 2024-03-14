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
 * Handle the Stripe Webhook for payment_intent.succeeded
 *
 * @since    1.0.0
 * @param    array $webhook_data This is the data from Stripe for this webhook.
 * @return   string The action taken by this webhook handler.
 */
function church_tithe_wp_stripe_webhook_payment_intent_succeeded( $webhook_data ) {

	$payment_intent_id = $webhook_data['data']['object']['id'];
	$charges           = $webhook_data['data']['object']['charges']['data'];

	// For any transaction with this PaymentIntent ID, add the charge ID to the charge column in the database.
	foreach ( $charges as $charge ) {

		// This will retrieve the balance_transaction from the passed-in charge, which is what we need in order to get the fee amount.
		$s = new Church_Tithe_WP_Stripe_Get(
			array(
				'url' => 'https://api.stripe.com/v1/balance/history/' . $charge['balance_transaction'],
			)
		);

		// Execute the call to Stripe.
		$balance_transaction = $s->call();

		$home_currency  = $balance_transaction['currency'];
		$gateway_fee_hc = $balance_transaction['fee'];
		$earnings_hc    = $balance_transaction['net'];

		// Update the transaction ID in question.
		$transaction_data = array(
			'date_paid'      => gmdate( 'Y-m-d H:i:s', $charge['created'] ),
			'home_currency'  => $home_currency,
			'gateway_fee_hc' => $gateway_fee_hc,
			'earnings_hc'    => $earnings_hc,
			'charge_id'      => $charge['id'],
		);

		$transaction = new Church_Tithe_WP_Transaction( $payment_intent_id, 'payment_intent_id' );

		// If no transaction was found matching that PaymentIntent ID from the webhook.
		if ( 0 === $transaction->id ) {
			// translators: The id of the PaymentIntent from Stripe which could not be found attached to a transaction in the Church Tithe WP database.
			$action_description = sprintf( __( 'Church Tithe WP could not find any transaction with the PaymentIntent Id %s', 'church-tithe-wp' ), $payment_intent_id );
			return $action_description;
		}

		// If a matching transaction record was found, matching the PaymentIntentID, update the transaction record to include the Balance data (fees, charge id, etc).
		$transaction_updated_result = $transaction->update( $transaction_data );

		if ( $transaction_updated_result ) {

			// Send the email receipt to the user and admin, if they weren't already sent on the frontend purchase confirmation.
			if ( ! $transaction->initial_emails_sent ) {
				$email_to_admin    = church_tithe_wp_send_receipt_email_to_admin( $transaction );
				$email_to_customer = church_tithe_wp_send_receipt_email( $transaction );

				if ( $email_to_admin && $email_to_customer ) {
					// Update the value of initial_emails_sent to be true so we don't double send the notifications in the future.
					$transaction->update(
						array(
							'initial_emails_sent' => 1,
						)
					);
				}
			}

			// translators: The ID of the transaction which was just updated.
			$action_description = sprintf( __( 'Church Tithe WP updated transaction %s', 'church-tithe-wp' ), $transaction->id );
		} else {
			// translators: the ID of the transaction which we were unable to update.
			$action_description = sprintf( __( 'Church Tithe WP was not able to update transaction', 'church-tithe-wp' ), $transaction->id );
		}

		// Get the arrangement attached to this transaction.
		$arrangement = new Church_Tithe_WP_Arrangement( $transaction->id, 'initial_transaction_id' );

		// If no arrangement was found matching that transaction ID.
		if ( 0 === $arrangement->id ) {
			// translators: The id of the transaction which is missing an arrangement.
			$action_description = sprintf( __( 'Church Tithe WP could not find any arrangement with the initial transaction ID %s', 'church-tithe-wp' ), $transaction->id );
			continue;
		}

		// If the arrangement in question has no recurring information, skip it.
		if ( ! $arrangement->interval_count || ! $arrangement->interval_string ) {
			// translators: The ID of the arrangement which had no recurring data attached.
			return sprintf( __( 'No arrangement info found for %s', 'church-tithe-wp' ), $arrangement->id );
		}

		// Set up a recurring Subscription at Stripe for this arrangement.
		$result = church_tithe_wp_create_stripe_subscription_from_arrangement( $arrangement, $transaction, $charge );

		$action_description = $result['details'];
	}

	return $action_description;
}

/**
 * Create a subscription at Stripe using a Church Tithe WP arrangement object.
 *
 * @since    1.0.0
 * @param    object $arrangement Church Tithe WP arrangement object.
 * @param    array  $transaction Church Tithe WP transaction object.
 * @param    array  $charge Array from the payment_intent.succeeded stripe webhook.
 * @return   array
 */
function church_tithe_wp_create_stripe_subscription_from_arrangement( $arrangement, $transaction, $charge ) {

	// If this arrangement already has a subscription ID attached to it, check if it's legit.
	if ( $arrangement->gateway_subscription_id ) {
		return array(
			'success' => false,
			'details' => __( 'A subscription already exists for this arrangement.', 'church-tithe-wp' ),
		);
	}

	// If the arrangement in question has no recurring information, skip it.
	if ( ! $arrangement->interval_count || ! $arrangement->interval_string ) {
		return array(
			'success' => false,
			'details' => __( 'This arrangement is not a recurring one', 'church-tithe-wp' ),
		);
	}

	if ( ! $arrangement->user_id ) {
		return array(
			'success' => false,
			// translators: The arrangement ID for which there was no user attached.
			'details' => sprintf( __( 'No user attached to arrangement ID %s', 'church-tithe-wp' ), $arrangement->id ),
		);
	}

	$user = get_user_by( 'id', $arrangement->user_id );

	if ( ! $user ) {

		return array(
			'success' => false,
			// translators: First: The user ID for which there was no user attached. Second: The arrangement ID which didn't exist.
			'details' => sprintf( __( 'User ID (%1$s) attached to arrangement ID %2$s does not actually exist. It might have been deleted.', 'church-tithe-wp' ), $arrangement->user_id, $arrangement->id ),
		);

		// If a WP User already exists for this email.
	} else {

		$user_id = $user->ID;

		// A user already exists for this email, so check if they have a Stripe customer ID already. If not, one will be created for them.
		$stripe_customer = church_tithe_wp_get_stripe_customer( $user->user_email );

	}

	// If the currency being used for this payment does not match the currency from Stripe.
	if ( ! empty( $stripe_customer['currency'] ) && strtolower( $arrangement->currency ) !== $stripe_customer['currency'] ) {

		return array(
			'success' => false,
			// translators: The currency that is required.
			'details' => sprintf( __( '%s Currency required. Please try again.', 'church-tithe-wp' ), strtoupper( $stripe_customer['currency'] ) ),
		);

	}

	// Check if the "Tithe" Product exists in Stripe or not.
	$stripe_product_id = get_option( 'church_tithe_wp_stripe_product_id' );

	if ( empty( $stripe_product_id ) ) {

		// Create a "Product" in Stripe, which will be the tithe.
		$s = new Church_Tithe_WP_Stripe(
			array(
				'idempotency_key' => 'create_the_tithe_product_' . $arrangement->id,
				'url'             => 'https://api.stripe.com/v1/products',
				'fields'          => array(
					'name' => apply_filters( 'church_tithe_wp_stripe_product_name', __( 'Tithe', 'church-tithe-wp' ) ),
					'type' => 'service',
				),
			)
		);

		// Execute the call to Stripe.
		$product = $s->call();

		if ( isset( $product['error'] ) ) {

			return array(
				'success' => false,
				'type'    => 'Product',
				'details' => $product['error'],
			);

		}

		// Save the product ID in the database for future usage.
		update_option( 'church_tithe_wp_stripe_product_id', $product['id'] );

	} else {

		// Check if the saved product ID actually exists in Stripe.
		$s = new Church_Tithe_WP_Stripe_Get(
			array(
				'idempotency_key' => 'check_if_the_tithe_product_exists_already_' . $arrangement->id,
				'url'             => 'https://api.stripe.com/v1/products/' . $stripe_product_id,
			)
		);

		// Execute the call to Stripe.
		$product = $s->call();

		// If the product saved matches the one in Stripe.
		if ( isset( $product['id'] ) && $stripe_product_id === $product['id'] ) {

			// Let's make sure the Statement Descriptor is current for the Product.
			$s = new Church_Tithe_WP_Stripe(
				array(
					'idempotency_key' => 'update_tithe_product_statement_descriptor_' . $arrangement->id,
					'url'             => 'https://api.stripe.com/v1/products/' . $stripe_product_id,
					'fields'          => array(
						'statement_descriptor' => church_tithe_wp_statement_descriptor(),
					),
				)
			);

			// Execute the call to Stripe.
			$product = $s->call();

		} else {

			// Create a "Product" in Stripe, which will be the tithe.
			$s = new Church_Tithe_WP_Stripe(
				array(
					'idempotency_key' => 'create_the_tithe_product_' . $arrangement->id,
					'url'             => 'https://api.stripe.com/v1/products',
					'fields'          => array(
						'name' => apply_filters( 'church_tithe_wp_stripe_product_name', __( 'Tithe', 'church-tithe-wp' ) ),
						'type' => 'service',
					),
				)
			);

			// Execute the call to Stripe.
			$product = $s->call();

			// If a new product was not able to be created.
			if ( isset( $product['error'] ) ) {

				return array(
					'success' => false,
					'type'    => 'Product',
					'details' => $product['error'],
				);

			}

			// Save the product ID in the database for future usage.
			update_option( 'church_tithe_wp_stripe_product_id', $product['id'] );

		}
	}

	// Attach a "Plan" to that Product in Stripe, which defines things about the amount, interval etc.
	$s = new Church_Tithe_WP_Stripe(
		array(
			'idempotency_key' => 'attach_plan_to_tithe_product' . $product['id'] . $charge['currency'] . $arrangement->id,
			'url'             => 'https://api.stripe.com/v1/plans',
			'fields'          => array(
				'product'        => $product['id'],
				'currency'       => $charge['currency'],
				'interval_count' => $arrangement->interval_count,
				'interval'       => $arrangement->interval_string,
				'amount'         => $arrangement->renewal_amount,
			),
		)
	);

	// Execute the call to Stripe.
	$plan = $s->call();

	if ( isset( $plan['error'] ) ) {

		return array(
			'success' => false,
			'type'    => 'Plan',
			'details' => $plan['error'],
		);

	}

	// Create a "Subscription" and add that "Plan" to the subscription in Stripe.
	$s = new Church_Tithe_WP_Stripe(
		array(
			'idempotency_key' => 'attach_plan_to_new_subscription' . $plan['id'] . $arrangement->id,
			'url'             => 'https://api.stripe.com/v1/subscriptions',
			'fields'          => array(
				'customer'                => $stripe_customer['id'],
				'items'                   => array(
					array(
						'plan' => $plan['id'],
					),
				),
				// Here, we offset the first charge in the subscription because the original PaymentIntent is considered to be the first charge.
				// It is done this way to offset all of the subscription logic to this webhook, thus speeding up the original purchase time immensely.
				'billing_cycle_anchor'    => strtotime( $arrangement->interval_count . ' ' . $arrangement->interval_string ),
				'application_fee_percent' => 1,
				'prorate'                 => 'false',
				'payment_behavior'        => 'allow_incomplete',
				'default_payment_method'  => $charge['payment_method'],
			),
		)
	);

	// Execute the call to Stripe.
	$subscription = $s->call();

	if ( isset( $subscription['error'] ) || ! isset( $subscription['id'] ) ) {

		// Email the admin to let them know about this error. They probably should know about this one.
		$admin_email = get_bloginfo( 'admin_email' );
		// translators: The URL of this website.
		$subject   = sprintf( __( 'A Subscription creation attempt has failed on %s.', 'church-tithe-wp' ), get_bloginfo( 'url' ) );
		$body      = __( 'Please email support@churchtithewp.com with the following information for assistance.', 'church-tithe-wp' ) . ' ' . wp_json_encode( $subscription['error'] ) . "\n" . __( 'Data in request:', 'church-tithe-wp' ) . "\n" . wp_json_encode( $s->fields );
		$mail_sent = wp_mail( $admin_email, $subject, $body );

		return array(
			'success' => false,
			'type'    => 'Subscription',
			'details' => 'Unable to create subscription. Details: ' . wp_json_encode( $subscription['error'] ) . "\n" . __( 'Data in request:', 'church-tithe-wp' ) . "\n" . wp_json_encode( $s->fields ),
			'all'     => $subscription,
		);

	}

	// Add the subscription ID to the arrangement.
	$arrangement_data = array(
		'gateway_subscription_id' => $subscription['id'],
		'current_period_end'      => gmdate( 'Y-m-d H:i:s', $subscription['current_period_end'] ),
	);
	$arrangement->update( $arrangement_data );

	// Add the current period data to the corresponding transaction.
	$transaction->update(
		array(
			'period_start_date' => $transaction->date_paid, // Since this is the initial transaction, the start date of the period will match the transaction's date.
			'period_end_date'   => gmdate( 'Y-m-d H:i:s', $subscription['current_period_end'] ),
		)
	);

	return array(
		'success'             => true,
		'stripe_subscription' => $subscription['id'],
		// translators: The ID of the Stripe Subscription that was created.
		'details'             => sprintf( __( 'Stripe subscription successfully created with the id: %s', 'church-tithe-wp' ), $subscription['id'] ),
	);
}
