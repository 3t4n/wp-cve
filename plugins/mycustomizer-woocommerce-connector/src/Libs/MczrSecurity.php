<?php

namespace MyCustomizer\WooCommerce\Connector\Libs;

class MczrSecurity {

	public function __construct() {
	}

	public function generateKey( $bit_length = 128 ) {
		$fp = @fopen( '/dev/random', 'rb' );
		if ( false !== $fp ) {
			$key = substr( base64_encode( @fread( $fp, ( $bit_length + 7 ) / 8 ) ), 0, ( ( $bit_length + 5 ) / 6 ) - 2 );
			@fclose( $fp );
			return $key;
		} else {
			return md5( microtime() . rand() );
		}
		return null;
	}
}
