<?php
/**
 * Object cache for Nova Poshta
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
 * Class ObjectCache
 *
 * @package NovaPoshta\Cache;
 */
class ObjectCache extends Cache {

	/**
	 * Set value for cache with key.
	 *
	 * @param string $key    Key name.
	 * @param mixed  $value  Value.
	 * @param int    $expire Expire in seconds.
	 */
	public function set( string $key, $value, int $expire ) {

		wp_cache_set( $key, $value, Main::PLUGIN_SLUG, $expire );
	}

	/**
	 * Get cache value by name
	 *
	 * @param string $key Key name.
	 *
	 * @return mixed
	 */
	public function get( string $key ) {

		return wp_cache_get( $key, Main::PLUGIN_SLUG );
	}

	/**
	 * Delete cache by key name.
	 *
	 * @param string $key Key name.
	 */
	public function delete( string $key ) {

		wp_cache_delete( $key, Main::PLUGIN_SLUG );
	}

	/**
	 * Flush object cache.
	 */
	public function flush() {

		wp_cache_flush();
	}
}
