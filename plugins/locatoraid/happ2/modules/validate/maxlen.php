<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Validate_Maxlen_HC_MVC extends _HC_MVC
{
	protected $maxlen = 100;

	public function params( $maxlen )
	{
		$this->maxlen = $maxlen;
		return $this;
	}

	public function validate( $value )
	{
		$return = TRUE;
		$msg = __('It can not exceed %s characters in length', 'locatoraid');

		$size = $this->_get_size( $value );
		if( $size <= $this->maxlen ){
			$return = TRUE;
		}
		else {
			$return = sprintf( $msg, $this->maxlen );
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