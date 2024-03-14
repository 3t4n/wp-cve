<?php

namespace Element_Ready\Modules\Header_Footer;

final class Init
{

	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services() 
	{
	  
		return [
		
			Base\Custom_Post_Type\Header_Footer::class,
			Base\Template::class,
			Settings\Page::class,
			
		];
	}

	/**
	* Loop through the classes, initialize them, 
	* and call the register() method if it exists
	* @return
	*/
	public static function register_services() 
	{
        self::include();
		foreach ( self::get_services() as $class ) {
			
			if(element_ready_get_modules_option('header_footer_builder')){
				$service = self::instantiate( $class );
				if ( method_exists( $service, 'register' ) ) {
					$service->register();
				}
			}
			
		}
	}

    /**
	* inlcude helpers file  
	* and call the include() method if it exists
	* @return error message
	*/
    public static function include(){
        // include file here
         try{
			
            require_once dirname( __FILE__ ) . '/Utility/Helper.php';
           
        } catch (\Exception $e) {

          return $e->getMessage();
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



