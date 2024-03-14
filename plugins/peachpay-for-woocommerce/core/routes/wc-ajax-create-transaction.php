<?php
/**
 * Handle WC-Ajax request for creating a PeachPay transaction
 *
 * @package PeachPay
 */

defined( 'ABSPATH' ) || exit;

/**
 * Ajax hook function for creating a PeachPay transaction.
 */
function pp_wc_ajax_create_transaction() {
	$session_id = PeachPay_Payment::get_session();

	// PHPCS:disable WordPress.Security.NonceVerification.Missing
	$gateway_id        = isset( $_POST['gateway_id'] ) ? sanitize_text_field( wp_unslash( $_POST['gateway_id'] ) ) : null;
	$checkout_location = isset( $_POST['checkout_location'] ) ? sanitize_text_field( wp_unslash( $_POST['checkout_location'] ) ) : null;
	// PHPCS:enable

	if ( ! $gateway_id ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => '"gateway_id" is a required field.',
			)
		);

		return;
	}

	if ( ! $checkout_location ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => '"checkout_location" is a required field.',
			)
		);

		return;
	}

	wp_send_json( PeachPay_Payment::create_transaction( $session_id, $gateway_id, $checkout_location ) );
}
