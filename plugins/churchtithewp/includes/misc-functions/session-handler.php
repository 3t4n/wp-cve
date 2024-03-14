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
 * This function checks whether a payment session exists.
 * Because the session is only created when the PaymentIntent is successful, this guarantees that the person
 * is the same person who made the payment. This does NOT confirm that a person actually has access to the email account
 * given, nor that they are actually the user in question. So remember that based on what you give away if the session verifies.
 *
 * @access      public
 * @since       1.0.0.
 * @param       string $user_id The ID of the user to whom this session belongs.
 * @param       string $transaction_id The ID of the transaction for which this session belongs.
 * @param       string $session_id The unique ID representing a payment session.
 * @return      bool
 */
function church_tithe_wp_payment_session_valid( $user_id, $transaction_id, $session_id ) {

	if ( empty( $user_id ) ) {
		return false;
	}

	if ( empty( $transaction_id ) ) {
		return false;
	}

	if ( empty( $session_id ) ) {
		return false;
	}

	if ( ! class_exists( 'PasswordHash' ) ) {
		require_once ABSPATH . WPINC . '/class-phpass.php';
	}

	$wp_hasher = new PasswordHash( 8, true );

	// Check if this session exists.
	$saved_session = get_transient( 'ctwp_payment_session_' . $user_id . '_' . $transaction_id );

	// Check if the code entered matches the current code for this user.
	if ( ! $wp_hasher->CheckPassword( $session_id, $saved_session ) ) {
		return false;
	}

	// If this user session does exist, return true.
	return true;
}

/**
 * This function creates a brand new visitor session which lasts for 1 hour.
 *
 * @access      public
 * @since       1.0.0.
 * @param       int $user_id The ID of the user to whom this session will belong.
 * @param       int $transaction_id The ID of the transaction for which this sessions will belong.
 * @return      string
 */
function church_tithe_wp_create_payment_session( $user_id, $transaction_id ) {

	$user_id        = absint( $user_id );
	$transaction_id = absint( $transaction_id );

	if ( empty( $user_id ) ) {
		return false;
	}

	if ( empty( $transaction_id ) ) {
		return false;
	}

	// Create a unique string to use as the session id.
	$session_id = wp_generate_password( 12, false );

	// Set the session id to exist for 1 hour.
	set_transient( 'ctwp_payment_session_' . $user_id . '_' . $transaction_id, wp_hash_password( $session_id ), HOUR_IN_SECONDS );

	return $session_id;
}
