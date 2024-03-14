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
 * Handle the Stripe Webhook for invoice.payment_succeeded. This is a successful renewal payment for a subscription.
 *
 * @since    1.0.0
 * @param    array $webhook_data This is the data from Stripe for this webhook.
 * @return   string The action taken by this webhook.
 */
function church_tithe_wp_stripe_webhook_invoice_payment_succeeded( $webhook_data ) {

	// First let's get the arrangement ID with this subscription ID.
	$arrangement = new Church_Tithe_WP_Arrangement( $webhook_data['data']['object']['subscription'], 'gateway_subscription_id' );

	// If no arrangement was found with that sub ID, do nothing.
	if ( ! $arrangement->id ) {

		// translators: The Stripe subscription ID that was not found in the Church Tithe WP Database.
		$action_description = sprintf( __( 'Church Tithe WP found no arrangement found with that sub ID: %s', 'church-tithe-wp' ), $webhook_data['data']['object']['subscription'] );
		return $action_description;

	}

	// Void any past_due invoices on this subscription. This prevents other charges from suddenly going through because a card was updated.
	$s = new Church_Tithe_WP_Stripe_Get(
		array(
			'url'    => 'https://api.stripe.com/v1/invoices',
			'fields' => array(
				'subscription' => $arrangement->gateway_subscription_id,
				'status'       => 'open',
				'limit'        => 100,
			),
		)
	);

	// Execute the call to Stripe.
	$past_due_invoices = $s->call();

	// Void all past due invoices on this subscription.
	foreach ( $past_due_invoices['data'] as $past_due_invoice ) {
		$s = new Church_Tithe_WP_Stripe(
			array(
				'idempotency_key' => 'void_invoice_' . $past_due_invoice['id'],
				'url'             => 'https://api.stripe.com/v1/invoices/' . $past_due_invoice['id'] . '/void',
			)
		);

		// Execute the call to Stripe.
		$voided_invoice = $s->call();

	}

	// Check whether we've already recorded this renewal payment (could be a duplicate webhook fire).
	$possibly_already_existing_transaction = new Church_Tithe_WP_Transaction( $webhook_data['data']['object']['charge'], 'charge_id' );

	// Don't create a new transaction entry in the Church Tithe WP "transactions" table if it already exists.
	if ( $possibly_already_existing_transaction->id ) {
		return $action_description;
	}

	// Get the initial transaction which generated this arrangement.
	$initial_transaction = new Church_Tithe_WP_Transaction( absint( $arrangement->initial_transaction_id ) );

	// Get the form from which the initial transaction was created.
	$form = new Church_Tithe_WP_Form( $initial_transaction->form_id );

	$transaction_data = array(
		'arrangement_id'    => $arrangement->id,
		'charge_id'         => $webhook_data['data']['object']['charge'],
		'date_paid'         => gmdate( 'Y-m-d H:i:s', $webhook_data['data']['object']['created'] ),
		'type'              => 'renewal',
		'user_id'           => $arrangement->user_id,
		'method'            => 'subscription',
		'page_url'          => $initial_transaction->page_url,
		'charged_amount'    => $webhook_data['data']['object']['amount_paid'],
		'charged_currency'  => $webhook_data['data']['object']['currency'],
		'is_live_mode'      => $webhook_data['livemode'],
		'payment_intent_id' => $webhook_data['data']['object']['payment_intent'],
	);

	$transaction                 = new Church_Tithe_WP_Transaction();
	$transaction_creation_result = $transaction->create( $transaction_data );

	if ( ! $transaction_creation_result['success'] ) {
		// translators: First: The plan/arrangement ID which was unable to have a renewal transaction created. Second: The reason it could not be created.
		$action_description = sprintf( __( 'Church Tithe WP was unable to record a renewal transaction for arrangement %1$s because %2$s', 'church-tithe-wp' ), $arrangement->id, $transaction_creation_result['code'] );
		return $action_description;
	}

	// This will retrieve the balance_transaction from the passed-in charge, which is what we need in order to get the fee amount.
	$s = new Church_Tithe_WP_Stripe_Get(
		array(
			'url' => 'https://api.stripe.com/v1/charges/' . $webhook_data['data']['object']['charge'],
		)
	);

	// Execute the call to Stripe.
	$charge = $s->call();

	// Now we can finally grab the balance transaction.
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
		'home_currency'        => $home_currency,
		'gateway_fee_hc'       => $gateway_fee_hc,
		'earnings_hc'          => $earnings_hc,
		'charge_id'            => $webhook_data['data']['object']['charge'],
		'statement_descriptor' => $charge['statement_descriptor'],
	);

	$transaction_updated_result = $transaction->update( $transaction_data );

	if ( $transaction_updated_result['success'] ) {

		// Send the email receipt to the user and admin.
		church_tithe_wp_send_receipt_email_to_admin( $transaction );
		$email_sent = church_tithe_wp_send_receipt_email( $transaction );

		// translators: First: The transaction ID which was just recorded. Second. The arrangement/plan ID the transaction belongs to.
		$action_description = sprintf( __( 'Church Tithe WP recorded a renewal transaction (%1$s) for arrangement %2$s', 'church-tithe-wp' ), $transaction->id, $arrangement->id );
	} else {
		// translators: First: The plan/arrangement ID which was unable to have a transaction added. Second. The reason it could not be added.
		$action_description = sprintf( __( 'Church Tithe WP was unable to record a renewal transaction for arrangement %1$s because %2$s', 'church-tithe-wp' ), $arrangement->id, $transaction_creation_result['code'] );
	}

	// Now let's grab the subscription and store its next bill date.
	$s = new Church_Tithe_WP_Stripe_Get(
		array(
			'url' => 'https://api.stripe.com/v1/subscriptions/' . $arrangement->gateway_subscription_id,
		)
	);

	// Execute the call to Stripe.
	$subscription = $s->call();

	// Save the old period's end date before we update the arrangement to the new one.
	$previous_period_end = $arrangement->current_period_end;

	$arrangement->update(
		array(
			// We will also make sure its status is active, as it may not have been prior to this invoice being paid.
			'recurring_status'   => 'active',
			'status_reason'      => '',
			'current_period_end' => gmdate( 'Y-m-d H:i:s', $subscription['current_period_end'] ),
		)
	);

	// Add the current period data to the corresponding transaction.
	$transaction->update(
		array(
			'period_start_date' => $previous_period_end,
			'period_end_date'   => gmdate( 'Y-m-d H:i:s', $subscription['current_period_end'] ),
		)
	);

	return $action_description;

}
