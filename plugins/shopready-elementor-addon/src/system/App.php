<?php
namespace Shop_Ready\system;

final class App
{
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services()
	{


		return [

				// Base public Resource	
			\Shop_Ready\system\base\assets\Register::class,
			\Shop_Ready\base\assets\Assets::class,

				// Base admin settings	
			\Shop_Ready\system\base\Meta::class,
			\Shop_Ready\system\base\dashboard\Page::class,
			\Shop_Ready\system\base\dashboard\Template::class,
			\Shop_Ready\system\base\dashboard\Dashboard::class,
			\Shop_Ready\system\base\dashboard\Notice::class,
			\Shop_Ready\system\base\dashboard\controls\Widgets::class,
			\Shop_Ready\system\base\dashboard\controls\Modules::class,
			\Shop_Ready\system\base\dashboard\controls\Templates::class,
			\Shop_Ready\system\base\dashboard\controls\Api::class,


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