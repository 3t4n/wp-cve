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
 * Endpoint which emails the user a login link/code. It is separated out like this so it can be unit tested.
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_email_login_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_email_login'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return false;
	}

	$endpoint_result = church_tithe_wp_email_login_handler();

	echo wp_json_encode( $endpoint_result );
	die();
}
add_action( 'init', 'church_tithe_wp_email_login_endpoint' );

/**
 * Attempt to log the user in
 *
 * @access   public
 * @since    1.0.0
 * @return   array
 */
function church_tithe_wp_email_login_handler() {

	// Verify the nonce.
	if ( ! isset( $_POST['church_tithe_wp_email_login_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_email_login_nonce'] ) ), 'church_tithe_wp_email_login_nonce' ) ) {
		return array(
			'success'    => false,
			'error_code' => 'nonce_failed',
			'details'    => 'Nonce failed.',
		);
	}

	// If required values do not exist in this call.
	if ( ! is_array( $_POST ) || ! isset( $_POST['church_tithe_wp_email'] ) ) {
		return array(
			'success'    => false,
			'error_code' => 'invalid_values',
			'details'    => 'Values not valid.',
		);
	}

	$email = sanitize_email( wp_unslash( $_POST['church_tithe_wp_email'] ) );

	// Check if a user exists with that email.
	$user = get_user_by( 'email', $email );

	// If that user does not exist.
	if ( ! $user ) {
		return array(
			'success'    => false,
			'error_code' => 'no_user_found',
			'details'    => __( 'No user was found with that email address.', 'church-tithe-wp' ),
		);
	}

	// Check how many times this user has requested a unsuccessfully-used login token in the past 24 hours.
	// These get cleared upon user login in the church_tithe_wp_attempt_user_login_handler function.
	$unsuccessful_login_codes = get_transient( 'church_tithe_wp_login_code_requests_' . $user->ID );
	$unsuccessful_login_codes = ! empty( $unsuccessful_login_codes ) ? $unsuccessful_login_codes : 0;

	// If they have had over 20 unsuccessful login codes in the last 24 hours, don't allow a new email/toke/code to be created.
	if ( $unsuccessful_login_codes > 20 ) {
		return array(
			'success'    => false,
			'error_code' => 'too_many_attempts',
			'details'    => __( 'Too many failed login attempts. Try again tomorrow.', 'church-tithe-wp' ),
		);
	}

	// Increment the number of login codes unsuccessfully used to log in in the last 24 hours.
	$unsuccessful_login_codes++;

	// Set the number of login attempts used for this code to 0.
	update_user_meta( $user->ID, 'church_tithe_wp_login_attempts', 0 );

	set_transient( 'church_tithe_wp_login_code_requests_' . $user->ID, $unsuccessful_login_codes, DAY_IN_SECONDS );

	// Generate a 6 digit numeric code which lasts for 240 seconds.
	$one_time_email_code = absint( wp_rand( 100000, 999999 ) );
	set_transient( 'church_tithe_wp_login_code_' . $user->ID, wp_hash_password( $one_time_email_code ), 240 );

	// Set up the subject line.
	// translators: The name of this website, and the code the user can type in to log in.
	$subject = sprintf( __( 'Your login code for %1$s', 'church-tithe-wp' ), get_bloginfo( 'name' ) );

	$line_1 = '<p>' . __( 'A one-time login code was requested for for the following account:', 'church-tithe-wp' ) . '</p>';
	// translators: The name of the current website.
	$line_2 = '<p>' . sprintf( __( 'Site Name: %s', 'church-tithe-wp' ), get_bloginfo( 'name' ) ) . '</p>';
	$line_3 = '<p>' . __( 'If this was a mistake, just ignore this email and nothing will happen.', 'church-tithe-wp' ) . '</p>';
	$line_4 = '<p>' . __( 'Find your login code after the dashes:', 'church-tithe-wp' ) . '</p>';
	$line_5 = '<p>|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|</p>';
	$line_6 = '<p>' . $one_time_email_code . '</p>';

	$message = $line_1 . $line_2 . $line_3 . $line_4 . $line_5 . $line_6;

	$email_headers = array(
		'Content-Type: text/html; charset=UTF-8',
	);

	// Attempt to send the email using wp_mail.
	$send_result = wp_mail( $email, $subject, $message, $email_headers );

	// If the email did not send.
	if ( ! $send_result ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'wp_mail_false',
				'details'    => 'The email could not be sent.',
			)
		);

		// Set Church Tithe WP to know that emails are not working.
		church_tithe_wp_unconfirm_wp_mail_health_check();

		die();
	}

	return array(
		'success' => true,
	);

}
