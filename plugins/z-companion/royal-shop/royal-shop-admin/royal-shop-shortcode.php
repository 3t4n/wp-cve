<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 
function z_companion_shortcode_template($section_name=''){
	switch ($section_name){
	case 'royal_shop_show_frontpage':
	$section = array(
                                                    'front-topslider',
                                                    'front-highlight',
                                                    'front-tabproduct',
                                                    'front-categoryslider',
                                                    'front-productslider',
                                                    'front-banner',
                                                    'front-productlist',
                                                    'front-brand',
                                                    
    );
    foreach($section as $value):
    require_once (Z_COMPANION_DIR_PATH . 'royal-shop/royal-shop-front-page/'.$value.'.php');
    endforeach;
    break;
	
	}
}
function z_companion_shortcodeid_data($atts){
    $output = '';
    $pull_quote_atts = shortcode_atts(array(
        'section' => ''
            ), $atts);
    $section_name = wp_kses_post($pull_quote_atts['section']);
  	$output = z_companion_shortcode_template($section_name);
    return $output;
}
add_shortcode('royal-shop', 'z_companion_shortcodeid_data');