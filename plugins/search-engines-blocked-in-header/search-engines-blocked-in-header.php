<?php
/*
Plugin Name: Search Engines Blocked in Header
Description: Display the 'Search Engines Discouraged' (or any translation) notification in the WordPress Toolbar if blog_public option has been checked.
Author: Marcel Bootsman
Version: 0.5.4
Author URI: https://marcelbootsman.nl
Text Domain: search-engines-blocked-in-header
Domain Path: /languages/
*/

add_action( 'admin_bar_menu', 'nostromo_search_engines_blocked', 1000 );
function nostromo_search_engines_blocked() {
	global $wp_admin_bar, $wpdb;
	if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
		return;
	}
	$url = '/wp-admin/options-reading.php';
	if ( ! get_option( 'blog_public' ) ) {
		$wp_admin_bar->add_menu( array( 'id'    => 'search_engines_blocked',
		                                'title' => __( 'Search Engines Discouraged', 'search-engines-blocked-in-header' ),
		                                'href'  => $url
		) );
	}
}

/* Load Text Domain */
add_action( 'plugins_loaded', 'sebih_load_textdomain' );
function sebih_load_textdomain() {
	load_plugin_textdomain( 'search-engines-blocked-in-header', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
?>
