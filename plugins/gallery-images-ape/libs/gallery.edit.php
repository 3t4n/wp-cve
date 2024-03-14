<?php 
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ){ exit;  }

class wpApeGalleryEditClass {


	//private $wizard = 0;
	
	function __construct(){
		//$this->wizard = !(int) get_option( WPAPE_GALLERY_NAMESPACE.'hideWizard', 0);
		$this->hooks();
	}

	function hooks(){
		//add_action( 'init', 												array( $this, 'addCssFiles') );

	}

	function addCssFiles(){
		wp_enqueue_style ( WPAPE_GALLERY_ASSET.'listing-style', WPAPE_GALLERY_URL.'assets/css/admin/edit.style.css', array( ), WPAPE_GALLERY_VERSION );
	}


}

$wpApeGalleryEditClass = new wpApeGalleryEditClass();