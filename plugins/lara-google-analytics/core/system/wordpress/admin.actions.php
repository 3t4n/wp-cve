<?php
namespace Lara\Widgets\GoogleAnalytics;

/**
 * @package    Google Analytics by Lara
 * @author     Amr M. Ibrahim <mailamr@gmail.com>
 * @link       https://www.xtraorbit.com/
 * @copyright  Copyright (c) XtraOrbit Web development SRL 2016 - 2020
 */

if (!defined("ABSPATH"))
    die("This file cannot be accessed directly");

add_action( 'admin_enqueue_scripts', 'Lara\Widgets\GoogleAnalytics\lrgawidget_enqueue',1000 );
add_action( 'wp_ajax_lrgawidget_hideShowWidget', 'Lara\Widgets\GoogleAnalytics\lrgawidget_callback' );
add_action( 'wp_ajax_lrgawidget_getAuthURL', 'Lara\Widgets\GoogleAnalytics\lrgawidget_callback' );
add_action( 'wp_ajax_lrgawidget_getAccessToken', 'Lara\Widgets\GoogleAnalytics\lrgawidget_callback' );
add_action( 'wp_ajax_lrgawidget_getAccountSummaries', 'Lara\Widgets\GoogleAnalytics\lrgawidget_callback' );
add_action( 'wp_ajax_lrgawidget_setMeasurementID', 'Lara\Widgets\GoogleAnalytics\lrgawidget_callback' );
add_action( 'wp_ajax_lrgawidget_settingsReset', 'Lara\Widgets\GoogleAnalytics\lrgawidget_callback' );
add_action( 'wp_ajax_lrgawidget_getMainGraph', 'Lara\Widgets\GoogleAnalytics\lrgawidget_callback' );
add_action( 'wp_ajax_lrgawidget_getBrowsers', 'Lara\Widgets\GoogleAnalytics\lrgawidget_callback' );
add_action( 'wp_ajax_lrgawidget_getLanguages', 'Lara\Widgets\GoogleAnalytics\lrgawidget_callback' );
add_action( 'wp_ajax_lrgawidget_getOS', 'Lara\Widgets\GoogleAnalytics\lrgawidget_callback' );
add_action( 'wp_ajax_lrgawidget_getDevices', 'Lara\Widgets\GoogleAnalytics\lrgawidget_callback' );
add_action( 'wp_ajax_lrgawidget_getScreenResolution', 'Lara\Widgets\GoogleAnalytics\lrgawidget_callback' );
add_action( 'wp_ajax_lrgawidget_getPages', 'Lara\Widgets\GoogleAnalytics\lrgawidget_callback' );
add_action( 'wp_ajax_lrgawidget_getGraphData', 'Lara\Widgets\GoogleAnalytics\lrgawidget_callback' );
add_action( 'wp_ajax_lrgawidget_getPermissions', 'Lara\Widgets\GoogleAnalytics\lrgawidget_callback' );
add_action( 'wp_ajax_lrgawidget_review_response', 'Lara\Widgets\GoogleAnalytics\lrgawidget_callback');
load_plugin_textdomain( 'lara-google-analytics', false, lrgawidget_plugin_dir . '/languages' );

function lrgawidget_load_system_bootstrap() {
	if (!defined("lrgawidget_system_bootstrap_loaded")){
		require(lrgawidget_plugin_dir . 'core/system/wordpress/system.bootstrap.class.php');
	}
}

function lrgawidget_enqueue($hook) {
	if ( ('index.php' === $hook) && !is_network_admin() ){
		lrgawidget_load_system_bootstrap();
		define ("lrgawidget_output_mode", "admin");
		if (current_user_can('manage_options')) {
			require(lrgawidget_plugin_dir . 'core/boot.php');
			$wstate = DataStore::database_get("user_options", "wstate");
			if ($wstate !== "hide"){
				wp_enqueue_style( lrgawidget_plugin_prefiex.'lrgawidget', lrgawidget_plugin_dist_url . 'css/'.lrgawidget_plugin_prefiex.'main.css'  ,array(),lrgawidget_plugin_scripts_version);
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( lrgawidget_plugin_prefiex.'main', lrgawidget_plugin_dist_url . 'js/'.lrgawidget_plugin_prefiex.'main.js' ,array('jquery'),lrgawidget_plugin_scripts_version,true);
				wp_localize_script( lrgawidget_plugin_prefiex.'main', 'lrgawidget_ajax_object', array( 'lrgawidget_ajax_url' => admin_url( 'admin-ajax.php' ) ));	
				add_action('in_admin_header','Lara\Widgets\GoogleAnalytics\lrga_welcome_panel');
			}else{
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( lrgawidget_plugin_prefiex.'main', lrgawidget_plugin_dist_url . 'js/lrgawidget_control.js' ,array('jquery'),lrgawidget_plugin_scripts_version);
				wp_localize_script( lrgawidget_plugin_prefiex.'main', 'lrgawidget_ajax_object', array( 'lrgawidget_ajax_url' => admin_url( 'admin-ajax.php' ) ));
			}

		}else{return;}
	}else{return;}
}

function lrgawidget_callback() {
	lrgawidget_load_system_bootstrap();
	if (current_user_can('manage_options')) {
		require(lrgawidget_plugin_dir . 'core/boot.php');
		require(lrgawidget_plugin_dir . 'core/lrgawidget.handler.php');
	}
}

function lrga_welcome_panel() {
	if(current_user_can('manage_options')){
		DataStore::$RUNTIME["askforreview"] = true;
	}
	require(lrgawidget_plugin_dir . 'widgets/lrgawidget.php');
}
	
?>