<?php

use ShopEngine\Utils\Helper;

defined('ABSPATH') || exit;
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
