<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

class apeGalleryRender{

	private static function getIdFromAttr( $attr ){
		if( !is_array($attr) ) return false;
		if( isset($attr['id']) && (int) $attr['id'] ) return (int) $attr['id'];
		if( isset($attr[0]) && (int) $attr[0] ) return (int) $attr[0];
		return false;
	}

	private static function getIdsFromAttr( $attr ){
		if( !is_array($attr) ) return '';
		if( isset($attr['ids']) && $attr['ids'] ) return trim( $attr['ids'] );
		return '';
	}

	public static function getContent( $attr ){

		$id = self::getIdFromAttr( $attr );
		$ids = self::getIdsFromAttr( $attr );

		$typeGallery = 'grid';

		if( $id ){
			$themeId = apeGalleryHelper::getThemeIdFromGallery( $id );
			$typeGallery =  get_post_meta( $themeId, WPAPE_GALLERY_NAMESPACE.'type', true );
		}

		switch ($typeGallery) {
			case 'carousel':   
			case 'cubeslider': if( !WPAPE_GALLERY_PREMIUM)  break;
			
			case 'slider':
					return self::renderSliderGallery( $attr, $ids );
				break;
			
			case 'grid':
			default:
					return self::renderBaseGallery( $attr, $ids );
				break;
		}
	}

	private static function renderSliderGallery( $attr, $ids = '' ){
		$gallery = new apeGallerySliderBuild($attr, $ids);
		return  $gallery->getGallery();
	}

	private static function renderBaseGallery( $attr, $ids = '' ){
		$gallery = new apeGalleryGridBuild($attr, $ids);
		return  $gallery->getGallery();
	}
}