<?php
/*
Plugin Name: WP Safe Mode
Version: 1.3
Plugin URI: http://wordpress.org/plugins/wp-safe-mode/
Description: Safe mode for debugging WordPress issues, without destroying your site for other visitors.
Author: Pixelite
Author URI: http://pixelite.com
Text Domain: wp-safe-mode
Network: true
*/

/*
Copyright (c) 2022, Marcus Sykes

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
/**
 *
 */
define('WP_SAFE_MODE_VERSION', '1.3');

if( is_admin() ){
	include_once('wp-safe-mode-admin.php');
}

/**
 * Add safe mode menu item to admin bar.
 * @param WP_Admin_Bar $wp_admin_bar
 */
function wp_safe_mode_settings_admin_bar_menu( $wp_admin_bar ){
	//Settings Link
	if( class_exists('WP_Safe_Mode') && WP_Safe_Mode::can_user_enable() ){
		$settings_url = is_network_admin() || !WP_Safe_Mode::$multisite_single_site ? network_admin_url('admin.php?page=wp-safe-mode') : admin_url('admin.php?page=wp-safe-mode');
		$wp_admin_bar->add_node( array(
			'id'     => 'wp-safe-mode-settings',
			'parent' => 'wp-safe-mode',
			'title'  => esc_html__('Safe Mode Settings', 'wp-safe-mode'),
			'href'   => $settings_url,
		) );
		if( is_multisite() ){
			$wp_admin_bar->add_menu( array(
				'parent' => 'network-admin',
				'id'     => 'network-admin-wp-safe-mode',
				'title'  => esc_html__('Safe Mode Settings', 'wp-safe-mode'),
				'href'   => network_admin_url('admin.php?page=wp-safe-mode'),
			) );
		}
	}
}
add_action('admin_bar_menu', 'wp_safe_mode_settings_admin_bar_menu', 9999, 1);

function wp_safe_mode_activation() {
	if( !class_exists('WP_Safe_Mode_Admin') ){
		include('wp-safe-mode-admin.php');
	}
	//install default settings
	WP_Safe_Mode_Admin::install_default_settings();
	//try to install the loader
	WP_Safe_Mode_Admin::toggle_loader_install( true );
}
register_activation_hook( __FILE__, 'wp_safe_mode_activation' );

/**
 * Deactivation hook which deletes the bootstrap file and removes the custom Must-Use plugins folder constant from wp-config.php
 */
function wp_safe_mode_deactivation(){
	include_once('wp-safe-mode-admin.php');
	WP_Safe_Mode_Admin::toggle_loader_install( false );
}
register_deactivation_hook( __FILE__, 'wp_safe_mode_deactivation' );

/**
 * Uninstallation hook, deletes all WP Safe Mode settings, including sites in a MultiSite Network.
 */
function wp_safe_mode_uninstall(){
	//delete settings
	if( is_multisite() ){
		delete_site_option('wp_safe_mode_settings'); //delete network options
		//delete options from all sites
		foreach( get_sites( array('fields'=>'ids') ) as $site_id ){
			delete_blog_option( $site_id, 'wp_safe_mode_settings' );
		}
	}else{
		delete_option('wp_safe_mode_settings');
	}
}
register_uninstall_hook(__FILE__, 'wp_safe_mode_uninstall');