<?php

namespace WPDesk\GatewayWPPay\BlueMediaApi\Exception;

use WPDesk\GatewayWPPay\BlueMediaApi\Dto\TransactionError;

class InvalidHashException extends ApiException {
	public function create_wp_error(): \WP_Error {
		return new \WP_Error( $this->code, __( 'Invalid hash', 'pay-wp' ) );
	}
}
