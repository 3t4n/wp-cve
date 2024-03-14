<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

add_action( 'admin_enqueue_scripts', 'in5_admin_assets', 99 );
function in5_admin_assets() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_style( 'in5-admin', IN5_PLUGIN_URL . 'assets/css/admin.css', array(), '1.0.0' );
	wp_enqueue_script( 'in5-admin', IN5_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), '1.0.0' );
	wp_localize_script( 'in5-admin', 'in5_ajax',
		array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'security' => wp_create_nonce( 'in5-security-string' ) ) );
	wp_enqueue_script( 'jquery-ui-widget', IN5_PLUGIN_URL . 'assets/js/jquery.ui.widget.js', array( 'jquery' ),
		'1.0.0' );
	wp_enqueue_script( 'iframe-transport', IN5_PLUGIN_URL . 'assets/js/jquery.iframe-transport.js', array( 'jquery' ),
		'1.0.0' );
	wp_enqueue_script( 'jquery-fileupload', IN5_PLUGIN_URL . 'assets/js/jquery.fileupload.js', array( 'jquery' ),
		'1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'in5_public_assets' );
function in5_public_assets() {
	wp_enqueue_style( 'in5-public', IN5_PLUGIN_URL . 'assets/css/public.css', array(), '1.0.0' );
	wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
	wp_enqueue_script( 'screenfull', IN5_PLUGIN_URL . 'assets/js/screenfull.js', array(), '1.0.0' );
	wp_enqueue_script( 'in5-public', IN5_PLUGIN_URL . 'assets/js/public.js', array( 'jquery' ), '1.0.0' );
}