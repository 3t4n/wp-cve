<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ){ exit;  }

class apeGallerySliderLoader extends apeGalleryFrontendModule{
	
	protected function init(){
		$this->moduleHTML();
	}

	protected function moduleHTML(){
		$loader = '<style type="text/css" scoped>
.ape-gallery-loading {
  	text-align: center;
	width: 100%;
	margin: 0;
	padding: 0;
	margin-bottom: -25px;
    position: relative;
    z-index: 2;
}
.ape-gallery-loading-bar {
  display: inline-block;
  width: 4px;
  height: 18px;
  border-radius: 4px;
  animation:  ape-gallery-loading 1s ease-in-out infinite;
}
.ape-gallery-loading-bar:nth-child(1) {
  background-color: #3498db;
  animation-delay: 0;
}
.ape-gallery-loading-bar:nth-child(2) {
  background-color: #c0392b;
  animation-delay: 0.09s;
}
.ape-gallery-loading-bar:nth-child(3) {
  background-color: #f1c40f;
  animation-delay: .18s;
}
.ape-gallery-loading-bar:nth-child(4) {
  background-color: #27ae60;
  animation-delay: .27s;
}
@keyframes ape-gallery-loading{
  0%{ transform: scale(1); }
  20%{ transform: scale(1, 2.2); }
  40%{ transform: scale(1); }
}		</style>';
		$loader .= '<div id="loader_'.$this->getCoreProperty('galleryId').'" class="ape-gallery-loading"><div class="ape-gallery-loading-bar"></div><div class="ape-gallery-loading-bar"></div><div class="ape-gallery-loading-bar"></div><div class="ape-gallery-loading-bar"></div></div>';
 		$this->setContent( $loader, 'begin');
 		$this->setContent( 'display: none;', 'styleMainDiv');
	}

}
