<?php
/**
* Plugin Name: Fullscreen mode B gone
* Plugin URI: https://plugins.followmedarling.se/fullscreen-mode-b-gone/
* Description: Finally get rid of that pesty fullscreen mode that you need to toggle every time you use a new device or clear your browser offline website data.
* Version: 1.06
* Author: Jonk @ Follow me Darling
* Author URI: https://plugins.followmedarling.se/
* Domain Path: /languages
* Text Domain: fullscreen_mode_b_gone
**/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function fullscreen_mode_b_gone() {
	$plugin_version = get_plugin_data( __FILE__ )['Version'];
	wp_enqueue_script(
		'fullscreen-mode-b-gone',
		plugins_url( 'js/admin.js', __FILE__ ),
		null,
		$plugin_version
	);
}
add_action( 'admin_enqueue_scripts', 'fullscreen_mode_b_gone' );

if ( is_admin() ) {
	add_action( 'admin_enqueue_scripts', 'enqueue_fullscreen_mode_b_gone_admin_css' );
	function enqueue_fullscreen_mode_b_gone_admin_css() {
		$plugin_version = get_plugin_data( __FILE__ )['Version'];
		wp_register_style( 
			'fullscreen-mode-b-gone-admin', 
			plugins_url( 'css/fullscreen-mode-b-gone-admin.min.css', __FILE__ ), 
			null, 
			$plugin_version
		);
		wp_enqueue_style( 'fullscreen-mode-b-gone-admin' );
	}
}
