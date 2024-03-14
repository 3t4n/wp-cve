<?php
namespace Elementor\Core\Responsive;

use Elementor\Core\Responsive\Files\Frontend;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor responsive.
 *
 * Elementor responsive handler class is responsible for setting up Elementor
 * responsive breakpoints.
 *
 * @since 1.0.0
 */
class Responsive {

	/**
	 * The Elementor breakpoint prefix.
	 */
	const BREAKPOINT_OPTION_PREFIX = 'elementor_viewport_';

	/**
	 * Default breakpoints.
	 *
	 * Holds the default responsive breakpoints.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var array Default breakpoints.
	 */
	private static $default_breakpoints = [
		'xs' => 0,
		'sm' => 480,
		'md' => 768,
		'lg' => 1025,
		'xl' => 1440,
		'xxl' => 1600,
	];

	/**
	 * Editable breakpoint keys.
	 *
	 * Holds the editable breakpoint keys.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var array Editable breakpoint keys.
	 */
	private static $editable_breakpoints_keys = [
		'md',
		'lg',
	];

	/**
	 * Get default breakpoints.
	 *
	 * Retrieve the default responsive breakpoints.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return array Default breakpoints.
	 */
	public static function get_default_breakpoints() {
		return self::$default_breakpoints;
	}

	/**
	 * Get editable breakpoints.
	 *
	 * Retrieve the editable breakpoints.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return array Editable breakpoints.
	 */
	public static function get_editable_breakpoints() {
		return array_intersect_key( self::get_breakpoints(), array_flip( self::$editable_breakpoints_keys ) );
	}

	/**
	 * Get breakpoints.
	 *
	 * Retrieve the responsive breakpoints.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return array Responsive breakpoints.
	 */


	public static function jltma_cbps() {
	    $default_breakpoints = self::$default_breakpoints;
	    $custom_breakpoints = json_decode(json_encode(get_option( 'jltma_mcb', [] )), true);
	    return array_merge($default_breakpoints, $custom_breakpoints);
	}

	public static function get_breakpoints() {
		
		self::$default_breakpoints = self::jltma_cbps();

		return array_reduce(
			array_keys( self::$default_breakpoints ), function( $new_array, $breakpoint_key ) {
				if ( ! in_array( $breakpoint_key, self::$editable_breakpoints_keys ) ) {
					$new_array[ $breakpoint_key ] = self::$default_breakpoints[ $breakpoint_key ];
				} else {
					$saved_option = Plugin::$instance->kits_manager->get_current_settings( self::BREAKPOINT_OPTION_PREFIX . $breakpoint_key );

					$new_array[ $breakpoint_key ] = $saved_option ? (int) $saved_option : self::$default_breakpoints[ $breakpoint_key ];
				}

				return $new_array;
			}, []
		);

	}


	/**
	 * @since 2.1.0
	 * @access public
	 * @static
	 */
	public static function has_custom_breakpoints() {
		return self::get_breakpoints();
	}

	/**
	 * @since 2.1.0
	 * @access public
	 * @static
	 */
	public static function get_devices() {
		
		$breakpoints = self::get_breakpoints();

		$devices = [
			'desktop' => [
				'key' => 'desktop',
				'name' => __('Desktop', 'elementor'),
				'min' => $breakpoints['lg'],
				'max' => 'full',
				'icon' => 'desktop'
			],
			'tablet' => [
				'key' => 'tablet',
				'name' => __('Tablet', 'elementor'),
				'min' => $breakpoints['md'],
				'max' => $breakpoints['lg'] - 1,
				'icon' => 'tablet'
			],
			'mobile' => [
				'key' => 'mobile',
				'name' => __('Mobile', 'elementor'),
				'min' => 0,
				'max' => $breakpoints['md'] - 1,
				'icon' => 'mobile'
			]
		];

		$desktop_min = $breakpoints['lg'];
		$tablet_max = $breakpoints['lg'] - 1;
		$mobile_max = $breakpoints['md'] - 1;

		foreach( $breakpoints as $bp_key => $bp_val ) {

			if ( gettype($bp_val) != 'array' ) continue;

			$min = $bp_val['input1'];

			if ( $bp_val['input2'] >= $desktop_min ) $desktop_min = $bp_val['input2'] + 1;

			$icon = 'desktop';
			if ( $min < $devices['desktop']['min'] ) $icon = 'tablet';
			if ( $min < $devices['tablet']['min'] ) $icon = 'mobile';

			$max = $bp_val['input2'];

			if ( $min > $devices['tablet']['min'] && $min < $devices['tablet']['max'] ) $tablet_max = $min - 1;
			if ( $min > $devices['mobile']['min'] && $min < $devices['mobile']['max'] ) $mobile_max = $min - 1;

			$devices[$bp_key] = [
				'key' 	=> $bp_key,
				'name' 	=> $bp_val['name'],
				'min' 	=> (int) $min,
				'max' 	=> (int) $max,
				'icon' 	=> $icon
			];

		}

		$devices['desktop']['min'] = (int) $desktop_min;
		$devices['tablet']['max'] = (int) $tablet_max;
		$devices['mobile']['max'] = (int) $mobile_max;

		return wp_list_sort( $devices, 'min', 'DESC' );

	}

	/**
	 * @since 2.1.0
	 * @access public
	 * @static
	 */
	public static function get_stylesheet_templates_path() {
		return ELEMENTOR_ASSETS_PATH . 'css/templates/';
	}

	/**
	 * @since 2.1.0
	 * @access public
	 * @static
	 */
	public static function compile_stylesheet_templates() {
		foreach ( self::get_stylesheet_templates() as $file_name => $template_path ) {
			$file = new Frontend( $file_name, $template_path );

			$file->update();
		}
	}

	/**
	 * @since 2.1.0
	 * @access private
	 * @static
	 */
	private static function get_stylesheet_templates() {
		$templates_paths = glob( self::get_stylesheet_templates_path() . '*.css' );

		$templates = [];

		foreach ( $templates_paths as $template_path ) {
			$file_name = 'custom-' . basename( $template_path );

			$templates[ $file_name ] = $template_path;
		}

		return apply_filters( 'elementor/core/responsive/get_stylesheet_templates', $templates );
	}
}
