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
 * Accept a File Download Password via the URL, verify it, and deliver it. Once verified, call "church_tithe_wp_deliver_attached_file" to actually deliver it.
 * To hit this endpoint formulate a URL like this: get_bloginfo( 'url' ) . '/?ctwp_file_download&ctwp_transaction_id=123&ctwp_session_id=12345&nonce=12345';
 *
 * @access   public
 * @since    1.0.0
 * @return   array
 */
function church_tithe_wp_verify_transaction_file_download() {

	if (
		! isset( $_GET['ctwp_file_download'] ) ||
		! isset( $_GET['ctwp_transaction_id'] ) ||
		! isset( $_GET['nonce'] )
	) {
		return;
	}

	// Verify the nonce.
	if (
		! isset( $_GET['nonce'] ) ||
		! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'church_tithe_wp_file_download_nonce' )
	) {
		wp_die( esc_textarea( __( 'Invalid nonce.', 'church-tithe-wp' ) ) );
	}

	// Sanitize the transaction ID from the URL.
	$transaction_id = absint( $_GET['ctwp_transaction_id'] );

	// If the transaction ID isn't a number...
	if ( ! $transaction_id ) {
		wp_die( esc_textarea( __( 'Invalid transaction.', 'church-tithe-wp' ) ) );
	}

	// Make sure the transaction exists.
	$transaction = new Church_Tithe_WP_Transaction( $transaction_id );

	if ( ! $transaction->id ) {
		wp_die( esc_textarea( __( 'Transaction not found.', 'church-tithe-wp' ) ) );
	}

	// If you are not logged in, you cannot download the file unless you have a valid purchase session.
	if ( ! is_user_logged_in() ) {

		if (
			! isset( $_GET['ctwp_session_id'] ) ||
			! isset( $_GET['ctwp_user_id'] )
		) {
			wp_die( esc_textarea( __( 'Invalid download attempt.', 'church-tithe-wp' ) ) );
		}

		// Check if the payment session is valid. This proves the person downloading the file is the person who did the payment, even though they might be logged out.
		$session_id = sanitize_text_field( wp_unslash( $_GET['ctwp_session_id'] ) );
		$user_id    = absint( $_GET['ctwp_user_id'] );

		// If this payment session does not validate, this is not a valid attempt at downloading the file.
		if ( ! church_tithe_wp_payment_session_valid( $user_id, $transaction->id, $session_id ) ) {
			wp_die( esc_textarea( __( 'Invalid session. Try logging in.', 'church-tithe-wp' ) ) );
		}

		// If the user IS logged in...
	} else {

		$user_id = get_current_user_id();

		// Make sure the current user matches the user on the transaction.
		if ( absint( $user_id ) !== absint( $transaction->user_id ) ) {
			wp_die( esc_textarea( __( 'Invalid user.', 'church-tithe-wp' ) ) );
		}
	}

	// Transaction refunded? No file for you!
	if ( ! empty( $transaction->refund_id ) ) {
		wp_die( esc_textarea( __( 'The transaction associated with the file download has been refunded.', 'church-tithe-wp' ) ) );
	}

	// No charge_id? No file for you! (This means local sites won't get access to files here, because webhooks won't reach local).
	if ( empty( $transaction->charge_id ) ) {
		wp_die( esc_textarea( __( 'The transaction associated with the file download has not been completed yet. Refresh this page in a few seconds. If the problem persists, please contact us.', 'church-tithe-wp' ) ) );
	}

	// This is a valid download attempt. Let's get the download details from the purchased form.
	$form = new Church_Tithe_WP_Form( $transaction->form_id );

	// If there's no form for some reason...
	if ( ! $form->id || ! $form->json ) {
		wp_die( esc_textarea( __( 'Something went wrong. Please contact the site administrator.', 'church-tithe-wp' ) ) );
	}

	// Get the unique settings about this form from the database.
	$form_unique_settings = json_decode( $form->json, true );

	$file_download_data = array(
		'user_id'        => $user_id,
		'form_id'        => $form->id,
		'transaction_id' => $transaction->id,
		'attachment_id'  => $form_unique_settings['file_download_attachment_data']['attachment_id'],
		'page_url'       => $transaction->page_url,
	);

	church_tithe_wp_deliver_attached_file( $file_download_data );
	die();

}
add_action( 'init', 'church_tithe_wp_verify_transaction_file_download' );
