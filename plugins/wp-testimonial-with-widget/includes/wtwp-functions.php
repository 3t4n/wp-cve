<?php
/**
 * Plugin generic functions file
 * 
 * @package WP Testimonials with rotator widget Pro
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to get plugin image sizes array
 * 
 * @since 2.2.4
 */
function wtwp_get_unique() {

	static $unique = 0;
	$unique++;

	// For Elementor & Beaver Builder
	if( ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_POST['action'] ) && $_POST['action'] == 'elementor_ajax' )
	|| ( class_exists('FLBuilderModel') && ! empty( $_POST['fl_builder_data']['action'] ) ) ) {
		$unique = current_time('timestamp') . '-' . rand();
	}

	return $unique;
}

/**
 * Function to get user image
 * 
 * @since 1.0
 */
function wtwp_get_image( $id, $size, $style = "circle" ) {

	$response = '';

	if ( has_post_thumbnail( $id ) ) {
		// If not a string or an array, and not an integer, default to 150x9999.
		if ( ( is_int( $size ) || ( 0 < intval( $size ) ) ) && ! is_array( $size ) ) {
			$size = array( intval( $size ), intval( $size ) );
		} elseif ( ! is_string( $size ) && ! is_array( $size ) ) {
			$size = array( 100, 100 );
		}

		$response = get_the_post_thumbnail( intval( $id ), $size, array('class' => $style) );

	}
	return $response;
}

/**
 * Sanitize number value and return fallback value if it is blank
 * 
 * @since 1.0
 */
function wtwp_clean_number( $var, $fallback = null ) {
	$data = absint( $var );
	return ( empty( $data ) && $fallback ) ? $fallback : $data;
}

/**
 * Sanitize Multiple HTML class
 * 
 * @since 1.0
 */
function wtwp_sanitize_html_classes($classes, $sep = " ") {
    $return = "";

    if( ! is_array( $classes ) ) {
        $classes = explode( $sep, $classes );
    }

    if( ! empty( $classes ) ) {
        foreach( $classes as $class ) {
            $return .= sanitize_html_class( $class ) . " ";
        }
        $return = trim( $return );
    }

    return $return;
}

/**
 * Function to add array after specific key
 * 
 * @since 1.0
 */
function wtwp_add_array(&$array, $value, $index, $from_last = false) {

	if( is_array($array) && is_array($value) ) {

		if( $from_last ) {
			$total_count 	= count($array);
			$index 			= ( ! empty( $total_count ) && ( $total_count > $index ) ) ? ( $total_count - $index ): $index;
		}

		$split_arr 	= array_splice( $array, max(0, $index) );
		$array 		= array_merge( $array, $value, $split_arr );
	}

	return $array;
}

/**
 * Function get_custom_fields_settings
 * 
 * @since 1.0
 */
function get_custom_fields_settings () {
	$fields = array();

	$fields['testimonial_client'] = array(
	    'name'			=> __( 'Client Name', 'wp-testimonial-with-widget' ),
	    'description'	=> __( 'Enter client name.', 'wp-testimonial-with-widget' ),
	    'type'			=> 'text',
	    'default'		=> '',
	    'section'		=> 'info'
	);

	$fields['testimonial_job'] = array(
	    'name'			=> __( 'Job Title', 'wp-testimonial-with-widget' ),
	    'description'	=> __( 'Enter job title.', 'wp-testimonial-with-widget' ),
	    'type'			=> 'text',
	    'default'		=> '',
	    'section'		=> 'info'
	);

	$fields['testimonial_company'] = array(
	    'name'			=> __( 'Company', 'wp-testimonial-with-widget' ),
	    'description'	=> __( 'Enter company name.', 'wp-testimonial-with-widget' ),
	    'type'			=> 'text',
	    'default'		=> '',
	    'section'		=> 'info'
	);

	$fields['testimonial_url'] = array(
	    'name'			=> __( 'URL', 'wp-testimonial-with-widget' ),
	    'description'	=> __( 'Enter company or job url.', 'wp-testimonial-with-widget' ),
	    'type'			=> 'text',
	    'default'		=> '',
	    'section'		=> 'info'
	);

	return $fields;
}

/**
 * Function to get shortcode design
 * 
 * @since 1.0
 */
function wptww_designs() {

	$design_arr = array(
		'design-1'	=> __( 'Design 1', 'wp-testimonial-with-widget' ),
		'design-2'	=> __( 'Design 2', 'wp-testimonial-with-widget' ),
		'design-3'	=> __( 'Design 3', 'wp-testimonial-with-widget' ),
		'design-4'	=> __( 'Design 4', 'wp-testimonial-with-widget' ),
	);
	
	return apply_filters('wptww_designs', $design_arr );
}