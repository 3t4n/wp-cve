<?php

if ( ! defined( 'ABSPATH' ) || trait_exists( 'WC_Payever_Logger_Trait' ) ) {
	return;
}

trait WC_Payever_Synchronization_Queue_Trait {

	/** @var WC_Payever_Synchronization_Queue */
	private $sync_queue_manager;

	/**
	 * @param WC_Payever_Synchronization_Queue $sync_queue_manager
	 * @return $this
	 * @internal
	 */
	public function set_sync_queue_manager( WC_Payever_Synchronization_Queue $sync_queue_manager ) {
		$this->sync_queue_manager = $sync_queue_manager;

		return $this;
	}

	/**
	 * @return WC_Payever_Synchronization_Queue
	 * @codeCoverageIgnore
	 */
	private function get_sync_queue_manager() {
		return null === $this->sync_queue_manager
			? $this->sync_queue_manager = new WC_Payever_Synchronization_Queue()
			: $this->sync_queue_manager;
	}
}
