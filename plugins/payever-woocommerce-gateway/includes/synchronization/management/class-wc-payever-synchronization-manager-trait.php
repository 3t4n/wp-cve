<?php

if ( ! defined( 'ABSPATH' ) || trait_exists( 'WC_Payever_Synchronization_Manager_Trait' ) ) {
	return;
}

trait WC_Payever_Synchronization_Manager_Trait {

	/** @var WC_Payever_Synchronization_Manager */
	private $sync_manager;

	/**
	 * @param WC_Payever_Synchronization_Manager $sync_manager
	 * @return $this
	 * @internal
	 */
	public function set_synchronization_manager( WC_Payever_Synchronization_Manager $sync_manager ) {
		$this->sync_manager = $sync_manager;

		return $this;
	}

	/**
	 * @return WC_Payever_Synchronization_Manager
	 * @codeCoverageIgnore
	 */
	private function get_synchronization_manager() {
		return null === $this->sync_manager
			? $this->sync_manager = new WC_Payever_Synchronization_Manager()
			: $this->sync_manager;
	}
}
