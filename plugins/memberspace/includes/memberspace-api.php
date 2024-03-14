<?php

/**
 * This class is used to communicate with the MemberSpace API.
 *
 */

class MemberSpace_Api {

	// Loads the customer site configuration from the MemberSpace server
	public static function get_site_config() {
		global $wp_version;
		$origin = parse_url( get_home_url(), PHP_URL_SCHEME ) . '://' . parse_url( get_home_url(), PHP_URL_HOST );
		$headers = array();

		// We are defining this variable for easier string interpolation of a constant in the $uri string.
		// PHP will only interpolate the string correctly if it has a {$var}, not {CONSTANT} because of the $
		$plugin_version = MEMBERSPACE_PLUGIN_VERSION;


		$uri = MemberSpace::API_BASE_URI . "/sites/current/configuration.json?wordpress_plugin_version={$plugin_version}";
		$headers = array(
			'Origin' => $origin,
			'X-MS-Website-Host' => $origin,
			'X-MS-CMS' => 'WordPress',
			'X-MS-Plugin-Version' => $plugin_version,
			'X-MS-PHP-Version' => PHP_VERSION,
			'X-MS-WP-Version' => $wp_version
		);

		$response = wp_remote_get( $uri, array( 'headers' => $headers ) );

		return array(
			'request_uri' => $uri,
			'request_headers' => $headers,
			'response_body' => json_decode( wp_remote_retrieve_body( $response ) ),
			'response_code' => wp_remote_retrieve_response_code( $response )
		);
	}

	private static function get_client_ip() {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = sanitize_text_field( $_SERVER['HTTP_CLIENT_IP'] ); // Shared IP
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = sanitize_text_field( $_SERVER['HTTP_X_FORWARDED_FOR'] ); // Proxied IP
		} else {
			$ip = sanitize_text_field( $_SERVER['REMOTE_ADDR'] ); // Server IP
		}

		return $ip;
	}
}
