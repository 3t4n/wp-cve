<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Wfob {
	public function __construct() {
		add_filter( 'wfob_bump_positions', [ $this, 'unset_mini_cart_position' ], 100 );
	}

	public function unset_mini_cart_position( $positions ) {
		if ( class_exists( 'WFACP_Mobile_Detect' ) ) {
			$detect = WFACP_Mobile_Detect::get_instance();
			if ( $detect->isMobile() && ! $detect->isTablet() ) {
				if ( isset( $positions['wfacp_below_mini_cart_items'] ) ) {
					unset( $positions['wfacp_below_mini_cart_items'] );
				}
			}
		}

		return $positions;
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Wfob(), 'wfob' );
