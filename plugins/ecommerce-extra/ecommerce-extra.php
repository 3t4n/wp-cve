<?php
/*
Plugin Name: eCommerce Extra Plugin
Plugin URI: http://www.ceylonthemes.com
Description: Enhances eCommerce Plus theme with additional functionality such as customizer and widgets.
Version: 0.0.6
Author: Ceylon Themes
Tested up to: 5.7
Author URI: http://www.ceylonthemes.com
License: GPL2
*/
define('ecommerce_extra_dir_uri', plugin_dir_url( __FILE__ ));
define('ecommerce_extra_dir_path', plugin_dir_path( __FILE__ ));

/**
 * Define plugin textdomain.
 */
function ecommerce_extra_textdomain() {
  load_plugin_textdomain( 'ecommerce-extra', false, plugin_dir_url(__FILE__). 'languages' ); 
}
add_action( 'init', 'ecommerce_extra_textdomain' );

/**
 * initialize plugin
 */
if( !function_exists('ecommerce_extra_init') ){
	function ecommerce_extra_init(){
		$activate_theme_data = wp_get_theme(); // getting current theme data
		$activate_theme = $activate_theme_data->parent();		
		
		if( 'eCommerce Plus' == $activate_theme || $activate_theme_data->name == 'eCommerce Plus' ){
			require("inc/customizer.php");
		}
	}
	add_action( 'init', 'ecommerce_extra_init' );
}

	require_once ('inc/customizer-repeater/functions.php');
	
	//load common functions		
	require_once("inc/actions.php");
	
		
add_action('widgets_init', function() {
	
	//register_widget('ecommerce_extra_products_carousel_grid_widget');
	
});

add_action( 'wp_enqueue_scripts', 'ecommerce_extra_enqueue');

function ecommerce_extra_enqueue(){

		wp_enqueue_style( 'ecommerce-extra-css', ecommerce_extra_dir_uri .'style.css', array(), '1.0.0' );

}


