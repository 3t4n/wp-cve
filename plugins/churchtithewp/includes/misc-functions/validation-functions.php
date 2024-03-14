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
 * Validation function caller.
 * This allows a safe way to take a user-submitted string, and call the correct corresponding validation function.
 *
 * @since  1.0.0
 * @param  string $function_name The name of the function to use for validation.
 * @param  string $value The value to validate.
 * @return array
 */
function church_tithe_wp_validation_caller( $function_name, $value ) {

	if ( 'church_tithe_wp_validate_image_upload' === $function_name ) {
		return church_tithe_wp_validate_image_upload( $value );
	}

	if ( 'church_tithe_wp_validate_currency_input' === $function_name ) {
		return church_tithe_wp_validate_currency_input( $value );
	}

	if ( 'church_tithe_wp_validate_integer_input' === $function_name ) {
		return church_tithe_wp_validate_integer_input( $value );
	}

	if ( 'church_tithe_wp_validate_simple_input' === $function_name ) {
		return church_tithe_wp_validate_simple_input( $value );
	}

	if ( 'church_tithe_wp_validate_test_stripe_webhook' === $function_name ) {
		return church_tithe_wp_validate_test_stripe_webhook( $value );
	}

	if ( 'church_tithe_wp_validate_live_stripe_webhook' === $function_name ) {
		return church_tithe_wp_validate_live_stripe_webhook( $value );
	}

	if ( 'church_tithe_wp_validate_sendgrid_api_key' === $function_name ) {
		return church_tithe_wp_validate_sendgrid_api_key( $value );
	}

	// If we made it this far, the function does not exist. So we can just kill this request.
	die();

}

/**
 * Validate an image file
 *
 * @since  1.0.0
 * @param  string $value The value to check and validate.
 * @return array
 */
function church_tithe_wp_validate_image_upload( $value ) {

	// Validate the type of file.
	if (
		'image/jpeg' === $value['type'] ||
		'image/png' === $value['type']
	) {
		return array(
			'success' => true,
			'value'   => $value,
		);
	} else {

		return array(
			'success'    => false,
			'error_code' => 'invalid_selection',
		);

	}

}

/**
 * Validate a currency
 *
 * @since  1.0.0
 * @param  string $value The value to confirm is a currency.
 * @return array
 */
function church_tithe_wp_validate_currency_input( $value ) {

	// Allow empty values to be saved.
	if ( ! $value ) {
		return array(
			'success' => true,
			'value'   => $value,
		);
	}

	$value = sanitize_text_field( $value );

	// If the value isn't 3 characters, we know it's not a valid currency code.
	if ( 3 !== strlen( $value ) ) {
		return array(
			'success'    => false,
			'error_code' => 'invalid_selection',
		);
	}

	// Get all available values.
	$all_available_currencies = church_tithe_wp_get_currencies();

	$matching_currencies = array();

	// Search the array.
	foreach ( $all_available_currencies as $currency_key => $currency_value ) {
		if ( stripos( $currency_key, $value ) !== false ) {
			$matching_currencies[ $currency_key ] = $currency_value;
		}
	}

	if ( $matching_currencies ) {
		return array(
			'success' => true,
			'value'   => $value,
		);
	} else {
		return array(
			'success'    => false,
			'error_code' => 'invalid_selection',
		);
	}

}

/**
 * Validate a number
 *
 * @since  1.0.0
 * @param  string $value The value to validate.
 * @return array
 */
function church_tithe_wp_validate_integer_input( $value ) {

	// Allow empty values to be saved.
	if ( ! $value ) {
		return array(
			'success' => true,
			'value'   => $value,
		);
	}

	if ( intval( $value ) ) {
		return array(
			'success' => true,
			'value'   => intval( $value ),
		);
	} else {
		return array(
			'success'    => false,
			'error_code' => 'not_an_integer',
		);
	}

}

/**
 * Validate a text field
 *
 * @since  1.0.0
 * @param  string $value The value to validate.
 * @return array
 */
function church_tithe_wp_validate_simple_input( $value ) {

	// Allow empty values to be saved.
	if ( ! $value ) {
		return array(
			'success' => true,
			'value'   => $value,
		);
	}

	if ( sanitize_text_field( $value ) ) {
		return array(
			'success' => true,
			'value'   => sanitize_text_field( $value ),
		);
	} else {
		return array(
			'success'    => false,
			'error_code' => 'error',
		);
	}

}

/**
 * Validate a Test Stripe Webhook signing secret
 *
 * @since    1.0.0
 * @param    string $value The value to validate.
 * @return   array
 */
function church_tithe_wp_validate_test_stripe_webhook( $value ) {

	$value = sanitize_text_field( $value );

	// Check if the string contains "whsec" which is the prefix Stripe puts before their webhook signing secrets.
	if ( false === strpos( $value, 'whsec_' ) ) {
		return array(
			'success'    => false,
			'error_code' => 'does_not_contain_whsec',
		);
	}

	// Make sure the string is 38 characters long.
	if ( 38 !== strlen( $value ) ) {
		return array(
			'success'    => false,
			'error_code' => 'invalid_length',
		);
	}

	return array(
		'success' => true,
		'value'   => $value,
	);
}

/**
 * Validate a Live Stripe Webhook signing secret
 *
 * @access   public
 * @since    1.0.0
 * @param    string $value The value to validate.
 * @return   array
 */
function church_tithe_wp_validate_live_stripe_webhook( $value ) {

	$value = sanitize_text_field( $value );

	// Check if the string contains "whsec" which is the prefix Stripe puts before their webhook signing secrets.
	if ( false === strpos( $value, 'whsec_' ) ) {
		return array(
			'success'    => false,
			'error_code' => 'does_not_contain_whsec',
		);
	}

	// Make sure the string is 38 characters long.
	if ( 38 !== strlen( $value ) ) {
		return array(
			'success'    => false,
			'error_code' => 'invalid_length',
		);
	}

	return array(
		'success' => true,
		'value'   => $value,
	);

}

/**
 * Validate a SendGrid API Key
 *
 * @since    1.0.0
 * @param    string $value The value to validate.
 * @return   array
 */
function church_tithe_wp_validate_sendgrid_api_key( $value ) {

	$value = sanitize_text_field( $value );

	// Make sure the string is 69 characters long, which is the length of a SendGrid API Key.
	if ( 69 !== strlen( $value ) ) {
		return array(
			'success'    => false,
			'error_code' => 'error',
		);
	}

	return array(
		'success' => true,
		'value'   => $value,
	);

}
