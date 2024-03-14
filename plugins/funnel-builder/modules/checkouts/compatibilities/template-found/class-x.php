<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_X {

	public function __construct() {

		/* checkout page */
		add_action( 'wfacp_checkout_page_found', [ $this, 'remove_x_customizer_fields' ] );
	}

	public function remove_x_customizer_fields() {

		remove_action( 'wp_head', 'x_output_generated_styles', 9998 );
		remove_action( 'x_head_css', 'x_customizer_output_css' );
		remove_action( 'x_head_css', 'x_customizer_output_custom_css', 25 );
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_X(), 'x' );
