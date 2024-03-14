<?php
/**
 * Plugin generic functions file
 *
 * @package Timeline and History Slider
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to get plugin image sizes array
 * 
 * @package Timeline and History Slider
 * @since 1.0.0
 */
function wpostahs_get_unique() {
	static $unique = 0;
	$unique++;

	// For Elementor & Beaver Builder
	if( ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_POST['action'] ) && $_POST['action'] == 'elementor_ajax' )
	|| ( class_exists('FLBuilderModel') && ! empty( $_POST['fl_builder_data']['action'] ) )
	|| ( function_exists('vc_is_inline') && vc_is_inline() ) ) {
		$unique = current_time('timestamp') . '-' . rand();
	}

	return $unique;
}

/**
 * Sanitize Multiple HTML class
 * 
 * @package Timeline and History Slider
 * @since 1.2.1
 */
function wpostahs_sanitize_html_classes($classes, $sep = " ") {
	$return = "";

	if( $classes && !is_array($classes) ) {
		$classes = explode($sep, $classes);
	}

	if( !empty($classes) ) {
		foreach($classes as $class){
			$return .= sanitize_html_class($class) . " ";
		}
		$return = trim( $return );
	}

	return $return;
}

/**
 * Function to add array after specific key
 * 
 * @package Timeline and History Slider
 * @since 1.3.4
 */
function wpostahs_logo_add_array(&$array, $value, $index, $from_last = false) {

	if( is_array($array) && is_array($value) ) {

		if( $from_last ) {
			$total_count	= count($array);
			$index			= (!empty($total_count) && ($total_count > $index)) ? ($total_count-$index): $index;
		}

		$split_arr	= array_splice($array, max(0, $index));
		$array		= array_merge( $array, $value, $split_arr);
	}

	return $array;
}