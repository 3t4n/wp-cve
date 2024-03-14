<?php
/**
 * Logo Showcase Shortcodes
 * 
 * @package Logo Showcase with Slick Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function lswss_render_logo_showcase( $atts, $content = '' ) {

	// Shortcode Parameters
	$atts = shortcode_atts(array(
		'id'		=> '',
		'css_class'	=> '',
	), $atts, 'slick_logo_carousel');

	// Return if no ID is passed
	if( empty( $atts['id'] ) ) {
		return $content;
	}

	// Validate Logo Showcase Post
	$showcase_post			= get_post( $atts['id'] );
	$showcase_post_type		= isset( $showcase_post->post_type ) 	? $showcase_post->post_type		: '';
	$showcase_post_status	= isset( $showcase_post->post_status )	? $showcase_post->post_status	: '';

	// Return if post is not valid
	if( $showcase_post_type != LSWSS_POST_TYPE || $showcase_post_status != 'publish' ) {
		return $content;
	}

	// Getting logo display type
	$prefix			= LSWSS_META_PREFIX;
	$display_type 	= get_post_meta( $atts['id'], $prefix.'display_type', true );
	$display_type	= ! empty( $display_type ) ? $display_type : 'slider';

	// Template Variables
	$atts['display_type']	= $display_type;
	$atts['images']			= get_post_meta( $atts['id'], $prefix.'gallery_id', true );

	// Return if no logo images are there
	if( empty( $atts['images'] ) ) {
		return $content;
	}

	ob_start();

	do_action( "lswss_render_logo_showcase_{$display_type}", $atts, $content, $showcase_post );

	$content .= ob_get_clean();
	return $content;
}

// Logo Showcase Shortcode
add_shortcode( 'slick_logo_carousel', 'lswss_render_logo_showcase' );