<?php

namespace WPDesk\GatewayWPPay\BlueMediaApi\Exception;

use WPDesk\GatewayWPPay\BlueMediaApi\Dto\TransactionError;

class TransactionErrorException extends ApiException {
	/** @var TransactionError */
	private $error;

	public function __construct( $message, TransactionError $error ) {
		$this->error = $error;
		parent::__construct( $message, 100 );
	}

	/**
	 * @return TransactionError
	 */
	public function get_transaction_error(): TransactionError {
		return $this->error;
	}

	public function create_wp_error(): \WP_Error {
		return new \WP_Error( $this->code, $this->message, $this->error->toArray() );
	}
}