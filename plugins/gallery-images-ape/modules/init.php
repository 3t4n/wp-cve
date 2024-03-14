<?php 
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

apeGalleryHelper::load('core/class.php', WPAPE_GALLERY_MODULES_PATH);

class wpApeGallery_Module_Init extends wpApeGallery_Module{

	function getModuleFileName(){
		return __FILE__ ;
	}

	function load(){
		apeGalleryHelper::load('theme/init.php', WPAPE_GALLERY_MODULES_PATH);
		apeGalleryHelper::load('fields/init.php', WPAPE_GALLERY_MODULES_PATH);
		apeGalleryHelper::load('ajax/init.php', 	WPAPE_GALLERY_MODULES_PATH);
		//apeGalleryHelper::load('media/init.php', WPAPE_GALLERY_MODULES_PATH);
		apeGalleryHelper::load('frontend/init.php', 	WPAPE_GALLERY_MODULES_PATH);
		apeGalleryHelper::load('block/init.php', 	WPAPE_GALLERY_MODULES_PATH);
	}
}
$moduleInit = new wpApeGallery_Module_Init();