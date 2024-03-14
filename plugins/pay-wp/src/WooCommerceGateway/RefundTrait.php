<?php

namespace WPDesk\GatewayWPPay\WooCommerceGateway;

use WPDesk\GatewayWPPay\BlueMediaApi\Dto\TransactionRefund;
use WPDesk\GatewayWPPay\BlueMediaApi\Exception\ApiException;

trait RefundTrait {
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order  = \WC_Order_Factory::get_order( $order_id );
		$refund = new TransactionRefund(
			uniqid( 'wppaymsg-', true ), // 32 chars!
			$order->get_transaction_id(),
			$amount,
			$order->get_currency()
		);
		try {
			$refund_response = $this->client_factory->get_client( $order->get_currency() )->doRefund( $refund );

			return $refund_response->get_remote_out_ID() !== '';
		} catch ( ApiException $e ) {
			return $e->create_wp_error();
		}
	}
}