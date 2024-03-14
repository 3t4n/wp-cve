<?php
/**
 * Overwrite helpers.
 *
 * @package GoogleAnalytics
 */

if ( false === function_exists( 'wp_json_encode' ) ) {
	/**
	 * Encode a variable into JSON.
	 *
	 * @param mixed $data Variable (usually an array or object) to encode as JSON.
	 *
	 * @return false|string The JSON encoded string, or false if it cannot be encoded.
	 */
	function wp_json_encode( $data ) {
		return json_encode( $data ); // phpcs:ignore
	}
}
