<?php
/*
Plugin Name: PHP Version
Description: You can able to see the current PHP version in WordPress admin dashboard widget.
Version: 1.0.0
Author: Mazedul Islam
Author URI: https://www.mazedit.com/mazedulislam
Tags: php version, PHP Version, mazedulislam27
*/
function mzd_pvw_enqueue_script( $pvw) {
	// dashboard page only
	if ( 'index.php' != $pvw ) {
		return;
	}
	// enqueue script to show PHP version in WordPress dashboard
	wp_enqueue_script( 'pvw_script', plugin_dir_url( __FILE__ ) . 'pvw.js' );
	// pass the PHP version to JavaScript
	wp_localize_script( 'pvw_script', 'pvwObj', array(
		'phpVersion' => phpversion()
	) );
}
add_action( 'admin_enqueue_scripts', 'mzd_pvw_enqueue_script' );