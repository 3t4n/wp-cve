<?php
/**
 * Watchful deactivated plugins test.
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
 * Watchful deactivated plugins test class.
 */
class HasDeactivatedPlugins extends Audit {

    public function __construct()
    {
        parent::__construct();
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
    }

    /**
	 * Run the test.
	 *
	 * @return mixed
	 */
	public function run() {
		$all_plugins    = get_plugins();
		$active_plugins = get_option( 'active_plugins', array() );

		if ( count( $all_plugins ) === count( $active_plugins ) ) {
			return $this->response->send_ok();
		}

		return $this->response->send_ko( $this->get_inactive_plugins( $all_plugins, $active_plugins ) );
	}

	/**
	 * Get the list of inactive plugins
	 *
	 * @param array $plugins        List of plugins.
	 * @param array $active_plugins List of active plugins.
	 *
	 * @return array of objects
	 */
	private function get_inactive_plugins( $plugins, $active_plugins ) {
		$inactive = array();

		foreach ( $plugins as $key => $item ) {
			if ( in_array( $key, $active_plugins, true ) ) {
				continue;
			}

			$t       = new \stdClass();
			$t->name = $item['Name'];
			$t->key  = $key;

			$inactive[] = $t;
		}

		return $inactive;
	}
}
