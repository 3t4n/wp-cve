<?php

/**
 * UAEL Module Base.
 *
 * @package UAEL
 */

namespace Payamito\Woocommerce\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Module Base
 *
 * @since 0.0.1
 */
abstract class Base
{
	/**
	 * @var instances
	 */
	protected static $instances;

	public $modules = [];

	/**
	 * Get Name
	 *
	 * @since 1.1.0
	 */
	abstract protected function get_name();

	/**
	 * include
	 *
	 * @since 1.1.0
	 */
	abstract protected function include();

	/**
	 * active module
	 *
	 * @since 1.1.0
	 */
	abstract public function deactivate();

	/**
	 * active module
	 *
	 * @since 1.1.0
	 */
	abstract protected function activate();

	/**
	 * inite class
	 *
	 * @since 1.1.0
	 */
	abstract protected function class();

	/**
	 * register  module
	 *
	 * @since 1.1.0
	 */
	protected function register_module( $module )
	{
		$this->modules = $module;
	}

	public function get_modules()
	{
		return $this->modules;
	}

	/**
	 * Class name to Call
	 *
	 * @since 1.1.0
	 */
	public static function class_name()
	{
		return get_called_class();
	}

	/**
	 * Class instance
	 *
	 * @return static
	 * @since  1.1.0
	 */
	public static function get_instance()
	{
		$class_name = static::class_name();

		if ( empty( static::$instances[ $class_name ] ) ) {
			static::$instances[ $class_name ] = new static();
		}

		return static::$instances[ $class_name ];
	}

	/**
	 * Constructor
	 */
	public function __construct() {}

}
