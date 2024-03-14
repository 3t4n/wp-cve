<?php
/**
 * The admin helper functionality of the plugin.
 *
 * @package    woo-product-slider
 * @subpackage woo-product-slider/admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.


if ( ! function_exists( 'spwps_array_search' ) ) {
	/**
	 * Array search key & value
	 *
	 * @param  mixed $array search array or not.
	 * @param  mixed $key array key.
	 * @param  mixed $value value.
	 * @return statement
	 */
	function spwps_array_search( $array, $key, $value ) {

		$results = array();

		if ( is_array( $array ) ) {
			if ( isset( $array[ $key ] ) && $array[ $key ] == $value ) {
				$results[] = $array;
			}

			foreach ( $array as $sub_array ) {
				$results = array_merge( $results, spwps_array_search( $sub_array, $key, $value ) );
			}
		}

		return $results;

	}
}


if ( ! function_exists( 'spwps_timeout' ) ) {
	/**
	 * Between Microtime
	 *
	 * @param  mixed $timenow current time.
	 * @param  mixed $starttime start time.
	 * @param  mixed $timeout time out.
	 * @return statement
	 */
	function spwps_timeout( $timenow, $starttime, $timeout = 30 ) {
		return ( ( $timenow - $starttime ) < $timeout ) ? true : false;
	}
}


if ( ! function_exists( 'spwps_wp_editor_api' ) ) {
	/**
	 *
	 * Check for wp editor api
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spwps_wp_editor_api() {
		global $wp_version;
		return version_compare( $wp_version, '4.8', '>=' );
	}
}
