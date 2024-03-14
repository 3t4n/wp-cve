<?php
/*
Plugin Name: DP Debug Menu
Plugin URI: https://dreamproduction.com/
Description: Quick debugger integrated in WordPress Admin Bar. Shows the template used for current page, number of queries, and execution time for PHP code.
Author: Dream Production
Version: 1.0.1
Author URI: https://dreamproduction.com/
Text Domain: dp-debug-menu
Domain Path: /languages
License: GPLv3
*/

add_action( 'plugins_loaded', 'dp_debug_menu', 10 );

function dp_debug_menu() {
	// Hook translation
	load_plugin_textdomain( 'dp-debug-menu', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	// Show in menu. 1000 priority, runs pretty late.
	add_action( 'admin_bar_menu', 'dp_add_debug_menu', 1000 );

	// Hook template catcher.
	add_filter( 'template_include', 'dp_set_template_global', 20 );
	add_filter( 'bp_load_template', 'dp_set_template_global', 20 );
}

/**
 * Add debug menu to WordPress Admin Bar.
 *
 * @param WP_Admin_Bar $wp_admin_bar
 * @return null
 */
function dp_add_debug_menu( $wp_admin_bar ) {
	global $dp_template;

	$current_theme = wp_get_theme();

	// Show for administrators on single site or super administrators on multisite.
	if ( ! current_user_can( 'administrator' ) ) {
		return null;
	}

	// No need for the debug menu in administration screen.
	if ( is_admin() ) {
		return null;
	}

	// Template name.
	$wp_admin_bar->add_node(
		array(
			'id'		=> 'dp-debug',
			'title'		=> sprintf( '%s: %s', __( 'Template', 'dp-debug-menu' ), basename( $dp_template ) ),
			'href'		=> '#',
		)
	);

	// Group for sub-menu.
	$wp_admin_bar->add_group(
		array(
			'parent'	=> 'dp-debug',
			'id'		=> 'dp-debug-list',
		)
	);

	// Theme name.
	$wp_admin_bar->add_menu(
		array(
			'id'		=> 'dp-theme-debug',
			'parent'	=> 'dp-debug-list',
			'title'		=> sprintf( '<strong>%s</strong>: %s', __( 'Theme', 'dp-debug-menu' ), $current_theme->display( 'Name' ) ),
			'href'		=> admin_url( 'themes.php' ),
		)
	);

	// Queries.
	$wp_admin_bar->add_menu(
		array(
			'id'		=> 'dp-queries-debug',
			'parent'	=> 'dp-debug-list',
			'title'		=> sprintf( '<strong>%s</strong>: %d', __( 'Queries', 'dp-debug-menu' ), get_num_queries() ),
			'href'		=> '#',
		 )
	);

	// Time.
	$wp_admin_bar->add_menu(
		array(
			'id'		=> 'dp-timer-debug',
			'parent'	=> 'dp-debug-list',
			'title'		=> sprintf( '<strong>%s</strong>: %ss', __( 'Loaded in', 'dp-debug-menu' ), timer_stop( 0 ) ),
			'href'		=> '#',
		)
	);
}

/**
 * Set current template as global variable to be read later.
 *
 * @param string $template
 * @global string $dp_template
 *
 * @return string $template
 */
function dp_set_template_global( $template ) {
	global $dp_template;

	/**
	 * Store current template
	 */
	$dp_template = $template;

	/**
	 * Roots Theme check
	 * @link http://www.rootstheme.com/
	 */
	if ( function_exists( 'roots_template_path' ) ) {
		$dp_template = roots_template_path();
	}

	// Used as a filter, return received template name.
	return $template;
}
