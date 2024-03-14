<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Salient {

	public function __construct() {
		/* checkout page */
		add_action( 'wfacp_checkout_page_found', [ $this, 'remove_head_actions' ] );

	}

	public function remove_head_actions() {

		if ( function_exists( 'nectar_custom_css' ) ) {
			remove_action( 'wp_head', 'nectar_custom_css' );
		}
		if ( function_exists( 'nectar_colors' ) ) {
			remove_action( 'wp_head', 'nectar_colors' );
		}
		if ( function_exists( 'nectar_typography' ) ) {
			remove_action( 'wp_head', 'nectar_typography' );
		}
	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Salient(), 'wfacp-salient' );
