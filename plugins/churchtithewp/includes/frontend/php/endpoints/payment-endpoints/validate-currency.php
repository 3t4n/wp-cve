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
 * Validate a user-typed currency API Endpoint. It is separated out like this so it can be unit tested.
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_confirm_currency_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_confirm_currency'] ) ) {
		return false;
	}

	$endpoint_result = church_tithe_wp_confirm_currency_handler();

	echo wp_json_encode( $endpoint_result );
	die();
}
add_action( 'init', 'church_tithe_wp_confirm_currency_endpoint' );

/**
 * Save note with tithe API Endpoint
 *
 * @access   public
 * @since    1.0.0
 * @return   array
 */
function church_tithe_wp_confirm_currency_handler() {

	// No user authentication or nonces are required here. Data-wise, this is essentially the same as just loading a frontend page.

	// Check if values were not there that need to be.
	if ( ! is_array( $_POST ) || ! isset( $_POST['church_tithe_wp_currency_to_confirm'] ) || ! isset( $_POST['church_tithe_wp_currency_to_confirm'] ) ) {
		return array(
			'success'    => false,
			'error_code' => 'missing_values',
			'details'    => 'Currency confirmation request was incorrect.',
		);
	}

	$search_term = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_currency_to_confirm'] ) );

	if ( strlen( $search_term ) < 3 ) {
		return array(
			'success'      => true,
			'success_type' => 'search_not_long_enough',
			'details'      => 'More characters are required to search',
		);
	}

	$matching_currencies = church_tithe_wp_currency_search_results( $search_term );

	if ( empty( $matching_currencies ) ) {
		return array(
			'success'    => false,
			'error_code' => 'no_matching_currencies_found',
			'details'    => 'No currencies were found with that search',
		);
	}

	// If more than one matching result was found, let them keep typing.
	if ( count( $matching_currencies ) > 1 ) {
		return array(
			'success'      => true,
			'success_type' => 'more_than_one_currency_matched',
			'details'      => 'More than one currency found',
		);
	}

	foreach ( $matching_currencies as $currency_code => $currency_description ) {
			$three_letter_currency_code = strtoupper( $currency_code );
			break;
	}
	$currency_symbol = html_entity_decode( church_tithe_wp_currency_symbol( $three_letter_currency_code ) );

	// Return the first matching currency found.
	return array(
		'success'                   => true,
		'success_type'              => 'one_currency_matched',
		'details'                   => 'One currency found',
		'validated_currency'        => $three_letter_currency_code,
		'validated_currency_symbol' => $currency_symbol,
		'validated_currency_type'   => church_tithe_wp_is_a_zero_decimal_currency( $three_letter_currency_code ) ? 'zero_decimal' : 'decimal',
	);

}
