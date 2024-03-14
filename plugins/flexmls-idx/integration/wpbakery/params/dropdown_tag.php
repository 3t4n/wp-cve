<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function dropdown_tag_field( $settings, $value ) {
	$isMultiple = !empty($settings['multiple']) ? $settings['multiple'] : '';
	$script = !empty($settings['script']) ? $settings['script'] : '';
	$class = !empty($settings['class']) ? $settings['class'] : '';
	$id = !empty($settings['id']) ? $settings['id'] : '';
	$isgroup = !empty($settings['isgroup']) ? $settings['isgroup'] : '';
	$dependent = !empty($settings['dependent_dd']) ? $settings['dependent_dd'] : '';
	$optdefault = !empty($settings['optdefault']) ? $settings['optdefault'] : 'All Sub Types';

	$dataId = uniqid('display_settings_values_');
	
	if($isMultiple === 'true'){
		$multiply = 'style="height: 110px;" multiple="multiple"';
	} else {
		$multiply = '';
	}
	
	$output = '';
	if ( is_array( $value ) ) {
		$value = isset( $value['value'] ) ? $value['value'] : array_shift( $value );
	} elseif (is_string( $value ) && $isMultiple === 'true') {
		$output.='<input type="hidden" dataId="'.$dataId.'" value="'.$value.'"/>';
	}
	if($id !== ''){
		$id_use = 'id=' . $id; 
	}
	$output .= '<select name="' . $settings['param_name'] . '" ' . $id_use . ' class="wpb_vc_param_value wpb-input wpb-select ' . $class . ' ' . $settings['param_name'] . ' ' . $settings['type'] . ' ' . $multiply . '">';
	if ( ! empty( $settings['value'] ) ) {
		if($isgroup == true){
			foreach ($settings['value'] as $key => $data_array) {
				$output .= "<optgroup label='{$key}'>";
				//$output .= "<option value="" selected='selected'>{$optdefault}</option>";
				foreach ($data_array as $val => $label) {
					$option_value = $val;
					$option_label = $label;

					$selected = '';
					$option_value_string = (string) $option_value;
					$value_string = (string) $value;
					if ( '' !== $value && $option_value_string === $value_string ) {
						$selected = 'selected="selected"';
					}
					$output .= '<option value="' . esc_attr( $option_value ) . '" ' . $selected . '>' . htmlspecialchars( $option_label ) . '</option>';
				}

				$output .= "</optgroup>";
			}
		} else {
			foreach ( $settings['value'] as $index => $data ) {
				if ( is_numeric( $index ) && ( is_string( $data ) || is_numeric( $data ) ) ) {
					$option_label = $data;
					$option_value = $data;
				} elseif ( is_numeric( $index ) && is_array( $data ) ) {
					$option_label = isset( $data['label'] ) ? $data['label'] : array_pop( $data );
					$option_value = isset( $data['value'] ) ? $data['value'] : array_pop( $data );
				} else {
					$option_value = $data;
					$option_label = $index;
				}
				$selected = '';
				$option_value_string = (string) $option_value;
				$value_string = (string) $value;
				if ( '' !== $value && $option_value_string === $value_string ) {
					$selected = 'selected="selected"';
				}
				$output .= '<option class="' . '" value="' . esc_attr( $option_value ) . '" ' . $selected . '>' . htmlspecialchars( $option_label ) . '</option>';
			}
		}
	}
	$output .= '</select>';

	if($script != ''){
		$custom_tag = 'script';
		if(is_string( $value ) && $isMultiple === 'true') {
			$output .= '<' . $custom_tag . '>window.vce_dd="'.$dataId.'"</' . $custom_tag . '>';
		} else {
			$output .= '<' . $custom_tag . '>window.vce_dd=false</' . $custom_tag . '>';
		}
		$output .= '<' . $custom_tag . ' src="' . esc_url( $script ) . '"></' . $custom_tag . '>';
		if($isgroup == true){
			$output .= '<' . $custom_tag . '>new DependentDropdowns("#'.$dependent.'", "#'.$id.'");</' . $custom_tag . '>';
		}
	}

	return $output;
}

vc_add_shortcode_param( 'dropdown_tag', 'dropdown_tag_field' );