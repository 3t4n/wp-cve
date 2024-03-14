<?php 
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ){ exit;  }

class wpApeGallery_Module{

	public $moduleFileName = '';
	
	public $modulePath = '';
	
	public $moduleUrl = '';

	function __construct(){
		$this->moduleFileName = $this->getModuleFileName();
		$this->init();
	}

	protected function getModuleFileName(){
		return __FILE__ ;
	}

	function init(){
		$this->defineVars();
		$this->load();
		$this->hooks();
	}

	function defineVars(){
		$this->modulePath 	= plugin_dir_path( $this->moduleFileName );
		$this->moduleUrl 	= plugin_dir_url( $this->moduleFileName );
	}

	function load(){}

	function hooks(){}

}