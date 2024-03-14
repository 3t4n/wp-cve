<?php
/**
 * Watchful brute force SQL test.
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

class HasDbWeakPassword extends Audit {

    /**
     * @return \stdClass
     */
	public function run() {
		$password = DB_PASSWORD;
		if ( ! $password || $this->is_password_weak( $password ) ) {
			return $this->response->send_ko( $password );
		}

		return $this->response->send_ok();
	}

	/**
	 * Check the given password with the list
	 *
	 * @param string $db_password The DB password.
	 *
	 * @return bool
	 */
	private function is_password_weak( $db_password ) {
        if (in_array($db_password, $this->passwords, true)) {
            return true;
        }

		return false;
	}
}
