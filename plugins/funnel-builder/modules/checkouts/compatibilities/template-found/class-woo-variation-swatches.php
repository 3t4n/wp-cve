<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Woo_Variation_Swatches {
	public function __construct() {

		/* checkout page */
		add_action( 'wfacp_checkout_page_found', [ $this, 'add_class' ] );

	}

	public function add_class() {
		add_filter( 'wfacp_body_class', function ( $aero_class ) {
			$body_class = get_body_class();
			if ( ! empty( $body_class ) ) {
				foreach ( $body_class as $key => $value ) {
					if ( false !== strpos( $value, 'woo-variation-swatches' ) ) {
						$aero_class[] = $value;
					}
				}
			}

			return $aero_class;
		}, 10 );

	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Woo_Variation_Swatches(), 'woo-variation-swatches' );
