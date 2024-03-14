<?php

namespace Shop_Ready\extension\header_footer;

use Shop_Ready\extension\header_footer\settings\Page_Settings;
use Shop_Ready\base\Module;

define( 'SHOP_READY_HEADER_FOOTER_PATH', plugin_dir_path( __FILE__ ) );
define( 'SHOP_READY_HEADER_FOOTER_MODULE_URL', plugin_dir_url( __FILE__ ) );


final class Service
{
	use Module;
	public static $ext_name = 'header_footer';
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services() 
	{
	
		if(self::sr_module_live()){
			return [
			
				base\custom_post_type\Header_Footer::class,
				base\Template::class,
				settings\General::class,
				Page_Settings::class,
				
			];
	    }

		return [];
	}

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
	private static function instantiate( $class )
	{
		$service = new $class();
		return $service;
	}
}



