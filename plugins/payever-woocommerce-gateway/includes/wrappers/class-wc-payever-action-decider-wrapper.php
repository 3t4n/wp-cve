<?php

use Payever\Sdk\Payments\Action\ActionDecider;
use Payever\Sdk\Payments\Base\PaymentsApiClientInterface;

class WC_Payever_Action_Decider_Wrapper {

	/**
	 * @param PaymentsApiClientInterface $api
	 *
	 * @return ActionDecider
	 */
	public function get_action_decider( PaymentsApiClientInterface $api ) {
		return new ActionDecider( $api );
	}
}
