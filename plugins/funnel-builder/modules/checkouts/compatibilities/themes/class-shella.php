<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Shella {

	public function __construct() {

		add_action( 'after_setup_theme', [ $this, 'remove_theme_customizer' ] );

	}

	public function remove_theme_customizer() {
		if ( ! function_exists( 'shella_register_customizer' ) ) {
			return;
		}

		if ( class_exists( 'WFACP_Common' ) && WFACP_Common::is_customizer() ) {
			remove_action( 'customize_register', 'shella_register_customizer' );

		}
	}

}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Shella(), 'wfacp-shella-theme' );
