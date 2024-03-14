<?php
/**
 * EverAccounting Tax functions.
 *
 * Functions related to taxes.
 *
 * @since   1.1.0
 * @package EverAccounting
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is tax enabled.
 *
 * @since 1.1.0
 * @return bool
 */
function eaccounting_tax_enabled() {
	return apply_filters( 'eaccounting_tax_enabled', eaccounting()->settings->get( 'tax_enabled', 'no' ) === 'yes' );
}

/**
 * Are prices inclusive of tax?
 *
 * @return bool
 */
function eaccounting_prices_include_tax() {
	return eaccounting_tax_enabled() && apply_filters( 'eaccounting_prices_include_tax', eaccounting()->settings->get( 'prices_include_tax' ) === 'yes' );
}

/**
 * Get calculated tax.
 *
 * @since 1.1.0
 *
 * @param string $amount  Amount to calculate tax for.
 * @param  string $rate   Tax rate.
 * @param bool   $inclusive Whether the amount is inclusive of tax.
 *
 * @return float|int
 */
function eaccounting_calculate_tax( $amount, $rate, $inclusive = false ) {
	$tax = 0.00;

	if ( $amount > 0 ) {

		if ( $inclusive ) {
			$pre_tax = ( $amount / ( 1 + $rate ) );
			$tax     = $amount - $pre_tax;
		} else {
			$tax = $amount * $rate;
		}
	}

	return $tax;
}
