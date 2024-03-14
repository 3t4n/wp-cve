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
 * Endpoint which returns a download link for a transaction's file download.
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_get_transaction_file_download_url_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_get_transaction_file_download_url'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return false;
	}

	$endpoint_result = church_tithe_wp_get_transaction_file_download_url_handler();

	echo wp_json_encode( $endpoint_result );
	die();
}
add_action( 'init', 'church_tithe_wp_get_transaction_file_download_url_endpoint' );

/**
 * Formulate a transaction file download URL.
 * get_bloginfo( 'url' ) . '/?ctwp_file_download&ctwp_transaction_id=123&ctwp_session_id=12345&nonce=12345'
 * Note that the user/transaction validation is done on the verification endpoint during file download.
 * This endpoint simply sets up a URL which still gets validated.
 *
 * @access   public
 * @since    1.0.0
 * @return   array
 */
function church_tithe_wp_get_transaction_file_download_url_handler() {

	// Verify the nonce.
	if (
		! isset( $_POST['church_tithe_wp_file_download_nonce'] ) ||
		! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_file_download_nonce'] ) ), 'church_tithe_wp_file_download_nonce' )
	) {
		return array(
			'success'    => false,
			'error_code' => 'nonce_failed',
			'details'    => 'Nonce failed.',
		);
	}

	// If required values do not exist in this call.
	if (
		! isset( $_POST['church_tithe_wp_transaction_id'] ) ||
		! isset( $_POST['church_tithe_wp_session_id'] ) ||
		! isset( $_POST['church_tithe_wp_user_id'] )
	) {
		return array(
			'success'    => false,
			'error_code' => 'invalid_values',
			'details'    => 'Values not valid.',
		);
	}

	$transaction_id = absint( $_POST['church_tithe_wp_transaction_id'] );
	$ctwp_session   = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_session_id'] ) );
	$user_id        = absint( $_POST['church_tithe_wp_user_id'] );
	$nonce          = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_file_download_nonce'] ) );

	// Override the user ID if they are logged in.
	if ( is_user_logged_in() ) {
		$user    = wp_get_current_user();
		$user_id = $user->ID;
	}

	// If the transaction ID isn't a number...
	if ( ! $transaction_id ) {
		wp_die( esc_textarea( __( 'Invalid transaction.', 'church-tithe-wp' ) ) );
	}

	// Make sure the transaction exists.
	$transaction = new Church_Tithe_WP_Transaction( $transaction_id );

	if ( ! $transaction->id ) {
		return array(
			'success'    => false,
			'error_code' => 'transaction_not_found',
			'details'    => 'Transaction was not found.',
		);
	}

	// Let's get the download details from the possibly-purchased form.
	$form = new Church_Tithe_WP_Form( $transaction->form_id );

	// If there's no form for some reason...
	if ( ! $form->id || ! $form->json ) {
		return array(
			'success'    => false,
			'error_code' => 'form_not_found',
			'details'    => 'Form was not found.',
		);
	}

	// Get the unique settings about this form from the database.
	$form_unique_settings = json_decode( $form->json, true );

	if ( ! isset( $ctwp_session ) ) {
		$ctwp_session = 0;
	}

	if (
		isset( $form_unique_settings['file_download_attachment_data'] ) &&
		isset( $form_unique_settings['file_download_attachment_data']['instructions_title'] )
	) {
		$instructions_title = $form_unique_settings['file_download_attachment_data']['instructions_title'];
	} else {
		$instructions_title = '';
	}

	if (
		isset( $form_unique_settings['file_download_attachment_data'] ) &&
		isset( $form_unique_settings['file_download_attachment_data']['instructions_description'] )
	) {
		$instructions_description = $form_unique_settings['file_download_attachment_data']['instructions_description'];
	} else {
		$instructions_description = '';
	}

	// Get the file attached to this form as the deliverable.
	$attachment_id   = $form_unique_settings['file_download_attachment_data']['attachment_id'];
	$attachment_file = get_attached_file( $form_unique_settings['file_download_attachment_data']['attachment_id'] );

	$filetype  = wp_check_filetype( $attachment_file );
	$file_name = get_the_title( $attachment_id ) . '.' . $filetype['ext'];
	$mime_type = get_post_mime_type( $attachment_id );

	return array(
		'success'                  => true,
		'success_code'             => 'download_file',
		'url'                      => get_bloginfo( 'url' ) . '/?ctwp_file_download&ctwp_transaction_id=' . $transaction_id . '&ctwp_session_id=' . $ctwp_session . '&ctwp_user_id=' . $user_id . '&nonce=' . $nonce,
		'file_title'               => $file_name,
		'mime_type'                => $mime_type,
		'instructions_title'       => $instructions_title,
		'instructions_description' => $instructions_description,
	);

}
