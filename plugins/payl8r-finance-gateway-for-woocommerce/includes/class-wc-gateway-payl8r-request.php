<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define('API_VERSION', '1.1');

/**
 * WC_Gateway_PayL8r_request class.
 *
 * @package WC_PayL8r
 */
class WC_Gateway_PayL8r_Request {

	/**
	 * Pointer to gateway making the request.
	 *
	 * @var WC_Gateway_PayL8r
	 */
	protected $gateway;

	/**
	 * Endpoint for requests from PayL8r.
	 *
	 * @var string
	 */
	protected $notify_url;

	/**
	 * PayL8r request base url.
	 *
	 * @var string
	 */
	protected $base_url = 'https://payl8r.com/process';

	/**
	 * Constructor.
	 *
	 * @param WC_Gateway_PayL8r $gateway Instance of the gateway.
	 */
	public function __construct( $gateway ) {
		$this->gateway    = $gateway;
		$this->notify_url = WC()->api_request_url( 'WC_Gateway_PayL8r' );
	}

	/**
	 * Get the PayL8r request URL for an order.
	 *
	 * @param  WC_Order $order The order to be sent.
	 * @return string
	 */
	public function get_request_url( $order ) {
		$data = $this->get_data( $order );
        $items = $this->get_item_data( $order );

        if (!empty($items)){
            $query = http_build_query( array(
                'r' => $this->gateway->username,
                'data' => $this->encrypt_data( $data ),
                'item_data' => $this->encrypt_items($items),
            ), '', '&' );
        }else{
            $query = http_build_query( array(
                'r' => $this->gateway->username,
                'data' => $this->encrypt_data( $data ),
            ), '', '&' );
        }
		return $this->base_url . '?' . $query;
	}

	/**
	 * Get PayL8r arguments to pass through
	 *
	 * @param  WC_Order $order The order to be sent.
	 * @return array
	 */
	protected function get_data( $order ) {
		WC_Gateway_Paypal::log( 'Generating payment data for order ' . $order->get_order_number() . '. Notify URL: ' . $this->notify_url );

		$return =  array(
			'username' => $this->gateway->username,
			'request_data' => array(
				'return_urls' => array(
					'abort' => add_query_arg( 'payl8r_error', 'error', WC()->cart->get_checkout_url() ),
					'fail' => add_query_arg( 'payl8r_error', 'error', WC()->cart->get_checkout_url() ),
					'success' => $order->get_checkout_order_received_url(),
					'return_data' => $this->notify_url,
				),
				'request_type' => 'standard_finance_request',
				'api_version' => API_VERSION,
				'test_mode' => intval( $this->gateway->testmode ),
				'order_details' => array(
					'order_id' => strval( $order->get_id() ),
					'currency' => 'GBP',
                    'total' => floatval( $order->order_total ),
					'description' => 'ok'
				),
				'customer_details' => array(
					'student' => 0,
					'firstnames' => $order->get_billing_first_name(),
					'surname' => $order->get_billing_last_name(),
					'email' => $order->get_billing_email(),
					'phone' => $order->get_billing_phone(),
					'address' => $order->get_billing_address_1(),
					'city' => $order->get_billing_city(),
					'country' => 'UK',
					'postcode' => $order->get_billing_postcode(),
				),
			),
		);

		return $return;
	}

	/**
	 * Gets a description for the order based on the product and variant names.
	 *
	 * @param WC_Order $order The order to get the description for.
	 * @return string
	 */
	public function get_description( $order ) {

		$description = array();

		foreach ( $order->get_items() as $item ) {
			$product = $order->get_product_from_item( $item );

			if ( $product->is_type( 'variation' ) ) {
				$item_description = $product->get_formatted_variation_attributes( true );
			} else {
				$item_description = $product->get_title();
			}

			$item_description = $item['qty'] . 'x ' . $item_description;

			$description[] = $item_description;
		}

		$description = implode( '<br />', $description );

		if ( strlen( $description ) > 80 ) {
			$description = substr( $description, 0, 77 ) . '...';
		}

		return $description;
	}

	public function get_item_data( $order ) {

		$description = array();

		foreach ( $order->get_items() as $order_item ) {
			$item                = new stdClass();
			$item->quantity = $order_item->get_quantity();
            $item->price    = round($order_item->get_subtotal(), 2);

            $item->price += round($order_item->get_subtotal_tax(), 2);

			$product = $order->get_product_from_item( $order_item );

			if ( $product->is_type( 'variation' ) ) {
				$item_description = $product->get_formatted_variation_attributes( true );
			} else {
				$item_description = $product->get_title();
			}

			$item->description = $item_description;
			$items[] = $item;

		}

		if (WC()->cart->calculate_shipping()){
			//Add the Shipping Costs
			$item                = new stdClass();
            $item->price = round($order->calculate_shipping(), 2);

            $item->price += round(floatval(WC()->cart->get_shipping_tax()), 2);

			$item->description = "Shipping Costs";
			$items[] = $item;
		}

		//Check if cart has an applied discount
		if (WC()->cart->has_discount()) {
			$item                = new stdClass();
            $item->price = round($order->get_total_discount() * -1, 2);
            $item->price -= round($order->get_discount_tax(), 2);
			$item->description = "Discount";
			$items[] = $item;
		}

		return $items;
	}

	/**
	 * Encrypts and encodes the order data.
	 *
	 * @param array $data Order data to encrypt.
	 * @return string
	 */
	protected function encrypt_data( $data ) {
		$encrypted = '';
		$json = json_encode( $data );
		openssl_public_encrypt( $json, $encrypted, $this->gateway->public_key );

		return base64_encode( $encrypted );
	}

	protected function encrypt_items($items) {
		$items_encoded = [];
		$split_json_strings = str_split(json_encode($items, TRUE), 800);

		foreach($split_json_strings as $split_json_string) {
			openssl_public_encrypt( $split_json_string, $encrypted, $this->gateway->public_key );
			$items_encoded[] = base64_encode($encrypted);
		}
		return $items_encoded;
	}

}
