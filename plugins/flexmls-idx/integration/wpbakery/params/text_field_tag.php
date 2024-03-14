<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function text_field_tag_form_field( $settings, $value ) {
	$value = htmlspecialchars( $value );
	$points = !empty($settings['points']) ? $settings['points'] : false ;
	$class_ = !empty($settings['class']) ? $settings['class'] : '' ;
	$size = !empty($settings['size']) ? $settings['size'] : '' ;

	if($points){
		$points = '<span class="points">'.$points.'</span>';
	}

	return '<input name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textinput ' . $settings['param_name'] . ' ' . $settings['type'] . ' ' . $class_. '" type="text" value="' . $value . '" size="'.$size.'"/>'.$points;
}

vc_add_shortcode_param( 'text_field_tag', 'text_field_tag_form_field' );