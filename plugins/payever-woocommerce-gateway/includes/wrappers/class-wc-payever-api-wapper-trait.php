<?php

if ( ! defined( 'ABSPATH' ) || trait_exists( 'WC_Payever_Api_Wrapper_Trait' ) ) {
	return;
}

trait WC_Payever_Api_Wrapper_Trait {

	/** @var WC_Payever_Api_Wrapper */
	private $api_wrapper;

	/**
	 * @param WC_Payever_API_Wrapper $api_wrapper
	 *
	 * @return $this
	 * @codeCoverageIgnore
	 */
	public function set_api_wrapper( WC_Payever_API_Wrapper $api_wrapper ) {
		$this->api_wrapper = $api_wrapper;

		return $this;
	}

	/**
	 * @return WC_Payever_API_Wrapper
	 * @codeCoverageIgnore
	 */
	protected function get_api_wrapper() {
		return null === $this->api_wrapper
			? $this->api_wrapper = new WC_Payever_API_Wrapper()
			: $this->api_wrapper;
	}
}
