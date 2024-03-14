<?php

/**
 * WooCommerce Price Based on Country (Basic)
 * Author: Oscar Gare
 * #[AllowDynamicProperties] 

  class WFACP_Product_Price_Based_Country
 */
#[AllowDynamicProperties] 

  class WFACP_Product_Price_Based_Country {
	public function __construct() {
		add_filter( 'wfacp_product_raw_data', [ $this, 'change_price_data' ], 10, 2 );
	}

	public function change_price_data( $raw_data, $product ) {
		if ( ! class_exists( 'WC_Product_Price_Based_Country' ) ) {
			return $raw_data;
		}
		/**
		 * @var $product WC_Product
		 * return $raw_data;
		 */
		$raw_data['regular_price'] = $product->get_regular_price();
		$raw_data['price']         = $product->get_price();

		return $raw_data;
	}
}

new WFACP_Product_Price_Based_Country();
