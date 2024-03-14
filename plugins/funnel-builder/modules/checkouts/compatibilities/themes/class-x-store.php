<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties]
class WFACP_Compatibility_With_X_Store {

	public function __construct() {
		add_action( 'wfacp_checkout_page_found', [ $this, 'remove_actions' ] );

		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_attachment_image_attributes' ] );
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'remove_attachment_image_attributes' ] );


	}

	public function remove_attachment_image_attributes() {
		if ( function_exists( 'etheme_lazy_attachment_attrs' ) ) {
			remove_filter( 'wp_get_attachment_image_attributes', 'etheme_lazy_attachment_attrs', 10, 3 );

		}
	}

	public function remove_actions() {
		if ( function_exists( 'etheme_load_admin_styles_customizer' ) ) {

			remove_action( 'customize_controls_print_footer_scripts', 'etheme_load_admin_styles_customizer' );
		}

		if ( function_exists( 'kirki_installer_register' ) ) {

			remove_action( 'customize_register', 'kirki_installer_register', 999 );
		}
		if ( function_exists( 'etheme_refresh_header_buttons_partials' ) ) {
			remove_action( 'customize_register', 'etheme_refresh_header_buttons_partials', 999 );
		}
		if ( function_exists( 'etheme_enqueue_style' ) ) {
			etheme_enqueue_style( "swatches-style" );
		}

	}


}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_X_Store(), 'x-store' );
