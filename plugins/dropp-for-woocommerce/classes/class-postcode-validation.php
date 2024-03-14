<?php
/**
 * Postcode Validation
 *
 * @package Dropp
 */

namespace Dropp;

/**
 * Postcode_Validation class
 */
class Postcode_Validation {

	/**
	 * Setup.
	 */
	public static function setup(): void {
		add_filter( 'woocommerce_validate_postcode', __CLASS__ . '::validate_postcode', 10, 3 );
	}

	/**
	 * Validate postcode.
	 *
	 * @param boolean $valid    Valid postcode.
	 * @param string $postcode Postcode.
	 * @param string $country  ISO2 country code.
	 *
	 * @return boolean Valid postcode.
	 */
	public static function validate_postcode( bool $valid, string $postcode, string $country ): bool {
		if ( ! $valid || 'IS' !== $country ) {
			return $valid;
		}
		return preg_match( '/^\d{3}$/', $postcode );
	}
}
