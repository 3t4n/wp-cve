<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Theme_Jupiter {

	public function __construct() {

		/* checkout page */
		add_action( 'wfacp_checkout_page_found', [ $this, 'remove_customizer_setting' ] );

	}

	public function remove_customizer_setting() {

		if ( ! WFACP_Common::is_customizer() ) {
			return;
		}


		global $wp_filter;
		foreach ( $wp_filter['customize_register']->callbacks as $key => $val ) {
			if ( 10 !== $key ) {
				continue;
			}
			foreach ( $val as $innerkey => $innerval ) {
				if ( isset( $innerval['function'] ) && is_array( $innerval['function'] ) ) {
					if ( is_a( $innerval['function']['0'], 'MK_Customizer' ) ) {
						$mk_customizer = $innerval['function']['0'];
						remove_action( 'customize_register', array( $mk_customizer, 'register_settings' ) );
						break;
					}
				}
			}
		}

	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Theme_Jupiter(), 'jupiter' );
