<?php
declare( strict_types=1 );

/**
 * PECL: Convert domain name from IDNA ASCII to Unicode
 */
if ( ! function_exists( 'idn_to_utf8' ) ) {
	function idn_to_utf8( string $domain, int $flags = 0, int $variant = 0, ?array &$idna_info = null ): string {
		return $domain;
	}
}

/**
 * PHP 8.1 brings a new function array_is_list, that returns whether a given array
 * is an array with all sequential integers starting from 0.
 * In other words, this function returns true if the given array is semantic list of values;
 * an array with all keys are integers, keys start from 0, with no gaps in between.
 * array_is_list function returns true on empty arrays as well.
 */
if ( ! function_exists( 'array_is_list' ) ) {
	function array_is_list( array $array ): bool {
		if ( empty( $array ) ) {
			return true;
		}
		$current_key = 0;

		foreach ( $array as $key => $noop ) {
			if ( $key !== $current_key ) {
				return false;
			}
			++$current_key;
		}
		return true;
	}
}

/**
 * Replace all occurrences of the search string with the replacement string.
 *
 * @author Sean Murphy <sean@iamseanmurphy.com>
 * @copyright Copyright 2012 Sean Murphy. All rights reserved.
 * @license http://creativecommons.org/publicdomain/zero/1.0/
 * @link http://php.net/manual/function.str-replace.php
 *
 * @param mixed $search
 * @param mixed $replace
 * @param mixed $subject
 * @param int $count
 * @return mixed
 */

if ( ! function_exists( 'mb_str_replace' ) ) {

	function mb_str_replace( $search, $replace, $subject, int &$count = 0 ) {

		if ( ! is_array( $subject ) ) {
			// Normalize $search and $replace so they are both arrays of the same length
			$searches = is_array( $search ) ? array_values( $search ) : array( $search );
			$replacements = is_array( $replace ) ? array_values( $replace ) : array( $replace );
			$replacements = array_pad( $replacements, count( $searches ), '');

			foreach ( $searches as $key => $search ) {
				$parts = mb_split( preg_quote( $search ), $subject );
				$count += count( $parts ) - 1;
				$subject = implode( $replacements[ $key ], $parts );
			}
		} else {
			// Call mb_str_replace for each subject in array, recursively
			foreach ( $subject as $key => $value ) {
				$subject[ $key ] = mb_str_replace( $search, $replace, $value, $count );
			}
		}

		return $subject;
	}
}

if ( ! function_exists( 'str_starts_with' ) ) {
    /**
     * Convenient way to check if a string starts with another string.
     *
     * @param string $haystack String to search through.
     * @param string $needle Pattern to match.
     * @return bool Returns true if $haystack starts with $needle.
     */
    function str_starts_with( string $haystack, string $needle ): bool {
        $length = strlen( $needle );
        return $needle === '' || substr( $haystack, 0, $length ) === $needle;
    }
}

if ( ! function_exists( 'str_ends_with' ) ) {
    /**
     * Convenient way to check if a string ends with another string.
     *
     * @param string $haystack String to search through.
     * @param string $needle Pattern to match.
     * @return bool Returns true if $haystack ends with $needle.
     */
    function str_ends_with( string $haystack, string $needle ): bool {
        $length = strlen( $needle );
        return $needle === '' || substr( $haystack, -$length ) === $needle;
    }
}

if ( ! function_exists( 'mb_ucwords' ) ) {
    function mb_ucwords( $text ) {
        return mb_convert_case( $text, MB_CASE_TITLE, 'UTF-8' );
    }
}

if ( ! function_exists( 'mb_str_starts_with' ) ) {
    /**
     * Multibyte - Convenient way to check if a string starts with another string.
     *
     * @param string $haystack String to search through.
     * @param string $needle Pattern to match.
     * @return bool Returns true if $haystack starts with $needle.
     */
    function mb_str_starts_with( string $haystack, string $needle ): bool {
        $length = mb_strlen( $needle );
        return mb_substr( $haystack, 0, $length ) === $needle;
    }
}

if ( ! function_exists( 'mb_str_ends_with' ) ) {
    /**
     * Multibyte - Convenient way to check if a string ends with another string.
     *
     * @param string $haystack String to search through.
     * @param string $needle Pattern to match.
     * @return bool Returns true if $haystack ends with $needle.
     */
    function mb_str_ends_with( string $haystack, string $needle ): bool {
        $length = mb_strlen( $needle );
        return mb_substr( $haystack, -$length ) === $needle;
    }
}

if ( ! function_exists( 'mb_strrev' ) ) {
	function mb_strrev ( $string, $encoding = null ) {

		if ( $encoding === null ) {
			$encoding = mb_detect_encoding( $string );
		}
		$length = mb_strlen( $string, $encoding );
		$reversed = '';
		while ( $length-- > 0 ) {
			$reversed .= mb_substr( $string, $length, 1, $encoding );
		}

		return $reversed;
	}
}
