<?php
namespace Shop_Ready\extension\generalwidgets;

use Shop_Ready\extension\generalwidgets\base\Extension_Base as Shop_Ready_Ext_Base;
use Shop_Ready\extension\generalwidgets\assets\Assets as Asset_load;
use Shop_Ready\extension\generalwidgets\assets\Register as Asset_Register;
use Elementor\Widgets_Manager;
define( 'SHOP_READY_GENWIDGET_PATH', plugin_dir_path( __FILE__ ) );
define( 'SHOP_READY_GEMWIDGET_MODULE_URL', plugin_dir_url( __FILE__ ) );

/*
* @since 1.0 
* Elementor Bootstrap
*/
final class Service extends Shop_Ready_Ext_Base {

	private static $_instance = null;
	public function __construct() {

		$this->run_dependency();
		$this->on_plugins_loaded();
	
	}

	public static function register_services() {
		
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
          
			foreach ( self::get_services() as $class ) {
          
				$service = self::instantiate( $class );
			
				if ( method_exists( $service, 'register' ) ) {
					$service->register();
				}
	
			}
		}

		return self::$_instance;
	
	}

	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services() 
	{
        
		$boot_service = [
			Asset_Register::class,
		    Asset_load::class,
		
		];
		
		$boot_service = array_merge( $boot_service, self::document() ,self::get_base(), self::get_defs() );
		
		return $boot_service;
	}

	

	private static function instantiate( $class )
	{
		$service = new $class();

		return $service;
	}

	public function on_plugins_loaded() {

        //load all required functionality before extension run 
		
      
		if ( $this->is_compatible() ) {
			$this->init();
		
		}

	}

	public function init() {
	
		do_action('woo_ready/genwidgets/loaded/before'); 
      
		/*----------------------------------
			ADD NEW ELEMENTOR CATEGORIES
		------------------------------------*/
		add_action( 'elementor/init', [ $this, 'add_elementor_category' ] );
       
		/*----------------------------------
			ADD PLUGIN WIDGETS ACTIONS
		-----------------------------------*/
		add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ],13 );
		/*----------------------------------
			ELEMENTOR REGISTER CONTROL
		-----------------------------------*/
		
	
		do_action('woo_ready/genwidgets/loaded/after'); 
	}
   
	private static function run_dependency(){
       
	    // include funtion file  
		foreach (array('config_function','general') as $file) {
			include( plugin_dir_path( __FILE__ ) . 'helper/'.$file.'.php');
		}
		
	}

	public function enqueue_frontend_scripts(){}
	
}

