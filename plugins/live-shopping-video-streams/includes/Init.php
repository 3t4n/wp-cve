<?php

/**
 * The WP Integration plugin for Channelize.io platform
 * Author:            Channelize.io
 * Author URI:        https://channelize.io/
 * Text Domain:       channelize
 * 
 * 
 * 
 *
 */

namespace Includes;

defined('ABSPATH') || exit;

require_once('chls_functions.php');

final class Init
{
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services()
	{
		return [
			Pages\CHLSAdmin::class,
			Base\CHLSSettingsLinks::class,
			CHLSChannelizeAjax\CHLSChannelizeAjax::class,
			CHLSRestApis\CHLSRestApis::class
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
