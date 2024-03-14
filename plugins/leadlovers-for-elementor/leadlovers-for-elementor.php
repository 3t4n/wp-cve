<?php
/*
Plugin Name: leadlovers for Elementor
Description: Integre seus formulários do Elementor Page Builder com a plataforma leadlovers.
Version: 1.10.1
Author: Leadlovers
Author URI: http://www.leadlovers.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function leadlovers_scripts_admin() {
	wp_enqueue_script( 'leadlovers_js_admin', plugins_url( '/script-admin.js', __FILE__ ), [], null, true );
}

function leadlovers_scripts_editor() {
	wp_enqueue_script( 'leadlovers_js_editor', plugins_url( '/script-editor.js', __FILE__ ), [
	'backbone-marionette',
	'elementor-common-modules',
	'elementor-editor-modules'
	], null, true );
}

function leadlovers_init() {
	add_action( 'admin_enqueue_scripts', 'leadlovers_scripts_admin', 99999 );
	add_action( 'elementor/editor/before_enqueue_scripts', 'leadlovers_scripts_editor', 99999 );
	
	load_plugin_textdomain( 'void' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}

	$elementor_version_required = '1.0.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		return;
	}
	
	require( __DIR__ . '/plugin.php' );
}

add_action( 'plugins_loaded', 'leadlovers_init' );