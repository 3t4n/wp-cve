<?php

namespace Nelio_Content\Helpers;

if ( ! function_exists( __NAMESPACE__ . '\get' ) ) {
	/**
	 * Returns the value at `$path` of `$element`. If the resolved value is null, `$default` is returned in its place.
	 *
	 * @param object|array $element the element to query.
	 * @param string|array $path    the path of the property to get.
	 * @param mixed        $default the value returned for null resolved values.
	 *
	 * @return mixed The value at path or default if not found.
	 */
	function get( $element, $path, $default = null ) {
		if ( is_string( $path ) ) {
			$path = explode( '.', $path );
			$path = empty( $path ) ? array() : $path;
		}//end if

		$result = array_reduce(
			$path,
			function( $e, $k ) {
				if ( is_array( $e ) ) {
					return isset( $e[ $k ] ) ? $e[ $k ] : null;
				} elseif ( is_object( $e ) ) {
					return property_exists( $e, $k ) ? $e->$k : null;
				} else {
					return null;
				}//end if
			},
			$element
		);

		return is_null( $result ) ? $default : $result;
	}//end get()
}//end if

if ( ! function_exists( __NAMESPACE__ . '\get_array' ) ) {
	/**
	 * Returns the value at `$path` of `$element`. If the resolved value is null or is not an array, `$default` is returned in its place.
	 *
	 * @param object|array $element the element to query.
	 * @param string|array $path    the path of the property to get.
	 * @param array        $default the value returned for null resolved values.
	 *
	 * @return array The value at path or default if not found.
	 */
	function get_array( $element, $path, array $default = array() ) {
		$result = get( $element, $path, $default );
		return is_array( $result ) ? $result : $default;
	}//end get_array()
}//end if

if ( ! function_exists( __NAMESPACE__ . '\get_float' ) ) {
	/**
	 * Returns the value at `$path` of `$element`. If the resolved value is null or is not a float, `$default` is returned in its place.
	 *
	 * @param object|array $element the element to query.
	 * @param string|array $path    the path of the property to get.
	 * @param float        $default the value returned for null resolved values.
	 *
	 * @return float The value at path or default if not found.
	 */
	function get_float( $element, $path, float $default = 0.0 ) {
		$result = get( $element, $path, $default );
		return is_float( $result ) ? $result : $default;
	}//end get_float()
}//end if

if ( ! function_exists( __NAMESPACE__ . '\get_int' ) ) {
	/**
	 * Returns the value at `$path` of `$element`. If the resolved value is null or is not an int, `$default` is returned in its place.
	 *
	 * @param object|array $element the element to query.
	 * @param string|array $path    the path of the property to get.
	 * @param int          $default the value returned for null resolved values.
	 *
	 * @return int The value at path or default if not found.
	 */
	function get_int( $element, $path, int $default = 0 ) {
		$result = get( $element, $path, $default );
		return is_int( $result ) ? $result : $default;
	}//end get_int()
}//end if

if ( ! function_exists( __NAMESPACE__ . '\get_object' ) ) {
	/**
	 * Returns the value at `$path` of `$element`. If the resolved value is null or is not an object, `$default` is returned in its place.
	 *
	 * @param object|array $element the element to query.
	 * @param string|array $path    the path of the property to get.
	 * @param object       $default the value returned for null resolved values.
	 *
	 * @return object The value at path or default if not found.
	 */
	function get_object( $element, $path, $default = null ) {
		$default = is_null( $default ) ? new \stdClass() : $default;
		$result  = get( $element, $path, $default );
		return is_object( $result ) ? $result : $default;
	}//end get_object()
}//end if

if ( ! function_exists( __NAMESPACE__ . '\get_string' ) ) {
	/**
	 * Returns the value at `$path` of `$element`. If the resolved value is null or is not a string, `$default` is returned in its place.
	 *
	 * @param object|array $element the element to query.
	 * @param string|array $path    the path of the property to get.
	 * @param string       $default the value returned for null resolved values.
	 *
	 * @return string The value at path or default if not found.
	 */
	function get_string( $element, $path, string $default = '' ) {
		$result = get( $element, $path, $default );
		return is_string( $result ) ? $result : $default;
	}//end get_string()
}//end if

if ( ! function_exists( __NAMESPACE__ . '\without' ) ) {
	/**
	 * Returns an array excluding all given values.
	 *
	 * If `$array` had numeric indices, the new array will have its indices reset.
	 *
	 * @param array $array    The array to inspect.
	 * @param mixed ...$items The values to exclude.
	 *
	 * @return array The new array of filtered values.
	 */
	function without( array $array, ...$items ): array {
		$result = array_reduce(
			$items,
			fn( $r, $i ) => array_filter( $r, fn( $c ) => $c !== $i ),
			$array
		);
		return every( array_keys( $array ), 'is_int' ) ? array_values( $result ) : $result;
	}//end without()
}//end if
