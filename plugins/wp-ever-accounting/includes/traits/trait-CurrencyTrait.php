<?php
/**
 * Currency Trait
 *
 * @package EverAccounting
 */

namespace EverAccounting\Traits;

use EverAccounting\Models\Currency;

defined( 'ABSPATH' ) || exit;

/**
 * Trait CurrencyTrait
 *
 * @package EverAccounting\Traits
 */
trait CurrencyTrait {
	/**
	 * Get currency object.
	 *
	 * @since 1.1.0
	 * @return Currency
	 */
	public function get_currency() {
		$currency = false;
		if ( array_key_exists( 'currency_code', $this->data ) ) {
			$code     = $this->get_currency_code() ? $this->get_currency_code() : null;
			$currency = eaccounting_get_currency( $code );
		}

		return $currency;
	}

	/**
	 * Get currency rate.
	 *
	 * @param string $context Context.
	 *
	 * @since 1.1.0
	 *
	 * @return int|string
	 */
	public function get_currency_rate( $context = 'edit' ) {
		if ( $this->get_currency() ) {
			return $this->get_currency()->get_rate( $context );
		}

		return 1;
	}

	/**
	 * Get currency rate.
	 *
	 * @param string $context Context.
	 *
	 * @since 1.1.0
	 * @return int|string
	 */
	public function get_currency_precision( $context = 'edit' ) {
		if ( $this->get_currency() ) {
			return $this->get_currency()->get_precision( $context );
		}

		return 2;
	}

	/**
	 * Get currency symbol.
	 *
	 * @param string $context Context.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public function get_currency_symbol( $context = 'edit' ) {
		if ( $this->get_currency() ) {
			return $this->get_currency()->get_symbol( $context );
		}

		return '$';
	}

	/**
	 * Get currency subunit.
	 *
	 * @param string $context Context.
	 *
	 * @since 1.1.0
	 * @return string
	 */
	public function get_currency_subunit( $context = 'edit' ) {
		if ( $this->get_currency() ) {
			return $this->get_currency()->get_subunit( $context );
		}

		return 2;
	}

	/**
	 * Get currency position.
	 *
	 * @param string $context Context.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public function get_currency_position( $context = 'edit' ) {
		if ( $this->get_currency() ) {
			return $this->get_currency()->get_position( $context );
		}

		return 'before';
	}

	/**
	 * Get currency rate.
	 *
	 * @param string $context Context.
	 *
	 * @since 1.1.0
	 *
	 * @return int|string
	 */
	public function get_currency_decimal_separator( $context = 'edit' ) {
		if ( $this->get_currency() ) {
			return $this->get_currency()->get_decimal_separator( $context );
		}

		return '.';
	}

	/**
	 * Get currency rate.
	 *
	 * @param string $context Context.
	 *
	 * @since 1.1.0
	 *
	 * @return int|string
	 */
	public function get_currency_thousand_separator( $context = 'edit' ) {
		if ( $this->get_currency() ) {
			return $this->get_currency()->get_thousand_separator( $context );
		}

		return ',';
	}

	/**
	 * Format amount.
	 *
	 * @param string $amount Amount.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public function format_amount( $amount ) {
		return eaccounting_price( $amount, $this->get_currency_code() );
	}

	/**
	 * Get converted amount.
	 *
	 * @param  string $amount Amount.
	 * @param string $code Currency code.
	 * @param null   $rate Rate.
	 *
	 * @since 1.1.0
	 */
	public function get_converted_amount( $amount, $code, $rate = null ) {
		// Get currency.
	}

}
