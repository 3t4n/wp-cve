<?php

use Payever\Sdk\Payments\PaymentsApiClient;
use Psr\Log\LogLevel;

class WC_Payever_API_Wrapper {

	/**
	 * @return \Payever\Sdk\Payments\PaymentsApiClient
	 * @throws Exception
	 */
	public function get_payments_api_client() {
		return WC_Payever_Api::get_instance()->get_payments_api_client();
	}

	/**
	 * @return \Payever\Sdk\Core\Http\ResponseEntity
	 * @throws Exception
	 */
	public function get_response_entity() {
		$pluginsApiClient = WC_Payever_Api::get_instance()->get_plugins_api_client();
		$pluginsApiClient->setHttpClientRequestFailureLogLevelOnce( LogLevel::NOTICE );

		return $pluginsApiClient->getLatestPluginVersion()->getResponseEntity();
	}

	/**
	 * @param PaymentsApiClient $apiClient
	 * @param int               $paymentId
	 * @param mixed             $amount
	 * @param string            $identifier
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function refund_payment_request( $apiClient, $paymentId, $amount, $identifier = null ) {
		return $apiClient->refundPaymentRequest( $paymentId, $amount, $identifier );
	}

	/**
	 * @param PaymentsApiClient $apiClient
	 * @param int               $paymentId
	 * @param mixed             $items
	 * @param string            $identifier
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function refund_items_payment_request( $apiClient, $paymentId, $items, $identifier = null ) {
		return $apiClient->refundItemsPaymentRequest( $paymentId, $items, $identifier );
	}
}
