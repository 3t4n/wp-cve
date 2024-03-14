<?php
/**
 * Functions File
 *
 * @package WP Trending Post Slider and Widget
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Update default settings
 * 
 * @since 1.0.0
 */
function wtpsw_default_settings(){ 

	global $wtpsw_options;

	$wtpsw_options = array(
							'post_range'	=> '',
							'post_types'	=> array( 'post' ),
						);

	$default_options = apply_filters('wtpsw_options_default_values', $wtpsw_options );

	// Update default options
	update_option( 'wtpsw_options', $default_options );

	// Overwrite global variable when option is update
	$wtpsw_options = wtpsw_get_settings();
}

/**
 * Get Settings From Option Page
 * 
 * Handles to return all settings value
 * 
 * @since 1.0.0
 */
function wtpsw_get_settings() {

	$options = get_option( 'wtpsw_options' );
	$settings = is_array( $options )	? $options : array();

	return $settings;
}

/**
 * Get an option
 * Looks to see if the specified setting exists, returns default if not
 * 
 * @since 1.0
 */
function wtpsw_get_option( $key = '', $default = false ) {
	global $wtpsw_options;

	$value = ! empty( $wtpsw_options[ $key ] ) ? $wtpsw_options[ $key ] : $default;
	$value = apply_filters( 'wtpsw_get_option', $value, $key, $default );
	return apply_filters( 'wtpsw_get_option_' . $key, $value, $key, $default );
}

/**
 * Sanitize Multiple HTML class
 * 
 * @since 1.5
 */
function wtpsw_sanitize_html_classes($classes, $sep = " ") {
	$return = "";

	if( ! is_array( $classes ) ) {
		$classes = explode( $sep, $classes );
	}

	if( ! empty( $classes ) ) {
		foreach( $classes as $class ){
			$return .= sanitize_html_class( $class ) . " ";
		}
		$return = trim( $return );
	}

	return $return;
}

/**
 * Convert Object To Array
 * 
 * @since 1.0.0
 */
function wtpsw_object_to_array($result) {

	$array = array();

	foreach ( $result as $key=>$value ) {
		if ( is_object( $value )) {
			$array[$key] = wtpsw_object_to_array( $value );
		} else {
			$array[$key] = $value;
		}
	}
	return $array;
}

/**
 * Function to unique number value
 * 
 * @since 1.0.0
 */
function wtpsw_get_unique() {
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
 * Function to get post excerpt
 * 
 * @since 1.0.0
 */
function wtpsw_get_post_excerpt( $post_id = null, $content = '', $word_length = '55', $more = '...' ) {

	$word_length = ! empty( $word_length ) ? $word_length : '55';

	// If post id is passed
	if( ! empty( $post_id )) {
		if ( has_excerpt( $post_id )) {
			$content = get_the_excerpt();
		} else {
			$content = ! empty( $content ) ? $content : get_the_content();
		}
	}

	if( ! empty( $content ) ) {
		$content = strip_shortcodes( $content ); // Strip shortcodes
		$content = wp_trim_words( $content, $word_length, $more );
	}

	return $content;
}

/**
 * Function to get registered post types
 * 
 * @since 1.0.0
 */
function wtpsw_get_post_types( $args = array() ) {

	// Taking defaults
	$all_post_types = array();
	$post_types		= array();

	$args = array(
				'public' => ! empty( $args['public'] ) ? $args['public'] : 'true'
				);

	$all_post_types = get_post_types( $args, 'object' );
	$exclude_post   = array( 'attachment' );

	foreach ( $all_post_types as $post_type_key => $post_data ) {
		if( ! in_array( $post_type_key, $exclude_post )) {
			$post_types[$post_type_key] = ! empty( $post_data->label ) ? $post_data->label : $post_type_key;
		}
	}

	return apply_filters( 'wtpsw_get_post_types', $post_types );
}

/**
 * Function to get comment count text
 * 
 * @since 1.0.0
 */
function wtpsw_get_comments_number( $post_id = '', $hide_empty = false ) {

	$comment_text = '';

	if( ! empty( $post_id )) {

		$comment_number = get_comments_number( $post_id );

		if ( $comment_number == 0 && empty( $hide_empty )) {
			$comment_text = esc_html__( '0 Comments', 'wtpsw' );
		} elseif ( $comment_number > 1 ) {
			$comment_text = $comment_number . esc_html__(' Comments', 'wtpsw');
		} elseif ( $comment_number == 1 ) {
			$comment_text = esc_html__('1 Comment', 'wtpsw');
		}
	}

	return $comment_text;
}