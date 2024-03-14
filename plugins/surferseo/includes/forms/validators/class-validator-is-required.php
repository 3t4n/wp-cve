<?php
/**
 * Is Required Validator.
 *
 * @package SurferSEO
 */

namespace SurferSEO\Forms\Validators;

/**
 * Validator to check if field is required.
 */
class Validator_Is_Required implements Surfer_Validator_Interface {

	/**
	 * Validate value.
	 *
	 * @param mixed $value - value to validate.
	 * @return bool
	 */
	public function validate( $value ) {
		if ( isset( $value ) && '' !== $value && ! empty( $value ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns error message in case of validation fail.
	 *
	 * @return string.
	 */
	public function get_error() {
		return __( 'This field is required.', 'surferseo' );
	}
}
