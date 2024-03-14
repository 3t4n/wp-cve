<?php 
/*
Plugin Name: Superfish Menus
Plugin URI: http://wordpress.org/extend/plugins/superfish/
Description: Adds jQuery Superfish effects to most WordPress menus.
Author: Matt Hodder
Version: 1.1.1
Author URI: http://www.matthodder.com/
*/

add_action('wp_enqueue_scripts', 'mh_superfish');
function mh_superfish() {
	wp_register_script('superfish', plugins_url( 'js/superfish.js', __FILE__ ), array('jquery'), '1.7.3', TRUE);
	wp_register_script('superfish-args', plugins_url( 'js/superfish_args.js', __FILE__ ), array('jquery'), '1.7.3', TRUE);
	
	if(!is_admin()) wp_enqueue_script('superfish');
	if(!is_admin()) wp_enqueue_script('superfish-args');
}