<?php
/**
 * Plugin Name: Force Full Width Shortcode
 * Plugin URI: http://jacobgoh.com
 * Description: This plugin's shortcode allows you to create full screen width element in any page or post.
 * Version: 1.0.0
 * Author: Jacob Goh
 * Author URI: http://jacobgoh.com
 * License: GPL2
 */

//register js script 
function jg_ffw_script(){
	wp_register_script( 'jg-force-full-width-shortcode-script', plugins_url( '/js/jg-force-full-width-shortcode.js', __FILE__ ) , array( 'jquery') );
}
add_action( 'wp_enqueue_scripts', 'jg_ffw_script' );

// adding shortcodes functions
function jg_ffw_shortcode( $atts, $content = null ) {
	wp_enqueue_script( 'jg-force-full-width-shortcode-script' );
	return '<div class="jg-force-full-width">' . $content . '</div>';
}
add_shortcode( 'jg-ffw', 'jg_ffw_shortcode' );