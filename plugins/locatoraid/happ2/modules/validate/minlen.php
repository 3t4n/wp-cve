<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Validate_Minlen_HC_MVC extends _HC_MVC
{
	public function validate( $value, $required )
	{
		$return = TRUE;
		$msg = __('At least %s characters required', 'locatoraid');

		$size = $this->_get_size( $value );
		if( $size >= $required ){
			$return = TRUE;
		}
		else {
			$return = sprintf( $msg, $required );
		}
		return $return;
	}

	protected function _get_size( $value )
	{
		$return = NULL;
		if( is_string($value) ){
			if( function_exists('mb_strlen') ){
				$return = mb_strlen($value);
			}
			else {
				$return = strlen($value);
			}
		}
		elseif( is_array($value) ){
			$return = count($value);
		}
		return $return;
	}
}