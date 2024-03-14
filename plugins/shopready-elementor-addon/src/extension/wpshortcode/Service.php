<?php

namespace Shop_Ready\extension\wpshortcode;

final class Service 
{
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services() 
	{

		return [
				
			// widgets Basic
			countdown\Countdown::class,
			common\Notice::class,
			
		];
	}

	/**
	 * Loop through the classes, initialize them, 
	 * and call the register() method if it exists
	 * @return
	 */
	public static function register_services() 
	{

		self::run_dependency();
		
		foreach ( self::get_services() as $class ) {

			$service = self::instantiate( $class );
		
			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}

		}
	}

	/**
	 * Initialize the class
	 * @param  class $class    class from the services array
	 * @return class instance  new instance of the class
	 */
	private static function instantiate( $class )
	{
		$service = new $class();

		return $service;
	}

	private static function run_dependency(){

	    // include funtion file  
		foreach (array('config_function') as $file) {
			include( plugin_dir_path( __FILE__ ) . 'helper/'.$file.'.php');
		}
	}
}