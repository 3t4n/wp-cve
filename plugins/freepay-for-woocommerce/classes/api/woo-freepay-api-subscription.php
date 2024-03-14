<?php
/**
 * WC_FreePay_API_Subscription class
 */

class WC_FreePay_API_Subscription extends WC_FreePay_API_Transaction {
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
		$this->api_url .= 'recurring/';
	}

	/**
	 * recurring function.
	 *
	 * Sends a 'recurring' request to the FreePay API
	 *
	 * @access public
	 *
	 * @param string $subscription_id
	 * @param WC_Order $order
	 * @param int $amount
	 *
	 * @return $request
	 * @throws FreePay_API_Exception
	 */
	public function recurring( $subscription_id, $order, $amount = null ) {
		// Check if a custom amount has been set
		if ( $amount === null ) {
			// No custom amount set. Default to the order total
			$amount = WC_Subscriptions_Order::get_recurring_total( $order );
		}

		$request_url = sprintf( '%s/%s', $subscription_id, "authorize" );

		$response = $this->post( $request_url, [
			'Amount'            => WC_FreePay_Helper::price_multiply( $amount ),
			'OrderId'			=> $order->get_id(),
		], true );

		return $response;
	}


	/**
	 * cancel function.
	 *
	 * Sends a 'cancel' request to the FreePay API
	 *
	 * @access public
	 *
	 * @param string $subscription_id
	 * @param WC_Order $order
	 *
	 * @return void
	 * @throws FreePay_API_Exception
	 */
	public function cancel( $subscription_id, $order ) {
		$this->delete( sprintf( '%s', $subscription_id ) );
		$order->add_order_note( __( 'Subscription canceled - deleted at gateway', 'freepay-for-woocommerce' ) );
	}
}