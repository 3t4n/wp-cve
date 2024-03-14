<?php
/**
 * Searchanise profiler helper
 *
 * @package Searchanise/Profiler
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

/**
 * Searchanise profiler class
 */
class Profiler {

	/**
	 * Blocs list
	 *
	 * @var array
	 */
	private static $blocks = array();

	/**
	 * Clear all blocks information
	 */
	public static function clear_blocks() {
		self::$blocks = array();
	}

	/**
	 * Start new profile block
	 *
	 * @param string $block_name Block name.
	 */
	public static function start_block( $block_name ) {
		self::$blocks[ $block_name ] = array();
		self::$blocks[ $block_name ]['start']['time'] = microtime( true );
		self::$blocks[ $block_name ]['start']['memory_usage'] = memory_get_usage();
		self::$blocks[ $block_name ]['start']['memory_peak_usage'] = memory_get_peak_usage();
	}

	/**
	 * End existed profile block
	 *
	 * @param string $block_name Block name.
	 */
	public static function end_block( $block_name ) {
		if ( ! empty( self::$blocks[ $block_name ]['start'] ) ) {
			self::$blocks[ $block_name ]['end']['time'] = microtime( true );
			self::$blocks[ $block_name ]['end']['memory_usage'] = memory_get_usage();
			self::$blocks[ $block_name ]['end']['memory_peak_usage'] = memory_get_peak_usage();
		}
	}

	/**
	 * Returns block information if block is finished
	 *
	 * @param string $block_name Block name.
	 *
	 * @return array Block info
	 */
	public static function get_block_info( $block_name ) {
		$info = array();

		if ( ! empty( self::$blocks[ $block_name ] ) && ! empty( self::$blocks[ $block_name ]['end'] ) ) {
			$info['time'] = self::$blocks[ $block_name ]['end']['time'] - self::$blocks[ $block_name ]['start']['time'];
			$info['memory_increased'] = self::nice_file_size( self::$blocks[ $block_name ]['end']['memory_usage'] - self::$blocks[ $block_name ]['start']['memory_usage'] );
			$info['memory_peak_increased'] = self::nice_file_size( self::$blocks[ $block_name ]['end']['memory_peak_usage'] - self::$blocks[ $block_name ]['start']['memory_peak_usage'] );
		}

		return $info;
	}

	/**
	 * Returns all blocks information
	 *
	 * @return array Block infos
	 */
	public static function get_blocks_info() {
		$info = array();

		foreach ( self::$blocks as $name => $block ) {
			$info[ $name ] = self::get_block_info( $name );
		}

		return $info;
	}

	/**
	 * Convert bytes value to human format
	 *
	 * @param int     $bytes        Bytes count.
	 * @param boolean $binary_prefix Binary prefix flag.
	 *
	 * @return string
	 */
	private static function nice_file_size( $bytes, $binary_prefix = true ) {
		$bytes = (int) $bytes;

		if ( is_int( $bytes ) && $bytes > 0 ) {
			if ( $binary_prefix ) {
				$unit = array( 'B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB' );
				if ( 0 == $bytes ) {
					return '0 ' . $unit[0];
				}
				$i = floor( log( $bytes, 1024 ) );
				return @round( $bytes / pow( 1024, ( $i ) ), 2 ) . ' ' . ( isset( $unit[ $i ] ) ? $unit[ $i ] : 'B' );
			} else {
				$unit = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
				if ( 0 == $bytes ) {
					return '0 ' . $unit[0];
				}
				$i = floor( log( $bytes, 1000 ) );
				return @round( $bytes / pow( 1000, ( $i ) ), 2 ) . ' ' . ( isset( $unit[ $i ] ) ? $unit[ $i ] : 'B' );
			}
		}
	}
}
