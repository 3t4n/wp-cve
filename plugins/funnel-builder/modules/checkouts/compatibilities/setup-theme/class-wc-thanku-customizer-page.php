<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WC_Thanku_Customizer_page {
	public function __construct() {
		/* checkout page */
		add_action( 'init', [ $this, 'actions' ] );

	}


	public function actions() {
		if ( ! WFACP_Common::is_customizer() ) {
			return;
		}
		WFACP_Common::remove_actions( 'customize_controls_print_scripts', 'VI_WOOCOMMERCE_THANK_YOU_PAGE_Admin_Design', 'customize_controls_print_scripts' );
	}


}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_Thanku_Customizer_page(), 'wctyp' );
