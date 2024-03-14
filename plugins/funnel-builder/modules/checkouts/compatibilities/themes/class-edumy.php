<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Edumy  By ApusTheme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties]
  class WFACP_Compatibility_With_Edumy {

	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'register_elementor_widget' ], 20 );
	}
	public function register_elementor_widget() {
		if ( class_exists( 'Edumy_Elementor_Extensions' ) && class_exists( 'Elementor\Plugin' ) && class_exists( 'WFACP_Core' ) ) {
			if ( is_admin() ) {
				return;
			}
			if ( false == wfacp_elementor_edit_mode() ) {
				$r_instance = WFACP_Common::remove_actions( 'init', 'Edumy_Elementor_Extensions', 'elementor_widgets' );
				if ( $r_instance instanceof Edumy_Elementor_Extensions ) {
					add_action( 'wp', array( $r_instance, 'elementor_widgets' ), 100 );
				}
			}
		}
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Edumy(), 'edumy_extension' );
