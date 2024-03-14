<?php
/*
 * Plugin Name: Email Subscribers - Group Selector
 * Plugin URI: https://www.icegram.com/
 * Description: Add-on for Email Subscribers plugin using which you can provide option to your subscribers to select interested groups in the Subscribe Form.
 * Version: 1.5.1
 * Author: Icegram
 * Author URI: https://www.icegram.com/
 * Requires at least: 3.9
 * Tested up to: 4.9.6
 * Text Domain: email-subscribers-advanced-form
 * Domain Path: /languages/
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Copyright (c) 2016-2018 Icegram
 */

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'es-af-stater.php');

register_activation_hook( ES_AF_FILE, array( 'es_af_registerhook', 'es_af_activation' ) );
register_deactivation_hook( ES_AF_FILE, array( 'es_af_registerhook', 'es_af_deactivation' ) );

add_action( 'widgets_init', array( 'es_af_registerhook', 'es_af_widget_loading' ) );
add_action( 'admin_menu', array( 'es_af_registerhook', 'es_af_adminmenu' ), 11 );
add_action( 'admin_enqueue_scripts', array( 'es_af_registerhook', 'es_af_load_scripts' ) );

// Admin Notices
add_action( 'admin_notices', array( 'es_af_registerhook', 'esaf_add_admin_notices' ) );
add_action( 'admin_init', array( 'es_af_registerhook', 'esaf_dismiss_admin_notice' ) );

add_shortcode( 'email-subscribers-advanced-form', 'es_af_shortcode' );

function es_af_add_scripts() {
	if (!is_admin()) {
		wp_register_style( 'email-subscribers-advanced-form', ES_AF_URL.'assets/css/styles.css' );
		wp_enqueue_style( 'email-subscribers-advanced-form');
	}
}

function es_af_textdomain() {
	load_plugin_textdomain( ES_AF_TDOMAIN , false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'es_af_textdomain' );
add_action( 'wp_enqueue_scripts', 'es_af_add_scripts' );
