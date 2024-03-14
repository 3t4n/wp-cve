<?php
/**
 * Church Tithe WP
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/Church Tithe WP
 * @copyright   Copyright (c) 2018, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode which is used to output the tithing form
 *
 * @since    1.0.0
 * @param    array $atts The shortcode's attributes.
 * @return   string
 */
function churchtithewp_shortcode_callback( $atts ) {

	$atts = shortcode_atts(
		array(
			'mode'       => 'form',
			'link_text'  => __( 'Leave a tithe', 'church-tithe-wp' ),
			'open_style' => null,
			'custom_id'  => '',
		),
		$atts,
		'churchtithewp'
	);

	$default_args = church_tithe_wp_tithe_form_vars();

	$sanitized_args = $default_args;

	// Sanitize the custom html ID.
	if ( ! empty( $atts['custom_id'] ) ) {
		$sanitized_args['custom_id'] = sanitize_text_field( wp_unslash( $atts['custom_id'] ) );
	}

	// Sanitize the Mode.
	if ( ! empty( $atts['mode'] ) ) {
		$sanitized_args['mode'] = sanitize_text_field( wp_unslash( $atts['mode'] ) );
	}

	// Sanitize the link text.
	if ( ! empty( $atts['link_text'] ) ) {
		$sanitized_args['strings']['link_text'] = sanitize_text_field( wp_unslash( $atts['link_text'] ) );
	}

	// Sanitize the open_style.
	if ( ! empty( $atts['open_style'] ) ) {
		$sanitized_args['open_style'] = sanitize_text_field( wp_unslash( $atts['open_style'] ) );
	}

	return church_tithe_wp_generate_output_for_tithe_form( $sanitized_args );

}
add_shortcode( 'churchtithewp', 'churchtithewp_shortcode_callback' );
