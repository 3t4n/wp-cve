<?php
namespace Shop_Ready\extension\templates;

define('SHOP_READY_TEMPLATES_PATH', plugin_dir_path(__FILE__));
define('SHOP_READY_TEMPLATES_MODULE_URL', plugin_dir_url(__FILE__));

final class Service
{
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services()
	{

		return [

			hooks\Custom_Route::class,
			hooks\Common::class,
				// product
			presets\Common::class,
			presets\Presets_Loader::class,
			hooks\product\Single::class,
			hooks\product\Layout::class,
				// // // shop  
			hooks\shop\Shop::class,
			hooks\shop\Layout::class,
			hooks\shop\Archive_Layout::class,
			hooks\shop\Shop_Archive::class,
				// // checkout comp
			hooks\checkout\Layout::class,
				// // cart comp
			hooks\cart\Layout::class,
				// //Order Thankyou
			hooks\order\Order::class,
			hooks\order\Layout::class,
				// //account
			hooks\account\Login_Register_Layout::class,
			hooks\account\Login_Register::class,


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