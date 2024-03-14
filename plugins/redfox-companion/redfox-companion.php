<?php
/*
Plugin Name: Redfox Companion
Description: Enhances redfox themes with additional functionality.
Version: 1.1
Author: redfoxthemes
Text Domain: redfox-companion
*/

define( 'rfc_plugin_url', plugin_dir_url( __FILE__ ) );
define( 'rfc_plugin_dir', plugin_dir_path( __FILE__ ) );

if( !function_exists('rfc_init') ){
	function rfc_init(){
		$themedata = wp_get_theme(); // Getting Theme Data		
		$template = $themedata->template;
		
		if(file_exists(rfc_plugin_dir . "include/$template/init.php")){
			require(rfc_plugin_dir . "include/$template/init.php");
		}
	}
}
add_action( 'init', 'rfc_init' );