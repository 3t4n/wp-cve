<?php

if ( ! defined( 'ABSPATH' ) || trait_exists( 'WC_Payever_Helper_Wrapper_Trait' ) ) {
	return;
}

trait WC_Payever_Helper_Wrapper_Trait {

	/** @var WC_Payever_Helper_Wrapper */
	private $helper_wrapper;

	/**
	 * @param WC_Payever_Helper_Wrapper $helper_wrapper
	 * @return $this
	 * @internal
	 */
	public function set_helper_wrapper( WC_Payever_Helper_Wrapper $helper_wrapper ) {
		$this->helper_wrapper = $helper_wrapper;

		return $this;
	}

	/**
	 * @return WC_Payever_Helper_Wrapper
	 * @codeCoverageIgnore
	 */
	protected function get_helper_wrapper() {
		return null === $this->helper_wrapper
			? $this->helper_wrapper = new WC_Payever_Helper_Wrapper()
			: $this->helper_wrapper;
	}
}
