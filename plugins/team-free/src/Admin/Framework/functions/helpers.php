<?php
/**
 *  Framework helpers file.
 *
 * @package    team-free
 * @subpackage team-free/framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! function_exists( 'spf_array_search' ) ) {
	/**
	 * Array search key & value
	 *
	 * @param  mixed $array main array.
	 * @param  mixed $key key.
	 * @param  mixed $value val.
	 * @return array
	 */
	function spf_array_search( $array, $key, $value ) {

		$results = array();

		if ( is_array( $array ) ) {
			if ( isset( $array[ $key ] ) && $array[ $key ] === $value ) {
				$results[] = $array;
			}

			foreach ( $array as $sub_array ) {
				$results = array_merge( $results, spf_array_search( $sub_array, $key, $value ) );
			}
		}

		return $results;

	}
}

if ( ! function_exists( 'sptp_timeout' ) ) {
	/**
	 * Between Microtime
	 *
	 * @param  string $timenow now.
	 * @param  string $starttime start.
	 * @param  int    $timeout timeout.
	 * @return bool
	 */
	function sptp_timeout( $timenow, $starttime, $timeout = 30 ) {

		return ( ( $timenow - $starttime ) < $timeout ) ? true : false;
	}
}


