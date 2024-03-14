<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ){ exit;  }


if(!function_exists('apeGallery')){
	function apeGallery( $id = 0, $noEcho = 0 ) {
		
		$id = (int) $id;
		
		if(!$id  ) return ;
	 	
	 	$retHTML = '';

		$retHTML = apeGalleryHelper::renderGalleryId($id);
		
		if( $noEcho ) return $retHTML;

		echo $retHTML;
	}
}