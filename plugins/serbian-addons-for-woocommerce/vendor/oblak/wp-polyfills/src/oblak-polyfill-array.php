<?php
/**
 * Array polyfills
 *
 * ! This file intentionally left without namespace
 *
 * @package WP Polyfills
 * @subpackage Array functions
 */

if ( ! function_exists( 'wp_array_flatmap' ) ) :
	/**
	 * Flattens and maps an array.
	 *
	 * @template T The type of the elements in the input array.
	 * @template R The type of the elements in the returned array.
	 * @param array<array-key, T> $arr Array to flatten and map.
	 * @param (callable(T): R)    $fcn Function to apply to each element.
	 * @return array<array-key, R>
	 */
	function wp_array_flatmap( $arr, $fcn ) {
		return array_merge( array(), ...array_map( $fcn, $arr ) );
	}

endif;

if ( ! function_exists( 'wp_array_diff_assoc' ) ) :
	/**
	 * Extracts a slice of array not including the specified keys.
	 *
     * @template T The type of the elements in the input array.
	 * @param  array<string, T>   $input_array Input array.
	 * @param  array<int, string> $keys        Keys to exclude.
	 * @return array<string, T>                Array with the keys removed.
	 */
	function wp_array_diff_assoc( array $input_array, array $keys ): array {
		return array_diff_key( $input_array, array_flip( $keys ) );
	}
endif;
