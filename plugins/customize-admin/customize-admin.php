<?php
/*
Plugin Name: Customize Admin
Plugin URI: https://vanderwijk.com/wordpress/wordpress-customize-admin-plugin/
Description: This plugin allows you to customize the appearance and branding of the WordPress admin interface.
Version: 1.9.1
Author: Johan van der Wijk
Author URI: https://vanderwijk.com
Text Domain: customize-admin-plugin
Domain Path: /languages

Release notes: Options page layout changes and WordPress v6.3 compatibility tested.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) die ( 'Error!' );

// Load the required files needed for the plugin to run in the proper order and add needed functions to the required hooks.
function ca_plugin_init () {
	load_plugin_textdomain ( 'customize-admin-plugin', false, 'customize-admin/languages' );
}
add_action ( 'plugins_loaded', 'ca_plugin_init' );

function ca_enqueue_scripts () {
	if ( 'settings_page_customize-admin/customize-admin-options' !== get_current_screen  ()->id ) {
		return;
	}

	wp_register_script ( 'ca-color-picker', WP_PLUGIN_URL . '/customize-admin/js/color-picker.js', array ( 'jquery' ) );
	wp_enqueue_script ( 'ca-color-picker' );
	wp_enqueue_script ( 'wp-color-picker' );

	wp_enqueue_media  ();
	wp_register_script ( 'ca-media-upload', WP_PLUGIN_URL . '/customize-admin/js/media-upload.js', array ( 'jquery' ) );
	wp_enqueue_script ( 'ca-media-upload' );

	// Enqueue code editor and settings for manipulating CSS
	$settings = wp_enqueue_code_editor ( array ( 'type' => 'text/css' ) );

	// Return if the editor was not enqueued.
	if ( false === $settings ) {
		return;
	}

	wp_add_inline_script (
		'code-editor',
		sprintf(
			'jQuery( function  () { wp.codeEditor.initialize ( "ca_custom_css", %s ); } );',
			wp_json_encode ( $settings )
		)
	);
}
add_action ( 'admin_enqueue_scripts', 'ca_enqueue_scripts' );

function ca_enqueue_styles  () {
	if ( 'settings_page_customize-admin/customize-admin-options' !== get_current_screen  ()->id ) {
		return;
	}

	wp_enqueue_style ( 'wp-color-picker' );
}
add_action ( 'admin_print_styles', 'ca_enqueue_styles' );

// URL for the logo on the login screen
function ca_logo_url($url) {
	if ( get_option ( 'ca_logo_url' ) != '' ) {
		return esc_url( get_option ( 'ca_logo_url' ) );
	} else {
		return esc_url( get_bloginfo( 'url' ) );
	}
}

// CSS for custom logo on the login screen
function ca_logo_file () {
	if ( get_option ( 'ca_logo_file' ) != '' ) {
		echo '<style>.login h1 a { background-image: url("' . esc_url ( get_option ( 'ca_logo_file' ) ) . '"); background-size: contain; width: 320px; }</style>';
	} else {
		echo '<style>.login h1 a { background-image: url("' . plugins_url( 'vanderwijk.png' , __FILE__ ) . '"); background-size: contain; width: 320px; }</style>';
	}
}

// CSS for custom background color
function ca_login_background_color () {
	if ( get_option ( 'ca_login_background_color' ) != '' ) {
		echo '<style>body { background-color: ' . esc_html ( get_option ( 'ca_login_background_color' ) ) . '!important; } </style>';
	}
}

// CSS for custom CSS
function ca_custom_css () {
	if ( get_option ( 'ca_custom_css' ) != '' ) {
		echo '<style>'. strip_tags( get_option ( 'ca_custom_css' ) ) . '</style>';
	}
}

// Remove the generator meta tag
function ca_remove_meta_generator () {
	if ( get_option ( 'ca_remove_meta_generator' ) != '' ) {
		remove_action ( 'wp_head', 'wp_generator' );
	}
}

// Remove the RSD meta tag
function ca_remove_meta_rsd () {
	if ( get_option ( 'ca_remove_meta_rsd' ) != '' ) {
		remove_action ( 'wp_head', 'rsd_link' );
	}
}

// Remove the WLW meta tag
function ca_remove_meta_wlw () {
	if ( get_option ( 'ca_remove_meta_wlw' ) != '' ) {
		remove_action ( 'wp_head', 'wlwmanifest_link' );
	}
}

// Remove the RSS feed links
function ca_remove_rss_links () {
	if ( get_option ( 'ca_remove_rss_links' ) != '' ) {
		remove_action ( 'wp_head', 'feed_links', 2 ); //removes feeds
		remove_action ( 'wp_head', 'feed_links_extra', 3 ); //removes comment feed links
	}
}

add_action (
	'wp_dashboard_setup',
	function  () {
		global $wp_meta_boxes;

		// Remove WordPress Site Health Status widget from dashboard
		if ( get_option ( 'ca_remove_dashboard_site_health_status' ) != '' ) {
			unset ( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health'] );
		}

		// Remove At a Glance widget from dashboard
		if ( get_option ( 'ca_remove_dashboard_at_a_glance' ) != '' ) {
			unset ( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'] );
		}

		// Remove Activity widget from dashboard
		if ( get_option ( 'ca_remove_dashboard_activity' ) != '' ) {
			unset ( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity'] );
		}

		// Remove Plugins widget from dashboard
		if ( get_option ( 'ca_remove_dashboard_plugins' ) != '' ) {
			unset ( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] );
		}

		// Remove Quick Draft widget from dashboard
		if ( get_option ( 'ca_remove_dashboard_quick_press' ) != '' ) {
			unset ( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );
		}

		// Remove WordPress Site News widget from dashboard
		if ( get_option ( 'ca_remove_dashboard_wordpress_news' ) != '' ) {
			unset ( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
		}

		// Remove WordPress Other News widget from dashboard
		if ( get_option ( 'ca_remove_dashboard_wordpress_other' ) != '' ) {
			unset ( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] );
		}

	}
);

add_filter ( 'login_headerurl', 'ca_logo_url' );
add_action ( 'login_head', 'ca_logo_file' );
add_action ( 'login_head', 'ca_login_background_color' );
add_action ( 'login_head', 'ca_custom_css' );

add_action ( 'init', 'ca_remove_meta_generator' );
add_action ( 'init', 'ca_remove_meta_rsd' );
add_action ( 'init', 'ca_remove_meta_wlw' );
add_action ( 'init', 'ca_remove_rss_links' );

require_once ( 'customize-admin-options.php' );