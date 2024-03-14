<?php 
/*
Plugin Name: Amigo Extensions
Version: 1.0.16
Author: amigothemes 
Author URI: https://amigothemes.com
Description: Amigo extension is to increase the awesome of our themes created by Amigo Themes. The main use of this plugin is to increase the functionality and features of amigo themes with a smooth user experience. This helps us to add a homepage and other functionality to the website. so you can build amazing websites that are suitable for business, portfolio, blogging & agency websites. You can see below listed free themes.
Text Domain: amigo-extensions
*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Define plugin main class
 * 
 */  

class Amigo_Extensions{

	public static $instance = null;
	private $current_theme = '';

	// construct function 
	public function __construct() {

		$this->current_theme = wp_get_theme();

		$this->define_properties();

		add_action( 'init', array( $this, 'theme_activatation') );

		register_activation_hook( __FILE__, array( $this, 'theme_default_item_generator') );
	}

	/**
 	* Define plugin default properties
 	*
 	* @package Amigo Extension WordPress plugin
 	*
 	* 
 	*/ 

 	public static function define_properties() {

 		define( 'AMIGO_PLUGIN_VER', '1.0.11' );
 		define( 'AMIGO_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
 		define( 'AMIGO_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
 	}

	/**
 	* Define instance
 	*
 	* @package Amigo Extension WordPress plugin
 	*
 	* 
 	*/ 
 	public static function get_instance() {

 		if ( null === self::$instance ) {

 			self::$instance = new self;
 		}

 		return self::$instance;
 	}

	/**
 	* Define method theme activation 
 	*
 	* @package Amigo Extension WordPress plugin
 	*
 	* 
 	*/ 

 	public function theme_activatation(){

 		// aqwa
 		if( $this->current_theme == 'Aqwa' ){			
 			require_once( AMIGO_PLUGIN_DIR_PATH.'includes/aqwa/init.php');
 		} 		

 		// Corpox
 		if( $this->current_theme == 'Corpox' ){			
 			require_once( AMIGO_PLUGIN_DIR_PATH.'includes/corpox/init.php');
 		} 	

 		// enron
 		if( $this->current_theme == 'Enron' ){
 			require_once( AMIGO_PLUGIN_DIR_PATH.'includes/enron/init.php');
 		}

 		// enron
 		if( $this->current_theme == 'Industri' ){
 			require_once( AMIGO_PLUGIN_DIR_PATH.'includes/industri/init.php');
 		} 		 		
 		
 	}

 	/**
 	* Amigo extension default page,widget generator
 	*
 	* @package Amigo Extensions WordPress plugin
 	*
 	* 
 	*/ 

 	public function theme_default_item_generator(){

 		if( $this->current_theme == 'Aqwa' ){	
 			require_once( AMIGO_PLUGIN_DIR_PATH.'includes/aqwa/defaults/default-homepage.php');
 			require_once( AMIGO_PLUGIN_DIR_PATH.'includes/aqwa/defaults/default-widgets.php');
 		}else if( $this->current_theme == 'Industri' ){	
 			require_once( AMIGO_PLUGIN_DIR_PATH.'includes/industri/defaults/default-homepage.php');
 			require_once( AMIGO_PLUGIN_DIR_PATH.'includes/industri/defaults/default-widgets.php');
 		}
 	}
 }

/**
 * Amigo extension Plugin call
 *
 * 
 */ 

if ( ! function_exists( 'Amigo_Extensions_Call' ) ) {

	function Amigo_Extensions_Call( $debug = false ) {

		return Amigo_Extensions::get_instance();
	}
}

// 
Amigo_Extensions_Call();
