<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Aqwa theme default settings
 */ 
require_once ( AMIGO_PLUGIN_DIR_PATH . 'includes/aqwa/defaults/default.php');


/**
 * Aqwa theme base class
 */ 
class Aqwa_Theme{	

	public function __construct() {			

		$this->general_settings();

		$this->template_parts();

		$this->customizer_controls();

		$this->customizer_settings();		

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) ,0 );		
	}	

	/**
 	* Aqwa theme enqueue style and scripts
 	*
 	* @package Amigo Extension WordPress plugin
 	*
 	* 
 	*/ 

 	public function enqueue(){

 		$js_uri     = AMIGO_PLUGIN_DIR_URL . 'includes/aqwa/assets/js/';
 		$css_uri    = AMIGO_PLUGIN_DIR_URL . 'includes/aqwa/assets/css/';		

 		wp_enqueue_style( 'aqwa-extension-main', $css_uri.'main.css', false, AMIGO_PLUGIN_VER, 'all' );
 		wp_enqueue_style( 'aqwa-magnific-popup', $css_uri.'magnific-popup.css', array(),AMIGO_PLUGIN_VER );
 		wp_enqueue_script( 'aqwa-magnific-popup', $js_uri .'jquery.magnific-popup.js', array( 'jquery' ), AMIGO_PLUGIN_VER, true );
 		wp_enqueue_script( 'aqwa-extension-custom', $js_uri .'custom.js', array( 'jquery' ), AMIGO_PLUGIN_VER, true );		

 	}


	/**
 	* Aqwa theme define google fonts and font-awsome icons
 	*
 	* @package Amigo Extension WordPress plugin
 	*
 	* 
 	*/ 

 	public function general_settings(){

 		require_once ( AMIGO_PLUGIN_DIR_PATH . '/lib/fa-icons.php'); 		
 	}

	/**
 	* Aqwa theme define homepage sections slider,header,about,blogs,footer etc.
 	*
 	* @package Amigo Extension WordPress plugin
 	*
 	* 
 	*/ 

 	public function template_parts(){

 		require_once ( AMIGO_PLUGIN_DIR_PATH.'includes/aqwa/template-parts/header.php' );
 		require_once ( AMIGO_PLUGIN_DIR_PATH.'includes/aqwa/template-parts/slider.php' );
 		require_once ( AMIGO_PLUGIN_DIR_PATH.'includes/aqwa/template-parts/about.php' );
 		require_once ( AMIGO_PLUGIN_DIR_PATH.'includes/aqwa/template-parts/service.php' ); 		
 		require_once ( AMIGO_PLUGIN_DIR_PATH.'includes/aqwa/template-parts/footer.php' );
 	}
	

	/**
 	* Aqwa theme add customizer custom controls.
 	*
 	* @package Amigo Extension WordPress plugin
 	*
 	* 
 	*/ 

 	public function customizer_controls(){ 		

 		// separator control
 			require_once ( AMIGO_PLUGIN_DIR_PATH . '/lib/customizer-controls/customizer-separator-control/separator-control.php');	

 		// range control
 			require_once ( AMIGO_PLUGIN_DIR_PATH . '/lib/customizer-controls/customizer-range-control/range-control.php');	

 		// repeater control
 			require_once ( AMIGO_PLUGIN_DIR_PATH . '/lib/customizer-controls/customizer-repeater-control/repeater-control.php');
 		
 	}	

	/**
 	* Aqwa theme add customizer settings.
 	*
 	* @package Amigo Extension WordPress plugin
 	*
 	* 
 	*/ 

 	public function customizer_settings(){		

 		// header bar customizer
 		require_once( AMIGO_PLUGIN_DIR_PATH .'includes/aqwa/customizer/above-header-customizer.php');

		// header navigation
 		require_once( AMIGO_PLUGIN_DIR_PATH .'includes/aqwa/customizer/header-navigation-customizer.php');	

		//footer 
 		require_once( AMIGO_PLUGIN_DIR_PATH .'includes/aqwa/customizer/footer-customizer.php');		

		// slider section customizer 
 		require_once( AMIGO_PLUGIN_DIR_PATH .'includes/aqwa/customizer/slider-customizer.php');	

		// about section customizer 		
 		require_once( AMIGO_PLUGIN_DIR_PATH .'includes/aqwa/customizer/about-customizer.php');

		// service section customizer 
 		require_once( AMIGO_PLUGIN_DIR_PATH .'includes/aqwa/customizer/service-customizer.php');	

		// blog section customizer 
 		require_once( AMIGO_PLUGIN_DIR_PATH .'includes/aqwa/customizer/blog-customizer.php');

 		// theme option customizer
 		require_once( AMIGO_PLUGIN_DIR_PATH .'includes/aqwa/customizer/theme-option-customizer.php');			
 	}	


 }

// object and call aqwa theme class
 $GLOBALS['Aqwa_Theme'] = new Aqwa_Theme();


