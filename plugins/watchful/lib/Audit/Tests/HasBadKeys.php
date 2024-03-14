<?php
/**
 * Watchful bad keys test.
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
 * Watchful bad keys test class.
 */
class HasBadKeys extends Audit {

	/**
	 * Run the test.
	 *
	 * @return mixed
	 */
	public function run() {
		$keys     = array(
			'AUTH_KEY',
			'SECURE_AUTH_KEY',
			'LOGGED_IN_KEY',
			'NONCE_KEY',
			'AUTH_SALT',
			'SECURE_AUTH_SALT',
			'LOGGED_IN_SALT',
			'NONCE_SALT',
		);
		$bad_keys = $this->get_bad_keys( $keys );

		if ( count( $bad_keys ) ) {
			return $this->response->send_ko( $bad_keys );
		}

		return $this->response->send_ok();
	}

	/**
	 * Get all invalid keys
	 *
	 * @param array $keys The keys.
	 *
	 * @return array
	 */
	private function get_bad_keys( $keys ) {
		$bad_keys = array();

		foreach ( $keys as $key ) {
			if (!defined($key)) {
				continue;
			}
			$constant = constant($key);

			if ( empty( $constant ) || trim( $constant ) === 'put your unique phrase here' || strlen( $constant ) < 32 ) {
				$t          = new \stdClass();
				$t->key     = $key;
				$t->value   = base64_encode( $constant );
				$t->encoder = 'base64';

				$bad_keys[] = $t;
			}
		}

		return $bad_keys;
	}
}
