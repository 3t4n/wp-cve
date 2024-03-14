<?php
/*----------------------------------------------------------------------------*\
	prime_slider Param
\*----------------------------------------------------------------------------*/

vc_add_shortcode_param( 'prime_slider', 'prime_slider_range_function', ASVC_URL . '/admin/params/slider/js/slider-params.js');
function prime_slider_range_function( $settings, $value ) {
	$defaults = array(
		'min'        => 0,
		'max'        => 100,
		'step'       => 1,
		'value'      => 0,
		'unit'       => '',
		'fill'       => true,
		'hide_input' => false
	);
	$settings = wp_parse_args( $settings, $defaults );
	$value = $value == null ? $settings[ 'value' ] : $value;

	$slider = '<div class="prime-vc-slider' . ( $settings[ 'hide_input' ] ? ' prime-hide-input' : '' ) . ( $settings[ 'fill' ] ? ' prime-fill' : '' ) . '">';
		$slider .= '<div class="prime-slider" data-min="' . esc_attr( $settings[ 'min' ] ) . '" data-max="' . esc_attr( $settings[ 'max' ] ) . '" data-step="' . esc_attr( $settings[ 'step' ] ) . '" data-value="' . esc_attr( $value ) . '"></div>';
	$slider .= '</div>';

	$input = '<input class="prime-value wpb_vc_param_value wpb-input ' . esc_attr( $settings[ 'param_name' ] ) . '" name="' . esc_attr( $settings[ 'param_name' ] ) . '" value="' . esc_attr( $value ) . '" type="text" />';

	$unit = $settings[ 'unit' ] != '' ? '<span class="prime-unit">' . $settings[ 'unit' ] . '</span>' : '';

	return '<div class="prime-slider-wrap">' . $slider . $unit . $input . '</div>';
}
