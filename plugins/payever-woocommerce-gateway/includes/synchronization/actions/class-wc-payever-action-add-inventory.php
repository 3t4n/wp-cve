<?php

if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Synchronization_Action_Handler_AddInventory' ) ) {
	return;
}

use Payever\Sdk\Inventory\Http\MessageEntity\InventoryChangedEntity;
use Payever\Sdk\ThirdParty\Action\ActionPayload;
use Payever\Sdk\ThirdParty\Action\ActionResult;
use Payever\Sdk\ThirdParty\Enum\ActionEnum;

class WC_Payever_Synchronization_Action_Handler_AddInventory extends WC_Payever_Synchronization_Action_Handler_InventoryBase {

	/**
	 * @inheritdoc
	 */
	public function getSupportedAction() {
		return ActionEnum::ACTION_ADD_INVENTORY;
	}

	/**
	 * @inheritdoc
	 *
	 * @throws Exception
	 */
	public function handle( ActionPayload $action_payload, ActionResult $action_result ) {
		/** @var InventoryChangedEntity $inventoryChangedEntity */
		$inventoryChangedEntity = $action_payload->getPayloadEntity();

		$this->change_stock(
			$inventoryChangedEntity->getSku(),
			$inventoryChangedEntity->getStock(),
			abs( $inventoryChangedEntity->getQuantity() )
		);

		$action_result->incrementUpdated();
	}
}
