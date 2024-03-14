<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

class apeGalleryFieldsHelper{

	public static function addField( $fileName, $dirName = '' ){
		
		if( !$fileName ) return array('type' => 'skip');

		if( !$dirName ) $dirName = WPAPE_GALLERY_FIELDS_SUB_FIELDS;

		if( !file_exists($dirName.$fileName) ) return array('type' => 'skip');

		return include $dirName.$fileName;
	}


	public static function addFields( $fileName, $dirName = '' ){
		if( !$fileName ) return array();	

		if( !$dirName ) $dirName = WPAPE_GALLERY_FIELDS_PATH_CONFIG.'metabox/';

		if( !file_exists($dirName.$fileName) ) return array();

		return include $dirName.$fileName;
	}


	public static function addExtFields( $fileName, $dirName = '' ){

		if( !WPAPE_GALLERY_PREMIUM ) return array();

		if( !$dirName ) $dirName = WPAPE_GALLERY_LICENCE_PATH_DIR.'fields/';

		return self::addFields( $fileName, $dirName );
	}	

	public static function addDependOptions( $fileName, $fileExtName, $dirName = '' , $dirExtName = '' ){

		if( WPAPE_GALLERY_PREMIUM ){
			if(!$dirExtName) $dirExtName = WPAPE_GALLERY_LICENCE_PATH_DIR.'fields/';
			return self::addFields( $fileExtName, $dirExtName );
		}

		return self::addFields( $fileName, $dirName );
	}


	public static function addDependField( $fileName, $fileExtName, $dirName = '' , $dirExtName = '' ){

		if( WPAPE_GALLERY_PREMIUM ){
			if(!$dirExtName) $dirExtName = WPAPE_GALLERY_LICENCE_PATH_DIR.'fields/subfields/';
			return self::addField( $fileExtName, $dirExtName );
		}

		return self::addField( $fileName, $dirName );
	}



}
