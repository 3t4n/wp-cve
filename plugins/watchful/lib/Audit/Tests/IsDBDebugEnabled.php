<?php
/**
 * Watchful DB debug enabled test.
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
 * Watchful DB debug enabled test class.
 */
class IsDBDebugEnabled extends Audit {

	/**
	 * Run the test.
	 *
	 * @return mixed
	 */
	public function run() {
		global $wpdb;

		return $this->check_value( $wpdb->show_errors, 0 );
	}
}
