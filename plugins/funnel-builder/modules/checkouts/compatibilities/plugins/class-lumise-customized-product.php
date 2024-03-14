<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Lumise_Fancy {
	public function __construct() {
		add_filter( 'wfacp_cart_item_thumbnail', [ $this, 'add_customized_image' ], 10, 2 );
	}

	public function add_customized_image( $img_src, $cart_item ) {

		if ( empty( $img_src ) && is_null( $cart_item ) ) {
			return $img_src;
		}

		/** Compatibility with Fancy Product Designer plugin*/
		if ( class_exists( 'Fancy_Product_Designer' ) && isset( $cart_item['fpd_data'] ) ) {
			$fpd_data = $cart_item['fpd_data'];
			$img_src  = $fpd_data['fpd_product_thumbnail'];
		}

		// Compatibility with Lumise Product Designer Tool plugin
		global $lumise;
		if ( ! empty( $lumise ) && isset( $cart_item['lumise_data'] ) ) {
			$cart_item_data = $lumise->lib->get_cart_data( $cart_item['lumise_data'] );
			if ( isset( $cart_item_data['screenshots'] ) && is_array( $cart_item_data['screenshots'] ) ) {
				$img_src = isset( $cart_item_data['screenshots'][0] ) ? $lumise->cfg->upload_url . $cart_item_data['screenshots'][0] : '';
			}
		}

		return $img_src;
	}


}


add_action( 'plugins_loaded', function () {
	if ( ! class_exists( 'lumise_woocommerce' ) ) {
		return;
	}
	WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Lumise_Fancy(), 'lumise_fancy_customized' );

}, 15 );


