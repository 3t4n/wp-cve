<?php

namespace CTXFeed\V5\Common;

use WP_Error;
use CTXFeed\V5\Utility\FileSystem;

/**
 * This is used to save feed
 *
 * @package    Woo_Feed
 * @subpackage Woo_Feed/includes
 * @since      1.0.0
 * @author     Ohidul Islam <wahid@webappick.com>
 * @property $info
 */
class SaveFeed {
	
	/**
	 * @var array $config Contain Feed Configuration.
	 */
	private static $config;
	
	public static function Save( $path, $file, $content, $config ) {
		self::checkDir( $path );
		self::$config = $config;
		
		$method = 'save' . strtoupper( self::$config->feedType );
		$type   = self::$config->feedType;
		
		$content = apply_filters( "ctx_save_{$type}_file", $content, $config );
		
		return self::$method( $path, $file, $content );
	}
	
	/**
	 * Check if the directory for feed file exist or not and make directory
	 *
	 * @param $path
	 *
	 * @return void
	 */
	private static function checkDir( $path ) {
		if ( ! file_exists( $path ) ) {
			wp_mkdir_p( $path );
		}
	}
	
	/**
	 * Save CSV file.
	 *
	 * @param string $path    WP Upload dir path.
	 * @param string $file    filename with extension
	 * @param array  $content File Contents.
	 *
	 * @return bool
	 */
	private static function saveCSV( $path, $file, $content ) {
		return FileSystem::WriteFile( $content, $path, $file );
	}
	
	/**
	 * Save TSV file.
	 *
	 * @param string $path    WP Upload dir path.
	 * @param string $file    filename with extension
	 * @param array  $content File Contents.
	 *
	 * @return bool
	 */
	private static function saveTSV( $path, $file, $content ) {
		return self::saveCSV( $path, $file, $content );
	}
	
	/**
	 * Save TXT file.
	 *
	 * @param string $path    WP Upload dir path.
	 * @param string $file    filename with extension
	 * @param array  $content File Contents.
	 *
	 * @return bool
	 */
	private static function saveTXT( $path, $file, $content ) {
		return self::saveCSV( $path, $file, $content );
	}
	
	/**
	 * Save XML File
	 *
	 * @param string $path    WP Upload dir path.
	 * @param string $file    filename with extension
	 * @param array  $content File Contents.
	 *
	 * @return bool
	 */
	private static function saveXML( $path, $file, $content ) {
		return FileSystem::WriteFile( $content, $path, $file );
	}
	
	/**
	 * Save JSON File.
	 *
	 * @param string $path    WP Upload dir path.
	 * @param string $file    filename with extension
	 * @param mixed  $content File Contents.
	 *
	 * @return mixed|WP_Error
	 */
	private static function saveJSON( $path, $file, $content ) {
		$content = json_encode( $content );
		
		return FileSystem::WriteFile( $content, $path, $file );
	}
	
	/**
	 * Save XLS File.
	 *
	 * @param string $path    WP Upload dir path.
	 * @param string $file    filename with extension
	 * @param array  $content File Contents.
	 *
	 * @return mixed|WP_Error
	 */
	private static function saveXLS( $path, $file, $content ) {
		return FileSystem::WriteFile( $content, $path, $file );
	}
}