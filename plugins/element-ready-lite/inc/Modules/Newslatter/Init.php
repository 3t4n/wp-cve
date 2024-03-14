<?php

namespace Element_Ready\Modules\Newslatter;

final class Init
{
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services() 
	{
		return [
			base\FrontEnd::class,
		];

	}

	/**
	 * Loop through the classes, initialize them, 
	 * and call the register() method if it exists
	 * @return
	 */
	public static function register_services() 
	{
		 
        self::defined_module_cons();
        
		foreach ( self::get_services() as $class ) {
			
			$service = self::instantiate( $class );
			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
			
		}
	}
 
	
	public static function defined_module_cons(){
		define( 'ELEMENT_READY_NEWSLATTER_MODULE_PATH', plugin_dir_path( __FILE__ ) );
		define( 'ELEMENT_READY_NEWSLATTER_MODULE_URL', plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Initialize the class
	 * @param  class $class  class from the services array
	 * @return class instance  new instance of the class
	 */
	private static function instantiate( $class )
	{
		$service = new $class();
		return $service;
	}
}



