<?php
/*
 * Plugin Name: VS Website Closed
 * Description: With this lightweight plugin you can close your website on selected days of the week.
 * Version: 2.9
 * Author: Guido
 * Author URI: https://www.guido.site
 * License: GNU General Public License v3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Requires PHP: 7.0
 * Requires at least: 5.3
 * Text Domain: very-simple-website-closed
 */

// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// enqueue color picker script
function vswc_enqueue_color_picker() {
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'vswc-color-picker-script', plugins_url('/js/vswc-color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'vswc_enqueue_color_picker' );

// add settings link
function vswc_action_links( $links ) {
	$settingslink = array( '<a href="'.admin_url( 'options-general.php?page=vswc' ).'">'.__('Settings', 'very-simple-website-closed').'</a>', );
	return array_merge( $links, $settingslink );
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'vswc_action_links' );

// create closed page
function vswc_template() {
	// include variables
	include 'vswc-variables.php';
	// include template
	include 'vswc-template.php';
}
add_action( 'template_redirect', 'vswc_template' );

// include options file
include 'vswc-options.php';
