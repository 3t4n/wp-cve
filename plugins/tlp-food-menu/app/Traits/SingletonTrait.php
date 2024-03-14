<?php
/**
 * Singleton Trait.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Traits;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Singleton Trait.
 */
trait SingletonTrait {
	/**
	 * Store the singleton object.
	 *
	 * @var boolean
	 */
	private static $singleton = false;

	/**
	 * Create an inaccessible constructor.
	 */
	private function __construct() {
		$this->init();
	}

	/**
	 * Prevent cloning.
	 */
	private function __clone() {
	}

	/**
	 * Prevent unserializing.
	 */
	public function __wakeup() {
	}

	/**
	 * Fetch an instance of the class.
	 */
	public static function get_instance() {
		if ( false === self::$singleton ) {
			self::$singleton = new self();
		}

		return self::$singleton;
	}
}
