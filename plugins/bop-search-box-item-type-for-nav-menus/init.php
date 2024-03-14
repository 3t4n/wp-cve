<?php 
/*
Plugin Name: Bop Search Box Item Type For Nav Menus
Description: Adds search box as a choice of item in navigation menus.
Version: 1.6.0
Author: The Bop
Author URI: http://thebop.biz
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: bop-nav-search-box-item
*/


//Reject if accessed directly
defined( 'ABSPATH' ) || die( 'Our survey says: ... X.' );



//NOTE: REMEMBER TO REPLACE PLUGIN_TEMPLATE and plugin-template in all files


/**
 * Get a path relative to the root of this plugin or relative to a given file path
 * 
 * @since 0.1.0
 * 
 * @param string $path - The path from the relative root.
 * @param bool/string $relative_file - The alternative filepath to use as the relative root.
 * @return string - The filepath.
 */
function bop_nav_search_box_item_plugin_path( $path = '', $relative_file = false ){
	$path = implode( DIRECTORY_SEPARATOR, explode( '/', ltrim( $path, '/' ) ) );
	$return  = '';
	if( $relative_file === false ){
		$return = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . $path;
	}else{
		$return = dirname( $relative_file ) . DIRECTORY_SEPARATOR . $path;
	}
	return $return;
}

/**
 * Echos the error message when system requirements aren't met
 * 
 * @since 0.1.0
 * 
 * @return void.
 */
function _bop_nav_search_box_item_requirements_error(){
	?>
	<div class="notice notice-error">
		<p><?php printf( __( 'Error: Bop Search Box Item Type For Nav Menus requires WordPress v3.4 or higher (current: %s) and PHP v5.3 or higher (current: %s). You must be up to date or this plugin will only give this message and nothing more.', 'bop-nav-search-box-item' ), $GLOBALS['wp_version'], phpversion() ); ?></p>
	</div>
	<?php
}

if ( version_compare( $GLOBALS['wp_version'], '3.4.0', '<' ) || version_compare( phpversion(), '5.3.0', '<' ) ) {
	
	//throw error and end plugin declarations and processes.
	add_action( 'admin_notices', '_bop_nav_search_box_item_requirements_error' );
	
	//leave file (avoid syntax conflicts with earlier php/wp versions)
	return;
	
}else{
	
	require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'plugin-keeping.php' );
	
	/* Let's get started! */
	require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'plugin.php' );
	
}
