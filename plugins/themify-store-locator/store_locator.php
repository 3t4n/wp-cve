<?php
/*
Plugin Name:  Themify Store Locator
Plugin URI:   https://themify.me/store-locator
Version:      1.1.8
Author:       Themify
Author URI:   https://themify.me
Description:  A free plugin to add store locations and stores map in your WordPress site.
Text Domain:  themify-store-locator
Domain Path:  /languages
Compatibility: 5.0.0
*/

defined( 'ABSPATH' ) or die( '-1' );
const THEMIFY_STORE_LOCATOR_VERSION='1.1.8';
register_activation_hook( __FILE__, 'themify_store_locator_plugin_activation' );
add_action( 'after_setup_theme', 'themify_store_locator_setup' );

function themify_store_locator_setup() {
	if( ! defined( 'THEMIFY_STORE_LOCATOR_DIR' ) ){
	    define( 'THEMIFY_STORE_LOCATOR_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
	}
	if( ! defined( 'THEMIFY_STORE_LOCATOR_URI' ) ){
	    define( 'THEMIFY_STORE_LOCATOR_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
	}
	include( THEMIFY_STORE_LOCATOR_DIR . 'includes/themify-metabox/themify-metabox.php' );
	include THEMIFY_STORE_LOCATOR_DIR . 'includes/init.php';
	Themify_Store_Locator::get_instance();
}

function themify_store_locator_plugin_activation($network){
	add_option( 'themify_store_locator_activation_redirect', true );
}
add_filter( 'plugin_row_meta', 'themify_store_locator_plugin_meta', 10, 2 );
function themify_store_locator_plugin_meta( $links, $file ) {
	if ( plugin_basename( __FILE__ ) === $file ) {
		$row_meta = array(
		  'changelogs'    => '<a href="' . esc_url( 'https://themify.org/changelogs/' ) . basename( dirname( $file ) ) .'.txt" target="_blank" aria-label="' . esc_attr__( 'Plugin Changelogs', 'themify' ) . '">' . esc_html__( 'View Changelogs', 'themify' ) . '</a>'
		);
 
		return array_merge( $links, $row_meta );
	}
	return (array) $links;
}
