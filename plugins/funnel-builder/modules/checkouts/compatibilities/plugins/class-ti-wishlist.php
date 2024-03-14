<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Ti_Wishlist {

	public function __construct() {
		/* checkout page */
		add_action( 'wfac_qv_images', [ $this, 'unhook_wishlist_button' ] );
	}

	public function unhook_wishlist_button() {
		remove_action( 'woocommerce_after_add_to_cart_button', 'tinvwl_view_addto_html', 0 );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Ti_Wishlist(), 'ti_wishlist' );

