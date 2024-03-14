<?php
/**
 * Plugin Name: Devices for Elementor
 * Description: An Elementor widget that lets you add devices and frames with screenshots to your page.
 * Version:     1.0.4
 * Author:      Namogo
 * Author URI:  https://namogo.com/
 * Text Domain: devices-elementor
 * License:     GPL3
 *
 *
 * Devices for Elementor is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Devices for Elementor is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Devices for Elementor. If not, see {License URI}.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require  __DIR__ . '/admin/class-admin-notices-dismissal.php';

define( 'DEVICES_ELEMENTOR__FILE__', 		__FILE__ );
define( 'DEVICES_ELEMENTOR_PLUGIN_BASE', 	plugin_basename( DEVICES_ELEMENTOR__FILE__ ) );
define( 'DEVICES_ELEMENTOR_URL', 			plugins_url( '/', DEVICES_ELEMENTOR__FILE__ ) );
define( 'DEVICES_ELEMENTOR_PATH', 			plugin_dir_path( DEVICES_ELEMENTOR__FILE__ ) );
define( 'DEVICES_ELEMENTOR_ASSETS_URL', 	DEVICES_ELEMENTOR_URL . 'assets/' );
define( 'DEVICES_ELEMENTOR_VERSION', 		'1.0.4' );
define( 'DE_ELEMENTOR_VERSION_REQUIRED', 	'1.4.1' );
define( 'DE_PHP_VERSION_REQUIRED', 			'5.0' );

/**
 * Load Devices for Elementor
 *
 * Load the plugin after Elementor (and other plugins) are loaded.
 *
 * @since 0.0.1
 */
function devices_elementor_load() {
	// Load localization file
	load_plugin_textdomain( 'devices-elementor' );

	add_action( 'admin_notices', 	'devices_elementor_notices' );
	add_action( 'admin_init', array( 'PAnD', 'init' ) );

	// Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'devices_elementor_fail_load' );
		return;
	}

	// Check version required=
	if ( ! version_compare( ELEMENTOR_VERSION, DE_ELEMENTOR_VERSION_REQUIRED, '>=' ) ) {

		add_action( 'admin_notices', 'devices_elementor_fail_load_out_of_date' );
		return;

	}

	// Check for required PHP version
	if ( version_compare( PHP_VERSION, DE_PHP_VERSION_REQUIRED, '<' ) || ! class_exists("DomDocument") ) {

		add_action( 'admin_notices', 	'devices_elementor_php_fail' );
		add_action( 'admin_init', 		'devices_elementor_deactivate' );
		return;
	}

	// Disable if Elementor Extras is already active
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	if ( is_plugin_active( 'elementor-extras/elementor-extras.php' ) ) {

		add_action( 'admin_notices', 	'devices_elementor_elementor_extras_active' );
		add_action( 'admin_init', 		'devices_elementor_deactivate' );
		return;
	}

	// Require the main plugin file
	require( __DIR__ . '/includes/plugin.php' );
}
add_action( 'plugins_loaded', 'devices_elementor_load' );

/**
 * Handles admin notice for non-active
 * Elementor plugin situations
 *
 * @since 0.0.1
 */
function devices_elementor_fail_load() {

	$class = 'notice notice-error';
	$message = sprintf( __( 'You need %1$s"Elementor"%2$s for the %1$s"Devices for Elementor"%2$s plugin to work.', 'devices-elementor' ), '<strong>', '</strong>' );

	$plugin = 'elementor/elementor.php';

	if ( _is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$action_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
		$button_label = __( 'Activate Elementor', 'devices-elementor' );

	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$action_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
		$button_label = __( 'Install Elementor', 'devices-elementor' );
	}

	$button = '<p><a href="' . $action_url . '" class="button-primary">' . $button_label . '</a></p><p></p>';

	printf( '<div class="%1$s"><p>%2$s</p>%3$s</div>', esc_attr( $class ), $message, $button );
}

/**
 * Handles admin notice for outdated Elementor version
 *
 * @since 0.0.1
 */
function devices_elementor_fail_load_out_of_date() {
	$class = 'notice notice-error';
	$message = __( 'Please update Elementor to at least version ' . DE_ELEMENTOR_VERSION_REQUIRED . ' to be able to activate Devices for Elementor', 'devices-elementor' );

	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}

/**
 * Handles admin notice for PHP version requirements
 *
 * @since 0.0.1
 */
function devices_elementor_php_fail() {

	$class = 'notice notice-error';
	$message = sprintf( __( '%1$s"Devices for Elementor"%2$s needs at least PHP version ' . DE_PHP_VERSION_REQUIRED .' to work properly. The plugin will be deactivated until you update PHP.', 'devices-elementor' ), '<strong>', '</strong>');

	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );

	if ( isset( $_GET['activate'] ) ) 
		unset( $_GET['activate'] );
}

/**
 * Handles admin notice for PHP version requirements
 *
 * @since 0.0.1
 */
function devices_elementor_notices() {

	$elementor_extras_url = 'https://shop.namogo.com/product/elementor-extras/?ref=devices-elementor';

	if ( ! PAnD::is_admin_notice_active( 'elementor-extras-notice-forever' ) ) {
		return;
	}

	$class = 'updated notice notice-success is-dismissible';
	$message = sprintf (__( 'If you like %1$s"Devices for Elementor"%2$s check out our %3$sElementor Extras%4$s plugin, with plenty of new Elementor widgets like %1$sButton Groups, Image Comparison, Hotspots, Circle Progress, Timeline and Devices widget with video support%2$s.', 'devices-elementor' ), '<strong>', '</strong>', '<a target="_blank" href="' . $elementor_extras_url . '">', '</a>');

	printf( '<div data-dismissible="elementor-extras-notice-forever" class="%1$s"><p>%2$s</p><p><a target="_blank" href="' . $elementor_extras_url . '" class="button-primary">%3$s</a></p><p></p></div>', esc_attr( $class ), $message, __( 'Get Extras', 'devices-elementor' ) );

	if ( isset( $_GET['activate'] ) ) 
		unset( $_GET['activate'] );
}

/**
 * Handles admin notice in case Elementor Extras is active
 *
 * @since 1.0.0
 */
function devices_elementor_elementor_extras_active() {

	if ( ! PAnD::is_admin_notice_active( 'elementor-extras-active-notice-forever' ) ) {
		return;
	}

	$class = 'notice notice-error is-dismissible';
	$message = sprintf( __( 'We noticed you have %1$s"Elementor Extras"%2$s installed and activated. %1$s"Devices for Elementor"%2$s is included in %1$s"Elementor Extras"%2$s, so you cannot activate it. Feel free to delete %1$s"Devices for Elementor"%2$s.', 'devices-elementor' ), '<strong>', '</strong>' );

	printf( '<div data-dismissible="elementor-extras-active-notice-forever" class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );

	if ( isset( $_GET['activate'] ) ) 
		unset( $_GET['activate'] );
}

/**
 * Deactivates the plugin
 *
 * @since 0.0.1
 */
function devices_elementor_deactivate() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

if ( ! function_exists( '_is_elementor_installed' ) ) {

	function _is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}