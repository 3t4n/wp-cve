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
 * Handle the Stripe Webhook for charge.succeeded
 *
 * @since    1.0.0
 * @param    array $webhook_data This is the data from Stripe for this webhook.
 * @return   string The action taken by this webhook handler.
 */
function church_tithe_wp_stripe_webhook_charge_succeeded( $webhook_data ) {

	// Get the id of the charge.
	$charge_id              = $webhook_data['data']['object']['id'];
	$balance_transaction_id = $webhook_data['data']['object']['balance_transaction'];

	// This webhook is only used to update a transaction that already exists.
	// The creation of a transaction happens with a PaymentIntent or an invoice.payment_succeeded, and that's it.

	// Check if a transaction with this charge_id exists in the transactions table.
	$possible_transaction = new Church_Tithe_WP_Transaction( $charge_id, 'charge_id' );

	// If there's no transaction matching this charge ID in our Transactions table, don't do anything. It didn't originate from church_tithe_wp.
	if ( 0 === $possible_transaction->id ) {
		return '';
	}

	// If there is a Transaction matching this charge ID in our Transactions table, update it.
	// This will retrieve the balance_transaction from the passed-in charge, which is what we need in order to get the fee amount.
	$s = new Church_Tithe_WP_Stripe_Get(
		array(
			'url' => 'https://api.stripe.com/v1/balance/history/' . $balance_transaction_id,
		)
	);

	// Execute the call to Stripe.
	$balance_transaction = $s->call();

	$home_currency  = $balance_transaction['currency'];
	$gateway_fee_hc = $balance_transaction['fee'];
	$earnings_hc    = $balance_transaction['net'];

	// Update the transaction ID in question.
	$transaction_data = array(
		'home_currency'  => $home_currency,
		'gateway_fee_hc' => $gateway_fee_hc,
		'earnings_hc'    => $earnings_hc,
		'charge_id'      => $charge_id,
	);

	$transaction_updated_result = $possible_transaction->update( $transaction_data );

	if ( $transaction_updated_result ) {
		// translators: The id of the transaction that was updated.
		$action_description = sprintf( __( 'Church Tithe WP updated transaction %s', 'church-tithe-wp' ), $possible_transaction->id );
	} else {
		// translators: The id of the transaction that was unable to be updated.
		$action_description = sprintf( __( 'Church Tithe WP was not able to update transaction %s', 'church-tithe-wp' ), $possible_transaction->id );
	}

	return $action_description;

}
