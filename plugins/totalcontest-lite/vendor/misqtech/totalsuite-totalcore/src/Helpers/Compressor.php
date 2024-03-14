<?php

namespace TotalContestVendors\TotalCore\Helpers;

class Compressor {

	public static function html( $input ) {
		//if ( ! defined( 'WP_DEBUG' ) || WP_DEBUG == false ):
		//$input = preg_replace( "/\n\r|\r\n|\n|\r|\t| {2}/", '', $input );
		//endif;
		// Original regexp: http://stackoverflow.com/a/32962616
		$search  = [ '/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', "/\n\r|\r\n|\n|\r|\t/", '/>\s+</' ];
		$replace = [ '>', '<', '\\1', '', '><' ];
		$input   = preg_replace( $search, $replace, $input );

		return $input;
	}

	public static function css( $input ) {
		//if ( ! defined( 'WP_DEBUG' ) || WP_DEBUG == false ):

		$patterns = [
			'#/\*.*?\*/#s'       => '', // Remove comments
			'/\s*([{}|:;,])\s+/' => '$1', // Remove whitespace
			'/\s\s+(.*)/'        => '$1', // Remove trailing whitespace at the start
			'/\;\}/'             => '}', // Remove unnecessary ;
		];

		$input = preg_replace( array_keys( $patterns ), array_values( $patterns ), $input );

		//endif;

		return $input;
	}

	public static function js( $input ) {
		return $input;
	}

}