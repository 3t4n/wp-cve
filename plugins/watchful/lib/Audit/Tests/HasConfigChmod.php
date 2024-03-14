<?php
/**
 * Watchful config chmod test.
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
 * Watchful config chmod test class.
 */
class HasConfigChmod extends Audit {

	/**
	 * Run the test.
	 *
	 * @return mixed
	 */
	public function run() {
		$last      = '0';
		$file_name = 'wp-config.php';

		$file = file_exists( ABSPATH . $file_name ) ? ABSPATH . $file_name : ABSPATH . '/../' . $file_name;

		$mode = substr( sprintf( '%o', fileperms( $file ) ), -4 );

		if ( substr( $mode, -1 ) === $last ) {
			return $this->response->send_ok();
		}

		return $this->response->send_ko( $mode );
	}
}
