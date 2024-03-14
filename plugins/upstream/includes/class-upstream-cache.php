<?php
/**
 * Upstream cache.
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Upstream cache class.
 */
class Upstream_Cache {

	/**
	 * Instance class.
	 *
	 * @var object $instance
	 */
	protected static $instance;

	/**
	 * Cache data.
	 *
	 * @var array $cache
	 */
	protected $cache = array();

	/**
	 * Cache setter.
	 *
	 * @param mixed $key Cache key.
	 * @param mixed $value Cache value.
	 */
	public function set( $key, $value ) {
		$this->cache[ $key ] = $value;
	}

	/**
	 * Cache getter.
	 *
	 * @param mixed $key Cache key.
	 * @return mixed Cache value.
	 */
	public function get( $key ) {
		if ( isset( $this->cache[ $key ] ) ) {
			return $this->cache[ $key ];
		}

		return false;
	}

	/**
	 * Cache reseter.
	 */
	public function reset() {
		$this->cache = array();
	}

	/**
	 * Instance getter.
	 */
	public static function get_instance() {
		if ( empty( static::$instance ) ) {
			$instance         = new self();
			static::$instance = $instance;
		}

		return static::$instance;
	}
}

/**
 * Cache metadata getter.
 */
function upstream_cache_get_metadata() {
	$str  = 'upstream_cache_get_metadata';
	$args = func_get_args();

	for ( $i = 0; $i < func_num_args(); $i++ ) {
		$str .= $args[ $i ];
	}

	$cache = Upstream_Cache::get_instance();
	$res   = $cache->get( $str );

	if ( false !== $res ) {
		return $res;
	}

	$cache->set( $str, $res );

	return call_user_func_array( 'get_metadata', $args );
}

/**
 * Cache post meta getter.
 */
function upstream_cache_get_post_meta() {
	$str  = 'upstream_cache_get_post_meta';
	$args = func_get_args();

	for ( $i = 0; $i < func_num_args(); $i++ ) {
		$str .= $args[ $i ];
	}

	$cache = Upstream_Cache::get_instance();
	$res   = $cache->get( $str );

	if ( false !== $res ) {
		return $res;
	}

	$cache->set( $str, $res );

	return call_user_func_array( 'get_post_meta', $args );
}
