<?php

namespace NativeRent;

use WP_Error;
use WP_Filesystem_Base;

use function defined;
use function dirname;
use function implode;
use function is_wp_error;
use function mb_strpos;
use function WP_Filesystem;

defined( 'ABSPATH' ) || exit;

/**
 * Handler class
 *
 * @note    This class is no longer used except to remove leftover configuration by previous versions.
 *
 * @package nativerent
 */
class Connect_Handler {


	/**
	 * Config file
	 *
	 * @var string
	 */
	private static $config_file = '';

	/**
	 * WP Filesystem
	 *
	 * @var WP_Filesystem_Base|null
	 */
	private static $filesystem = null;

	/**
	 * File name
	 *
	 * @var string
	 */
	private static $file_name = '$nativerent_connect_file';

	/**
	 * Lines with code from the config file
	 *
	 * @var array
	 */
	private static $config_lines = array();

	/**
	 * Get WP filesystem
	 *
	 * @return WP_Filesystem_Base
	 */
	private static function get_filesystem() {
		if ( empty( self::$filesystem ) ) {
			self::set_filesystem();
		}

		return self::$filesystem;
	}

	/**
	 * Set up WP filesystem
	 */
	private static function set_filesystem() {
		if ( ! empty( self::$filesystem ) ) {
			return;
		}

		global $wp_filesystem;

		// Create object to work with files if it is not exists already.
		if ( ! $wp_filesystem ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
		}

		self::$filesystem = $wp_filesystem;
	}

	/**
	 * Get config path
	 */
	private static function get_config_path() {
		if ( ! empty( self::$config_file ) ) {
			return self::$config_file;
		}

		$fs = self::get_filesystem();

		if ( $fs->exists( ABSPATH . 'wp-config.php' ) ) {
			$global_config_file = ABSPATH . 'wp-config.php';
		} elseif ( $fs->exists( dirname( ABSPATH ) . '/wp-config.php' ) ) {
			$global_config_file = dirname( ABSPATH ) . '/wp-config.php';
		} elseif ( defined( 'DEBIAN_FILE' ) && $fs->exists( DEBIAN_FILE ) ) {
			$global_config_file = DEBIAN_FILE;
		} else {
			return new WP_Error( 'nativerent-install', 'Cannot locate wp-config.php' );
		}

		if ( ! $fs->is_readable( $global_config_file ) || ! $fs->is_writable( $global_config_file ) ) {
			return new WP_Error( 'nativerent-install', 'wp-config.php cannot be modified' );
		}

		self::$config_file = $global_config_file;

		return self::$config_file;
	}

	/**
	 * Get config lines
	 */
	private static function set_config_lines() {
		if ( ! empty( self::$config_lines ) ) {
			return true;
		}

		$global_config_file = self::get_config_path();
		if ( is_wp_error( $global_config_file ) ) {
			return false;
		}

		self::$config_lines = self::get_filesystem()->get_contents_array( $global_config_file );

		return self::$config_lines ? true : false;
	}

	/**
	 * Remove lines for Nativerent from array with config lines
	 */
	private static function remove_lines() {
		$output_lines = array();

		// Remove lines if they already exist.
		foreach ( self::$config_lines as $line ) {
			if ( false !== mb_strpos( $line, self::$file_name ) ) {
				continue;
			}
			$output_lines[] = $line;
		}

		self::$config_lines = $output_lines;
	}

	/**
	 * Remove connect file from config
	 */
	public static function remove_connect_file_from_config() {
		$global_config_file = self::get_config_path();

		if ( is_wp_error( $global_config_file ) ) {
			return $global_config_file;
		}

		self::set_config_lines();
		self::remove_lines();
		self::get_filesystem()->put_contents( $global_config_file, implode( '', self::$config_lines ) );
	}
}
