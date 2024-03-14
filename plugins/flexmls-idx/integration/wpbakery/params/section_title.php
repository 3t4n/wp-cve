<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function section_title_field($settings){
    $output = '<div class="flexmls-shortcode-section-title">'. $settings['text'] .'</div>';
    return $output;
}

vc_add_shortcode_param( 'section_title', 'section_title_field' );



