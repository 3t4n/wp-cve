<?php
/**
 * Bot protection functions.
 *
 * @package PeachPay
 */

defined( 'PEACHPAY_ABSPATH' ) || exit;

/**
 * Determines whether the order should go through depending on captcha.
 *
 * @param boolean $is_admin Determines whether this is performed in the settings page.
 * @param string  $key The secret key retrieved from the settings page.
 * @param string  $token The token retrieved from the settings page.
 */
function peachpay_captcha_validation( $is_admin = false, $key = '', $token = '' ) {
    // PHPCS:disable WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	$bot_protection_is_enabled = $is_admin ? true : peachpay_bot_protection_enabled();

	if ( $bot_protection_is_enabled ) {
		$captcha_token = isset( $_POST['peachpay_captcha_token'] ) ? wp_unslash( $_POST['peachpay_captcha_token'] ) : null;

		if ( ! $captcha_token && ! $is_admin ) {
			wc_add_notice( __( 'Failed to generate captcha token. Please try again or contact the store for assistance.', 'peachpay-for-woocommerce' ), 'error' );
			return;
		}

		$secret_key = $is_admin ? $key : PeachPay_Bot_Protection_Settings::get_setting( 'secret_key' );

		$data = array(
			'secret'   => $secret_key,
			'response' => $is_admin ? $token : $captcha_token,
		);

		$headers = array(
			'Content-Type' => 'application/x-www-form-urlencoded',
		);

		$response = wp_remote_post(
			'https://www.google.com/recaptcha/api/siteverify',
			array(
				'headers' => $headers,
				'body'    => $data,
			)
		);

		if ( is_wp_error( $response ) ) {
			if ( $is_admin ) {
				return new WP_Error( 'response-error', __( 'Failed to connect to the captcha verification service.', 'peachpay-for-woocommerce' ) );
			}
			wc_add_notice( __( 'Failed to connect to the captcha verification service. Please try again or contact the store for assistance.', 'peachpay-for-woocommerce' ), 'error' );
			return;
		}

		$response_body = wp_remote_retrieve_body( $response );
		$response_data = json_decode( $response_body, true );

		if ( empty( $response_data ) ) {
			if ( $is_admin ) {
				return new WP_Error( 'invalid-response', __( 'Invalid response from the captcha verification service.', 'peachpay-for-woocommerce' ) );
			}
			wc_add_notice( __( 'Invalid response from the captcha verification service. Please try again or contact the store for assistance.', 'peachpay-for-woocommerce' ), 'error' );
			return;
		}

		// condition for successful captcha token
		if ( $response_data['success'] && isset( $response_data['score'] ) && $response_data['score'] >= 0.5 ) {
			return;
		}

		// Invalid secret key error
		if ( isset( $response_data['error-codes'][0] ) && 'invalid-input-secret' === $response_data['error-codes'][0] ) {
			if ( $is_admin ) {
				return new WP_Error( 'captcha-error', __( 'Captcha verification failed. Error: Invalid secret key.', 'peachpay-for-woocommerce' ) );
			}
			wc_add_notice( __( 'Captcha verification failed. Error: Invalid secret key. Please contact the store for assistance.', 'peachpay-for-woocommerce' ), 'error' );
		} else {
			wc_add_notice( __( 'Captcha verification failed. Please try again or contact the store for assistance.', 'peachpay-for-woocommerce' ), 'error' );
		}
	}

    // PHPCS:enable
}

/**
 * Function to determine whether bot protection is enabled.
 */
function peachpay_bot_protection_enabled() {
	$enabled = 'yes' === PeachPay_Bot_Protection_Settings::get_setting( 'enabled' ) &&
		! empty( PeachPay_Bot_Protection_Settings::get_setting( 'site_key' ) ) &&
		! empty( PeachPay_Bot_Protection_Settings::get_setting( 'secret_key' ) );

	return $enabled;
}

/**
 * Function to add bot protection data to feature set.
 *
 * @param array $data Peachpay data array.
 */
function peachpay_bot_protection_feature_flag( $data ) {
	$enabled = peachpay_bot_protection_enabled();

	$data['bot_protection'] = array(
		'enabled' => $enabled,
	);

	$data['bot_protection']['metadata'] = array(
		'site_key' => $enabled ? PeachPay_Bot_Protection_Settings::get_setting( 'site_key' ) : '',
	);

	return $data;
}

/**
 * Validates the secret key.
 */
function peachpay_validate_secret_key() {
	if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'peachpay-captcha-validation' ) ) {
		return wp_send_json(
			array(
				'success' => false,
				'message' => 'Invalid nonce. Please refresh the page and try again.',
			)
		);
	}

	if ( ! isset( $_POST['secret_key'] ) || ! isset( $_POST['captcha_token'] ) ) {
		return wp_send_json(
			array(
				'success' => false,
				'message' => 'Missing token',
			)
		);
	}

	$secret_key = sanitize_text_field( wp_unslash( $_POST['secret_key'] ) );
	$token      = sanitize_text_field( wp_unslash( $_POST['captcha_token'] ) );

	$response = peachpay_captcha_validation( true, $secret_key, $token );

	if ( is_wp_error( $response ) ) {
		return wp_send_json(
			array(
				'success' => false,
				'message' => $response->get_error_message(),
			)
		);
	}

	return wp_send_json(
		array(
			'success' => true,
		)
	);
}
