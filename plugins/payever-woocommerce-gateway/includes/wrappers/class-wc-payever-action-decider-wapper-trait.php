<?php

if ( ! defined( 'ABSPATH' ) || trait_exists( 'WC_Payever_Action_Decider_Wrapper_Trait' ) ) {
	return;
}

trait WC_Payever_Action_Decider_Wrapper_Trait {

	/** @var WC_Payever_Action_Decider_Wrapper */
	private $action_decider_wrapper;

	/**
	 * @param WC_Payever_Action_Decider_Wrapper $action_decider_wrapper
	 *
	 * @return $this
	 * @codeCoverageIgnore
	 * @codeCoverageIgnorecatch
	 */
	public function set_action_decider_wrapper( WC_Payever_Action_Decider_Wrapper $action_decider_wrapper ) {
		$this->action_decider_wrapper = $action_decider_wrapper;

		return $this;
	}

	/**
	 * @return WC_Payever_Action_Decider_Wrapper
	 * @codeCoverageIgnore
	 */
	private function get_action_decider_wrapper() {
		return null === $this->action_decider_wrapper
			? $this->action_decider_wrapper = new WC_Payever_Action_Decider_Wrapper()
			: $this->action_decider_wrapper;
	}
}
