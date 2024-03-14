<?php
if ( ! function_exists( 'array_key_first' ) ) {
	/**
	 * Polyfill for array_key_first() function added in PHP 7.3.
	 *
	 * Get the first key of the given array without affecting
	 * the internal array pointer.
	 *
	 * @param array $arr An array.
	 * @return string|int|null The first key of array if the array
	 *                         is not empty; `null` otherwise.
	 */
	function array_key_first( array $arr ) {
		foreach ( $arr as $key => $value ) {
			return $key;
		}
	}
}

if ( ! function_exists( 'array_key_last' ) ) {
	/**
	 * Polyfill for `array_key_last()` function added in PHP 7.3.
	 *
	 * Get the last key of the given array without affecting the
	 * internal array pointer.
	 *
	 * @param array $arr An array.
	 * @return string|int|null The last key of array if the array
	 *.                        is not empty; `null` otherwise.
	 */
	function array_key_last( array $arr ) {
		if ( empty( $arr ) ) {
			return null;
		}
		end( $arr );
		return key( $arr );
	}
}

if ( ! function_exists( 'str_contains' ) ) {
	/**
	 * Polyfill for `str_contains()` function added in PHP 8.0.
	 *
	 * Performs a case-sensitive check indicating if needle is
	 * contained in haystack.
	 *
	 * @since 5.9.0
	 *
	 * @param string $haystack The string to search in.
	 * @param string $needle   The substring to search for in the haystack.
	 * @return bool True if `$needle` is in `$haystack`, otherwise false.
	 */
	function str_contains( $haystack, $needle ) {
		return ( '' === $needle || false !== strpos( $haystack, $needle ) );
	}
}

if ( ! function_exists( 'str_starts_with' ) ) {
	/**
	 * Polyfill for `str_starts_with()` function added in PHP 8.0.
	 *
	 * Performs a case-sensitive check indicating if
	 * the haystack begins with needle.
	 *
	 * @since 5.9.0
	 *
	 * @param string $haystack The string to search in.
	 * @param string $needle   The substring to search for in the `$haystack`.
	 * @return bool True if `$haystack` starts with `$needle`, otherwise false.
	 */
	function str_starts_with( $haystack, $needle ) {
		if ( '' === $needle ) {
			return true;
		}
		return 0 === strpos( $haystack, $needle );
	}
}

if ( ! function_exists( 'str_ends_with' ) ) {
	/**
	 * Polyfill for `str_ends_with()` function added in PHP 8.0.
	 *
	 * Performs a case-sensitive check indicating if
	 * the haystack ends with needle.
	 *
	 * @since 5.9.0
	 *
	 * @param string $haystack The string to search in.
	 * @param string $needle   The substring to search for in the `$haystack`.
	 * @return bool True if `$haystack` ends with `$needle`, otherwise false.
	 */
	function str_ends_with( $haystack, $needle ) {
		if ( '' === $haystack && '' !== $needle ) {
			return false;
		}
		$len = strlen( $needle );
		return 0 === substr_compare( $haystack, $needle, -$len, $len );
	}
}
