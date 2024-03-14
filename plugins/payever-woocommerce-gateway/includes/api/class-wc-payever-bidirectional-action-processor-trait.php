<?php

if ( ! defined( 'ABSPATH' ) || trait_exists( 'WC_Payever_Bidirectional_Action_Processor_Trait' ) ) {
	return;
}

use Payever\Sdk\ThirdParty\Action\BidirectionalActionProcessor;

trait WC_Payever_Bidirectional_Action_Processor_Trait {

	/** @var BidirectionalActionProcessor */
	private $action_processor;

	/**
	 * @param BidirectionalActionProcessor $action_processor
	 * @return $this
	 * @internal
	 */
	public function set_bidirectional_action_processor( BidirectionalActionProcessor $action_processor ) {
		$this->action_processor = $action_processor;

		return $this;
	}

	/**
	 * @return BidirectionalActionProcessor
	 * @throws Exception
	 * @codeCoverageIgnore
	 */
	private function get_bidirectional_action_processor() {
		return null === $this->action_processor
			? $this->action_processor = WC_Payever_Api::get_instance()->get_bidirectional_sync_action_processor()
			: $this->action_processor;
	}
}
