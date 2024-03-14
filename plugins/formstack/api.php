<?php
/**
 * Formstack API class.
 *
 * @package Formstack
 * @author Formstack
 */

/**
 * A quick interface to the Formstack API. Documentation to the API can be
 * found here: https://support.formstack.com/index.php?pg=kb.book&id=3
 *
 * I'm using JSON rather than the serialized PHP because I prefer the object
 * notation and wanted to avoid casting.
 *
 * @author michael
 */
class Formstack_API {

	const API_URL = 'https://www.formstack.com/api';

	/**
	 * Make a Formstack API request and decode the response.
	 *
	 * @param string $api_key The Formstack API key.
	 * @param string $method  The API web method.
	 * @param array  $args    The parameters for the API request.
	 * @return array
	 */
	public static function request( $api_key, $method, $args = array() ) {

	    $args['api_key'] = $api_key;
	    $args['type']    = 'json';

		$url = self::API_URL . '/' . $method;

		$res = wp_remote_get( "{$url}?" . http_build_query( $args ), array( 'timeout' => 30 ) );

		if ( is_wp_error( $res ) || 200 !== wp_remote_retrieve_response_code( $res ) ) {
			return array();
		}

		return json_decode( wp_remote_retrieve_body( $res ) );
	}
}
