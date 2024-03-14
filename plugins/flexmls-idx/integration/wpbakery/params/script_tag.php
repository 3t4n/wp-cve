<?php
if (!defined('ABSPATH')) die('-1');

function scripts_field($settings){
    $script = $settings['path'];
    $output = '';
    if(!empty($script)){
		$custom_tag = 'script';
		$output .= '<' . $custom_tag . ' src="' . esc_url( $script ) . '"></' . $custom_tag . '>';
	}
    return $output;
}

vc_add_shortcode_param( 'scripts_tag', 'scripts_field' );