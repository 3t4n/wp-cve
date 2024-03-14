<?php
/**
 * WcGetnet Authorization.
 *
 * @package Wc Getnet
 */

declare(strict_types=1);

namespace WcGetnet\Services;

use WcGetnet\Services\WcGetnetApi;

class WcGetnetAuth {

	/**
	 * Generate base_64 auth.
	 *
	 * @return string
	 */
	public static function create_auth_base_64() {
		$wc_credentials = new WcGetnetApi();

		return base64_encode( $wc_credentials->client_id . ':' . $wc_credentials->client_secret );
	}

	public static function get_seller_id() {
		$wc_credentials = new WcGetnetApi();

		return $wc_credentials->seller_id;
	}
}
