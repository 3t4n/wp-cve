<?php

namespace Nelio_Content\Helpers;

if ( ! function_exists( __NAMESPACE__ . '\every' ) ) {
	/**
	 * Checks if `$predicate` returns truthy for all elements of `$collection`.
	 * The predicate is invoked with one argument: ($value).
	 *
	 * @param array    $collection The collection to iterate over.
	 * @param callable $predicate  The function invoked per iteration.
	 *
	 * @return bool `true` if any element passes the predicate check, else `false`.
	 */
	function every( array $collection, callable $predicate ): bool {
		return ! some( $collection, not( $predicate ) );
	}//end every()
}//end if

if ( ! function_exists( __NAMESPACE__ . '\find' ) ) {
	/**
	 * Iterates over elements of `$collection`, returning the first element
	 * `$predicate` returns truthy for. The predicate is invoked with one
	 * argument: ($value).
	 *
	 * @param array    $collection The collection to iterate over.
	 * @param callable $predicate  The function invoked per iteration.
	 *
	 * @return mixed|null the the matched element, else `null`
	 */
	function find( $collection, $predicate = null ) {
		$predicate = is_null( $predicate ) ? __NAMESPACE__ . '\identity' : $predicate;
		return array_reduce(
			$collection,
			fn( $result, $item ) => empty( $result ) && $predicate( $item ) ? $item : $result,
			null
		);
	}//end find()
}//end if

if ( ! function_exists( __NAMESPACE__ . '\key_by' ) ) {
	/**
	 * Creates a dictionary composed of keys generated from the results of running each element of $collection thru
	 * $iteratee. The corresponding value of each key is the last element responsible for generating the key. The
	 * $iteratee is invoked with one argument.
	 *
	 * @param array           $collection The collection to iterate over.
	 * @param callable|string $iteratee   The iteratee to transform keys.
	 *
	 * @return array The composed aggregate dictionary.
	 */
	function key_by( $collection, $iteratee = null ) {
		$iteratee = is_null( $iteratee ) ? __NAMESPACE__ . '\identity' : $iteratee;
		$keys     = array_map(
			fn( $item ) => is_callable( $iteratee )
				? call_user_func( $iteratee, $item )
				: get( $item, $iteratee ),
			$collection
		);
		return array_combine( $keys, $collection );
	}//end key_by()
}//end if

if ( ! function_exists( __NAMESPACE__ . '\some' ) ) {
	/**
	 * Checks if `$predicate` returns truthy for any element of `collection`.
	 * The predicate is invoked with one argument: ($value).
	 *
	 * @param array    $collection The collection to iterate over.
	 * @param callable $predicate  The function invoked per iteration.
	 *
	 * @return bool `true` if any element passes the predicate check, else `false`.
	 */
	function some( array $collection, callable $predicate ): bool {
		return array_reduce(
			$collection,
			fn( $result, $item ) => $result || ! empty( call_user_func( $predicate, $item ) ),
			false
		);
	}//end some()
}//end if
