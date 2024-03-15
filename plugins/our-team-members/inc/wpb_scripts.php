<?php

/**
 * WPB Post Sliderby WpBean
 */




/**
 * Register Scripts
 */

function wpb_otm_register_scripts(){

	wp_register_style('wpb_otm_grid', plugins_url('../assets/css/wpb_otm-custom-bootstrap.css', __FILE__ ), '', '3.0.2', false);
	wp_register_style('wpb_otm_flaticon', plugins_url('../assets/css/flaticon.css', __FILE__ ), '', '1.0', false);
	wp_register_style('font-awesome', plugins_url('../assets/css/font-awesome.min.css', __FILE__ ), '', '4.7.0', false);
	wp_register_style('wpb_otm_main_css', plugins_url('../assets/css/main.css', __FILE__ ), '', '1.0', false);

	wp_register_script('bars', plugins_url('../assets/js/bars.js', __FILE__), array('jquery'), null, false);	

}
add_action( 'wp_enqueue_scripts', 'wpb_otm_register_scripts', 20 ); 





/**
 * Calling the scripts
 */

function wpb_otm_get_scripts( $atts ){

	wp_enqueue_style('wpb_otm_grid');
	wp_enqueue_style('wpb_otm_flaticon');
	wp_enqueue_style('font-awesome');
	wp_enqueue_style('wpb_otm_main_css');
	wp_enqueue_script('bars');

}



/**
 * Register style & scripts for Elementor
 */

function wpb_otm_elementor_register_style(){

	wp_register_style('wpb_otm_grid', plugins_url('../assets/css/wpb_otm-custom-bootstrap.css', __FILE__ ), '', '3.0.2', false);
	wp_register_style('wpb_otm_flaticon', plugins_url('../assets/css/flaticon.css', __FILE__ ), '', '1.0', false);
	wp_register_style('font-awesome', plugins_url('../assets/css/font-awesome.min.css', __FILE__ ), '', '4.7.0', false);
	wp_register_style('wpb_otm_main_css', plugins_url('../assets/css/main.css', __FILE__ ), '', '1.0', false);

}

function wpb_otm_elementor_register_scripts(){

	wp_register_script('bars', plugins_url('../assets/js/bars.js', __FILE__), array('jquery'), '1.0', true);		

}

function wpb_otm_elementor_enqueue_frontend_styles(){
	wp_enqueue_style('wpb_otm_grid');
	wp_enqueue_style('wpb_otm_flaticon');
	wp_enqueue_style('font-awesome');
	wp_enqueue_style('wpb_otm_main_css');
}