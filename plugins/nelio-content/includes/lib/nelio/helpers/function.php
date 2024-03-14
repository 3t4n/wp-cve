<?php

namespace Nelio_Content\Helpers;

if ( ! function_exists( __NAMESPACE__ . '\flow' ) ) {
	/**
	 * Creates a function that returns the result of invoking the given functions, where
	 * each successive invocation is supplied the return value of the previous.
	 *
	 * @param callable $func     The first function to invoke.
	 * @param callable ...$funcs The remaining functions to invoke.
	 *
	 * @return callable
	 */
	function flow( callable $func, callable ...$funcs ): callable {
		return fn( $value ) => array_reduce(
			array( $func, ...$funcs ),
			fn( $v, $f ) => call_user_func( $f, $v ),
			$value
		);
	}//end flow()
}//end if

if ( ! function_exists( __NAMESPACE__ . '\identity' ) ) {
	/**
	 * Returns the given argument as is.
	 *
	 * @param mixed $value Any value.
	 *
	 * @return mixed The $value.
	 */
	function identity( $value ) {
		return $value;
	}//end identity()
}//end if

if ( ! function_exists( __NAMESPACE__ . '\not' ) ) {
	/**
	 * Creates a function that negates the result of `$predicate`.
	 *
	 * @param callable $predicate The predicate to negate.
	 *
	 * @return callable The new negated predicate.
	 */
	function not( $predicate ) {
		return fn( ...$values ) => ! call_user_func( $predicate, ...$values );
	}//end not()
}//end if
