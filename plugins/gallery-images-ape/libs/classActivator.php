<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ){ exit;  }

class ApeGalleryActivator {
	public static function activate(){ 
		apeGalleryHelper::writeLog("ApeGalleryActivator run Activate");
		add_option( 'ApeGalleryInstall', 'now' );
	}
	public static function deactivate(){ 
		apeGalleryHelper::writeLog("ApeGalleryActivator run Deactivate");
		delete_option( 'ApeGalleryInstall' );
	}
}