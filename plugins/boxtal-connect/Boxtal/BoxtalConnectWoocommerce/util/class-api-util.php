<?php
/**
 * Contains code for api util class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Util
 */

namespace Boxtal\BoxtalConnectWoocommerce\Util;

use Boxtal\BoxtalConnectWoocommerce\Plugin;
use Boxtal\BoxtalConnectWoocommerce\Branding;

/**
 * Api util class.
 *
 * Helper to manage API responses.
 */
class Api_Util {

	/**
	 * API request validation.
	 *
	 * @param integer $code http code.
	 * @param mixed   $body to send along response.
	 * @void
	 */
	public static function send_api_response( $code, $body = null ) {
		header( 'X-Version: ' . Branding::$plugin_version );
		http_response_code( $code );
		if ( null !== $body ) {
            // phpcs:ignore
			echo Auth_Util::encrypt_body( $body );
		}
		die();
	}
}
