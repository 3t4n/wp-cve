<?php
/*
Plugin Name: Britetechs Companion
Description: Enhances britetechs themes with additional functionality.
Version: 2.1.0
Author: Britetechs
Author URI: https://Britetechs.com
Text Domain: britetechs-companion
*/
if(!define('bc_plugin_url', plugin_dir_url( __FILE__ ))){
	define( 'bc_plugin_url', plugin_dir_url( __FILE__ ) );
}
if(!define('bc_plugin_dir', plugin_dir_path( __FILE__ ))){
	define( 'bc_plugin_dir', plugin_dir_path( __FILE__ ) );
}

if( !function_exists('bc_init') ){
	function bc_init(){
		 
		/* Retrive Current Theme Contents Here */
		$themedata = wp_get_theme();
		$mytheme = $themedata->name;
		$mytheme = strtolower( $mytheme );
		$mytheme = str_replace( ' ','-', $mytheme );
		
		if(file_exists( bc_plugin_dir . "inc/$mytheme/init.php")){

			require("inc/$mytheme/init.php");

		}else if($mytheme=='hotel-imperial'|| $mytheme=='hotely'){

			require("inc/hotelone/init.php");

		}else if($mytheme=='astro'){

			require("inc/businesswp/init.php");

		}else if($mytheme=='bloovo'){

			require("inc/bizcor/init.php");

		}	
	}
}
bc_init();

function bc_install_theme_pages() {
    require_once bc_plugin_dir . 'inc/theme-pages-activator.php';
    BC_Theme_Pages_Activator::activate();
}
register_activation_hook( __FILE__, 'bc_install_theme_pages' );