<?php
/**
 * Shortcodes
 */

defined( 'ABSPATH' ) || exit;

/**
 * OpenTable Widget shortcode.
 *
 * @param  array  $atts Shortcode configuration attributes pulled from shortcode instance.
 * @return string       Widget script embed if restaurant ID has been specified, error message if not.
 */
function sot_shortcode_widget( $atts = array() ) {

	// Shortcode attributes
	$atts = shortcode_atts( array(
		'restaurant-id' => '',
		'type'          => 'standard',
		'language'      => 'en',
		'iframe'        => 'true',
	), $atts );

	// If no restaurant ID has been specified, return an error message
	if ( ! $atts['restaurant-id'] ) {
		return sprintf( '<p>%s</p>', esc_html__( 'Error: Please specify your OpenTable restaurant ID in the shortcode.', 'shortcode-for-opentable' ) );
	}

	// Build the embed URL
	$url = esc_url( add_query_arg( array(
		'rid'     => $atts['restaurant-id'],
		'domain'  => 'com',
		'type'    => $atts['type'] == 'button' ? 'button' : 'standard',
		'theme'   => $atts['type'],
		'lang'    => $atts['language'],
		'overlay' => 'false',
		'iframe'  => $atts['iframe'],
	), 'https://www.opentable.com/widget/reservation/loader' ) );

	// Only enqueue the front-end CSS when the shortcode is actually being used
	wp_enqueue_style( 'sot-front-end' );

	// Return the script tag
	return "<script type='text/javascript' src='$url'></script>";

}
add_shortcode( 'opentable-widget', 'sot_shortcode_widget' );
