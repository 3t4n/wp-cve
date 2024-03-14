<?php
/**
 * Transient cache for Nova Poshta
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Cache;

use NovaPoshta\Main;

/**
 * Class TransientCache
 *
 * @package NovaPoshta\Cache;
 */
class TransientCache extends Cache {

	/**
	 * Set value for cache with key.
	 *
	 * @param string $key    Key name.
	 * @param mixed  $value  Value.
	 * @param int    $expire Expire in seconds.
	 */
	public function set( string $key, $value, int $expire ) {

		set_transient( Main::PLUGIN_SLUG . '-' . $key, $value, $expire );
	}

	/**
	 * Get cache by key name.
	 *
	 * @param string $key Key name.
	 *
	 * @return mixed
	 */
	public function get( string $key ) {

		return get_transient( Main::PLUGIN_SLUG . '-' . $key );
	}

	/**
	 * Delete cache by key name.
	 *
	 * @param string $key Key name.
	 */
	public function delete( string $key ) {

		delete_transient( Main::PLUGIN_SLUG . '-' . $key );
	}

	/**
	 * Flush cache.
	 */
	public function flush() {

		global $wpdb;
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '\_transient\_" . Main::PLUGIN_SLUG . "%'" ); // phpcs:ignore
	}
}
