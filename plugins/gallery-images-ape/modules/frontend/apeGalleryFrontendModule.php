<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

abstract class apeGalleryFrontendModule {

	protected $galleryCore = null;

	public function __construct( $galleryCore ){
		$this->setCore( $galleryCore );
		$this->init();
	}

	protected function setCore( $galleryCore ){
		return $this->galleryCore = $galleryCore;
	}

	protected function setContent( $content, $point = '', $position = 'after' ){
		$this->galleryCore->setContent( $content, $point, $position );
	}

	protected function getCoreProperty( $property ){
		if( property_exists( $this->galleryCore, $property) ){
			return $this->galleryCore->{$property};
		}
		return null;
	}

	abstract protected function init();
}