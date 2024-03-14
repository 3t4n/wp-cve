<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

class wpApeGallery_Module_Frontend extends wpApeGallery_Module{

	function getModuleFileName(){ return __FILE__ ; }

	public function load(){

		apeGalleryHelper::load( 
			array(
				'apeGalleryFunction.php',

				'apeGalleryFrontendModule.php',

				'apeGalleryRenderHelper.php',
				'apeGallerySource.php',

				'apeGalleryBuild.php',
				'apeGalleryBuildV2.php',

				'apeGalleryGridBuild.php',
				'apeGallerySliderBuild.php',

				'apeGalleryRender.php',
				'apeGalleryShortcode.php',
			), 
			$this->modulePath 
		);
	}
}

new wpApeGallery_Module_Frontend();