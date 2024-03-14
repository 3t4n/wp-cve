<?php
namespace Enteraddons\Classes;

/**
 * Enteraddons helper class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */
if( !defined( 'WPINC' ) ) {
    die;
}

class Assets_Cache {

	const FILEPREFXI = 'enteraddons-';

	protected static $postId;
	
	protected static $widgets;

	protected static $upload_path;

	protected static $upload_url;

	function __construct( $post_id, $widgets = null ) {

		self::$postId  = $post_id;
		self::$widgets = $widgets;
		//
		$this->getUploadDir();
	}

	public function getUploadDir() {

		$upload_dir 	   = wp_upload_dir();
		self::$upload_path = trailingslashit( $upload_dir['basedir'] );
		self::$upload_url  = trailingslashit( $upload_dir['baseurl'] );	
	}

	public static function getCacheDirName() {
		return trailingslashit('enteraddons') . trailingslashit('css');
	}

	public static function getCacheDir() {
		return self::$upload_path . self::getCacheDirName();
	}

	public static function getCacheDirUrl() {
		return self::$upload_url . self::getCacheDirName();
	}

	public static function createCacheFolder() {
		if( !is_dir( self::getCacheDir() ) ) {
			@mkdir( self::getCacheDir() , 0777, true);
		}
	}

	public static function getFileName() {
		$post_id = self::$postId;
		return self::getCacheDir() . self::FILEPREFXI . "{$post_id}.css";
	}

	public static function getFileUrl() {
		$post_id = self::$postId;
		// Set global variable
		global $ea_cache_dir_url, $ea_cache_file_prefix;
		$ea_cache_dir_url = self::getCacheDirUrl();
		$ea_cache_file_prefix = self::FILEPREFXI;

		return file_exists( self::getFileName() ) ? self::getCacheDirUrl() . self::FILEPREFXI . "{$post_id}.css" : '';
	}

	public static function deleteCacheFile() {
		if( file_exists( self::getFileName() ) ) {
			unlink( self::getFileName() );
		}
	}

	public static function enqueue() {
		if( !self::getFileUrl() ) {
			return;
		}
		
		$post_id = self::$postId;
		$version = ENTERADDONS_VERSION.'.'.get_post_modified_time();
		wp_enqueue_style( 'enteraddons-'.$post_id, self::getFileUrl(), array('elementor-frontend'), $version, false );
	}

	public static function putStyle() {
		self::createCacheFolder();
		file_put_contents( self::getFileName() , self::getStyle( self::$widgets ) );
	}

	public static function getStyle( $filesName , $is_pro = false ) {
		$style = '';
		foreach( $filesName as $widgetName ) {
			
			$getFile = ENTERADDONS_PLUGIN_MODE == 'DEV' ? "css/{$widgetName}.css" : "min-css/{$widgetName}.min.css";

			$file_path = ENTERADDONS_DIR_PATH."assets/widgets-css/{$getFile}";

			$filePath = apply_filters( 'ea_css_assets_path_inject', $file_path, $widgetName );

			if( is_readable($filePath) ) {
				$style .= file_get_contents($filePath);
			}
		}
		return $style;
	} 

}
