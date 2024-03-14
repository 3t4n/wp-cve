<?php
/*
Plugin Name:  Themify Shortcodes
Plugin URI:   https://wordpress.org/plugins/themify-shortcodes/
Version:      2.0.8
Author:       Themify
Author URI:   https://themify.me
Description:  A set of Themify shortcodes that can be used with any theme.
Text Domain:  themify-shortcodes
Domain Path:  /languages
Compatibility: 5.0.0
*/

defined( 'ABSPATH' ) or die( '-1' );

/**
 * Bootstrap Themify Shortcodes plugin
 *
 * @since 1.0
 */
function themify_shortcodes_setup() {
	if( ! defined( 'THEMIFY_SHORTCODES_DIR' ) )
		define( 'THEMIFY_SHORTCODES_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

	if( ! defined( 'THEMIFY_SHORTCODES_URI' ) )
		define( 'THEMIFY_SHORTCODES_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

	if( ! defined( 'THEMIFY_SHORTCODES_VERSION' ) ) {
		$data = get_file_data( __FILE__, array( 'Version' ) );
		define( 'THEMIFY_SHORTCODES_VERSION', $data[0] );
	}

	include THEMIFY_SHORTCODES_DIR . 'includes/system.php';

	Themify_Shortcodes::get_instance();
}
add_action( 'after_setup_theme', 'themify_shortcodes_setup', 100 );
add_filter( 'plugin_row_meta', 'themify_shortcodes_plugin_meta', 10, 2 );
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'themify_shortcodes_action_links' );
function themify_shortcodes_plugin_meta( $links, $file ) {
	if ( plugin_basename( __FILE__ ) == $file ) {
		$row_meta = array(
		  'changelogs'    => '<a href="' . esc_url( 'https://themify.me/changelogs/' ) . basename( dirname( $file ) ) .'.txt" target="_blank" aria-label="' . esc_attr__( 'Plugin Changelogs', 'themify-shortcodes' ) . '">' . esc_html__( 'View Changelogs', 'themify-shortcodes' ) . '</a>'
		);
 
		return array_merge( $links, $row_meta );
	}
	return (array) $links;
}
function themify_shortcodes_action_links( $links ) {
	if ( is_plugin_active( 'themify-updater/themify-updater.php' ) ) {
		$tlinks = array(
		 '<a href="' . admin_url( 'index.php?page=themify-license' ) . '">'.__('Themify License', 'themify-shortcodes') .'</a>',
		 );
	} else {
		$tlinks = array(
		 '<a href="' . esc_url('https://themify.me/docs/themify-updater-documentation') . '">'. __('Themify Updater', 'themify-shortcodes') .'</a>',
		 );
	}
	return array_merge( $links, $tlinks );
}