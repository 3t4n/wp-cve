<?php
/**
 * Watchful debug enabled test.
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
 * Watchful debug enabled test class.
 */
class IsDebugEnabled extends Audit {

	/**
	 * Run the test.
	 *
	 * @return mixed
	 */
	public function run() {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			return $this->response->send_ko();
		}

		return $this->response->send_ok();
	}
}
