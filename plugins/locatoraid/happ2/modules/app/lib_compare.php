<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class App_Lib_Compare_HC_MVC extends _HC_MVC
{
	public function is_valid( $e, $conditions = array() )
	{
		$return = TRUE;
		if( ! $conditions ){
			return $return;
		}

		$return = FALSE;
		$allowed_compares = array('=', '<>', '>=', '<=', '>', '<', 'IN', 'NOTIN', 'LIKE');

		foreach( $conditions as $c ){
			list( $key, $compare, $with ) = $c;
			$compare = trim( $compare );
			$compare = strtoupper( $compare );

			if( ! in_array($compare, $allowed_compares) ){
				echo "COMPARING BY '$compare' IS NOT ALLOWED!<br>";
				$return = FALSE;
				break;
			}

			if( ! array_key_exists($key, $e) ){
				$return = FALSE;
				break;
			}

			$what = $e[$key];
			if( is_array($what) && array_key_exists('id', $what) ){
				$what = $what['id'];
			}

			switch( $compare ){
				case '=':
					$return = ( $what == $with ) ? TRUE : FALSE;
					break;
				case '<>':
					$return = ( $what != $with ) ? TRUE : FALSE;
					break;
				case '>=':
					$return = ( $what >= $with ) ? TRUE : FALSE;
					break;
				case '<=':
					$return = ( $what <= $with ) ? TRUE : FALSE;
					break;
				case '>':
					$return = ( $what > $with ) ? TRUE : FALSE;
					break;
				case '<':
					$return = ( $what < $with ) ? TRUE : FALSE;
					break;
				case 'IN':
					$return = in_array($what, $with) ? TRUE : FALSE;
					break;
				case 'NOT IN':
				case 'NOTIN':
					$return = (! in_array($what, $with)) ? TRUE : FALSE;
					break;
				case 'LIKE':
					$return = (strpos($with, $what) !== FALSE) ? TRUE : FALSE;
					break;
			}
		}

		return $return;
	}
}