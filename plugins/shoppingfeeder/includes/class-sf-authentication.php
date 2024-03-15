<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Makes sure the plugin is defined before trying to use it
if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

class SF_Authentication {
	/**
	 * @var string
	 */
	public $version = '1.0.0';

	const RANDOM_STRING = 'The mice all ate cheese together.';
	const MAX_TIMEOUT = 300; //5 minutes

	public static function auth( array $headers, $incoming_scheme, $incoming_method ) {
		//check the API key
		$incoming_api_key = '';
		$incoming_auth_timestamp = strtotime( '1970-00-00' );
		$incoming_signature = '';
		foreach ( $headers as $key => $value ) {
			if ( strtolower( 'X-SFApiKey' ) == strtolower( $key )) {
				$incoming_api_key = $value;
			}
			if ( strtolower( 'X-SFTimestamp' ) == strtolower( $key )) {
				$incoming_auth_timestamp = $value;
			}
			if ( strtolower( 'X-SFSignature' ) == strtolower( $key )) {
				$incoming_signature = $value;
			}
		}

		//check the timestamp
		if ( time() - $incoming_auth_timestamp <= self::MAX_TIMEOUT )
		{
			if ( is_multisite() && is_plugin_active_for_network( plugin_basename( __FILE__ ) ) )
			{
				$local_api_key = get_site_option('shoppingfeeder_api_key');
				$local_api_secret = get_site_option('shoppingfeeder_api_secret');
			}
			else
			{
				$local_api_key = get_option('shoppingfeeder_api_key');
				$local_api_secret = get_option('shoppingfeeder_api_secret');
			}

			if ( $local_api_key == $incoming_api_key ) {

				$string_to_sign = $incoming_method . "\n" .
					$incoming_auth_timestamp . "\n" .
					self::RANDOM_STRING;

				if (function_exists('hash_hmac'))
				{
					$signature = hash_hmac('sha256', $string_to_sign, $local_api_secret);
				}
				elseif (function_exists('mhash'))
				{
					$signature = bin2hex(mhash(MHASH_SHA256, $string_to_sign, $local_api_secret));
				}

				if ( $incoming_signature == $signature ) {
					return true;
				} else {
					return 'Authentication failed due to invalid credentials. Signature requested: '.$incoming_signature;
				}
			} else {
				return 'Authentication failed due to invalid API key. Key requested: '.$incoming_api_key;
			}
		} else {
			return 'Authentication failed due to timeout being exceeded. Request time: '.$incoming_auth_timestamp.' Server time: '.time();
		}
	}

	public static function get_api_keys() {
		if ( is_multisite() && is_plugin_active_for_network( plugin_basename( __FILE__ ) ) )
		{
			$local_api_key = get_site_option('shoppingfeeder_api_key');
			$local_api_secret = get_site_option('shoppingfeeder_api_secret');
		}
		else
		{
			$local_api_key = get_option('shoppingfeeder_api_key');
			$local_api_secret = get_option('shoppingfeeder_api_secret');
		}

		return array(
			'api_key' => $local_api_key,
			'api_secret' => $local_api_secret
		);
	}

}