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
 * Endpoint which returns a one-time download link for a free download.
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_get_free_file_download_url_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_get_free_file_download_url'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return false;
	}

	$endpoint_result = church_tithe_wp_get_free_file_download_url_handler();

	echo wp_json_encode( $endpoint_result );
	die();
}
add_action( 'init', 'church_tithe_wp_get_free_file_download_url_endpoint' );

/**
 * Generate a temporary download URL. This is stored as a transient identified by a random password, containing an attachment's details.
 * That transient will then be called when the file is downloaded by passing the password in the URL.
 *
 * @access   public
 * @since    1.0.0
 * @return   array
 */
function church_tithe_wp_get_free_file_download_url_handler() {

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
		! is_array( $_POST ) ||
		! isset( $_POST['church_tithe_wp_email'] ) ||
		! isset( $_POST['church_tithe_wp_page_url'] ) ||
		! isset( $_POST['church_tithe_wp_form_id'] )
	) {
		return array(
			'success'    => false,
			'error_code' => 'invalid_values',
			'details'    => 'Values not valid.',
		);
	}

	// Sanitize the passed-in variables.
	$email    = sanitize_email( wp_unslash( $_POST['church_tithe_wp_email'] ) );
	$page_url = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_page_url'] ) );

	// If this was not a valid page url...
	if ( ! filter_var( $page_url, FILTER_VALIDATE_URL ) ) {
		return array(
			'success'    => false,
			'error_code' => 'invalid_page_url',
			'details'    => 'Invalid page url provided.',
		);
	}

	$form_id        = absint( $_POST['church_tithe_wp_form_id'] );

	// Check if the user is logged in.
	$user = wp_get_current_user();

		// If the user is logged-in, the passed-in email is not used.
	if ( ! empty( $user->user_email ) ) {
		$email = $user->user_email;
	}

	// Get the form row from the database.
	$form = new Church_Tithe_WP_Form( $form_id );

	// If no form was found...
	if ( ! $form->id ) {
		return array(
			'success'    => false,
			'error_code' => 'invalid_form_id',
			'details'    => 'Form ID was not valid.',
		);
	}

	// Get the unique settings about this form from the database.
	$form_unique_settings = json_decode( $form->json, true );

	// Check if this form requires a transaction. If so, this is the wrong endpoint.
	if ( $form_unique_settings['file_download_attachment_data']['transaction_required'] ) {
		return array(
			'success'    => false,
			'error_code' => 'transaction_required',
			'details'    => 'A transaction is required to download this file.',
		);
	}

	// Get the file attached to this form as the deliverable.
	$attachment_id   = $form_unique_settings['file_download_attachment_data']['attachment_id'];
	$attachment_file = get_attached_file( $form_unique_settings['file_download_attachment_data']['attachment_id'] );

	if ( ! $attachment_file ) {
		return array(
			'success'    => false,
			'error_code' => 'attachment_not_found',
		);
	}

	// If this form requires an email address, the file is only available in their email inbox with 1 exception: they are logged into WP right now.
	if ( $form_unique_settings['file_download_attachment_data']['email_required'] ) {
		// If the user is logged out...
		if ( ! $user->ID ) {

			// If this was not a valid email...
			if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				return array(
					'success'    => false,
					'error_code' => 'invalid_email',
					'details'    => 'Invalid email provided.',
				);

				// If an email was passed in, send the secret one-time file URL to their email.
			} else {

				$transient_creation_result = church_tithe_wp_create_file_download_transient( $form_id, null, $page_url, $email );

				if ( ! $transient_creation_result['success'] ) {
					return array(
						'success'    => false,
						'error_code' => $transient_creation_result['error_code'],
						'details'    => $transient_creation_result['error_code'],
					);
				}

				// Send the file download to the user's email with the 1-time token.
				// When clicked, that email will:
				// 0. Validate the token. If it passes:
				// 1. Generate their user account with the subscriber role (because TJWP does not store email addresses).
				// 2. Download the file.
				$email_sent = church_tithe_wp_send_free_file_download_email(
					$email,
					$transient_creation_result['file_download_url'],
					$form_unique_settings['file_download_attachment_data']['instructions_title'],
					$form_unique_settings['file_download_attachment_data']['instructions_description']
				);

				if ( ! $email_sent ) {
					return array(
						'success'    => false,
						'error_code' => 'email_not_sent',
						'details'    => $email_sent,
					);
				}

				// Tell the user to check their email.
				return array(
					'success'                  => true,
					'success_code'             => 'check_your_email',
					'details'                  => __( 'Check your email to download the file', 'church-tithe-wp' ),
					'instructions_title'       => $form_unique_settings['file_download_attachment_data']['instructions_title'],
					'instructions_description' => $form_unique_settings['file_download_attachment_data']['instructions_description'],
				);

			}
		}
	}

	$transient_creation_result = church_tithe_wp_create_file_download_transient( $form_id, null, $page_url, $email );

	if ( ! $transient_creation_result['success'] ) {
		return array(
			'success'    => false,
			'error_code' => $transient_creation_result['error_code'],
			'details'    => $transient_creation_result['error_code'],
		);
	}

	$filetype  = wp_check_filetype( $attachment_file );
	$file_name = get_the_title( $attachment_id ) . '.' . $filetype['ext'];
	$mime_type = get_post_mime_type( $attachment_id );

	return array(
		'success'                  => true,
		'success_code'             => 'download_file',
		'url'                      => $transient_creation_result['file_download_url'],
		'file_title'               => $file_name,
		'mime_type'                => $mime_type,
		'instructions_title'       => $form_unique_settings['file_download_attachment_data']['instructions_title'],
		'instructions_description' => $form_unique_settings['file_download_attachment_data']['instructions_description'],
	);

}
