<?php
/**
 * Watchful script debug enabled test.
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
 * Watchful script debug enabled test class.
 */
class IsScriptDebugEnabled extends Audit {

	/**
	 * Run the test.
	 *
	 * @return mixed
	 */
	public function run() {
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			return $this->response->send_ko();
		}

		return $this->response->send_ok();
	}
}
