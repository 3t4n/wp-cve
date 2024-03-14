<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_UneroTheme {
	public function __construct() {

		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ] );
	}

	public function actions() {
		remove_action( 'wp_footer', 'unero_product_images_lightbox' );
		remove_action( 'wp_footer', 'unero_off_canvas_cart' );
		remove_action( 'wp_footer', 'unero_off_canvas_menu_sidebar' );
		remove_action( 'wp_footer', 'unero_site_canvas_layer' );
		remove_action( 'wp_footer', 'unero_off_canvas_mobile_menu' );
		remove_action( 'wp_footer', 'unero_search_modal' );
		remove_action( 'wp_footer', 'unero_login_modal' );
		remove_action( 'wp_footer', 'unero_quick_view_modal' );
		remove_action( 'wp_footer', 'unero_back_to_top' );
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_UneroTheme(), 'unero_theme' );
