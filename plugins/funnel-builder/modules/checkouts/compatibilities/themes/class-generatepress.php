<?php
/**
 * GeneratePress Theme Compatibility added
 * https://generatepress.com
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties]
class WFACP_Compatibility_With_GeneratePress {
	public function __construct() {
		add_action( 'wfacp_checkout_page_found', [ $this, 'register_elementor_widget' ], 20 );
	}

	public function register_elementor_widget() {

		if ( class_exists( 'WFACP_Common' ) && WFACP_Common::is_customizer() ) {
			remove_action( 'customize_register', 'generate_customize_register', 20 );
			remove_action( 'customize_register', 'generate_default_fonts_customize_register' );
			remove_action( 'customize_register', 'generate_pro_compat_customize_register', 100 );
		}
	}
}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_GeneratePress(), 'generatepress' );

