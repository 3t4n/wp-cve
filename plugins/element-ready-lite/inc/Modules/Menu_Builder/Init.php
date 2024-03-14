<?php

namespace Element_Ready\Modules\Menu_Builder;

final class Init
{
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services() 
	{
	
		return [
		
			Base\Enqueue::class,
			Base\Mega_Menu_Cpt::class,
			Base\Menu_Settings::class,
			Base\Page_Settings::class,
			Elementor\Hook::class,
			
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
		self::include();
		
        if(element_ready_get_components_option('megamenu')){
			
			foreach ( self::get_services() as $class ) {
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
	
	public static function defined_module_cons(){
		define( 'ELEMENT_READY_MENU_MODULE_PATH', plugin_dir_path( __FILE__ ) );
		define( 'ELEMENT_READY_MEGA_MENU_MODULE_URL', plugin_dir_url( __FILE__ ) );
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



