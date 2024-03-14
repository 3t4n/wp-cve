<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Add css
function ns_load_css_bta() {
	wp_enqueue_style( 'font-awesome.min', WPBTTA_NS_PLUGIN_DIR_URI .'assets/css/font-awesome.min.css', array(), '4.5.0' );
	wp_enqueue_style( 'ns-bta-style', WPBTTA_NS_PLUGIN_DIR_URI .'assets/css/ns-bta-style.css', array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'ns_load_css_bta' );




function ns_bta_load_admin_css($hook) {
	wp_enqueue_style( 'ns-bta-admin-style-js', WPBTTA_NS_PLUGIN_DIR_URI .'assets/css/ns-bta-admin-style.css', array(), '1.0.0' );	
}
add_action( 'admin_enqueue_scripts', 'ns_bta_load_admin_css' );
?>