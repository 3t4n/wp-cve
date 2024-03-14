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
 * Endpoint which gets arrangements for the currently-logged-in user. It is separated out like this so it can be unit tested.
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_check_if_user_logged_in_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_check_if_user_logged_in'] ) ) {
		return false;
	}

	$endpoint_result = church_tithe_wp_check_if_user_logged_in_handler();

	echo wp_json_encode( $endpoint_result );
	die();
}
add_action( 'init', 'church_tithe_wp_check_if_user_logged_in_endpoint' );

/**
 * Check if the user is logged in
 *
 * @access   public
 * @since    1.0.0
 * @return   array
 */
function church_tithe_wp_check_if_user_logged_in_handler() {

	// Nonces are not needed here, because the nonce would be anonymous, and nonces are user specific.
	// So it will fail if the user has logged in in this session.

	$user = wp_get_current_user();

	if ( ! isset( $_POST['church_tithe_wp_check_if_user_logged_in'] ) ) {
		return array(
			'success'        => false,
			'error_code'     => 'invalid_request',
			// 'frontend_nonces' => church_tithe_wp_refresh_and_get_frontend_nonces(),
			'user_logged_in' => $user->ID ? true : false,
		);
	}

	// If no current user was found.
	if ( ! $user->ID ) {
		return array(
			'success'        => false,
			'error_code'     => 'not_logged_in',
			// 'frontend_nonces' => church_tithe_wp_refresh_and_get_frontend_nonces(),
			'user_logged_in' => $user->ID ? true : false,
		);
	}

	// If a current user was found.
	return array(
		'success'        => true,
		// 'frontend_nonces' => church_tithe_wp_refresh_and_get_frontend_nonces(),
		'user_logged_in' => $user->ID ? true : false,
	);

}
