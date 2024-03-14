<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @package    Wp_Menu_Caching
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once( WP_PLUGIN_DIR . '/menu-caching/admin/class-menu-caching-admin.php' );

if ( !class_exists( 'Wp_Menu_Caching_Admin' ) ) exit;

$plugin_admin = new Wp_Menu_Caching_Admin( 'menu-caching', '1.0' );
$plugin_admin->dc_purge_menu_html_transients();

delete_option( 'dc_menu_html_index' );
delete_option( 'dc_menu_nonces_index' );
delete_option( 'dc_mc_nocache_menus' );
