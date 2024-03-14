<?php

if ( ! defined( 'ABSPATH' ) || trait_exists( 'WC_Payever_Product_Uuid_Trait' ) ) {
	return;
}

trait WC_Payever_Product_Uuid_Trait {

	/** @var WC_Payever_Product_Uuid */
	private $product_uuid_manager;

	/**
	 * @param WC_Payever_Product_Uuid $product_uuid_manager
	 * @return $this
	 * @internal
	 */
	public function set_product_uuid_manager( WC_Payever_Product_Uuid $product_uuid_manager ) {
		$this->product_uuid_manager = $product_uuid_manager;

		return $this;
	}

	/**
	 * @return WC_Payever_Product_Uuid
	 * @codeCoverageIgnore
	 */
	private function get_product_uuid_manager() {
		return null === $this->product_uuid_manager
			? $this->product_uuid_manager = new WC_Payever_Product_Uuid()
			: $this->product_uuid_manager;
	}
}
