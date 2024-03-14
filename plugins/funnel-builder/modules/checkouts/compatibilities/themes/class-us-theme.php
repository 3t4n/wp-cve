<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Active_Us_theme {
	public function __construct() {
		$this->remove_actions();
	}

	public function remove_actions() {
		$instance = wfacp_template();
		remove_action( 'wp_enqueue_scripts', 'us_woocomerce_dequeue_checkout_styles', 100 );
		if ( $instance->get_template_type() != 'pre_built' ) {
			return;
		}
		remove_action( 'wp_enqueue_scripts', 'us_custom_styles', 18 );
		remove_action( 'wp_enqueue_scripts', 'us_jscripts' );
		remove_action( 'wp_enqueue_scripts', 'us_styles', 12 );
		remove_action( 'wp_enqueue_scripts', 'us_woocomerce_dequeue_checkout_styles', 100 );
		remove_action( 'wp_footer', 'us_pass_header_settings_to_js', - 2 );
		//for imprezza theme
		remove_action( 'wp_footer', 'us_pass_header_settings_to_js' );
		remove_action( 'wp_footer', 'us_theme_js', 98 );

	}
}

add_action( 'wfacp_after_checkout_page_found', function () {
	if ( ! function_exists( 'us_woocomerce_dequeue_checkout_styles' ) ) {
		return;
	}
	WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Active_Us_theme(), 'us_theme' );
} );

