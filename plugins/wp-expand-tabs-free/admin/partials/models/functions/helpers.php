<?php
/**
 * The admin helper functions.
 *
 * @since        2.0.1
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/admin/partials
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! function_exists( 'wptabspro_array_search' ) ) {
	/**
	 *
	 * Array search key & value
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param array  $array search array.
	 * @param string $key the array key.
	 * @param mixed  $value The array value.
	 *
	 * @return $results
	 */
	function wptabspro_array_search( $array, $key, $value ) {

		$results = array();

		if ( is_array( $array ) ) {
			if ( isset( $array[ $key ] ) && $array[ $key ] == $value ) {
				$results[] = $array;
			}

			foreach ( $array as $sub_array ) {
				$results = array_merge( $results, wptabspro_array_search( $sub_array, $key, $value ) );
			}
		}

		return $results;

	}
}

if ( ! function_exists( 'wptabspro_microtime' ) ) {
	/**
	 * Between microtime.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $timenow Current time.
	 * @param integer $starttime Starting time.
	 * @param integer $timeout Time out.
	 * @return boolean
	 */
	function wptabspro_timeout( $timenow, $starttime, $timeout = 30 ) {

		return ( ( $timenow - $starttime ) < $timeout ) ? true : false;
	}
}

if ( ! function_exists( 'wptabspro_wp_editor_api' ) ) {
	/**
	 *
	 * Check for wp editor api
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wptabspro_wp_editor_api() {

		global $wp_version;
		return version_compare( $wp_version, '4.8', '>=' );
	}
}

