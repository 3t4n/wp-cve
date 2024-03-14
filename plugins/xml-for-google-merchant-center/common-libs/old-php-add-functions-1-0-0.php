<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// 1.0.0 (23-05-2023)
// Maxim Glazunov (https://icopydoc.ru)
// This code adds several features to older PHP versions.

/**
 * @since 1.0.0 (23-05-2023)
 * 
 * @source	https://www.php.net/manual/ru/function.array-key-first.php
 * 
 * @param	array				$arr (require)
 * 
 * @return	string|null
 */
if ( version_compare( PHP_VERSION, '7.3.0' ) <= 0 ) {
	if ( ! function_exists( 'array_key_first' ) ) {
		function array_key_first( array $arr ) {
			foreach ( $arr as $key => $unused ) {
				return $key;
			}
			return null;
		}
	}
}

/**
 * @since 1.0.0 (23-05-2023)
 * 
 * @param	array				$arr (require)
 * 
 * @return	string|null
 */
if ( version_compare( PHP_VERSION, '7.3.0' ) <= 0 ) {
	if ( ! function_exists( "array_key_last" ) ) {
		function array_key_last( $array ) {
			if ( ! is_array( $array ) || empty( $array ) ) {
				return null;
			}
			return array_keys( $array )[ count( $array ) - 1 ];
		}
	}
}