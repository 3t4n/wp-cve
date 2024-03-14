<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
if( ! class_exists( 'WP_Customize_Control' ) ){
	return;
}
add_action( 'customize_preview_init', 'z_companion_royal_shop_focus_section_enqueue');
add_action( 'customize_controls_init', 'z_companion_royal_shop_focus_section_helper_script_enqueue' );
function z_companion_royal_shop_focus_section_enqueue(){
	   wp_enqueue_style( 'royal-shop-focus-section-style',Z_COMPANION_PLUGIN_DIR_URL . 'royal-shop/customizer/customize-focus-section/css/focus-section.css');
		wp_enqueue_script( 'royal-shop-focus-section-script',Z_COMPANION_PLUGIN_DIR_URL . 'royal-shop/customizer/customize-focus-section/js/focus-section.js', array('jquery'),'',false);
	}
function z_companion_royal_shop_focus_section_helper_script_enqueue(){
		wp_enqueue_script( 'royal-shop-focus-section-addon-script', Z_COMPANION_PLUGIN_DIR_URL . 'royal-shop/customizer/customize-focus-section/js/addon-focus-section.js', array('jquery'),'',false);
	}

