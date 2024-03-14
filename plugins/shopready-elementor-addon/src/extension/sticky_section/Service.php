<?php
namespace Shop_Ready\extension\sticky_section;
use Shop_Ready\base\Module;

final class Service 
{

	use Module;
	public static $ext_name = 'sticky_section';
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services() 
	{
	
		return [
			base\Sticky::class,	
		];
	}

	/**
	 * Loop through the classes, initialize them, 
	 * and call the register() method if it exists
	 * @return
	 */
	public static function register_services() 
	{
		if(self::sr_module_live()){

			foreach ( self::get_services() as $class ) {

				$service = self::instantiate( $class );
			
				if ( method_exists( $service, 'register' ) ) {
					$service->register();
				}

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
}