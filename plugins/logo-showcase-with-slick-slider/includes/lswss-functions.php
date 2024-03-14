<?php
/**
 * Plugin generic functions file
 *
 * @package Logo Showcase with Slick Slider
 * @since 1.0
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
function lswss_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'lswss_clean', $var );
	} else {
		$data = is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		return wp_unslash($data);
	}
}

/**
 * Sanitize number value and return fallback value if it is blank
 * 
 * @since 1.0
 */
function lswss_clean_number( $var, $fallback = null, $type = 'int' ) {

	$var = is_numeric( $var ) ? trim( $var ) : 0;

	if( $type == 'int' ) {
		$data = absint( $var );
	} elseif ( $type == 'number' ) {
		$data = intval( $var );
	} else {
		$data = abs( $var );
	}

	return ( empty($data) && isset($fallback) ) ? $fallback : $data;
}

/**
 * Sanitize url
 * 
 * @since 1.0
 */
function lswss_clean_url( $url ) {
	return esc_url_raw( trim( $url ) );
}

/**
 * Clean Html Tags
 * Allow only WordPress Post supported HTML tags.
 * 
 * @since 1.0
 */
function lswss_clean_html( $data = array() ) {

	if ( is_array($data) ) {

		$data = array_map('lswss_clean_html', $data);

	} elseif ( is_string( $data ) ) {

		$data = trim( $data );
		$data = wp_filter_post_kses($data);
	}

	return $data;
}

/**
 * Sanitize multiple HTML classes
 * 
 * @since 1.0
 */
function lswss_sanitize_html_classes($classes, $sep = " ") {
	$return = "";

	if( ! is_array( $classes ) ) {
		$classes = explode($sep, $classes);
	}

	if( ! empty( $classes ) ) {
		foreach($classes as $class) {
			$return .= sanitize_html_class( $class ) . " ";
		}
		$return = trim( $return );
	}

	return $return;
}

/**
 * Function to unique number value
 * 
 * @since 1.0
 */
function lswss_get_unique() {
	static $unique = 0;
	$unique++;

	// For VC front end editing
	if ( ( function_exists('vc_is_page_editable') && vc_is_page_editable() ) || 
		 ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_POST['action'] ) && $_POST['action'] == 'elementor_ajax' && isset($_POST['editor_post_id']) )
		)
	{
		return rand() .'-'. current_time( 'timestamp' );
	}

	return $unique;
}

/**
 * Function to add array after specific key
 * 
 * @since 1.0
 */
function lswss_add_array(&$array, $value, $index, $from_last = false) {
	
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

/**
 * Function to get image
 * 
 * @since 1.0
 */
function lswss_get_image( $post_id = '', $size = 'full' ) {

	$size			= ! empty( $size ) ? $size : 'full';
	$image_data		= wp_get_attachment_image_src( $post_id, $size );
	$image			= isset( $image_data[0] ) ? $image_data[0] : '';
	
	return $image;
}

/**
 * Function to validate that public script should be enqueue at last.
 * Call this function at last.
 * 
 * @since 1.0
 */
function lswss_enqueue_script() {

	// Check public script is in queue
	if( wp_script_is( 'lswssp-public-script', 'enqueued' ) ) {
		
		// Dequeue Script
		wp_dequeue_script( 'lswssp-public-script' );

		// Enqueue Script
		wp_enqueue_script( 'lswssp-public-script' );
	}
}

/**
 * Function to get display type
 * 
 * @since 1.0
 */
function lswss_display_type() {

	$display_type = array(
						'slider'	=> __('Logo Showcase Carousel', 'logo-showcase-with-slick-slider'),
						'grid'		=> __('Logo Showcase Grid', 'logo-showcase-with-slick-slider'),						
					);

	return $display_type;
}

/**
 * Function to get logo grid shortcode designs
 * 
 * @since 1.0
 */
function lswss_logo_grid_designs() {

	$designs = array(
						'design-1'	=> __('Design 1', 'logo-showcase-with-slick-slider'),						
						
					);
	return $designs;
}

/**
 * Function to get logo slider shortcode designs
 * 
 * @since 1.0
 */
function lswss_logo_slider_designs() {

	$designs = array(
						'design-1'	=> __('Design 1', 'logo-showcase-with-slick-slider'),						
					);
	return $designs;
}

/**
 * Function to get post meta settings and set default setting array if not there.
 * 
 * @since 1.0
 */
function lswss_get_post_sett( $post_id ) {

	// Default Settings
	$default_sett = array(
						'grid'	=> array(
										'design'			=> 'design-1',
										'show_title'		=> 'false',
										'show_desc'			=> 'false',
										'grid'				=> 5,
										'link_target'		=> '_blank',
										'min_height'		=> '',
										'max_height'		=> 200,
										'ipad'				=> '',
										'tablet'			=> '',
										'mobile'			=> '',
									),
						'slider'	=> array(
										'design'			=> 'design-1',
										'show_title'		=> 'false',
										'show_desc'			=> 'false',
										'link_target'		=> '_blank',
										'min_height'		=> '',
										'max_height'		=> 200,
										'slides_show'		=> 5,
										'slides_scroll'		=> 1,
										'autoplay'			=> 'true',
										'autoplay_speed'	=> 3000,
										'speed'				=> 600,
										'arrow'				=> 'true',
										'dots'				=> 'true',
										'pause_on_hover'	=> 'true',
										'loop'				=> 'true',
										'centermode'		=> 'false',
										'center_padding'	=> '',
										'ipad'				=> '',
										'tablet'			=> '',
										'mobile'			=> '',
									),
							
						'tab' => '#lswss_grid_general_sett',
					);

	// Getting Post Setting
	$prefix		= LSWSS_META_PREFIX;
	$post_sett	= get_post_meta( $post_id, $prefix.'sett', true );
	$post_sett	= ! empty( $post_sett ) ? (array)$post_sett : array();

	$settings	= lswss_wp_parse_args( $post_sett, $default_sett );

	return $settings;
}

/**
 * Function to recursively merge multidimentional array with default one.
 * 
 * @since 1.0
 */
function lswss_wp_parse_args( $settings, $default_settings ) {

	$settings			= (array)$settings;
	$default_settings	= (array)$default_settings;

	// First simply merge single values 
	$settings = wp_parse_args( $settings, $default_settings );

	// Merge Sub Array Values
	foreach ($default_settings as $default_sett_key => $default_sett_val) {
		if ( is_array( $default_sett_val ) && isset( $settings[$default_sett_key] ) ) {
			$settings[$default_sett_key] = wp_parse_args( $settings[$default_sett_key], $default_settings[$default_sett_key] );
		}
	}

	return $settings;
}