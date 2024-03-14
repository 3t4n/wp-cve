<?php
/**
 * Watchful login info test.
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
 * Watchful login info test class.
 */
class HasUnnecessaryLoginInfo extends Audit {

	/**
	 * Run the test.
	 *
	 * @return mixed
	 */
	public function run() {
		$params = array(
			'log' => 'sn-test_3453344355',
			'pwd' => 'sn-test_2344323335',
		);
		$url    = get_bloginfo( 'wpurl' ) . '/wp-login.php';

		$body = $this->get_login_body( $url, $params );

		if ( stristr( $body, 'invalid username' ) !== false ) {
			return $this->response->send_ko();
		}

		return $this->response->send_ok();
	}

	/**
	 * Get the html body after a failed login attempt.
	 *
	 * @param string $url  The URL of the page to test.
	 * @param array  $user The user (login and password).
	 *
	 * @return mixed
	 */
	private function get_login_body( $url, $user ) {
		if ( ! class_exists( 'WP_Http' ) ) {
			require ABSPATH . WPINC . '/class-http.php';
		}

		$http     = new \WP_Http();
		$response = (array) $http->request(
			$url,
			array(
				'method' => 'POST',
				'body'   => $user,
			)
		);

		return wp_remote_retrieve_body( $response );
	}
}
