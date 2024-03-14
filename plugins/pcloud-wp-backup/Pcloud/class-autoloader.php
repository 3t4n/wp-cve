<?php
/**
 * Class Autoloader.
 *
 * @file class-autoloader.php
 * @package pcloud_wp_backup
 */

namespace Pcloud;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Simple Recursive Autoloader.
 *
 * @noinspection PhpUnused
 */
class Autoloader {

	/**
	 * The topmost directory where recursion should begin. Defaults to the current directory.
	 *
	 * @var string $path_top Top path.
	 */
	protected static $path_top = __DIR__;

	/**
	 * A placeholder to hold the file iterator so that directory traversal is only performed once.
	 *
	 * @var RecursiveIteratorIterator|null $file_iterator File iterrator.
	 */
	protected static $file_iterator = null;

	/**
	 * Autoload function for registration with spl_autoload_register.
	 * Looks recursively through project directory and loads class files based on filename match.
	 *
	 * @param string $class_name Class name.
	 */
	public static function loader( string $class_name ) {
		$directory = new RecursiveDirectoryIterator( static::$path_top, FilesystemIterator::SKIP_DOTS );
		if ( is_null( static::$file_iterator ) ) {
			static::$file_iterator = new RecursiveIteratorIterator( $directory, RecursiveIteratorIterator::LEAVES_ONLY );
		}

		$filename  = $class_name . '.php';
		$file_path = $class_name . '.php';
		if ( false !== strpos( $file_path, '\\' ) ) {
			$path_components = explode( '\\', $file_path );
			if ( is_array( $path_components ) && count( $path_components ) > 0 ) {
				$filename = end( $path_components );
			}
		}

		foreach ( static::$file_iterator as $file ) {
			if (
				( strtolower( $file->getFilename() ) === strtolower( $filename ) ) ||
				( strtolower( $file->getFilename() ) === strtolower( 'class-' . $filename ) )
			) {
				if ( $file->isReadable() ) {
					include_once $file->getPathname();
				}
				break;
			}
		}
	}

	/**
	 * Sets the $path property.
	 *
	 * @param string $path The path representing the top level where recursion should begin.
	 */
	public static function set_path( string $path ) {
		static::$path_top = $path;
	}

}

autoloader::set_path( plugin_dir_path( __FILE__ ) . 'Classes/' );

spl_autoload_register( '\Pcloud\Autoloader::loader' );
