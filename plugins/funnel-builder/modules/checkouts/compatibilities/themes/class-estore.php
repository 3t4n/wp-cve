<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Estore {

	public function __construct() {

		add_action( 'wfacp_checkout_page_found', [ $this, 'unhook_customizer_register' ] );

	}

	public function unhook_customizer_register() {

		if ( function_exists( 'estore_customize_register' ) ) {
			remove_action( 'customize_register', 'estore_customize_register' );
		}

	}

}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Estore(), 'estore' );
