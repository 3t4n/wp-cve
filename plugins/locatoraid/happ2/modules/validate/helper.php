<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Validate_Helper_HC_MVC extends _HC_MVC
{
	// returns errors array
	public function validate( array $values, array $validators, $full_check = TRUE )
	{
		$return = array();

		reset( $validators );
		foreach( array_keys($validators) as $k ){
			if( ! $full_check ){
				if( ! array_key_exists($k, $values) ){
					continue;
				}
			}
			$value = array_key_exists($k, $values) ? $values[$k] : NULL;

			$this_return = $this->validate_value( $value, $validators[$k] );
			if( $this_return !== TRUE ){
				$return[ $k ] = $this_return;
			}
		}

		return $return;
	}

	public function validate_value( $value, $validators )
	{
		$return = TRUE;

		reset( $validators );
		foreach( $validators as $validator ){
			if( ! is_callable($validator) ){
				$validator = array( $validator, 'validate' );
			}

			$validator_return = call_user_func( $validator, $value );
			// $validator_return = $validator
				// ->validate( $value )
				// ;
			if( $validator_return !== TRUE ){
				$return = $validator_return;
				break;
			}
		}
		return $return;
	}
}