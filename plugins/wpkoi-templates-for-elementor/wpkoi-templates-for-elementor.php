<?php
/*
Plugin Name: WPKoi Templates for Elementor
Plugin URI: https://wpkoi.com/wpkoi-templates-for-elementor/
Description: WPKoi Templates for Elementor extends Elementor Template Library with WPKoi pages from the popular WPKoi Themes.
Version: 2.5.7
Author: WPKoi
Author URI: https://wpkoi.com
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wpkoi-templates-for-elementor
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Set our version
define( 'WPKOI_TEMPLATES_FOR_ELEMENTOR_VERSION', '2.5.7' );

// Set our root directory
define( 'WPKOI_TEMPLATES_FOR_ELEMENTOR_DIRECTORY', plugin_dir_path( __FILE__ ) );
define( 'WPKOI_TEMPLATES_FOR_ELEMENTOR_URL', plugins_url( '/', __FILE__ ) );


/**
 * Display admin error message if PHP version is older than 5.4.0.
 * Otherwise execute the main plugin class.
 */
if ( version_compare( phpversion(), '5.6.0', '<' ) ) {

	function wpkoi_templates_for_elementor_old_php_admin_error_notice() {
		$message = sprintf( esc_html__( 'The %2$sWPKoi Templates for Elementor%3$s plugins requires %2$sPHP 5.6.0+%3$s to run properly. Please contact your hosting company and ask them to update the PHP version of your site to at least PHP 5.6.0.%4$s Your current version of PHP: %2$s%1$s%3$s', 'wpkoi-templates-for-elementor' ), phpversion(), '<strong>', '</strong>', '<br>' );

		printf( '<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}
	add_action( 'admin_notices', 'wpkoi_templates_for_elementor_old_php_admin_error_notice' );
}	
if ( version_compare( phpversion(), '5.6.0', '<' ) ) {
	return;	
}

if ( ! function_exists( 'wpkoi_templates_for_elementor_active_premium' ) ) {
	add_action( 'admin_notices', 'wpkoi_templates_for_elementor_active_premium' );
	/**
	 * Checks to see if Premium plugin is active.
	 *
	 **/
	function wpkoi_templates_for_elementor_active_premium() {

		// Get the data
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if ( is_plugin_active( 'wpkoi-templates-for-elementor-premium/wpkoi-templates-for-elementor-premium.php' ) )  {
			// Premium is active
			printf(
				'<div class="notice is-dismissible notice-warning">
					<p>%1$s</p>
				</div>',
				esc_html__( 'WPKoi Templates for Elementor Premium is active. You can deactivate the free version!', 'wpkoi-templates-for-elementor' )
			);
		}
	}
}

// Checks to see if Elementor plugin is active. If not, tell them.
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'wpkoi-templates-for-elementor-premium/wpkoi-templates-for-elementor-premium.php' ) )  {
	return;	
}

if ( ! function_exists( 'wpkoi_templates_for_elementor_active_plugin' ) ) {
	add_action( 'admin_notices', 'wpkoi_templates_for_elementor_active_plugin' );
	/**
	 * Checks to see if Elementor plugin is active. If not, tell them.
	 *
	 **/
	function wpkoi_templates_for_elementor_active_plugin() {

		// Get the data
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if ( !is_plugin_active( 'elementor/elementor.php' ) )  {
			if ( !is_plugin_active( 'elementor-pro/elementor-pro.php' ) )  {
				// Elementor is not active
				printf(
					'<div class="notice is-dismissible notice-warning">
						<p>%1$s <a href="https://wordpress.org/plugins/elementor/" target="_blank">%2$s</a></p>
					</div>',
					esc_html__( 'WPKoi Templates for Elementor requires Elementor Page Builder to be active.', 'wpkoi-templates-for-elementor' ),
					esc_html__( 'Install now.', 'wpkoi-templates-for-elementor' )
				);
			}
		}
	}
}

// Checks to see if Elementor plugin is active. If not, tell them.
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( !is_plugin_active( 'elementor/elementor.php' ) )  {
	if ( !is_plugin_active( 'elementor-pro/elementor-pro.php' ) )  {
		return;	
	}
}

// Add script to Editor
add_action( 'admin_enqueue_scripts', 'wpkoi_templates_for_elementor_admin_add_scripts');
function wpkoi_templates_for_elementor_admin_add_scripts(){
	
	wp_register_style( 'wpkoi-templates-for-elementor-css',  WPKOI_TEMPLATES_FOR_ELEMENTOR_URL . 'assets/css/wpkoi-templates-for-elementor.css' , '', WPKOI_TEMPLATES_FOR_ELEMENTOR_VERSION );
	wp_enqueue_style( 'wpkoi-templates-for-elementor-css');

}

// Element options
require WPKOI_TEMPLATES_FOR_ELEMENTOR_DIRECTORY . 'inc/element-options.php';

if ( ! function_exists( 'wpkoi_templates_for_elementor_create_menu' ) ) {
	add_action( 'admin_menu', 'wpkoi_templates_for_elementor_create_menu' );
	/**
	 * Adds our "WPKoi Templates for Elementor Activation" dashboard menu item
	 *
	 */
	function wpkoi_templates_for_elementor_create_menu() {
		add_menu_page( 'WPKoi Templates for Elementor', 'WPKoi Templates', 'manage_options', 'wpkoi-templates-for-elementor/wpkoi-templates.php', '', '', 59 );
	}
}

// Add WPKoi elements to page builder
add_action( 'plugins_loaded', 'wpkoi_templates_for_elementor_add_elements' );
function wpkoi_templates_for_elementor_add_elements() {
	if ( ( !defined('WPKOI_ELEMENTS_PATH' ) ) && ( ! function_exists( 'add_wpkoi_elements_elements' ) ) && ( ! function_exists( 'add_asagi_premium_elements' ) ) && ( ! function_exists( 'add_bekko_premium_elements' ) ) && ( ! function_exists( 'add_chagoi_premium_elements' ) ) && ( ! function_exists( 'add_lovewp_premium_elements' ) ) && ( ! function_exists( 'add_goshiki_premium_elements' ) ) && ( ! function_exists( 'add_ochiba_premium_elements' ) ) && ( ! function_exists( 'add_koromo_premium_elements' ) ) && ( ! function_exists( 'add_kohaku_premium_elements' ) ) ) {
		require WPKOI_TEMPLATES_FOR_ELEMENTOR_DIRECTORY . 'elements/elementor.php';
	}
}