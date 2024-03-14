<?php

/**
 * This file is responsible for handling all the payments related stuff of the plugin.
 *
 * Defines PayPal payments methods and flow. Also updates
 * property payments information against the transaction.
 *
 * @since 2.0.0
 */
class Realhomes_Paypal_Payments_Handler {

	/**
	 * PayPal checkout API URL
	 *
	 * @var string
	 */
	private $checkout_url;

	/**
	 * PayPal token generating API URL
	 *
	 * @var string
	 */
	private $token_url;

	/**
	 * PayPal Client ID
	 *
	 * @var string
	 */
	private $clientId;

	/**
	 * PayPal Client Secrete ID
	 *
	 * @var string
	 */
	private $clientSecret;

	/**
	 * Publish property after payment or not
	 *
	 * @var bool|string
	 */
	private $publish_property;

	/**
	 * Price per property that needs to be paid via PayPal
	 *
	 * @var string
	 */
	private $price_per_property;

	/**
	 * Currency code in which payment needs to be charged
	 *
	 * @var string
	 */
	private $currency_code;

	/**
	 * Redirect URL after payment is successful, expected My Properties' page URL
	 *
	 * @var string
	 */
	private $redirect_url;

	public function __construct() {

		// Setting PayPal API Keys and Settings Data.
		$rpp_settings             = get_option( 'rpp_settings' );
		$this->clientId           = isset( $rpp_settings['client_id'] ) ?? '';
		$this->clientSecret       = isset( $rpp_settings['secret_id'] ) ?? '';
		$this->price_per_property = isset( $rpp_settings['payment_amount'] ) ?? '';
		$this->currency_code      = isset( $rpp_settings['currency_code'] ) ?? '';
		$this->redirect_url       = isset( $rpp_settings['redirect_page_url'] ) ?? '';
		$this->publish_property   = isset( $rpp_settings['publish_property'] ) ?? '';

		// Setting PayPal API URLs
		$base               = isset( $rpp_settings['enable_sandbox'] ) ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';
		$this->checkout_url = $base . '/v2/checkout/orders/';
		$this->token_url    = $base . '/v1/oauth2/token/';

		add_action( 'wp_ajax_realhomes_create_paypal_order', array( $this, 'create_paypal_order' ) );
		add_action( 'wp_ajax_realhomes_complete_order', array( $this, 'complete_order' ) );
	}

	/**
	 * Create PayPal orders with property information to capture the payment.
	 */
	public function create_paypal_order() {

		if ( empty( $_POST['property_id'] ) ) {
			die( esc_html__( 'Error: Missing property ID information.', 'realhomes-paypal-payments' ) );
		}

		// Replace with your access token, amount, currency, description, and metadata
		$accessToken      = $this->get_paypal_access_token();
		$property_id      = intval( $_POST['property_id'] );
		$property_heading = esc_html( get_the_title( $property_id ) );

		// PayPal API endpoint for creating an order
		$order_url = $this->checkout_url; // Use sandbox URL for testing

		// Set up the request data
		$order_data = array(
			'intent'         => 'CAPTURE',
			'purchase_units' => array(
				array(
					'amount'       => array(
						'currency_code' => $this->currency_code,
						'value'         => $this->price_per_property,
					),
					'description'  => $property_heading, // Add a description here
					'custom_id'    => $property_id, // Add custom metadata as needed
					"reference_id" => $this->generate_uuid(),
				),
			),

		);

		// Set up the request arguments
		$request_args = array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $accessToken,
				'Content-Type'  => 'application/json',
			),
			'body'    => json_encode( $order_data ),
		);

		// Make the API request using the WordPress HTTP API
		$response = wp_safe_remote_post( $order_url, $request_args );

		// Check for errors in the API request
		if ( is_wp_error( $response ) ) {
			die( esc_html__( 'Error: ', 'realhomes-paypal-payments' ) . $response->get_error_message() );
		}

		// Decode the JSON response
		$json_response = wp_remote_retrieve_body( $response );
		$data          = json_decode( $json_response, true );

		// Check if the order creation was successful
		if ( isset( $data['id'] ) ) {
			die( $data['id'] ); // Return the order ID
		} else {
			// Handle the case where order creation failed
			die( esc_html__( 'Error: Unable to create PayPal order for the property.', 'realhomes-paypal-payments' ) );
		}
	}

	/**
	 * Generate PayPal access token to perform API actions.
	 *
	 * @return mixed|void
	 */
	private function get_paypal_access_token() {

		// PayPal API endpoint for obtaining access token
		$token_url = $this->token_url;

		// Set up the request data
		$post_data = array(
			'grant_type' => 'client_credentials',
		);

		// Set up the request arguments
		$request_args = array(
			'body'    => http_build_query( $post_data ),
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( $this->clientId . ':' . $this->clientSecret ),
				'Content-Type'  => 'application/x-www-form-urlencoded',
			),
		);

		// Make the API request using the WordPress HTTP API
		$response = wp_safe_remote_post( $token_url, $request_args );

		// Check for errors in the API request
		if ( is_wp_error( $response ) ) {
			die( esc_html__( 'Error: ', 'realhomes-paypal-payments' ) . $response->get_error_message() );
		}

		// Decode the JSON response
		$json_response = wp_remote_retrieve_body( $response );
		$data          = json_decode( $json_response, true );

		// Check if the access token is present in the response
		if ( isset( $data['access_token'] ) ) {
			return $data['access_token'];
		} else {
			// Handle the case where access token retrieval failed
			die( esc_html__( 'Error: Unable to retrieve access token', 'realhomes-paypal-payments' ) );
		}
	}

	/**
	 * Generate unique UUID for the PayPal API request as reference.
	 *
	 * @return string
	 */
	private function generate_uuid() {
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

			// 16 bits for "time_mid"
			mt_rand( 0, 0xffff ),

			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand( 0, 0x0fff ) | 0x4000,

			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand( 0, 0x3fff ) | 0x8000,

			// 48 bits for "node"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}

	/**
	 * Capture the payment and proceed with the property data update after success.
	 *
	 * @return void
	 */
	public function complete_order() {
		$orderID     = $_POST['order_id'];
		$accessToken = $this->get_paypal_access_token();

		// PayPal API endpoint for capturing a payment
		$capture_url = $this->checkout_url . $orderID . '/capture';

		// Set up the request arguments
		$request_args = array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $accessToken,
				'Content-Type'  => 'application/json',
			),
		);

		// Make the API request using the WordPress HTTP API
		$response = wp_safe_remote_post( $capture_url, $request_args );

		// Check for errors in the API request
		if ( is_wp_error( $response ) ) {
			die( esc_html__( 'Error: ', 'realhomes-paypal-payments' ) . $response->get_error_message() );
		}

		// Decode the JSON response
		$json_response = wp_remote_retrieve_body( $response );
		$payment_data  = json_decode( $json_response, true );

		// Check if the payment capture was successful
		if ( isset( $payment_data['status'] ) && $payment_data['status'] === 'COMPLETED' ) {
			$this->update_property_payment_information( $payment_data ); // Update property payment information
			die( json_encode( array( 'redirect_url' => $this->redirect_url ) ) ); // Successful payment capture data returned
		} else {
			// Handle the case where payment capture failed
			die( esc_html__( 'Error: Unable to capture payment for the property', 'realhomes-paypal-payments' ) );
		}
	}

	/**
	 * Update property payment information based on given payment transaction data.
	 *
	 * @param $payment_data
	 *
	 * @return void
	 */
	public function update_property_payment_information( $payment_data ) {

		// Get two major nodes for property payments details from payment data
		$payer_detail   = $payment_data['payer'];
		$payment_detail = $payment_data['purchase_units'][0]['payments']['captures'][0];

		// Prepare the property payment details from payment data
		$property_id            = $payment_detail['custom_id'];
		$payment_id             = $payment_detail['id'];
		$payment_status         = ucfirst( strtolower( $payment_detail['status'] ) );
		$payment_amount         = $payment_detail['amount']['value'];
		$payment_currency       = $payment_detail['amount']['currency_code'];
		$payer_email            = $payer_detail['email_address'];
		$payer_first_name       = ucfirst( $payer_detail['name']['given_name'] );
		$payer_last_name        = ucfirst( $payer_detail['name']['surname'] );
		$payment_timestamp      = $payment_detail['update_time'];
		$payment_time           = new DateTime( $payment_timestamp );
		$payment_time_formatted = $payment_time->format( 'F j, Y, g:i a' );

		// Update property payments details to its meta-data
		update_post_meta( $property_id, 'txn_id', $payment_id );
		update_post_meta( $property_id, 'payment_status', $payment_status );
		update_post_meta( $property_id, 'payment_gross', $payment_amount );
		update_post_meta( $property_id, 'payment_date', $payment_time_formatted );
		update_post_meta( $property_id, 'mc_currency', $payment_currency );
		update_post_meta( $property_id, 'payer_email', $payer_email );
		update_post_meta( $property_id, 'first_name', $payer_first_name );
		update_post_meta( $property_id, 'last_name', $payer_last_name );

		if ( $this->publish_property ) { // Publish the property if it's enabled from settings
			// Set publish property args
			$property_args = array(
				'ID'          => $property_id,
				'post_status' => 'publish',
			);

			// Update the property into the database
			wp_update_post( $property_args );
		}
	}
}

$paypal_payments_handler = new Realhomes_Paypal_Payments_Handler();