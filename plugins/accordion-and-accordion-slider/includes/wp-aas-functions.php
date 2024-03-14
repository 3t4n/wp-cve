<?php
/**
 * Plugin generic functions file
 *
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 * 
 * @since 1.0
 */
function wp_aas_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'wp_aas_clean', $var );
	} else {
		$data = is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		return wp_unslash($data);
	}
}

/**
 * Sanitize URL
 * 
 * @since 1.0
 */
function wp_aas_clean_url( $url ) {
	return esc_url_raw( trim( $url ) );
}

/**
 * Sanitize number value and return fallback value if it is blank
 * 
 * @since 1.2.3
 */
function wp_aas_clean_number( $var, $fallback = null, $type = 'int' ) {

	$var = trim( $var );
	$var = is_numeric( $var ) ? $var : 0;

	if ( $type == 'number' ) {
		$data = intval( $var );
	} else if ( $type == 'abs' ) {
		$data = abs( $var );
	} else if ( $type == 'float' ) {
		$data = (float)$var;
	} else {
		$data = absint( $var );
	}

	return ( empty( $data ) && isset( $fallback ) ) ? $fallback : $data;
}

/**
 * Function to unique number value
 * 
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */
function wp_aas_get_unique() {
	static $unique = 0;
	$unique++;

	return $unique;
}

/**
 * Function to add array after specific key
 * 
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */
function wp_aas_add_array(&$array, $value, $index, $from_last = false) {

	if( is_array($array) && is_array($value) ) {

		if( $from_last ) {
			$total_count	= count($array);
			$index			= ( ! empty( $total_count ) && ( $total_count > $index )) ? ( $total_count-$index ) : $index;
		}

		$split_arr	= array_splice( $array, max( 0, $index ));
		$array		= array_merge( $array, $value, $split_arr );
	}
	
	return $array;
}

/**
 * Function to get registered post types
 * 
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */
function wp_aas_get_post_types() {

	// Getting registered post type
	$post_type_args = array(
		'public' => true
	);

	$custom_post_types = get_post_types($post_type_args);
	$custom_post_types = (!empty($custom_post_types) && is_array($custom_post_types)) ? array_keys($custom_post_types) : array();

	// Exclude some post type
	$include_post_types = apply_filters('wp_aas_gallery_support', array(WP_AAS_POST_TYPE));
	$custom_post_types = array_merge($custom_post_types, (array)$include_post_types);

	// Exclude some post type
	$exclude_post_types = apply_filters('wp_aas_gallery_support', array('attachment'));
	$custom_post_types = array_diff($custom_post_types, (array)$exclude_post_types);

	return $custom_post_types;
}