<?php

namespace Shop_Ready\extension\elegrid;

define('SHOP_READY_GRID_PATH', plugin_dir_path(__FILE__));
define('SHOP_READY_GRID_MODULE_URL', plugin_dir_url(__FILE__));
final class Service
{
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services()
	{

		return [
			hooks\product\Grid_Structure::class,
			hooks\product\Grid_Flip_Center_Structure::class,
		];
	}

	/**
	 * Loop through the classes, initialize them, 
	 * and call the register() method if it exists
	 * @return
	 */
	public static function register_services()
	{

		foreach (self::get_services() as $class) {

			$service = self::instantiate($class);

			if (method_exists($service, 'register')) {
				$service->register();
			}
		}
	}

	/**
	 * Initialize the class
	 * @param  class $class    class from the services array
	 * @return class instance  new instance of the class
	 */
	private static function instantiate($class)
	{
		$service = new $class();

			return $service;
	}
}
