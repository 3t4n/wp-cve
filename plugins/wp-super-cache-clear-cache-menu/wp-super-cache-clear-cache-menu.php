<?php
/**
Plugin Name: Clear All Cache for WP Super Cache 
Plugin URI: https://apasionados.es/blog/vaciar-cache-wp-super-cache-plugin-wordpress-1933/
Description: Clear all cached files of the WP Super Cache plugin directly from the admin menu (option only available to super admins).
Version: 2.0
Author: Apasionados.es
Author URI: https://apasionados.es/
Text Domain: wp-super-cache-clear-cache-menu
*/

$plugin_header_translate = array( __('Clear all cached files of the WP Super Cache plugin directly from the admin menu (option only available to super admins).', 'wp-super-cache-clear-cache-menu') );

function only_show_option_if_wp_super_cache_is_active() {
	if ( is_plugin_active( 'wp-super-cache/wp-cache.php' ) ) {
		load_plugin_textdomain( 'wp-super-cache-clear-cache-menu', WPCACHEHOME . 'languages', basename( dirname( __FILE__ ) ) . '/languages' );
		function clear_all_cached_files_wpsupercache() {
			global $wp_admin_bar;
			if ( !is_super_admin() || !is_admin_bar_showing() )
				return;
			$wp_admin_bar->add_menu( array(
						'parent' => '',
						'id' => 'delete-cache-completly',
						'title' => __( 'Clear all cached files', 'wp-super-cache-clear-cache-menu' ),
						'meta' => array( 'title' => __( 'Clear all cached files of WP Super Cache', 'wp-super-cache-clear-cache-menu' ) ),
						'href' => wp_nonce_url( admin_url('options-general.php?page=wpsupercache&wp_delete_cache=1&tab=contents'), 'wp-cache' )
						) );
		}
		add_action( 'wp_before_admin_bar_render', 'clear_all_cached_files_wpsupercache', 999 );
	} 
}
add_action( 'admin_init', 'only_show_option_if_wp_super_cache_is_active' );

?>