<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ){ exit;  }

class Gallery_Images_Ape {

	public $version = WPAPE_GALLERY_VERSION;

	public function __construct(){
		$this->hooks();
	}

	public function hooks(){
	}
}
