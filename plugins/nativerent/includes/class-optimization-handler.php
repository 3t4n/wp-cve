<?php
/**
 * Optimization handler
 *
 * @package nativerent
 */

namespace NativeRent;

defined( 'ABSPATH' ) || exit;

/**
 * Class Optimization_Handler
 */
class Optimization_Handler {

	/**
	 * Files
	 *
	 * @var array
	 */
	public static $scripts_patterns
		= array(
			'nativerent',
			'window.NRent(AdUnits|Counter|Plugin)',
			'nativerent-integration-head',
		);

	/**
	 * Init actions
	 */
	public static function init() {
		// WP Rocket.
		add_filter( 'rocket_exclude_js', array( __CLASS__, 'exclude_js' ) );
		add_filter( 'rocket_exclude_defer_js', array( __CLASS__, 'exclude_js' ) );
		add_filter( 'rocket_delay_js_exclusions', array( __CLASS__, 'exclude_js' ) );
		add_filter( 'rocket_excluded_inline_js_content', array( __CLASS__, 'exclude_js' ) );

		// LiteSpeed Cache.
		add_filter( 'litespeed_optimize_js_excludes', array( __CLASS__, 'exclude_js' ) );
		add_filter( 'litespeed_optm_js_defer_exc', array( __CLASS__, 'exclude_js' ) );
		add_filter( 'litespeed_optm_gm_js_exc', array( __CLASS__, 'exclude_js' ) );
	}

	/**
	 * Excludes JS files from minification/combine
	 *
	 * @param  string[]|null $scripts  Array of JS patterns to be excluded.
	 *
	 * @return string[]
	 */
	public static function exclude_js( $scripts = array() ) {
		return ! is_array( $scripts )
			? self::$scripts_patterns
			: array_merge( $scripts, self::$scripts_patterns );
	}
}
