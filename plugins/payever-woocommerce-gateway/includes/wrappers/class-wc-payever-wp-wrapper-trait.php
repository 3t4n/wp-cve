<?php

if ( ! defined( 'ABSPATH' ) || trait_exists( 'WC_Payever_WP_Wrapper_Trait' ) ) {
	return;
}

trait WC_Payever_WP_Wrapper_Trait {

	/** @var WC_Payever_WP_Wrapper */
	private $wp_wrapper;

	/**
	 * @param WC_Payever_WP_Wrapper $wp_wrapper
	 * @return $this
	 * @internal
	 */
	public function set_wp_wrapper( WC_Payever_WP_Wrapper $wp_wrapper ) {
		$this->wp_wrapper = $wp_wrapper;

		return $this;
	}

	/**
	 * @return WC_Payever_WP_Wrapper
	 * @codeCoverageIgnore
	 */
	protected function get_wp_wrapper() {
		return null === $this->wp_wrapper
			? $this->wp_wrapper = new WC_Payever_WP_Wrapper()
			: $this->wp_wrapper;
	}
}
