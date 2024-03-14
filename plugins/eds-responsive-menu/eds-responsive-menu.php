<?php
/*
Plugin Name: eDS Responsive Menu 
Plugin URI: https://edatastyle.com/product/eds-reponsive-menu/
Description: eDS Responsive Menu Plugins,Control the edge, skins, toggle breakpoints, and more, right from the admin panel.
Version:1.2 
Author: eDataStyle
Author URI: https://edatastyle.com/product/eds-reponsive-menu/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( !class_exists( 'EDS_Responsive_menu' ) ) :

class EDS_Responsive_menu {
	 /**
     * Option key, and option page slug
     * @var string
     */
    private $key = 'eds_responsive_menu';
    /**
     * Options Page title
     * @var string
     */
    protected $title = '';

    /**
     * Options Page hook
     * @var string
     */
    protected $options_page = '';

    /**
     * Constructor
     * @since 0.1.0
     */
	public function __construct() {
		$this->define_constants();
		$this->includes();
	    // Set constants
		$this->dir = EDS_MENU_DIR;
		$this->uri = EDS_MENU_URI;
		$this->temp_dir = EDS_MENU_TEMP_DIR;
		$this->temp_uri = EDS_MENU_TEMP_URL;
		$this->stylesheet_dir = EDS_MENU_STYLESHEET_DIR;
		$this->stylesheet_uri = EDS_MENU_STYLESHEET_URL;

		$this->version = '0.1';
		
		
	}
	public function define_constants() {
		$defines = array(
			'EDS_MENU_DIR' => plugin_dir_path( __FILE__ ),
			'EDS_MENU_URI' => plugin_dir_url( __FILE__ ),
			'EDS_MENU_TEMP_DIR' => trailingslashit( get_template_directory() ),
			'EDS_MENU_TEMP_URL' => trailingslashit( get_template_directory_uri() ),
			'EDS_MENU_STYLESHEET_DIR' => trailingslashit( get_stylesheet_directory() ),
			'EDS_MENU_STYLESHEET_URL' => trailingslashit( get_stylesheet_directory_uri() ),
			'EDS_MENU_FILE' =>plugin_basename( __FILE__ )
		);
	
		foreach( $defines as $k => $v ) {
			if ( !defined( $k ) ) {
				define( $k, $v );
			}
		}
		
		// active modules
		//defined( 'eds_ACTIVE_FRAMEWORK' )  or  define( 'eds_ACTIVE_FRAMEWORK',  true );


	}
	public function includes() {
		
		if ( !class_exists( 'EDS_Menu_Admin' ) ) :
			require_once EDS_MENU_DIR . 'inc/welcome.php';
			new EDS_Menu_Admin();
		endif;
		if( ! function_exists( 'eds_framework_init' ) && ! class_exists( 'EDSFramework' ) ) :
			
			require_once plugin_dir_path( __FILE__ ) .'/framework/eds-framework.php';
			
			
		endif;
		
		
		if (!is_admin() ) :
			require_once EDS_MENU_DIR . 'inc/eds_menu_frontend.php';
			new EDS_Menu_Frontend();
		endif;
		
		
	}
	
	
	
	
	
	
	
}

$GLOBALS['eds_responsive_menu'] = new EDS_Responsive_menu();
endif;

