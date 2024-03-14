<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * @param $settings
 * @param $value
 *
 * @return string
 * @since 4.4
 */
function select_tag_field( $settings, $value ) {
    $fmc_field = !empty($settings['param_name']) ? $settings['param_name'] : '';
    $collection = !empty($settings['value']) ? $settings['value'] : '';
    $option_value_attr = !empty($settings['option_value_attr']) ? $settings['option_value_attr'] : '';
    $option_display_attr = !empty($settings['option_display_attr']) ? $settings['option_display_attr'] : '';
	$default = !empty($settings['default']) ? $settings['default'] : '';
	$isMultiple = !empty($settings['multiple']) ? $settings['multiple'] : '';
	
	if($isMultiple === 'true'){
		$multiply = 'style="height: 110px;" multiple="multiple"';
	} else {
		$multiply = '';
	}

	$output = '';
	$output .= '<select fmc_field="' . $fmc_field . '" fmc-type="select" name="' . $fmc_field . '" class="wpb_vc_param_value wpb-input wpb-select ' . $fmc_field . ' ' . $settings['type'] . '" '.$multiply.'>';
	if ( is_array( $value ) ) {
		$value = isset( $value[$option_value_attr] ) ? $value[$option_value_attr] : array_shift( $value );
	}
	if ( ! empty( $collection ) ) {
		foreach ( $collection as $index => $data ) {
			if ( is_numeric( $index ) && ( is_string( $data ) || is_numeric( $data ) ) ) {
				$option_label = $data;
				$option_value = $data;
			} elseif ( is_numeric( $index ) && is_array( $data ) ) {
				$option_label = isset( $data[$option_display_attr] ) ? $data[$option_display_attr] : array_pop( $data );
				$option_value = isset( $data[$option_value_attr] ) ? $data[$option_value_attr] : array_pop( $data );
			} else {
				$option_value = $index;
				$option_label = $data;
			}
			$selected = '';
			$option_value_string = (string) $option_value;
			$value_string = (string) $value;
			if ( '' !== $value && $option_value_string === $value_string ) {
				$selected = 'selected="selected"';
			}
			$output .= '<option value="' . esc_attr( $option_value ) . '" ' . $selected . '>' . htmlspecialchars( $option_label ) . '</option>';
		}
	}
	$output .= '</select>';

	return $output;
}

vc_add_shortcode_param( 'select_tag', 'select_tag_field' );
