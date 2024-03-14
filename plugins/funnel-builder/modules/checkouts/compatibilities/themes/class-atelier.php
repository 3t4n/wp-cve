<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Atelier {

	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_styling' ] );
	}

	public function remove_styling() {
		remove_action( 'wp_head', 'sf_custom_styles' );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Atelier(), 'atelier' );
