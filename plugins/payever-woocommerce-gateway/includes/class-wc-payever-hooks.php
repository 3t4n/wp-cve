<?php

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

use Automattic\WooCommerce\Utilities\NumberUtil;

/**
 * WC_Payever_Hooks Class.
 */
class WC_Payever_Hooks {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'pe_round', array( $this, 'round' ), 10, 2 );
		add_filter( 'pe_format', array( $this, 'format' ), 10, 1 );
	}

	/**
	 * Round.
	 *
	 * @param int|float $value
	 * @param int|null $precision
	 * @return float
	 */
	public function round( $value, $precision = null ) {
		if ( is_null( $precision ) ) {
			$precision = wc_get_price_decimals();
		}

		if ( class_exists( NumberUtil::class ) ) {
			return NumberUtil::round( $value, $precision );
		}

		if ( defined( 'WC_DISCOUNT_ROUNDING_MODE' ) &&
			PHP_ROUND_HALF_DOWN === WC_DISCOUNT_ROUNDING_MODE &&
			function_exists( 'wc_legacy_round_half_down' ) // @since 3.2.6
		) {
			return wc_legacy_round_half_down( $value, $precision );
		}

		return round( $value, $precision );
	}

	/**
	 * Format.
	 *
	 * @param int|float $value
	 * @return string
	 */
	public function format( $value ) {
		return str_replace( ',', '', sprintf( '%0.2f', $value ) );
	}
}

new WC_Payever_Hooks();
