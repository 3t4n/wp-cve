<?php
/**
 * Watchful themes to update test.
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
 * Watchful themes to update test class.
 */
class HasThemesToUpdate extends Audit {

	/**
	 * Run the test.
	 *
	 * @return mixed
	 */
	public function run() {
		$list = $this->get_update_list();

		if ( ! $list || ! count( $list ) ) {
			return $this->response->send_ok();
		}

		return $this->response->send_ko( $list );
	}


	/**
	 * Get list of themes to update.
	 *
	 * @return array
	 */
	private function get_update_list() {
		$status = get_site_transient( 'update_themes' );

		if ( false === $status ) {
			wp_update_themes();
			set_transient( 'update_themes', $status );
		}

		$status    = get_site_transient( 'update_themes' );
		$to_update = array();

		if ( empty( $status ) ) {
			return null;
		}

		foreach ( $status->checked as $key => $version ) {
			if ( ! isset( $status->response[ $key ]['new_version'] ) || $version === $status->response[ $key ]['new_version'] ) {
				continue;
			}

			$t          = new \stdClass();
			$t->name    = $key;
			$t->current = $version;
			$t->latest  = $status->response[ $key ]['new_version'];

			$to_update[] = $t;
		}

		return $to_update;
	}
}
