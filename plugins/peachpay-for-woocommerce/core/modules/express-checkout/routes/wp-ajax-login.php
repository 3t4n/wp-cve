<?php
/**
 * WP ajax request for logging in while inside the PeachPay express checkout.
 *
 * @package PeachPay
 */

defined( 'ABSPATH' ) || exit;

/**
 * Ajax endpoint for logging in a WP user for use within the PeachPay Express Checkout.
 */
function pp_checkout_wp_ajax_login() {
	if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'peachpay-ajax-login' ) ) {
		return wp_send_json(
			array(
				'success' => false,
				'message' => 'Invalid nonce. Please refresh the page and try again.',
			)
		);
	}

	$credentials = array(
		'user_login'    => isset( $_POST['username'] ) ? sanitize_text_field( wp_unslash( $_POST['username'] ) ) : '',
		'user_password' => isset( $_POST['password'] ) ? sanitize_text_field( wp_unslash( $_POST['password'] ) ) : '',
		'remember'      => isset( $_POST['remember'] ) ? sanitize_text_field( wp_unslash( $_POST['remember'] ) ) === 'forever' : false,
	);

	$user = wp_signon( $credentials, is_ssl() );
	if ( is_wp_error( $user ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'message' => $user->get_error_message(),
			)
		);
	} else {
		echo wp_json_encode(
			array(
				'success' => true,
			)
		);
	}

	die();
}
