<?php

if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Synchronization_Action_Handler_CreateProduct' ) ) {
	return;
}

use Payever\Sdk\ThirdParty\Enum\ActionEnum;

class WC_Payever_Synchronization_Action_Handler_CreateProduct extends WC_Payever_Synchronization_Action_Handler_UpdateProduct {

	/**
	 * @inheritDoc
	 */
	public function getSupportedAction() {
		return ActionEnum::ACTION_CREATE_PRODUCT;
	}
}
