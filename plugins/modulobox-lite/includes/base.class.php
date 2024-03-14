<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ModuloBox Base Helper class
 *
 * @class ModuloBox_Base
 * @version	1.0.0
 * @since 1.0.0
 */
class ModuloBox_Base {

	/**
	 * Cloning disabled
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function __clone() {
	}

	/**
	 * De-serialization disabled
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function __wakeup() {
	}

	/**
	 * Construct disabled
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
	}

	/**
	 * Convert php.ini number notation (e.g.: '2M') to an integer
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $size
	 * @return string
	 */
	public static function let_to_num( $size ) {

		$l   = substr( $size, -1 );
		$ret = substr( $size, 0, -1 );

		switch ( strtoupper( $l ) ) {
			case 'P':
				$ret *= 1024;
			case 'T':
				$ret *= 1024;
			case 'G':
				$ret *= 1024;
			case 'M':
				$ret *= 1024;
			case 'K':
				$ret *= 1024;
		}

		return $ret;

	}

	/**
	 * Convert fraction string to decimal
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $fraction
	 * @return number
	 */
	public static function convert_to_decimal( $fraction ) {

		$numbers = explode( '/', $fraction );
		return isset( $numbers[0] ) && isset( $numbers[1] ) ? $numbers[0] / $numbers[1] : floatval( $fraction );

	}

	/**
	 * Get image sizes
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public static function get_images_sizes() {

		$img_sizes = array();
		$int_sizes = (array) get_intermediate_image_sizes();

		foreach ( $int_sizes as $key => $value ) {
			$img_sizes[ $value ] = ucfirst( str_replace( '_', ' ', $value ) );
		}

		return $img_sizes;

	}

	/**
	 * Get debug mode state
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public static function get_debug_mode() {

		return defined( 'WP_DEBUG' ) && WP_DEBUG ? true : false;

	}

	/**
	 * Get PHP memory limit
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public static function get_memory_limit() {

		$memory = self::let_to_num( WP_MEMORY_LIMIT );

		if ( function_exists( 'memory_get_usage' ) ) {

			$system = self::let_to_num( @ini_get( 'memory_limit' ) );
			$memory = max( $memory, $system );

		}

		return size_format( $memory );

	}

	/**
	 * Get PHP memory usage
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public static function get_memory_usage() {

		return size_format( memory_get_usage() );

	}

	/**
	 * Get WordPress max upload size
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public static function get_max_upload_size() {

		return size_format( wp_max_upload_size() );

	}

	/**
	 * Get PHP software
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public static function get_server_software() {

		return $_SERVER['SERVER_SOFTWARE'];

	}

	/**
	 * Get PHP version
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public static function get_phpversion() {

		return function_exists( 'phpversion' ) ? phpversion() : __( 'Couldn\'t determine PHP version because phpversion() doesn\'t exist.', 'modulobox' );

	}

	/**
	 * Get PHP post max size
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public static function get_post_max_size() {

		return size_format( self::let_to_num( ini_get( 'post_max_size' ) ) );

	}

	/**
	 * Get PHP max execution time
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return number
	 */
	public static function get_max_execution_time() {

		return ini_get( 'max_execution_time' );

	}

	/**
	 * Get PHP max input vars
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return number
	 */
	public static function get_max_input_vars() {

		$var = 'max_input_vars';
		$php = self::get_phpversion();
		return version_compare( $php, '5.3.9', '>=' ) ? ini_get( $var ) : 1000;

	}

	/**
	 * Get activated plugins
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return number
	 */
	public static function get_active_plugins() {

		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {

			$network_plugins = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
			$active_plugins  = array_merge( $active_plugins, $network_plugins );

		}

		return $active_plugins;

	}
}
