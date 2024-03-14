<?php
/**
 * Plugin generic functions file
 *
 * @package Product Slider and Carousel with Category for WooCommerce
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to unique number value
 * 
 * @since 1.2.5
 */
function wcpscwc_get_unique() {
	
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
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 * 
 * @since 2.5
 */
function wcpscwc_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'wcpscwc_clean', $var );
	} else {
		$data = is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		return wp_unslash( $data );
	}
}

/**
 * Sanitize number value and return fallback value if it is blank
 * 
 * @since 2.5
 */
function wcpscwc_clean_number( $var, $fallback = null, $type = 'int' ) {

	if ( $type == 'number' ) {
		$data = intval( $var );
	} else {
		$data = absint( $var );
	}

	return ( empty( $data ) && isset( $fallback ) ) ? $fallback : $data;
}

/**
 * Sanitize Multiple HTML class
 * 
 * @since 2.5
 */
function wcpscwc_sanitize_html_classes($classes, $sep = " ") {
	$return = "";

	if( ! is_array( $classes ) ) {
		$classes = explode($sep, $classes);
	}

	if( ! empty( $classes ) ) {
		foreach($classes as $class){
			$return .= sanitize_html_class($class) . " ";
		}
		$return = trim( $return );
	}

	return $return;
}

/**
 * Function to check woocommerce version
 * 
 * @since 1.2.2
 */
function wcpscwc_wc_version( $version = '3.0', $operator = '>=' ) {
	
	global $woocommerce;

	$operator = ! empty( $operator ) ? $operator : '=';

	if( version_compare( $woocommerce->version, $version, $operator ) ) {
		return true;
	}
	return false;
}