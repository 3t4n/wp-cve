<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Include scripts
function ns_load_js_bta() {
	wp_enqueue_script( 'ns-smooth-scroll-back-to-top', WPBTTA_NS_PLUGIN_DIR_URI . 'assets/js/ns-bk-to-top-arrow.js', array('jquery'), '1.0.0', true );
	wp_localize_script( 'ns-smooth-scroll-back-to-top', 'ns_btta_ajax_hit', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
}
add_action( 'wp_enqueue_scripts', 'ns_load_js_bta' );

// Include scripts admin
function ns_load_js_bta_admin() {
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'ns-smooth-scroll-back-to-top-admin', WPBTTA_NS_PLUGIN_DIR_URI . 'assets/js/custom.js', array('jquery', 'wp-color-picker'), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'ns_load_js_bta_admin' );
?>