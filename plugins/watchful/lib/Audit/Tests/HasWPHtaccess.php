<?php
/**
 * Watchful htaccess test.
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
 * Watchful htaccess test class.
 */
class HasWPHtaccess extends Audit {

	/**
	 * Run the test.
	 *
	 * @return mixed
	 */
	public function run() {
		$file        = '.htaccess';
		$server_name = isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : '';
		if ( preg_match( '#IIS/([\d.]*)#', $server_name ) ) {
			$file = 'web.config'; // IIS.
		}

		if ( ! file_exists( ABSPATH . '/' . $file ) ) {
			return $this->response->send_ko();
		}

		return $this->response->send_ok();
	}
}
