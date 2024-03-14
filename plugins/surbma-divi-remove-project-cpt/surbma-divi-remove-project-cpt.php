<?php

/*
Plugin Name: Surbma | Divi Remove Project CPT
Plugin URI: https://surbma.com/wordpress-plugins/
Description: Removes the Project Custom Post Type from Divi theme.

Version: 2.0

Author: Surbma
Author URI: https://surbma.com/

License: GPLv2

Text Domain: surbma-divi-remove-project-cpt
Domain Path: /languages/
*/

// Prevent direct access to the plugin
if ( !defined( 'ABSPATH' ) ) exit( 'Good try! :)' );

// Localization
function surbma_divi_remove_project_cpt_init() {
	load_plugin_textdomain( 'surbma-divi-remove-project-cpt', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'surbma_divi_remove_project_cpt_init' );

function surbma_divi_remove_project_cpt() {
    if ( wp_basename( get_bloginfo( 'template_directory' ) ) == 'Divi' ) {
    	global $wp_post_types;
    	if ( isset( $wp_post_types[ 'project' ] ) ) {
        	unset( $wp_post_types[ 'project' ] );
        	return true;
    	}
	}
    return false;
}
add_action( 'init', 'surbma_divi_remove_project_cpt' );
