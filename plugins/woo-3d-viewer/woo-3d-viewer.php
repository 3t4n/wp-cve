<?php
/*
Plugin Name: Easy 3D Viewer
Description: WP/WooCommerce 3D viewer
Author: Sergey Burkov
Text Domain: woo3dv
Version: 1.8.6.3
WC requires at least: 3.5
WC tested up to: 8.6
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define('WOO3DV_VERSION', '1.8.6.3');

global $wpdb;
if ( !function_exists( 'get_home_path' ) ) {
	require_once ABSPATH . '/wp-admin/includes/file.php';
}

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

include 'includes/woo3dv-functions.php';

if ( is_admin() ) {
	add_action( 'admin_enqueue_scripts', 'woo3dv_enqueue_scripts_backend' );
	#add_action( 'wp_ajax_woo3dv_handle_upload', 'woo3dv_handle_upload' );
	add_action( 'wp_ajax_woo3dv_handle_zip', 'woo3dv_handle_zip' );
	include 'includes/woo3dv-admin.php';
}
else {
	add_action( 'wp_enqueue_scripts', 'woo3dv_enqueue_scripts_frontend' );
}


register_activation_hook( __FILE__, 'woo3dv_activate' );
register_deactivation_hook( __FILE__, 'woo3dv_deactivate' );

add_action('init', 'woo3dv_check_installation');
function woo3dv_check_installation() {

	if ( ! function_exists( 'get_plugin_data' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$woo3dv_plugin_data = get_plugin_data(  __FILE__ );
	$woo3dv_current_version = get_option('woo3dv_version');

	if (!empty($woo3dv_current_version) && version_compare($woo3dv_current_version, $woo3dv_plugin_data['Version'], '<')) {
		woo3dv_check_install();
	}
}

?>