<?php
/**
 * Plugin Name:     WP Widget CSS Classes
 * Plugin URI:      
 * Description:     WP Widget CSS Classes gives you the ability to add custom classes to your WordPress widgets.
 * Author:          Dipak Parmar
 * Author URI:      https://profiles.wordpress.org/dipakparmar443/
 * Donate link:     https://www.paypal.me/dipakparmar443/
 * Text Domain:     wp-ngd-widget-css-classes
 * Domain Path:     /languages
 * Version:         1.1
 * Donate link:     https://www.paypal.me/dipakparmar443/
 * @package         WP_NGD_Widget_Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'includes/class-' . basename( __FILE__ );

/**
 * Plugin textdomain.
 */
function wp_ngd_widget_css_classes_textdomain() {
	load_plugin_textdomain( 'wp-ngd-widget-css-classes', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'wp_ngd_widget_css_classes_textdomain' );

/**
 * Plugin activation.
 */
function wp_ngd_widget_css_classes_activation() {
	// Activation code here.
}
register_activation_hook( __FILE__, 'wp_ngd_widget_css_classes_activation' );

/**
 * Plugin deactivation.
 */
function wp_ngd_widget_css_classes_deactivation() {
	// Deactivation code here.
}
register_deactivation_hook( __FILE__, 'wp_ngd_widget_css_classes_deactivation' );

/**
 * Initialization class.
 */
function wp_ngd_widget_css_classes_init() {
	global $wp_ngd_widget_classes;
	$wp_ngd_widget_classes = new WP_NGD_Widget_Class();
}
add_action( 'plugins_loaded', 'wp_ngd_widget_css_classes_init' );