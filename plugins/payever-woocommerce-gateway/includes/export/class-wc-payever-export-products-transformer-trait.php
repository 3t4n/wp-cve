<?php

if ( ! defined( 'ABSPATH' ) || trait_exists( 'WC_Payever_Export_Products_Transformer_Trait' ) ) {
	return;
}

trait WC_Payever_Export_Products_Transformer_Trait {

	/** @var WC_Payever_Export_Products_Transformer */
	private $products_transformer;

	/**
	 * @param WC_Payever_Export_Products_Transformer $products_transformer
	 * @return $this
	 * @internal
	 */
	public function set_products_transformer( WC_Payever_Export_Products_Transformer $products_transformer ) {
		$this->products_transformer = $products_transformer;

		return $this;
	}

	/**
	 * @return WC_Payever_Export_Products_Transformer
	 * @codeCoverageIgnore
	 */
	private function get_products_transformer() {
		return null === $this->products_transformer
			? $this->products_transformer = new WC_Payever_Export_Products_Transformer()
			: $this->products_transformer;
	}
}
