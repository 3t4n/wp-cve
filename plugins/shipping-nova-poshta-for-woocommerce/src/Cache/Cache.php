<?php
/**
 * Abstract Cache for Nova Poshta
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Cache;

/**
 * Class Cache
 *
 * @package NovaPoshta\Cache;
 */
abstract class Cache {

	/**
	 * Set value for cache with key.
	 *
	 * @param string $key    Key name.
	 * @param mixed  $value  Value.
	 * @param int    $expire Expire in seconds.
	 */
	abstract public function set( string $key, $value, int $expire );

	/**
	 * Get cache value by name
	 *
	 * @param string $key Key name.
	 *
	 * @return mixed
	 */
	abstract public function get( string $key );

	/**
	 * Delete cache by key name.
	 *
	 * @param string $key Key name.
	 */
	abstract public function delete( string $key );

	/**
	 * Flush all caches.
	 */
	abstract public function flush();
}
