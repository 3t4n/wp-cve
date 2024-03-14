<?php

if ( ! defined( 'ABSPATH' ) || trait_exists( 'WC_Payever_Payments_Api_Client_Trait' ) ) {
	return;
}

use Payever\Sdk\Payments\PaymentsApiClient;

trait WC_Payever_Payments_Api_Client_Trait {

	/** @var PaymentsApiClient */
	private $payments_api_client;

	/**
	 * @param PaymentsApiClient $payments_api_client
	 * @return $this
	 * @internal
	 */
	public function set_payments_api_client( PaymentsApiClient $payments_api_client ) {
		$this->payments_api_client = $payments_api_client;

		return $this;
	}

	/**
	 * @return PaymentsApiClient
	 * @throws Exception
	 */
	private function get_payments_api_client() {
		return null === $this->payments_api_client
			? $this->payments_api_client = WC_Payever_Api::get_instance()->get_payments_api_client()
			: $this->payments_api_client;
	}
}
