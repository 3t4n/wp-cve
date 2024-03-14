<?php
defined('ABSPATH') || exit;
use ShopEngine\Utils\Helper;

if(!function_exists('print_attribute')){
	function print_attribute($attributes = []){
		$return_value = "";
		if(is_array($attributes)){
			foreach($attributes as $atts_key => $atts_value){
				if(is_array($atts_value) || is_object($atts_value)){
					$atts_value = wp_json_encode($atts_value);
				}
				$return_value .= $atts_key . '="' . $atts_value . '" ';
			}
		}

		return $return_value;
	}
}

if(!function_exists('render_icon')){
	function render_icon($icon , $attr = []){
		$return_value = '';
		foreach($attr as $key => $value){
			if(is_array($value) || is_object($value)){
				$value = wp_json_encode($value);
			}
			$return_value .= $key . '="' . $value . '" ';
		}
		$html = "<i class='".$icon."' " .$return_value."></i>";

		echo wp_kses($html, Helper::get_kses_array());
	}
}
