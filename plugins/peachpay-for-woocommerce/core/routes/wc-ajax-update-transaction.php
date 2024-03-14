<?php
/**
 * Handle WC-Ajax request for updating a PeachPay transaction.
 *
 * @package PeachPay
 */

defined( 'ABSPATH' ) || exit;

/**
 * Ajax hook function for updating a PeachPay transaction.
 */
function pp_wc_ajax_update_transaction() {
	$options = array();

	$session_id = PeachPay_Payment::get_session();

    // PHPCS:disable WordPress.Security.NonceVerification.Missing
	$transaction_id = isset( $_POST['transaction_id'] ) ? sanitize_text_field( wp_unslash( $_POST['transaction_id'] ) ) : null;
	$order_status   = isset( $_POST['order_status'] ) ? sanitize_text_field( wp_unslash( $_POST['order_status'] ) ) : null;
	$payment_status = isset( $_POST['payment_status'] ) ? sanitize_text_field( wp_unslash( $_POST['payment_status'] ) ) : null;
	$note           = isset( $_POST['note'] ) ? sanitize_text_field( wp_unslash( $_POST['note'] ) ) : null;
	// PHPCS:enable

	if ( ! $transaction_id ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => '"transaction_id" is a required field.',
			)
		);

		return;
	}

	if ( $order_status ) {
		$options['order_status'] = $order_status;
	}

	if ( $payment_status ) {
		$options['payment_status'] = $payment_status;
	}

	if ( $note ) {
		$options['note'] = $note;
	}

	wp_send_json( PeachPay_Payment::update_transaction( $transaction_id, $session_id, $options ) );
}
