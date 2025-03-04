<?php

defined( 'ABSPATH' ) || exit();

class EPHD_File_Manager {

	const UPLOADS_DIR = 'help-dialog/';

	private static $wp_uploads_dir = '';
	private static $wp_uploads_url = '';

	/**
	 * Get plugin UPLOAD directory
	 */
	public static function get_plugin_base_uploads_dir() {

		if ( ! empty( self::$wp_uploads_dir ) ) {
			return self::$wp_uploads_dir;
		}

		$wp_upload_dir = wp_upload_dir();
		if ( $wp_upload_dir['error'] ) {
			return new WP_Error( 'upload_dir_error', $wp_upload_dir['error'] );
		}

		self::$wp_uploads_dir = $wp_upload_dir['basedir'] . '/' . self::UPLOADS_DIR;

		// check if directory exist and create if not
		self::make_directory( self::$wp_uploads_dir );

		return self::$wp_uploads_dir;
	}

	public static function get_upload_path_dir( $directory ) {
		return self::get_plugin_base_uploads_dir() . trailingslashit( $directory );
	}

	/**
	 * Get plugin UPLOAD URL
	 */
	public static function get_plugin_base_uploads_url() {

		if ( ! empty( self::$wp_uploads_url ) ) {
			return self::$wp_uploads_url;
		}

		$wp_upload_dir = wp_upload_dir();

		if ( $wp_upload_dir['error'] ) {
			return new WP_Error( 'upload_dir_error', $wp_upload_dir['error'] );
		}

		self::$wp_uploads_url = $wp_upload_dir['baseurl'] . '/' . self::UPLOADS_DIR;

		// check if directory exist and create if not
		self::make_directory( self::$wp_uploads_dir );

		return self::$wp_uploads_url;
	}

	public static function get_upload_path_url( $directory ) {
		return self::get_plugin_base_uploads_url() . trailingslashit( $directory );
	}

	/**
	 * Make directory if needed
	 *
	 * @param $dir_path
	 * @return bool
	 */
	public static function make_directory( $dir_path ) {
		if ( ! is_dir( $dir_path ) ) {
			// @mkdir( $this->get_cache_assets_dir(), 0755, true );
			return wp_mkdir_p( $dir_path );
		}

		return true;
	}

	/**
	 * Read content of a file
	 *
	 * @param $file_path
	 * @param $default
	 *
	 * @return false|string
	 */
	public static function read( $file_path, $default = '' ) {
		return is_readable( $file_path ) ? file_get_contents( $file_path ) : $default;
	}

	/**
	 * Write content into a file.
	 *
	 * @param $path
	 * @param $content
	 * @return false|int
	 */
	public static function write( $path, $content ) {

		$put_contents = file_put_contents( $path, $content );
		if ( ! $put_contents ) {
			return new WP_Error( 'file-error', 'Cannot create file "' . $path . '"' );
		}

		return $put_contents;
	}

	/**
	 * Delete a single file.
	 * @param $file_name
	 */
	public static function delete_file( $file_name ) {
		unlink( $file_name );
	}

	/**
	 * Delete all files in a directory
	 * @param $directory
	 */
	public static function delete_all_files( $directory ) {
		$files = glob( $directory . '*' );
		foreach ( $files as $file ) {
			if ( is_file( $file ) ) {
				unlink( $file );
			}
		}
	}

	/**
	 * Get file path by file name
	 * @param $file_name
	 * return string
	 */
	public static function get_file_path( $file_name ) {
		$dir_path  = self::get_plugin_base_uploads_dir();
		if ( is_wp_error( $dir_path ) ) {
			return $dir_path;
		}

		$file_path = $dir_path . $file_name;

		return $file_path;
	}

	/**
	 * Get file url by file name or false if file not exist
	 * @param $file_name
	 * @return string|false
	 */
	public static function get_file_url( $file_name ) {

		$file_path = self::get_file_path( $file_name );
		if ( is_wp_error( $file_path ) ) {
			return $file_path;
		}

		if ( ! file_exists( $file_path ) ) {
			return false;
		}

		$dir_path  = self::get_plugin_base_uploads_url();
		if ( is_wp_error( $dir_path ) ) {
			return $dir_path;
		}

		$file_path = $dir_path . $file_name;

		return $file_path;
	}
}
