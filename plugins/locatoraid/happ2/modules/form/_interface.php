<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
interface Form_Input_Interface_HC_MVC {
	public function grab( $name, $post );
	public function render( $name, $value = NULL );
}

class Form_Input_HC_MVC extends _HC_MVC
{
	public function name( $name )
	{
		$prefix = 'hc-';

		$return = $name;
		if( substr($return, 0, strlen($prefix)) != $prefix ){
			$return = $prefix . $return;
		}
		return $return;
	}

	public function grab( $name, $post )
	{
		$name = $this->name($name);
		$ret = null;

		if( substr($name, -strlen('[]')) == '[]' ){
			$core_name = substr($name, 0, -strlen('[]'));
			if( isset($post[$core_name]) ){
				$ret = $post[$core_name];
			}
		}
		else {
			if( isset($post[$name]) ){
				$ret = $post[$name];
			}
		}
		if( (! is_array($ret)) && (null !== $ret) ){
			$ret = trim( $ret );
		}

		return $ret;
	}
}