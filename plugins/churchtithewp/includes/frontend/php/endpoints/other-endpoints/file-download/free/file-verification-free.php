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
 * Accept a File Download Password via the URL, and verify it. Once verified, call "church_tithe_wp_deliver_attached_file" to actually deliver it.
 *
 * @access   public
 * @since    1.0.0
 * @return   bool
 */
function church_tithe_wp_verify_free_file_download() {

	if ( ! isset( $_GET['ctwp_file_download'] ) || ! isset( $_GET['ctwp_file_download_value'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}

	$file_download_key   = sanitize_text_field( wp_unslash( $_GET['ctwp_file_download'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$file_download_value = sanitize_text_field( wp_unslash( $_GET['ctwp_file_download_value'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	$result = church_tithe_wp_verify_file_download_transient( $file_download_key, $file_download_value );

	if ( ! $result['success'] ) {

		if ( 'transient_not_found' === $result['error_code'] ) {
			wp_die( esc_textarea( __( 'File download link expired.', 'church-tithe-wp' ) ) );
		}

		if ( 'values_missing_in_transient' === $result['error_code'] ) {
			wp_die( esc_textarea( __( 'Invalid file download.', 'church-tithe-wp' ) ) );
		}

		if ( 'invalid_password' === $result['error_code'] ) {
			wp_die( esc_textarea( __( 'Invalid download link.', 'church-tithe-wp' ) ) );
		}

		if ( 'invalid_form_id_in_transient' === $result['error_code'] ) {
			wp_die( esc_textarea( __( 'Could not locate the file for download at this time.', 'church-tithe-wp' ) ) );
		}
	}

	// Get the unique settings about this form from the Form Object.
	$form_unique_settings = json_decode( $result['form']->json, true );

	$file_download_data = array(
		'user_id'        => $result['transient_data']['user_id'],
		'form_id'        => $result['form']->id,
		'transaction_id' => $result['transient_data']['transaction_id'],
		'attachment_id'  => $form_unique_settings['file_download_attachment_data']['attachment_id'],
		'page_url'       => $result['transient_data']['page_url'],
	);

	// If there is an email in the transient...
	if ( ! empty( $result['transient_data']['email'] ) ) {
		// Check if a WP user exists for the email.
		$user    = get_user_by( 'email', $result['transient_data']['email'] );
		$user_id = $user->ID;

		if ( ! $user ) {
			// If one doesn't exist, we can safely generate one now, because the transient was verified as coming from their email.
			// This allows us to store the email address in WP core (GDPR reasons).
			$user_id = wp_create_user( $result['transient_data']['email'], wp_generate_password(), $result['transient_data']['email'] );
		}

		// If there isn't an email in the transient...
	} else {
		$user_id = 0;
	}

	// Set the user ID to use for the Download Log.
	$file_download_data['user_id'] = $user_id;

	church_tithe_wp_deliver_attached_file( $file_download_data );
	die();

}
add_action( 'init', 'church_tithe_wp_verify_free_file_download' );
