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
 * Endpoint which gets a arrangement for the currently-logged-in user. It is separated out like this so it can be unit tested.
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_get_arrangement_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_get_arrangement'] ) ) {
		return false;
	}

	$endpoint_result = church_tithe_wp_get_arrangement_handler();

	echo wp_json_encode( $endpoint_result );
	die();
}
add_action( 'init', 'church_tithe_wp_get_arrangement_endpoint' );

/**
 * Get a single arrangement from the frontend
 *
 * @access   public
 * @since    1.0.0
 * @return   array
 */
function church_tithe_wp_get_arrangement_handler() {

	// Verify the nonce.
	if ( ! isset( $_POST['church_tithe_wp_get_arrangement_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_get_arrangement_nonce'] ) ), 'church_tithe_wp_get_arrangement_nonce' ) ) {
		return array(
			'success'    => false,
			'error_code' => 'nonce_failed',
			'details'    => 'Nonce failed.',
		);
	}

	$user = wp_get_current_user();

	// If no current user was found.
	if ( ! $user->ID ) {
		return array(
			'success'        => false,
			'error_code'     => 'not_logged_in',
			// 'frontend_nonces' => church_tithe_wp_refresh_and_get_frontend_nonces(), phpcs:ignore Squiz.PHP.CommentedOutCode.Found
			'user_logged_in' => $user->ID ? true : false,
		);
	}

	// If json_decode failed, the JSON is invalid.
	if ( ! is_array( $_POST ) || ! isset( $_POST['church_tithe_wp_arrangement_id'] ) ) {
		return array(
			'success'        => false,
			'error_code'     => 'invalid_params',
			'details'        => 'Invalid params',
			// 'frontend_nonces' => church_tithe_wp_refresh_and_get_frontend_nonces(), phpcs:ignore Squiz.PHP.CommentedOutCode.Found
			'user_logged_in' => $user->ID ? true : false,
		);
	}

	$church_tithe_wp_arrangement_id = absint( $_POST['church_tithe_wp_arrangement_id'] );

	$arrangement = new Church_Tithe_WP_Arrangement( $church_tithe_wp_arrangement_id );

	if ( 0 === $arrangement->id ) {
		return array(
			'success'        => false,
			'error_code'     => 'no_matching_arrangement_found',
			'details'        => 'No Plan found with that ID',
			// 'frontend_nonces' => church_tithe_wp_refresh_and_get_frontend_nonces(), phpcs:ignore Squiz.PHP.CommentedOutCode.Found
			'user_logged_in' => $user->ID ? true : false,
		);
	}

	if ( absint( $user->ID ) !== absint( $arrangement->user_id ) ) {
		return array(
			'success'        => false,
			'error_code'     => 'invalid_user',
			'details'        => 'Invalid user' . $user->ID . '-' . $arrangement->user_id,
			// 'frontend_nonces' => church_tithe_wp_refresh_and_get_frontend_nonces(), phpcs:ignore Squiz.PHP.CommentedOutCode.Found
			'user_logged_in' => $user->ID ? true : false,
		);
	}

	return array(
		'success'          => true,
		'arrangement_info' => church_tithe_wp_arrangement_info_format_for_endpoint( $arrangement ),
		// 'frontend_nonces' => church_tithe_wp_refresh_and_get_frontend_nonces(), phpcs:ignore Squiz.PHP.CommentedOutCode.Found
		'user_logged_in'   => $user->ID ? true : false,
	);

}
