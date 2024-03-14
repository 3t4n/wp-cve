<?php

if ( ! defined( 'ABSPATH' ) || trait_exists( 'WC_Payever_Products_Api_Client_Trait' ) ) {
	return;
}

use Payever\Sdk\Products\ProductsApiClient;

trait WC_Payever_Products_Api_Client_Trait {

	/** @var ProductsApiClient */
	private $product_api_client;

	/**
	 * @param ProductsApiClient $product_api_client
	 * @return $this
	 * @internal
	 */
	public function set_product_api_client( ProductsApiClient $product_api_client ) {
		$this->product_api_client = $product_api_client;

		return $this;
	}

	/**
	 * @return \Payever\Sdk\Inventory\InventoryApiClient|ProductsApiClient
	 * @throws Exception
	 * @codeCoverageIgnore
	 */
	private function get_product_api_client() {
		return null === $this->product_api_client
			? $this->product_api_client = WC_Payever_Api::get_instance()->get_products_api_client()
			: $this->product_api_client;
	}
}
