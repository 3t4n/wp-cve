<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties]

  class WFACP_Compatibility_With_Active_BuzzStore {

	public function __construct() {

		/* checkout page */
		add_action( 'wfacp_checkout_page_found', [ $this, 'remove_actions' ] );
	}

	public function remove_actions() {

		if ( function_exists( 'buzzstorepro_customize_register' ) && WFACP_Common::is_customizer() ) {
			remove_action( 'customize_register', 'buzzstorepro_customize_register' );
		}

	}

}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Active_BuzzStore(), 'wfacp-buzzstore' );



