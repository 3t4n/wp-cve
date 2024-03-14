<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class App_Lib_Args_HC_MVC extends _HC_MVC
{
	protected $args = array();

	public function get( $k = NULL )
	{
		if( $k === NULL ){
			$return = $this->args;
		}
		else {
			$return = NULL;
			if( array_key_exists($k, $this->args) ){
				$return = $this->args[$k];
			}
		}
		return $return;
	}

	public function parse( $args )
	{
		$this->args = hc2_parse_args( $args, TRUE );
		return $this;
	}

	public function from_string( $string = '' )
	{
		$args = explode('/', $string);
		$return = hc2_parse_args( $args, TRUE );
		return $return;
	}

	public function to_string( $params = array() )
	{
		$final_params = array();
		foreach( $params as $k => $p ){
			if( is_array($p) ){
				$final_p = array();
				foreach( $p as $p2 ){
					if( is_array($p2) ){
						$p2 = join('|', $p2);
					}
					// $p2 = urlencode( $p2 ); 
					$final_p[] = $p2;
				}
				$p = join('|', $final_p);
			}
			else {
				// $p = urlencode($p);
			}
			// $p = urlencode($p);
			$final_params[] = $k;
			$final_params[] = $p;
		}
		$params = join('/', $final_params);
		return $params;
	}
}