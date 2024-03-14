<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_fifu {
	public function __construct() {

		/* checkout page */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ] );
		add_filter( 'wfacp_product_image', [ $this, 'change_product_image' ], 10, 2 );

	}

	/**
	 * @param $product_image_url
	 * @param $product WC_Product
	 *
	 * @return string
	 */
	public function change_product_image( $product_image_url, $product ) {

		$product_id = $product->get_id();

		if ( $product_id > 0 ) {
			$url = get_post_meta( $product_id, 'fifu_image_url', true );

			if ( $url != '' ) {
				return $url;
			}
		}

		return $product_image_url;
	}

	public function actions() {
		remove_filter( 'wc_get_template', 'fifu_woo_template', 10 );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_fifu(), 'fifu' );

