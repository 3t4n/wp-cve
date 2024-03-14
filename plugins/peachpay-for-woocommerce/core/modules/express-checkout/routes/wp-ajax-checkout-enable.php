<?php
/**
 * WP ajax request for enabling the Express Checkout.
 *
 * @package PeachPay
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sets the PeachPay Express Checkout setting.
 */
function pp_checkout_wp_ajax_checkout_enable() {
	if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'peachpay-enable-express-checkout' ) ) {
		return wp_send_json(
			array(
				'success' => false,
				'message' => 'Invalid nonce. Please refresh the page and try again.',
			)
		);
	}

	if ( ! isset( $_POST['setting_value'] ) ) {
		return wp_send_json(
			array(
				'success' => false,
				'message' => 'Missing setting id or value',
			)
		);
	}

	$value = sanitize_text_field( wp_unslash( $_POST['setting_value'] ) ) === 'true' ? 'yes' : 'no';

	PeachPay::update_option( 'pp_checkout_enable', $value );

	return wp_send_json(
		array(
			'success' => true,
		)
	);
}
