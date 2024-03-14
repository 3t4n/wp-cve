<?php

class wpAPEGalleryModule_Ajax_Images{
	
	public  static function getImagesTagsFromIdsStr( $ids = '' ){
		if( $ids == '' ) return '';
		
		$idArray = explode(',', $ids);
		if( is_array($idArray) && count($idArray) ) return self::getImagesTagsFromIds( $idArray );

		return '';
	}

	public  static function getImagesTagsFromIds( $ids = array() ){
		$returnHtml = '';
		for ($i=0; $i < count($ids); $i++) { 
			$returnHtml .= self::getImageTag($ids[$i]);
		}
		return $returnHtml;
	}

	public  static function getImageTag( $id = 0 ){
		
		$attachment_id = (int)$id;
		if( $attachment_id == 0  ) return 'Error::empty input id';

		$url = wp_get_attachment_thumb_url( $attachment_id );
		if( $url ) return '<img src="'.$url.'" />';
		return '';
	}

}