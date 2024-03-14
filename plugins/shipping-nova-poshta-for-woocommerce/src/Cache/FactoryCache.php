<?php
/**
 * Cache factory
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
 * Class FactoryCache
 *
 * @package NovaPoshta\Cache
 */
class FactoryCache {

	/**
	 * Object Cache
	 *
	 * @var ObjectCache
	 */
	private $object_cache;

	/**
	 * Transient Cache
	 *
	 * @var TransientCache
	 */
	private $transient_cache;

	/**
	 * FactoryCache constructor.
	 *
	 * @param TransientCache $transient_cache Transient cache.
	 * @param ObjectCache    $object_cache    Object cache.
	 */
	public function __construct( TransientCache $transient_cache, ObjectCache $object_cache ) {

		$this->object_cache    = $object_cache;
		$this->transient_cache = $transient_cache;
	}

	/**
	 * Transient Cache
	 *
	 * @return Cache
	 */
	public function transient(): Cache {

		return $this->transient_cache;
	}

	/**
	 * Object Cache
	 *
	 * @return Cache
	 */
	public function object(): Cache {

		return $this->object_cache;
	}
}
