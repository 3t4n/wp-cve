<?php
/**
 * Trait that abstracts the Singleton design pattern.
 *
 * @package UpStream\Traits
 */

namespace UpStream\Traits;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait that abstracts the Singleton design pattern.
 *
 * @package     UpStream
 * @subpackage  Traits
 * @author      UpStream <https://upstreamplugin.com>
 * @copyright   Copyright (c) 2018 UpStream Project Management
 * @license     GPL-3
 * @since       1.11.0
 */
trait Singleton {

	/**
	 * The singleton class's instance.
	 *
	 * @var     \ReflectionClass
	 *
	 * @since   1.11.0
	 * @access  private
	 * @static
	 */
	private static $instance = null;

	/**
	 * Retrieve the singleton instance.
	 * If the singleton it's not loaded, it will be initialized first.
	 *
	 * @since   1.11.0
	 * @static
	 *
	 * @return  \ReflectionClass
	 */
	public static function getInstance() { // phpcs:ignore
		// Ensure the singleton is loaded.
		self::instantiate();

		return self::$instance;
	}

	/**
	 * Initializes the singleton if it's not loaded yet.
	 *
	 * @since   1.11.0
	 * @static
	 * @final
	 *
	 * @uses    \ReflectionClass
	 */
	final public static function instantiate() {
		if ( empty( self::$instance ) ) {
			$reflection     = new \ReflectionClass( __CLASS__ );
			self::$instance = $reflection->newInstanceArgs( func_get_args() );
		}
	}

	/**
	 * Prevent the class instance being serialized.
	 *
	 * @since   1.11.0
	 * @final
	 *
	 * @throws  \Exception Exception.
	 */
	final public function __sleep() {
		throw new \Exception( 'You cannot serialize a singleton.' );
	}

	/**
	 * Prevent the class instance being unserialized.
	 *
	 * @since   1.11.0
	 * @final
	 *
	 * @throws  \Exception Exception.
	 */
	final public function __wakeup() {
		throw new \Exception( 'You cannot unserialize a singleton.' );
	}

	/**
	 * Prevent the class instance being cloned.
	 *
	 * @since   1.11.0
	 * @final
	 */
	final public function __clone() {
		// Do nothing.
	}
}
