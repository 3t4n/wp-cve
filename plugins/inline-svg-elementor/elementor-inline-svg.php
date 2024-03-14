<?php
/**
 * Plugin Name: Elementor Inline SVG
 * Description: An Elementor widget that lets you add an SVG file as markup instead of an html image tag.
 * Version:     1.2.0
 * Author:      Namogo
 * Author URI:  https://namogo.com/
 * Text Domain: elementor-inline-svg
 * License:     GPL3
 *
 *
 * Elementor Inline SVG is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Elementor Inline SVG is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Elementor Inline SVG. If not, see {License URI}.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ELEMENTOR_SVG__FILE__', 		__FILE__ );
define( 'ELEMENTOR_SVG_VERSION', 		'1.1.0' );
define( 'ELEMENTOR_VERSION_REQUIRED', 	'1.5.8' );
define( 'PHP_VERSION_REQUIRED', 		'5.0' );

/**
 * Load Elementor Inline SVG
 *
 * Load the plugin after Elementor (and other plugins) are loaded.
 *
 * @since 0.0.1
 */
function elementor_inline_svg_load() {
	// Load localization file
	load_plugin_textdomain( 'elementor-inline-svg' );

	// Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'elementor_inline_svg_fail_load' );
		return;
	}

	// Check version required=
	if ( ! version_compare( ELEMENTOR_VERSION, ELEMENTOR_VERSION_REQUIRED, '>=' ) ) {

		add_action( 'admin_notices', 'elementor_inline_svg_fail_load_out_of_date' );
		return;

	}

	// Check for required PHP version
	if ( version_compare( PHP_VERSION, PHP_VERSION_REQUIRED, '<' ) || ! class_exists("DomDocument") ) {

		// add_action( 'admin_notices', 	'elementor_inline_svg_php_fail' );
		add_action( 'admin_init', 		'elementor_inline_svg_deactivate' );
		return;
	}

	// Check for PHP dom extension
	if ( ! class_exists("DomDocument") ) {

		add_action( 'admin_notices', 	'elementor_inline_svg_dom_extension_fail' );
		add_action( 'admin_init', 		'elementor_inline_svg_deactivate' );
		return;
	}

	// Require the main plugin file
	require( __DIR__ . '/includes/plugin.php' );
}
add_action( 'plugins_loaded', 'elementor_inline_svg_load' );

/**
 * Handles admin notice for non-active
 * Elementor plugin situations
 *
 * @since 0.0.1
 */
function elementor_inline_svg_fail_load() {
	$class = 'notice notice-warning is-dismissible';
	$message = __( 'Please make sure you update Elementor to the latest version to make sure Elementor Inline SVG work properly.', 'elementor-inline-svg' );

	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}

/**
 * Handles admin notice for PHP version requirements
 *
 * @since 0.0.3
 */
function elementor_inline_svg_php_fail() {
	global $php_version_required;

	$class = 'notice notice-error';
	$message = __( 'Elementor Inline SVG needs at least PHP version ' . PHP_VERSION_REQUIRED .' to work properly. We deactivated the plugin for now.', 'elementor-inline-svg' );

	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );

	if ( isset( $_GET['activate'] ) ) 
		unset( $_GET['activate'] );
}

/**
 * Handles admin notice for missing dom library
 *
 * @since 0.0.3
 */
function elementor_inline_svg_dom_extension_fail() {
	global $php_version_required;

	$class = 'notice notice-error';
	$message = __( 'Elementor Inline SVG requires the PHP Dom extension to work properly. This usually ships with PHP > 5.0 but we couldn\'t detect it on your system. Please make sure you enable it and re-activate the plugin.', 'elementor-inline-svg' );

	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );

	if ( isset( $_GET['activate'] ) ) 
		unset( $_GET['activate'] );
}

/**
 * Handles admin notice for outdated Elementor version
 *
 * @since 0.0.1
 */
function elementor_inline_svg_fail_load_out_of_date() {
	$class = 'notice notice-error is-dismissible';
	$message = __( 'Please update Elementor to the latest version to be able to activate Elementor Inline SVG', 'elementor-inline-svg' );

	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}

/**
 * Deactivates the plugin
 *
 * @since 0.0.3
 */
function elementor_inline_svg_deactivate() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}
