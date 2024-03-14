<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Validate_Match_HC_MVC extends _HC_MVC
{
	public function validate( $value, $compare_to, $compare_to_label )
	{
		$return = TRUE;
		$msg = __('This field does not match the %s field', 'locatoraid');

		if( $value != $compare_to ){
			$return = sprintf($msg, $compare_to_label);
		}

		return $return;
	}
}