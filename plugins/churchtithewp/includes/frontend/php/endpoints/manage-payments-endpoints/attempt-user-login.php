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
 * Endpoint which attempts to log the user in. It is separated out like this so it can be unit tested.
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_attempt_user_login_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_attempt_user_login'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return false;
	}

	$endpoint_result = church_tithe_wp_attempt_user_login_handler();

	echo wp_json_encode( $endpoint_result );
	die();
}
add_action( 'init', 'church_tithe_wp_attempt_user_login_endpoint' );

/**
 * Attempt to log the user in
 *
 * @access   public
 * @since    1.0.0
 * @return   array
 */
function church_tithe_wp_attempt_user_login_handler() {

	// Verify the nonce.
	if ( ! isset( $_POST['church_tithe_wp_login_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_login_nonce'] ) ), 'church_tithe_wp_login_nonce' ) ) {
		return array(
			'success'    => false,
			'error_code' => 'nonce_failed',
			'details'    => __( 'Nonce failed.', 'church-tithe-wp' ),
		);
	}

	// If required values do not exist in this call.
	if ( ! is_array( $_POST ) || ! isset( $_POST['church_tithe_wp_email'] ) || ! isset( $_POST['church_tithe_wp_login_code'] ) ) {
		return array(
			'success'    => false,
			'error_code' => 'invalid_values',
			'details'    => __( 'Values not valid.', 'church-tithe-wp' ),
		);
	}

	$email              = sanitize_email( wp_unslash( $_POST['church_tithe_wp_email'] ) );
	$entered_login_code = absint( wp_unslash( $_POST['church_tithe_wp_login_code'] ) );

	// Get the user in question.
	$user = get_user_by( 'email', $email );

	// If that user does not exist.
	if ( ! $user ) {
		return array(
			'success'    => false,
			'error_code' => 'no_user_found',
			'details'    => __( 'No user was found with that email address.', 'church-tithe-wp' ),
		);
	}

	$saved_login_code = get_transient( 'church_tithe_wp_login_code_' . $user->ID );

	// If the saved login code has expired.
	if ( ! $saved_login_code ) {
		return array(
			'success'    => false,
			'error_code' => 'no_user_found',
			'details'    => __( 'No user was found with that email address.', 'church-tithe-wp' ),
		);
	}

	$login_attempts = get_user_meta( $user->ID, 'church_tithe_wp_login_attempts', true );
	$login_attempts = ! empty( $login_attempts ) ? $login_attempts : 0;

	// Get the number of login attempts this user has had since their login token was created (which only lasts 240 seconds).
	// You only get 10 tries total over 240 seconds.
	// After you use up all 10, you can request another email code. But you can only request 20 failed email codes in a row per day.
	// If you successufully log in with any email code, That counter is reset to 0.
	if ( $login_attempts >= 10 ) {

		// You only get 10 tries at typing in the token. Otherwise, you're probably a bot.
		return array(
			'success'    => false,
			'error_code' => 'too_many_failed_attempts',
			'details'    => __( 'Too many failed attempts.', 'church-tithe-wp' ),
		);
	}

	// Increment the number of login attempts.
	$login_attempts++;

	// Increment the number of login attempts for this user.
	update_user_meta( $user->ID, 'church_tithe_wp_login_attempts', $login_attempts );

	if ( ! class_exists( 'PasswordHash' ) ) {
		require_once ABSPATH . WPINC . '/class-phpass.php';
	}

	$wp_hasher = new PasswordHash( 8, true );

	// Check if the code entered matches the current code for this user.
	if ( ! $wp_hasher->CheckPassword( $entered_login_code, $saved_login_code ) ) {
		return array(
			'success'    => false,
			'error_code' => 'invalid_code',
			'details'    => __( 'Invalid code. Please check and try again.', 'church-tithe-wp' ),
		);
	}

	// If we are here, the codes match! Now we can log the user in, and delete the login code. We can also reset their login attempts.

	// Reset the login attempts.
	update_user_meta( $user->ID, 'church_tithe_wp_login_attempts', 0 );

	// Reset the login code.
	delete_transient( 'church_tithe_wp_login_code_' . $user->ID, 'church_tithe_wp_login_code', 0 );

	// Reset the number of failed login code requests in the last 24 hours.
	delete_transient( 'church_tithe_wp_login_code_requests_' . $user->ID );

	// Now we will actually set the current user to be that user, since the signon was successful.
	wp_set_current_user( $user->ID );
	wp_set_auth_cookie( $user->ID );

	return array(
		'success'        => true,
		'user_logged_in' => $user->ID ? true : false,
	);

}
