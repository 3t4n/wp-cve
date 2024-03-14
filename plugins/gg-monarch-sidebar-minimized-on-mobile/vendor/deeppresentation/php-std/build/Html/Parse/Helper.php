<?php namespace MSMoMDP\Std\Html\Parse;

class Helper {

	// helper functions
	// -----------------------------------------------------------------------------
	// get html dom form file
	public static function file_get_html() {
		$dom  = new Dom;
		$args = func_get_args();
		$dom->load( call_user_func_array( 'file_get_contents', $args ), true );
		return $dom;
	}

	// get html dom form string
	public static function str_get_html( $str, $lowercase = true ) {
		$dom = new Dom;
		$dom->load( $str, $lowercase );
		return $dom;
	}

	// get dom form file (deprecation)
	public static function file_get_dom() {
		$dom  = new Dom;
		$args = func_get_args();
		$dom->load( call_user_func_array( 'file_get_contents', $args ), true );
		return $dom;
	}

	// get dom form string (deprecation)
	public static function str_get_dom( $str, $lowercase = true ) {
		$dom = new Dom;
		$dom->load( $str, $lowercase );
		return $dom;
	}
}
