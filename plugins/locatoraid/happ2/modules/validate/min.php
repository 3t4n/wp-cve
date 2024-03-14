<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Validate_Min_HC_MVC extends _HC_MVC
{
	public function validate( $value, $min )
	{
		$msg = sprintf( __('At least %s is required', 'locatoraid'), $min );

		$return = ( $value >= $min ) ? TRUE : $msg;
		return $msg;
	}
}