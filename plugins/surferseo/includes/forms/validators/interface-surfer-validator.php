<?php
/**
 * Interface for field validator.
 *
 * @package SurferSEO
 */

namespace SurferSEO\Forms\Validators;

/**
 * Interface for Surfer Forms Validator.
 */
interface Surfer_Validator_Interface {

	/**
	 * Validate value.
	 *
	 * @param mixed $value - value to validate.
	 * @return bool
	 */
	public function validate( $value );

	/**
	 * Returns error message in case of validation fail.
	 *
	 * @return string.
	 */
	public function get_error();
}
