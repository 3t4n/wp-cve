<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles gateway responses.
 *
 * @package WC_PayL8r
 */
class WC_Gateway_PayL8r_Response {

	/**
	 * Reference to the gateway;
	 *
	 * @var WC_Gateway_PayL8r
	 */
	protected $gateway;

	/**
	 * Response data returned
	 *
	 * @var string
	 */
	protected $data;

	/**
	 * Constructor.
	 *
	 * @param WC_Gateway_PayL8r $gateway Instance of the gateway.
	 */
	public function __construct( $gateway ) {
		$this->gateway = $gateway;

		add_action( 'woocommerce_api_wc_gateway_payl8r', array( $this, 'handle_response' ) );
	}

	/**
	 * Processes a response.
	 *
	 * @return void
	 */
	public function handle_response() {

		if ( empty( $_POST['response'] ) ) {
			return;
		}

		$this->data = $this->decrypt_response( $_POST['response'] );

		if ( false === $this->data ) {
			return;
		}

		$order = $this->get_order( $this->data );

		if ( false === $order ) {
			return;
		}

		$this->process_order( $order );
	}

	/**
	 * Decrypts and decodes the response.
	 *
	 * @param string $response The encrypted response from the gateway.
	 */
	public function decrypt_response( $response ) {
		$response = base64_decode( $response );

		if ( false === $response ) {
			WC_Gateway_Paypal::log( 'Error: Could not decode response' );
			return false;
		}

		if ( false === openssl_public_decrypt( $response, $response, $this->gateway->public_key ) ) {
			WC_Gateway_Paypal::log( 'Error: Could not decrypt response' );
			return false;
		}

		$response = json_decode( $response );

		if ( null === $response ) {
			WC_Gateway_Paypal::log( 'Error: Invalid json' );
			return false;
		}

		if ( empty( $response->return_data ) ) {
			WC_Gateway_Paypal::log( 'Error: Invalid response, return_data key not found' );
			return false;
		}

		return $response->return_data;

	}

	/**
	 * Get the order that the response is in relation to.
	 *
	 * @param array $data The response data.
	 */
	public function get_order( $data ) {
		if ( empty( $data->order_id ) ) {
			WC_Gateway_Paypal::log( 'Error: Invalid response, order_id not found.' );
			return false;
		}

		$order = wc_get_order( $data->order_id );

		if ( ! $order ) {
			WC_Gateway_Paypal::log( 'Error: Order (#' . $order->get_order_number() . ') not found.' );
			return false;
		}

		return $order;
	}

	/**
	 * Process an order.
	 *
	 * Updates the order details with data sent back from the gateway
	 * and marks as complete or failed depenidng on the status.
	 *
	 * @param WC_Order $order The order to act upon.
	 */
	public function process_order( $order ) {
		WC_Gateway_Paypal::log( 'Processing order #' . $order->get_order_number() );

		if ( isset( $this->data->customer_data ) ) {
			$billing_address = array(
				'first_name' => $this->data->customer_data->firstnames,
				'last_name' => $this->data->customer_data->surname,
				'address_1' => $this->data->customer_data->address,
				'city' => $this->data->customer_data->city,
				'postcode' => $this->data->customer_data->postcode,
				'country' => 'UK',
				'email' => $this->data->customer_data->email,
				'phone' => $this->data->customer_data->phone,
			);
			$shipping_address = array(
				'first_name' => $this->data->customer_data->delivery_firstnames,
				'last_name' => $this->data->customer_data->delivery_surname,
				'address_1' => $this->data->customer_data->delivery_address,
				'city' => $this->data->customer_data->delivery_city,
				'postcode' => $this->data->customer_data->delivery_postcode,
				'country' => 'UK',
				'email' => $this->data->customer_data->email,
				'phone' => $this->data->customer_data->phone,
			);
			$order->set_address( $billing_address, 'billing' );
			$order->set_address( $shipping_address, 'shipping' );
		}

		if ( 'completed' !== $order->status ) {
			update_post_meta( $order_id, '_payl8r_status', $this->data->status );

			if ( method_exists( $this, 'order_' . strtolower( $this->data->status ) ) ) {
				call_user_func( array( $this, 'order_' . strtolower( $this->data->status ) ), $order );
			}
		}

	}

	/**
	 * Mark order as complete.
	 *
	 * @param WC_Order $order The order being updated.
	 */
	public function order_accepted( $order ) {
		WC_Gateway_Paypal::log( 'Order #' . $order->get_order_number() . ' accepted.' );

		$order->payment_complete();
		WC()->cart->empty_cart();
	}

	/**
	 * Mark order as failed when the order is abandoned.
	 *
	 * @param WC_Order $order The order being updated.
	 */
	public function order_abandoned( $order ) {

		if ( 'completed' !== $order->status && 'processing' !== $order->status && 'on Hold' !== $order->status) {

			WC_Gateway_Paypal::log( 'Order #' . $order->get_order_number() . ' abandoned.' );

			$order->update_status( 'failed', 'Customer abandoned this order.' );

		} else {

			WC_Gateway_Paypal::log( 'Order #' . $order->get_order_number() . ' abandoned.' . ' Abandonment message recieved from Payl8r but the order already had an order status of ' . $order->status . '. So status was not changed' );

		}
	}

	/**
	 * Mark order as failed when the order is declined.
	 *
	 * @param WC_Order $order The order being updated.
	 */
	public function order_declined( $order ) {
		
		if ( 'completed' !== $order->status && 'processing' !== $order->status && 'on Hold' !== $order->status) {

			WC_Gateway_Paypal::log( 'Order #' . $order->get_order_number() . ' declined. (' . $this->data->reason . ')' );

			$order->update_status( 'failed', 'PayL8r declined this order.' );

		} else {
			
			WC_Gateway_Paypal::log( 'Order #' . $order->get_order_number() . ' declined.' . ' Rejection  message recieved from Payl8r but the order already had an order status of ' . $order->status . '. So status was not changed' );

		}
	}
}
