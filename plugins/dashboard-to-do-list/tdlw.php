<?php
/*
	Plugin Name: Dashboard To-Do List
	Description: Dashboard To-Do list widget with option to show as a floating list on your website.
	Version: 1.3.2
	Author: Andrew Rapps
	Author URI: https://arwebdesign.co.uk
	License: GPL2
	Text Domain: dashboard-to-do-list
	Domain Path: /languages
	*/

	if ( ! defined( 'ABSPATH' ) ) exit;

	if( !function_exists('get_plugin_data') ){
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	// Get version
	if ( !function_exists( 'ardtdw_version' ) ) {
		function ardtdw_version() {
			$ardtdw_plugin_data = get_plugin_data( __FILE__ );
			$ardtdw_plugin_version = $ardtdw_plugin_data['Version'];
			return $ardtdw_plugin_version;
		}
	}

// Load text domain
	function dashboard_to_do_list_load_plugin_textdomain() {
		load_plugin_textdomain( 'dashboard-to-do-list', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}
	add_action( 'plugins_loaded', 'dashboard_to_do_list_load_plugin_textdomain' );

// Load frontend scripts
	if ( !function_exists( 'ardtdw_widget_scripts' ) ) {
		function ardtdw_widget_scripts() {
			wp_register_style( 'ardtdw_widget_css', plugins_url( '/public/assets/todo-widget.css', __FILE__ ), false, ardtdw_version(), 'all' );
			wp_enqueue_style( 'ardtdw_widget_css' );
		}
		add_action( 'wp_enqueue_scripts', 'ardtdw_widget_scripts' );
	}

// Load backend scripts
	if ( !function_exists( 'ardtdw_widget_scripts_admin' ) ) {
		function ardtdw_widget_scripts_admin() {
			wp_register_style( 'ardtdw_widget_admincss', plugins_url( '/admin/assets/widgets.css', __FILE__ ), false, ardtdw_version(), 'all' );
			wp_enqueue_style( 'ardtdw_widget_admincss' );
		}
		add_action( 'admin_enqueue_scripts', 'ardtdw_widget_scripts_admin' );
	}


  // --------- Import Files ---------- //
	include_once('public/todo-widget-html.php');
	require_once('admin/todo-widget.php');
