<?php
/**
 * Watchful DB prefix test.
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
 * Wathful DB prefix test class.
 */
class HasDBPrefix extends Audit {

	/**
	 * Run the test.
	 *
	 * @return mixed
	 */
	public function run() {
		$prefix = array( 'wp', 'wordpress', 'wp3' );

		global $wpdb;

		// Remove the "_".
		$current = substr( $wpdb->prefix, 0, -1 );

		if ( ! in_array( $current, $prefix, true ) ) {
			return $this->response->send_ok();
		}

		return $this->response->send_ko( $current );
	}
}
