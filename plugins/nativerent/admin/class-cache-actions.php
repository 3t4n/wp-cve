<?php

namespace NativeRent\Admin;

defined( 'ABSPATH' ) || exit;

use function add_action;
use function defined;
use function get_option;
use function update_option;

/**
 * Admin\Cache_Actions class
 */
class Cache_Actions {

	const CACHE_CLEAR_OPTION = 'nativerent_need_to_clear_cache';

	/**
	 * Button
	 *
	 * @var Clear_Cache_Button
	 */
	public static $button;

	/**
	 * Init
	 */
	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'check_need_to_clear_cache' ), 20 );
		add_action( 'nativerent_cache_is_cleared', array( __CLASS__, 'do_after_cache_is_cleared' ) );

		self::$button = Clear_Cache_Button::instance();
	}

	/**
	 * Check need to clear cache
	 */
	public static function check_need_to_clear_cache() {
		$option = get_option( self::CACHE_CLEAR_OPTION );
		if ( ! $option ) {
			return;
		}

		switch ( $option ) {
			case '2':
				$type = '_adjust';
				break;
			case '3':
				$type = '_deactivated';
				break;
			default:
				$type = '';
				break;
		}
		self::add_notice( 'clear_cache' . $type );
	}

	/**
	 * Add notice
	 *
	 * @param  string $notice  Notice name to add.
	 */
	private static function add_notice( $notice = '' ) {
		Notices::add_notice( $notice );
	}

	/**
	 * Fix need to clear cache
	 *
	 * @param  string $value  Option value to set.
	 */
	public static function need_to_clear_cache( $value = '1' ) {
		self::update_option( $value ? $value : '1' );
	}

	/**
	 * Action after cache is cleared
	 */
	public static function do_after_cache_is_cleared() {
		self::add_notice( 'cache_is_cleared' );
		self::update_option();
	}

	/**
	 * Update plugin version in the database
	 *
	 * @param  int $value  Option value.
	 */
	public static function update_option( $value = '0' ) {
		update_option( self::CACHE_CLEAR_OPTION, $value ); // True for autoload by default.
	}
}
