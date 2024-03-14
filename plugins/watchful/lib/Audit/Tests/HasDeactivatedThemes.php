<?php
/**
 * Watchful deactivated themes test.
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
 * Watchful deactivated themes test class.
 */
class HasDeactivatedThemes extends Audit {

	/**
	 * Run the test.
	 *
	 * @return mixed
	 */
	public function run() {
		$all_themes = wp_get_themes();

		if ( 2 === ( count( $all_themes ) && ! is_child_theme() ) || count( $all_themes ) > 1 ) {
			return $this->response->send_ko( $this->get_inactive_theme( $all_themes ) );
		}

		return $this->response->send_ok();
	}

	/**
	 * Get the list of inactive themes
	 *
	 * @param array $theme_list List of themes.
	 * @return array of objects
	 */
	private function get_inactive_theme( $theme_list ) {
		$active_theme = wp_get_theme();
		$inactive     = array();

		foreach ( $theme_list as $key => $item ) {
			if ( $item->Name === $active_theme->Name ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName
				continue;
			}

			$t       = new \stdClass();
			$t->name = $item->Name; // phpcs:ignore WordPress.NamingConventions.ValidVariableName

			$inactive[] = $t;
		}

		return $inactive;
	}
}
