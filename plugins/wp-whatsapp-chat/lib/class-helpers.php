<?php

namespace QuadLayers\QLWAPP;

final class Helpers {
	public static function parse_body( $body, $valid_args ) {
		$args = array();

		foreach ( $valid_args as $field => $sanitize_callback ) {
			if ( isset( $body[ $field ] ) ) {
				$args[ $field ] = call_user_func( $sanitize_callback, $body[ $field ] );
			}
		}

		return $args;
	}
}
