<?php

if ( ! defined( 'ABSPATH' ) || trait_exists( 'WC_Payever_Subscription_Manager_Trait' ) ) {
	return;
}

trait WC_Payever_Subscription_Manager_Trait {

	/** @var WC_Payever_Subscription_Manager */
	private $subscription_manager;

	/**
	 * @param WC_Payever_Subscription_Manager $subscription_manager
	 * @return $this
	 * @internal
	 */
	public function set_subscription_manager( WC_Payever_Subscription_Manager $subscription_manager ) {
		$this->subscription_manager = $subscription_manager;

		return $this;
	}

	/**
	 * @return WC_Payever_Subscription_Manager
	 * @codeCoverageIgnore
	 */
	private function get_subscription_manager() {
		return null === $this->subscription_manager
			? $this->subscription_manager = new WC_Payever_Subscription_Manager()
			: $this->subscription_manager;
	}
}
