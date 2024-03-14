<?php 
namespace Shop_Ready\base;


trait Register_Service {

 
    /**
	 * Loop through the classes, initialize them, 
	 * and call the register() method if it exists
	 * @return
	 */
	public static function register_services() 
	{

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
	public static function instantiate( $class )
	{
		$service = new $class();

		return $service;
	}
}
