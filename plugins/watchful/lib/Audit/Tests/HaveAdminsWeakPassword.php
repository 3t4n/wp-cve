<?php
/**
 * Watchful admin brute force test.
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

class HaveAdminsWeakPassword extends Audit {

	/**
	 * Run the test.
	 *
	 * @return \stdClass
	 */
	public function run() {
		$weak_admins = $this->check_passwords();

		if ( count( $weak_admins ) ) {
			return $this->response->send_ko( $weak_admins );
		}

		return $this->response->send_ok();
	}

	/**
	 * Check admin's passwords with the password list
	 *
	 * @return array of object
	 */
	private function check_passwords() {
		$admins = get_users( array( 'role' => 'administrator' ) );

		$weak_admins = array();

		foreach ( $admins as $admin ) {
			foreach ( $this->passwords as $password ) {
				if ( wp_check_password( $password, $admin->data->user_pass ) ) {
					$t           = new \stdClass();
					$t->login    = $admin->data->user_login;
					$t->password = $password;

					$weak_admins[] = $t;
				}
			}
		}

		return $weak_admins;
	}
}
