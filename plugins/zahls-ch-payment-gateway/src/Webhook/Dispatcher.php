<?php

namespace ZahlsPaymentGateway\Webhook;

use Zahls\Models\Response\Transaction;
use ZahlsPaymentGateway\Service\OrderService;
use ZahlsPaymentGateway\Service\ZahlsApiService;
use ZahlsPaymentGateway\Util\StatusUtil;

class Dispatcher
{
	/**
	 * @var ZahlsApiService
	 */
	private $zahls_api_service;

	/**
	 * @var OrderService
	 */
	private $order_service;

	/**
	 * Settings prefix
	 *
	 * @var string
	 */
	private $prefix;

	public function __construct($zahls_api_service, $order_service, $prefix)
	{
		$this->zahls_api_service = $zahls_api_service;
		$this->order_service = $order_service;
		$this->prefix = $prefix;
	}

	public function check_webhook_response()
	{
		try {
			$resp = $_REQUEST;
			$order_id = $resp['transaction']['invoice']['referenceId'];
			$gateway_id = $resp['transaction']['invoice']['paymentRequestId'];
			
			
			if ( ! empty( $this->prefix ) && strpos( $order_id, $this->prefix ) === false ) {
				$this->send_response( 'Prefix mismatch' );
			}

			$arr = explode('_', $order_id);
			$order_id = end($arr);

		   if (!isset($resp['transaction']['status'])) {
               //throw new \Exception('Missing transaction status');
			   $this->send_response( 'Missing transaction status' );
			}else{

			/**
			 * @var \Zahls\Models\Response\Transaction
			 */
			$transaction = $this->zahls_api_service->getZahlsTransaction($resp['transaction']['id']);
			
			if(!empty($transaction)){
			

			if ($transaction->getStatus() !== $resp['transaction']['status']) {
                throw new \Exception('Fraudulent transaction status');
			}

			// Check if subscription to handle accordingly
			$subscriptions = [];
			$pre_auth_id = '';
			if (!empty($resp['transaction']['preAuthorizationId'])) {
				$subscriptions = wcs_get_subscriptions_for_order($order_id, array( 'order_type' => 'any' ));

				// $order_id is the subscription id in case of payment method change. In this case $subscriptions will be empty
				if (!$subscriptions) {
					$subscriptions[] = new \WC_Subscription($order_id);
				}

				// Identify the correct order_id
				// Automatic subscription payment > order_id is already valid and matches the referenceId. It must not be overwritten
				// Payment method change > $order_id is a subscriptionId and must be overwritten
				// Subscription renewal > $order_id is from an old order and must be overwritten
				$firstSubscription = reset($subscriptions);
				$order_id = $firstSubscription->get_last_order();
				$pre_auth_id = $resp['transaction']['preAuthorizationId'];
			}

			$order = new \WC_Order($order_id);

			if (!$order_id || !$order) {
                throw new \Exception('Fraudulent request');
			}

            $newTransactionStatus = $transaction->getStatus();
            // A confirmed transaction can also be a partial payment (with bank transfer). Therefore the new correct status must be determined by
            if (in_array($newTransactionStatus, [Transaction::CONFIRMED, Transaction::REFUNDED, Transaction::PARTIALLY_REFUNDED])) {
                $gateway = $this->zahls_api_service->getZahlsGateway($gateway_id);
				$confirmedAmount = StatusUtil::getAmountByStatusAndGateway($gateway, [Transaction::CONFIRMED]);
                $refundedAmount = StatusUtil::getAmountByStatusAndGateway($gateway, [Transaction::PARTIALLY_REFUNDED, Transaction::REFUNDED]);
                $newTransactionStatus = StatusUtil::determineNewOrderStatus($order->get_total('edit'), $confirmedAmount, $refundedAmount);

            }

			$this->order_service->handleTransactionStatus($order, $subscriptions, $transaction, $newTransactionStatus, $pre_auth_id);
			$this->send_response( 'Success: Processed webhook response' );
			
			}else{
				//transaction missing
				$this->send_response( 'Error: No such transaction found in WooCommerce' );
			}
				
			}

		} catch (\Exception $e) {
            throw new \Exception('Error: ' . $e->getMessage());
		//	echo $e->getMessage();
		}
	}

	/**
	 * Returns webhook response.
	 *
	 * @param string     $message       success or error message.
	 * @param array      $data          response data.
	 * @param string|int $response_code response code.
	 */
	private function send_response( $message, $data = [], $response_code = 200 ) {
		$response['message'] = $message;
		if ( ! empty( $data ) ) {
			$response['data'] = $data;
		}
		echo wp_json_encode( $response );
		http_response_code( $response_code );
		die;
	}
}