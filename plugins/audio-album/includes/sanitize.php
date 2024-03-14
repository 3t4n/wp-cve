<?php


/**
* Exit if accessed directly
*
*/
if ( ! defined( 'ABSPATH' ) ) exit;


/**
* Sanitize Hex Color
*
*/
if( !function_exists( 'cc_sanitize_hex_color' ) ) {

	function cc_sanitize_hex_color( $color, $default ) {
		if ( '' === $color )
			return $default;

		// 3 or 6 hex digits, or the empty string.
		if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
			return $color;

		return $default;
	}
}


/**
* Sanitize checkbox
*
*/
function cc_audioalbum_sanitize_checkbox( $input ) {
	if ( $input == 1 ) {
		return 1;
	} else {
		return '';
	}
}