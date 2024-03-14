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
 * Endpoint which handles sending a receipt to the user. It is separated out like this so it can be unit tested.
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_email_transaction_receipt_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_email_transaction_receipt'] ) ) {
		return false;
	}

	$endpoint_result = church_tithe_wp_email_transaction_receipt_handler();

	echo wp_json_encode( $endpoint_result );
	die();
}
add_action( 'init', 'church_tithe_wp_email_transaction_receipt_endpoint' );

/**
 * Handler function for the send email receipt endpoint. It is separated out like this so it can be unit tested.
 *
 * @access   public
 * @since    1.0.0
 * @return   array
 */
function church_tithe_wp_email_transaction_receipt_handler() {

	// If the person is not logged in, check their payment session.
	if ( ! is_user_logged_in() ) {

		if (
			! isset( $_POST['church_tithe_wp_user_id'] ) ||
			! isset( $_POST['church_tithe_wp_transaction_id'] ) ||
			! isset( $_POST['church_tithe_wp_session_id'] )
		) {
			return array(
				'success'    => false,
				'error_code' => 'invalid_session',
				'details'    => 'Invalid session',
			);
		}

		// Check if the payment session is valid. This proves the person saving the note with the tithe is the person who did the payment, even though they might be logged out.
		$user_id        = absint( $_POST['church_tithe_wp_user_id'] );
		$transaction_id = absint( $_POST['church_tithe_wp_transaction_id'] );
		$session_id     = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_session_id'] ) );

		// If this payment session does not validate, this is not a valid attempt at send a transaction receipt.
		if ( ! church_tithe_wp_payment_session_valid( $user_id, $transaction_id, $session_id ) ) {
			return array(
				'success'    => false,
				'error_code' => 'invalid_session',
				'details'    => 'Invalid session',
			);
		}
		// If they are logged in, check the nonce instead.
	} else {
		if ( ! isset( $_POST['church_tithe_wp_email_transaction_receipt_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_email_transaction_receipt_nonce'] ) ), 'church_tithe_wp_email_transaction_receipt_nonce' ) ) {
			return array(
				'success'    => false,
				'error_code' => 'nonce_failed',
				'details'    => 'Nonce failed.',
			);
		}
	}

	// Check whether we should send the email regardless of whether this is a duplicate send of the receipt.
	if ( ! isset( $_POST['church_tithe_wp_send_regardless_of_initial_emails_sent'] ) ) {
		return array(
			'success'    => false,
			'error_code' => 'required_value_missing',
			'details'    => 'A required value was missing from the request.',
		);
	}

	$send_regardless_of_initial_emails_sent = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_send_regardless_of_initial_emails_sent'] ) );
	$send_regardless_of_initial_emails_sent = 'true' === $send_regardless_of_initial_emails_sent ? true : false;

	if ( ! isset( $_POST['church_tithe_wp_transaction_id'] ) || empty( $_POST['church_tithe_wp_transaction_id'] ) ) {

		return array(
			'success'    => false,
			'error_code' => 'no_transaction_id_given',
			'details'    => 'No Transaction ID given',
		);

	}

	// Get the object for the transaction ID in question.
	$transaction = new Church_Tithe_WP_Transaction( sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_transaction_id'] ) ) );

	if ( 0 === $transaction->id ) {
		return array(
			'success'    => false,
			'error_code' => 'invalid_transaction_id_given',
			'details'    => 'No Transaction found with that ID',
		);
	}

	// If the person is logged in (if logged out, they are already confirmed valid using the payment session above).
	if ( is_user_logged_in() ) {

		// Double check that the user logged in is the same user attached to the transaction being emailed.
		if ( intval( $transaction->user_id ) !== intval( get_current_user_id() ) ) {
			return array(
				'success'    => false,
				'error_code' => 'invalid_user',
				'details'    => 'Invalid User',
			);
		}
	}

	// It's possible that the webhook beat this endpoint to sending the email. So we need to check this to avoid a duplicate send.
	if ( ! $transaction->initial_emails_sent || $send_regardless_of_initial_emails_sent ) {
		// Send the email receipt to the user.
		$email_sent = church_tithe_wp_send_receipt_email( $transaction );
	} else {
		// Technically, if initial_emails_sent is true, the emails were sent, just not in this endpoint call.
		return array(
			'success' => true,
			'message' => 'email_not_sent_because_duplicate',
		);
	}

	// Check if we should also notify the admin about this transaction.
	if ( isset( $_POST['church_tithe_wp_notify_admin_too'] ) ) {
		$notify_admin_too = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_notify_admin_too'] ) );

		// Check the value of initial_emails_sent so we don't double notify the admin about a transaction.
		if ( $notify_admin_too && ! $transaction->initial_emails_sent ) {
			$email_sent = church_tithe_wp_send_receipt_email_to_admin( $transaction );
		}
	}

	if ( ! $email_sent ) {
		return array(
			'success'    => false,
			'error_code' => 'unable_to_send_email',
		);
	}

	// Update the value of initial_emails_sent to be true so we don't double send the notifications when the webhook comes in.
	$transaction->update(
		array(
			'initial_emails_sent' => 1,
		)
	);

	return array(
		'success' => true,
	);

}
