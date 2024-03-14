<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function callback_field($settings){
    $callback_function = !empty($settings['function']) ? $settings['function'] : '';
    $output = '';
    if($callback_function != ''){
        $custom_tag = 'script';
        $output .= '<' . $custom_tag . '>
        jQuery(document).ready(function () {
            console.log(window.integration_connect);
            if(window.integration_connect.'.$callback_function.'){
                setInterval(function(){
                    window.integration_connect.'.$callback_function.'();
                }, 1000)
            }
        })</' . $custom_tag . '>';
	}
    return $output;
}

vc_add_shortcode_param( 'callback', 'callback_field' );



