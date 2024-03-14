<?php
/**
 * WC_FreePay_API_Payment class
 */

class WC_FreePay_API_Payment extends WC_FreePay_API_Transaction {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct( $resource_data = null ) {
		// Run the parent construct
		parent::__construct();

		// Set the resource data to an object passed in on object instantiation.
		// Usually done when we want to perform actions on an object returned from
		// the API sent to the plugin callback handler.
		if ( is_object( $resource_data ) ) {
			$this->resource_data = $resource_data;
		}

		// Append the main API url
		$this->api_url .= 'authorization/';
	}

	/**
	 * capture function.
	 *
	 * Sends a 'capture' request to the FreePay API
	 *
	 * @access public
	 *
	 * @param int $transaction_id
	 * @param WC_Order $order
	 * @param int $amount
	 *
	 * @return object
	 * @throws FreePay_API_Exception
	 * @throws FreePay_Exception
	 * @throws FreePay_Capture_Exception
	 */
	public function capture( $transaction_id, $order, $amount = null ) {
		// Check if a custom amount ha been set
		if ( $amount === null ) {
			// No custom amount set. Default to the order total
			$amount = $order->get_total();
		}

		$result = $this->post( sprintf( '%s/%s', $transaction_id, "capture" ), [ 'amount' => WC_FreePay_Helper::price_multiply( $amount ) ] );

		if ( isset($result->IsSuccess) && !$result->IsSuccess ) {
			throw new FreePay_Capture_Exception( sprintf( 'Capturing payment on order #%s failed. GatewayMessage: %s. GatewayCode: %s.', $order->get_id(), $result->GatewayStatusMessage, $result->GatewayStatusCode ) );
		}

		return $this;
	}


	/**
	 * cancel function.
	 *
	 * Sends a 'cancel' request to the FreePay API
	 *
	 * @access public
	 *
	 * @param int $transaction_id
	 * @param WC_Order $order
	 *
	 * @return void
	 * @throws FreePay_API_Exception
	 */
	public function cancel( $transaction_id, $order ) {
		$this->delete( $transaction_id );
		$order->add_order_note( __( 'Order canceled - deleted at gateway', 'freepay-for-woocommerce' ) );
	}


	/**
	 * refund function.
	 *
	 * Sends a 'refund' request to the FreePay API
	 *
	 * @access public
	 *
	 * @param string $transaction_id
	 * @param WC_Order $order
	 * @param int $amount
	 * @param string $reason
	 *
	 * @return void
	 * @throws FreePay_API_Exception
	 * @throws FreePay_Exception
	 */
	public function refund( $transaction_id, $order, $amount = null, $reason = '' ) {
		// Check if a custom amount ha been set
		if ( $amount === null ) {
			// No custom amount set. Default to the order total
			$amount = $order->get_total();
		}

		$result = $this->post( sprintf( '%s/%s', $transaction_id, "credit" ), [
			'Amount'   => WC_FreePay_Helper::price_multiply( $amount ),
			'Comment'   => $reason,
		] );

		if ( isset($result->IsSuccess) && !$result->IsSuccess ) {
			throw new FreePay_API_Exception( sprintf( 'Refunding payment on order #%s failed. GatewayMessage: %s. GatewayCode: %s.', $order->get_id(), $result->GatewayStatusMessage, $result->GatewayStatusCode ) );
		}
	}

	public function exists() {
		return is_object( $this->resource_data );
	}

	public function getTransactionOrderId() {
		return $this->resource_data->OrderID;
	}

	public function can_i_refund() {
		if ( ! is_object( $this->resource_data ) ) {
			return false;
		}

		$captureDate = $this->resource_data->DateCaptured;
		return !empty($captureDate) && $captureDate != 'NULL';
	}

	public function can_i_cancel() {
		if ( ! is_object( $this->resource_data ) ) {
			return false;
		}

		$authDate = $this->resource_data->DateAuthorized;
		$captureDate = $this->resource_data->DateCaptured;

		return !empty( $authDate ) && $authDate != 'NULL' && (empty( $captureDate ) || $captureDate == 'NULL');
	}

	public function can_i_capture() {
		if ( ! is_object( $this->resource_data ) ) {
			return false;
		}

		$authDate = $this->resource_data->DateAuthorized;
		$captureDate = $this->resource_data->DateCaptured;
		$emptyCaptureDate = empty( $captureDate ) || $captureDate == 'NULL';
		$isAmountLeftToCapture = false;

		if($this->resource_data->TotalAmountCaptured > 0) {
			$isAmountLeftToCapture = $this->resource_data->TotalAmountCaptured < $this->resource_data->AuthorizationAmount;
		}

		return !empty( $authDate ) && $authDate != 'NULL' && ($isAmountLeftToCapture || $emptyCaptureDate);
	}
}