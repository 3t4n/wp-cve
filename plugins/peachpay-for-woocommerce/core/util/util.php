<?php
/**
 * PeachPay Utility Files.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

require_once PEACHPAY_ABSPATH . 'core/util/currency.php';
require_once PEACHPAY_ABSPATH . 'core/util/product.php';
require_once PEACHPAY_ABSPATH . 'core/util/cart.php';
require_once PEACHPAY_ABSPATH . 'core/util/shipping.php';
require_once PEACHPAY_ABSPATH . 'core/util/url.php';
require_once PEACHPAY_ABSPATH . 'core/util/button.php';
require_once PEACHPAY_ABSPATH . 'core/util/order.php';
require_once PEACHPAY_ABSPATH . 'core/util/language.php';
require_once PEACHPAY_ABSPATH . 'core/util/translation.php';
require_once PEACHPAY_ABSPATH . 'core/util/string.php';
require_once PEACHPAY_ABSPATH . 'core/util/environment.php';
require_once PEACHPAY_ABSPATH . 'core/util/plugin.php';

/* Array util. Once more functions exists for arrays we will make a file. */

/**
 * Helper function to get safely retrieve a value out of an array without duplicating ternary checks everywhere.
 *
 * @param array         $array The array to retrieve a value from.
 * @param string|number $key The index or key of the value to retrieve.
 */
function peachpay_array_value( $array, $key ) {
	return $array && is_array( $array ) && isset( $array[ $key ] ) ? $array[ $key ] : null;
}

/**
 * Adds a value to an array if the value is not empty.
 *
 * @param array  $data The array of data.
 * @param string $key The key to add the value to.
 * @param string $value The value to add if not empty.
 */
function peachpay_add_if_not_empty( &$data, $key, $value ) {
	if ( $value ) {
		$data[ $key ] = $value;
	}
}
