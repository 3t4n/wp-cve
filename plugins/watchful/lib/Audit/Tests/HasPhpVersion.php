<?php
/**
 * Watchful PHP version test.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful\Audit\Tests;

use Watchful\Audit\Audit;

/**
 * Watchful PHP version test class.
 */
class HasPhpVersion extends Audit {

	/**
	 * Run the test.
	 *
	 * @return mixed
	 */
	public function run() {
		$headers     = $this->get_headers( home_url() );
		$bad_headers = array();

		foreach ( $headers as $key => $header ) {
            if (is_array($header)) {
                $header = implode(', ', $header);
            }
			if (
				( 'server' === $key || 'x-powered-by' === $key ) &&
				stripos( $header, phpversion() ) !== false
			) {
				$bad_headers[ $key ] = $header;
			}
		}

		if ( ! empty( $bad_headers ) ) {
			return $this->response->send_ko( $bad_headers );
		}

		return $this->response->send_ok();
	}

	/**
	 * Get the http header from the given url
	 *
	 * @param string $url The URL to retrieve headers from.
	 *
	 * @return mixed
	 */
	private function get_headers( $url ) {
		if ( ! class_exists( 'WP_Http' ) ) {
			require ABSPATH . WPINC . '/class-http.php';
		}

		$http     = new \WP_Http();
		$response = (array) $http->request( $url );

		return wp_remote_retrieve_headers( $response );
	}
}
