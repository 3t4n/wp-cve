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
 * Save note with tithe API Endpoint It is separated out like this so it can be unit tested.
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_save_note_with_tithe_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_save_note_with_tithe'] ) ) {
		return false;
	}

	$endpoint_result = church_tithe_wp_save_note_with_tithe_handler();

	echo wp_json_encode( $endpoint_result );
	die();
}
add_action( 'init', 'church_tithe_wp_save_note_with_tithe_endpoint' );

/**
 * Save note with tithe API Endpoint
 *
 * @access   public
 * @since    1.0.0
 * @return   array
 */
function church_tithe_wp_save_note_with_tithe_handler() {

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

		// If this payment session does not validate, this is not a valid attempt at updating the note.
		if ( ! church_tithe_wp_payment_session_valid( $user_id, $transaction_id, $session_id ) ) {
			return array(
				'success'    => false,
				'error_code' => 'invalid_session',
				'details'    => 'Invalid session',
			);
		}
	} else {
		// Verify the nonce.
		if ( ! isset( $_POST['church_tithe_wp_note_with_tithe_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_note_with_tithe_nonce'] ) ), 'church_tithe_wp_note_with_tithe' ) ) {
			return array(
				'success'    => false,
				'error_code' => 'nonce_failed',
				'details'    => 'Nonce failed.',
			);
		}
	}

	// Check if values were not there that need to be.
	if ( ! is_array( $_POST ) || ! isset( $_POST['church_tithe_wp_transaction_id'] ) || ! isset( $_POST['church_tithe_wp_note_with_tithe'] ) ) {
		return array(
			'success'    => false,
			'error_code' => 'missing_values',
			'details'    => 'Note with tithe request was incorrect.',
		);
	}

	// Get the transaction object which we are adding the note to.
	$transaction = new Church_Tithe_WP_Transaction( absint( $_POST['church_tithe_wp_transaction_id'] ) );

	if ( 0 === $transaction->id ) {
		return array(
			'success'    => false,
			'error_code' => 'invalid_transaction_id_given',
			'details'    => 'No Transaction found with that ID',
		);
	}

	// If the person is logged in (if logged out, they are already confirmed valid using the payment session above).
	if ( is_user_logged_in() ) {

		// Double check that the user logged in is the same user attached to the transaction being updated.
		if ( intval( $transaction->user_id ) !== intval( get_current_user_id() ) ) {
			return array(
				'success'    => false,
				'error_code' => 'invalid_user',
				'details'    => 'Invalid User',
			);
		}
	}

	// Add the note to that transaction.
	$transaction_data = $transaction->update(
		array(
			'note_with_tithe' => sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_note_with_tithe'] ) ),
		)
	);

	return array(
		'success'          => $transaction_data['success'],
		'transaction_id'   => $transaction_data['transaction']->id,
		'transaction_data' => $transaction_data,
	);

}
