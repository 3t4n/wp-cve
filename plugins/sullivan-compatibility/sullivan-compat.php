<?php
/*
Plugin Name: Sullivan Compatibility Plugin
Description: Compatibility plugin for the WordPress theme Sullivan. Includes the custom post type Slideshows, which will allow you to add slideshows to the WooCommerce shop home page and the blog home page when the Sullivan theme is active.
Version: 1.0.4
Author: Anders Norén
Author URI: http://www.andersnoren.se
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: sullivan-compatibility
Domain Path: /languages
*/


define( 'SULLIVAN_COMPAT_MAIN_FILE_PATH', __FILE__ );


/* ====================================================================
|  SETUP AND GENERAL
|  General features and setup actions
'---------------------------------------------------------------------- */

if ( ! function_exists( 'sullivan_compat_installation' ) ) {
	function sullivan_compat_installation(){

		// Flush rewrite rules
		flush_rewrite_rules();
		
	}
}
register_activation_hook( SULLIVAN_COMPAT_MAIN_FILE_PATH, 'sullivan_compat_installation' );


/* ====================================================================
|  LOAD TEXT DOMAIN
'---------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_compat_load_plugin_textdomain' ) ) {
	function sullivan_compat_load_plugin_textdomain() {
		load_plugin_textdomain( 'sullivan-compatibility', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}
}
add_action( 'plugins_loaded', 'sullivan_compat_load_plugin_textdomain' );


/* ====================================================================
|  LOAD IN ADDITIONAL PHP FILES
|  Include CPTs and taxonomies
'---------------------------------------------------------------------- */

require_once( untrailingslashit( dirname( __FILE__ ) ) . '/register-posttypes.php' );
require_once( untrailingslashit( dirname( __FILE__ ) ) . '/register-taxonomies.php' );
require_once( untrailingslashit( dirname( __FILE__ ) ) . '/slideshow-meta.php' );