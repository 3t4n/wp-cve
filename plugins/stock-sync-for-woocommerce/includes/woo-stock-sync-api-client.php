<?php

/**
 * Prevent direct access to the script.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Automattic\WooCommerce\Client;

class Woo_Stock_Sync_Api_Client {
	/**
	 * Get API client
	 */
	public static function create( $api_url, $api_key, $api_secret ) {
		return new Client(
			$api_url,
			$api_key,
			$api_secret,
			array(
				'wp_api' => true,
				'version' => 'wc/v2',
				'verify_ssl' => false,
				'query_string_auth' => true,
				'timeout' => apply_filters( 'woo_stock_sync_api_timeout', 120 ),
			)
		);
	}
}
