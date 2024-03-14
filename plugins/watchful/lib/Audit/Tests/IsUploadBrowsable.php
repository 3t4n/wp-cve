<?php
/**
 * Watchful upload browsable test.
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
 * Watchful upload browsable test class.
 */
class IsUploadBrowsable extends Audit {

	/**
	 * Run the test.
	 *
	 * @return mixed
	 */
	public function run() {
		$upload_dir = wp_upload_dir();

		$args = array(
			'method'      => 'GET',
			'timeout'     => 5,
			'redirection' => 0,
			'httpversion' => 1.0,
			'blocking'    => true,
			'headers'     => array(),
			'body'        => null,
			'cookies'     => array(),
		);

		$response = wp_remote_get( rtrim( $upload_dir['baseurl'], '/' ) . '/?nocache=' . wp_rand(), $args );
		$body     = wp_remote_retrieve_body( $response );
		$code     = wp_remote_retrieve_response_code( $response );

		if ( '200' === $code && stripos( $body, 'index' ) !== false ) {
			return $this->response->send_ko( $upload_dir['baseurl'] . '/' );
		}

		return $this->response->send_ok();
	}
}
