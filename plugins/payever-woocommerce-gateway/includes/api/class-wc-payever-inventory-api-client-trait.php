<?php

if ( ! defined( 'ABSPATH' ) || trait_exists( 'WC_Payever_Products_Api_Client_Trait' ) ) {
	return;
}

use Payever\Sdk\Inventory\InventoryApiClient;

trait WC_Payever_Inventory_Api_Client_Trait {

	/** @var InventoryApiClient */
	private $inventory_api_client;

	/**
	 * @param InventoryApiClient $inventory_api_client
	 * @return $this
	 * @internal
	 */
	public function set_inventory_api_client( InventoryApiClient $inventory_api_client ) {
		$this->inventory_api_client = $inventory_api_client;

		return $this;
	}

	/**
	 * @return InventoryApiClient
	 * @throws Exception
	 * @codeCoverageIgnore
	 */
	private function get_inventory_api_client() {
		return null === $this->inventory_api_client
			? $this->inventory_api_client = WC_Payever_Api::get_instance()->get_inventory_api_client()
			: $this->inventory_api_client;
	}
}
