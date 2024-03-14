<?php
/*
Plugin Name: RRF Scroll To Top
Plugin URI: http://designingway.com/plugins/rrf-scroll-to-top
Description: This plugin will add a scroll to top button on footer right.
Author: Rasel Ahmed
Author URI: http://designingway.com
Version: 1.1
*/


/* Adding Latest jQuery from Wordpress */
function scroll_to_top_latest_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'scroll_to_top_latest_jquery');

/* Adding Plugin javascript file */
function scroll_to_top_plugin_files() {
	wp_register_script( 'plugin-script', plugins_url('rrf-scroll-to-top/js/jquery.scrollUp.min.js'), false, '1.0', true);
    wp_enqueue_script( 'plugin-script' );
}
add_action( 'init', 'scroll_to_top_plugin_files' );

/* Adding plugin javascript active file */
function scroll_to_top_plugin_active() {
	wp_register_script( 'plugin-script-active', plugins_url('rrf-scroll-to-top/js/active.js'), false, '1.0', true);
    wp_enqueue_script( 'plugin-script-active' );
}
add_action( 'init', 'scroll_to_top_plugin_active' );

/* Adding Plugin custm CSS file */
function scroll_to_top_plugin_styles() {
	wp_register_style( 'plugin-style', plugins_url('css/custom.css', __FILE__) );
    wp_enqueue_style( 'plugin-style' );
}
add_action( 'wp_enqueue_scripts', 'scroll_to_top_plugin_styles' );
?>