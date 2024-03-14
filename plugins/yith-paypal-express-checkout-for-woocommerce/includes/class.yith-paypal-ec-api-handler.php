<?php //phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH PayPal Express Checkout for WooCommerce
 * @since  1.0.0
 * @author YITH <plugins@yithemes.com>
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_PayPal_EC_API_Handler' ) ) {
	/**
	 * Class YITH_PayPal_EC_API_Handler
	 */
	class YITH_PayPal_EC_API_Handler {

		/**
		 * The production endpoint
		 *
		 * @var string
		 */
		protected static $production_endpoint = 'https://api-3t.paypal.com/nvp';

		/**
		 * The sandbox endpoint
		 *
		 * @var string
		 */
		protected static $sandbox_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';

		/**
		 *  NVP API version
		 *
		 * @var string
		 */
		protected $api_version = '204';

		/**
		 * Request http version 1.1 default.
		 *
		 * @var string
		 */
		protected $request_http_version = '1.1';

		/**
		 * Request user agent
		 *
		 * @var string
		 */
		protected $request_user_agent;

		/**
		 * YWSBS_PayPal_Express_Checkout_Request
		 *
		 * @var object
		 */
		protected $request;

		/**
		 * Request URI
		 *
		 * @var string
		 */
		protected $request_uri;

		/**
		 * Method used for the request
		 *
		 * @var string
		 */
		protected $request_method = 'POST';

		/**
		 * Request headers
		 *
		 * @var array
		 */
		protected $request_headers = array();

		/**
		 * Name of class for the response
		 *
		 * @var string
		 */
		protected $response_handler;

		/**
		 * Content of results
		 *
		 * @var array
		 */
		protected $response_result;

		/**
		 * API username
		 *
		 * @var string
		 */
		protected $api_username;

		/**
		 * API password
		 *
		 * @var string
		 */
		protected $api_password;

		/**
		 * API Signature
		 *
		 * @var string
		 */
		protected $api_signature;

		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @since  1.0.0
		 */
		public function __construct() {

			// set the REQUEST URI.
			$this->request_uri = ( 'sandbox' === yith_paypal_ec()->ec->env ) ? self::$sandbox_endpoint : self::$production_endpoint;

			// Set API credentials.
			$this->environment   = yith_paypal_ec()->ec->env;
			$this->api_username  = yith_paypal_ec()->ec->api_username;
			$this->api_password  = yith_paypal_ec()->ec->api_password;
			$this->api_signature = yith_paypal_ec()->ec->api_signature;
			$this->api_subject   = yith_paypal_ec()->ec->api_subject;

		}


		/**
		 * Get the PayPal request URL for an order.
		 *
		 * @param  WC_Order $order Order.
		 * @param  bool     $sandbox Sandbox.
		 *
		 * @return string
		 * @throws YITH_PayPal_EC_Exception Throws an Exception.
		 */
		public function get_request_url( $order, $sandbox = false ) {

			$order        = wc_get_order( $order );
			$request_args = array(
				'get_billing_agreement' => false !== $this->needs_billing_agreement( $order ),
				'no_shipping'           => ! ( WC()->cart ? WC()->cart->needs_shipping() : $order->needs_shipping_address() ),
				'cancel_url'            => esc_url_raw( $order->get_cancel_order_url_raw() ),
				'return_url'            => $this->get_callback(
					array(
						'yith_paypal_ec_back' => 1,
						'billing_agreement'   => $this->needs_billing_agreement( $order ),
					)
				),
				'order'                 => $order,
				'custom'                => wp_json_encode(
					array(
						'order_id'  => $order->get_id(),
						'order_key' => $order->get_order_key(),
						'url'       => get_home_url(),

					)
				),
				'from'                  => 'checkout',
			);

			$response = $this->call_set_express_checkout( $request_args );

			return yith_paypal_ec()->ec->get_paypal_redirect_url( $response->get_token(), 1 );

		}

		/**
		 * Return the request url from cart
		 *
		 * @param int  $order_id Order id.
		 * @param bool $sandbox Environment.
		 * @return string
		 */
		public function get_request_url_from_cart( $order_id, $sandbox = false ) {

			if ( isset( WC()->session->yith_paypal_session ) && WC()->session->yith_paypal_session['token'] ) {
				$order = wc_get_order( $order_id );
				$keys  = array(
					'yith_paypal_ec_back' => 1,
					'billing_agreement'   => $this->needs_billing_agreement( $order ),
					'token'               => WC()->session->yith_paypal_session['token'],
					'order_id'            => $order_id,
				);
				$url   = add_query_arg( $keys, WC()->api_request_url( 'yith_paypal_ec' ) );

				return $url;
			}
		}

		/**
		 * Set arguments of request from cart
		 *
		 * @return WP_Error|YITH_PayPal_EC_Response
		 * @throws YITH_PayPal_EC_Exception Throws an Exception.
		 */
		public function set_express_checkout_args_from_cart() {
			$request_args = array(
				'get_billing_agreement' => false !== $this->needs_billing_agreement(),
				'no_shipping'           => WC()->cart->needs_shipping() ? 0 : 1,
				'cancel_url'            => esc_url_raw( wc_get_cart_url() ),
				'return_url'            => $this->get_callback(
					array(
						'yith_paypal_do_express_checkout' => 1,
						'billing_agreement'               => $this->needs_billing_agreement(),
					)
				),
				'from'                  => 'cart',
			);

			return $this->call_set_express_checkout( $request_args );
		}


		/**
		 * Set express checkout from order
		 *
		 * @param WC_Order $order Order.
		 * @return WP_Error|YITH_PayPal_EC_Response
		 * @throws YITH_PayPal_EC_Exception Throws an Exception.
		 */
		public function set_express_checkout_args_from_order( $order ) {
			$request_args = array(
				'get_billing_agreement' => false !== $this->needs_billing_agreement( $order ),
				'no_shipping'           => true,
				'cancel_url'            => esc_url_raw( get_home_url() ),
				'order'                 => $order,
				'return_url'            => $this->get_callback(
					array(
						'yith_paypal_do_express_checkout' => 1,
						'billing_agreement'               => $this->needs_billing_agreement(),
					)
				),
				'from'                  => 'pay_order',
				'custom'                => wp_json_encode(
					array(
						'order_id'  => $order->get_id(),
						'order_key' => $order->get_order_key(),
						'url'       => get_home_url(),
					)
				),
			);

			return $this->call_set_express_checkout( $request_args );
		}

		/**
		 * Set Express Checkout
		 *
		 * @param array $args Arguments.
		 *
		 * @return WP_Error|YITH_PayPal_EC_Response
		 * @throws YITH_PayPal_EC_Exception Throws an Exception.
		 */
		public function call_set_express_checkout( $args ) {

			$request = $this->get_request();

			$request->set_express_checkout( $args );

			$this->response_handler = 'YITH_PayPal_EC_Response';

			return $this->prepare_request( $request );
		}

		/**
		 * Get transaction details by transaction id
		 *
		 * @param string $transaction_id Transaction id.
		 *
		 * @return WP_Error|YITH_PayPal_EC_Response
		 * @throws YITH_PayPal_EC_Exception Throws an Exception.
		 */
		public function call_get_transaction_details( $transaction_id ) {

			$request = $this->get_request();

			$request->get_transaction_details( $transaction_id );

			$this->response_handler = 'YITH_PayPal_EC_Response';

			return $this->prepare_request( $request );
		}


		/**
		 * Do Capture the Authorized payment
		 *
		 * @param array $args Arguments.
		 *
		 * @return WP_Error|YITH_PayPal_EC_Response|YITH_PayPal_EC_Response_Payment
		 * @throws YITH_PayPal_EC_Exception Throws an Exception.
		 */
		public function call_do_capture( $args ) {

			$request = $this->get_request();

			$request->do_capture( $args );

			$this->response_handler = 'YITH_PayPal_EC_Response';

			return $this->prepare_request( $request );
		}

		/**
		 * Do Void the Authorized payment
		 *
		 * @param string $transaction_id Transaction id.
		 * @return WP_Error|YITH_PayPal_EC_Response|YITH_PayPal_EC_Response_Payment
		 * @throws YITH_PayPal_EC_Exception Throws an Exception.
		 */
		public function call_do_void( $transaction_id ) {

			$request = $this->get_request();

			$request->do_void( $transaction_id );

			$this->response_handler = 'YITH_PayPal_EC_Response';

			return $this->prepare_request( $request );
		}


		/**
		 * Do Reference Transaction for recurring payments.
		 *
		 * @param WC_Order $order Order.
		 *
		 * @return WP_Error|YITH_PayPal_EC_Response|YITH_PayPal_EC_Response_Payment
		 * @throws YITH_PayPal_EC_Exception Throws an Exception.
		 */
		public function call_do_reference_transaction( $order ) {

			$billing_agreement_id = $order->get_meta( 'billing_agreement_id' );

			$args = array(
				'notify_url' => $this->get_callback( 'do_ref_trans' ),
				'custom'     => wp_json_encode(
					array(
						'order_id'  => $order->get_id(),
						'order_key' => $order->get_order_key(),
						'url'       => get_home_url(),
					)
				),
			);

			$request = $this->get_request();

			$request->do_reference_transaction( $billing_agreement_id[0], $order, $args );

			$this->response_handler = 'YITH_PayPal_EC_Response_Payment_Reference_Transaction';

			return $this->prepare_request( $request );
		}

		/**
		 * Call refund request.
		 *
		 * @param WC_Order $order Order.
		 * @param float    $amount Amount.
		 * @param string   $reason Refund reason.
		 *
		 * @return WP_Error|YITH_PayPal_EC_Response|YITH_PayPal_EC_Response_Payment
		 * @throws YITH_PayPal_EC_Exception Throws an Exception.
		 */
		public function call_refund_transaction( $order, $amount, $reason ) {

			$request = $this->get_request();

			$args = array(
				'TRANSACTIONID' => $order->get_transaction_id(),
				'NOTE'          => html_entity_decode( wc_trim_string( $reason, 255 ), ENT_NOQUOTES, 'UTF-8' ),
				'REFUNDTYPE'    => 'Full',
			);

			if ( ! is_null( $amount ) ) {
				$args['AMT']          = number_format( $amount, 2, '.', '' );
				$args['CURRENCYCODE'] = $order->get_currency();
				$args['REFUNDTYPE']   = 'Partial';
			}

			$request->refund_transaction( $args );

			$this->response_handler = 'YITH_PayPal_EC_Response';

			return $this->prepare_request( $request );
		}

		/**
		 * Get Express Checkout Details
		 *
		 * @param string $token Token from set_express_checkout response.
		 * @return object
		 * @throws YITH_PayPal_EC_Exception Throws an Exception.
		 * @since 1.0.0
		 */
		public function call_get_express_checkout_details( $token ) {

			$request = $this->get_request();

			$request->get_express_checkout_details( $token );

			$this->response_handler = 'YITH_PayPal_EC_Response';

			return $this->prepare_request( $request );
		}

		/**
		 * Get Express Checkout Details
		 *
		 * @param string $token Token from set_express_checkout response.
		 * @param int    $order_id Order id.
		 * @param array  $args Arguments.
		 *
		 * @return YITH_PayPal_EC_Response_Payment response object
		 * @throws YITH_PayPal_EC_Exception Throws an Exception.
		 * @since 1.0.0
		 */
		public function call_do_express_checkout_payment( $token, $order_id, $args ) {

			$request = $this->get_request();

			$request->do_express_checkout_payment( $token, $order_id, $args );

			$this->response_handler = 'YITH_PayPal_EC_Response_Payment';

			return $this->prepare_request( $request );
		}

		/**
		 * Get Express Checkout Details
		 *
		 * @param string $token Token from set_express_checkout response.
		 * @return YITH_PayPal_EC_Response_Payment response object
		 * @throws YITH_PayPal_EC_Exception Throws an Exception.
		 * @since 1.0.0
		 */
		public function call_create_billing_agreement( $token ) {

			$request = $this->get_request();

			$request->create_billing_agreement( $token );

			$this->response_handler = 'YITH_PayPal_EC_Response_Payment';

			return $this->prepare_request( $request );
		}


		/**
		 * Prepares the request and send the response to get_response
		 *
		 * @param mixed $request Request.
		 *
		 * @return YITH_PayPal_EC_Response|WP_Error|YITH_PayPal_EC_Response_Payment
		 * @throws YITH_PayPal_EC_Exception Throws an Exception.
		 * @since 1.0.0
		 */
		protected function prepare_request( $request ) {

			// ensure API is in its default state.
			$this->clear_response();
			$this->request = $request;
			yith_paypal_ec()->ec->log_add_message( print_r( $request, true ) );  // phpcs:ignore
			$response = wp_safe_remote_request( $this->get_request_uri(), $this->get_default_request_args() );

			try {
				$response = $this->get_response( $response );

			} catch ( Exception $exception ) {
				// trigger that a request is done.
				$this->trigger_request();
				throw new YITH_PayPal_EC_Exception( $this->response );
			}

			return $response;
		}

		/**
		 * Triggers that a request is done
		 *
		 * @since 1.0.0
		 */
		protected function trigger_request() {

			$request_fields = array(
				'method'     => $this->get_request_method(),
				'uri'        => $this->get_request_uri(),
				'user-agent' => $this->get_request_user_agent(),
				'headers'    => $this->get_request_headers( true ),
				'body'       => $this->request->get_body( true ),
			);

			$response_fields = array(
				'code'    => $this->response_result['code'],
				'message' => $this->response_result['message'],
				'headers' => $this->response_result['headers'],
				'body'    => $this->response_result['body'],
			);

			do_action( 'yith_paypal_ec_api_request_triggered', $request_fields, $response_fields, $this );
		}

		/**
		 * Return the response
		 *
		 * @param YITH_PayPal_EC_Response $response Response object.
		 *
		 * @return  YITH_PayPal_EC_Response
		 * @throws Exception Throws an Exception.
		 * @since 1.0.0
		 */
		protected function get_response( $response ) {

			/**
			 * WP_Error
			 *
			 * @var WP_Error $response
			 */
			if ( is_wp_error( $response ) ) {
				throw new Exception( $response->get_error_message(), $response->get_error_code() );
			}

			$this->response_result = array(
				'code'    => wp_remote_retrieve_response_code( $response ),
				'message' => wp_remote_retrieve_response_message( $response ),
				'body'    => wp_remote_retrieve_body( $response ),
				'headers' => wp_remote_retrieve_headers( $response ),
			);

			$handler_class = $this->get_response_handler();

			/**
			 * YITH_PayPal_EC_Response
			 *
			 * @var YITH_PayPal_EC_Response
			 */
			$this->response = new $handler_class( $this->response_result['body'] );

			if ( $this->response->has_error() ) {
				throw new Exception( $this->response->get_error_message(), $this->response->get_error_code() );
			}
			yith_paypal_ec()->ec->log_add_message( print_r( $this->response, true ) ); //phpcs:ignore
			return $this->response;
		}

		/**
		 * Gets the default request args
		 *
		 * @return array
		 * @since 1.0.0
		 */
		protected function get_default_request_args() {

			$args = array(
				'method'      => $this->get_request_method(),
				'timeout'     => MINUTE_IN_SECONDS,
				'redirection' => 0,
				'httpversion' => $this->get_request_http_version(),
				'sslverify'   => true,
				'blocking'    => true,
				'user-agent'  => $this->get_request_user_agent(),
				'headers'     => $this->get_request_headers(),
				'body'        => $this->request->get_body(),
				'cookies'     => array(),
			);

			return apply_filters( 'yith_paypal_ec_default_paypal_request_args', $args );
		}

		/**
		 * Clear the API response
		 *
		 * @since 1.2.0
		 */
		protected function clear_response() {
			$this->response_result = null;
			$this->response        = null;
		}


		/**
		 * Get a new request from the class YITH_PayPal_EC_Request
		 *
		 * @return YITH_PayPal_EC_Request
		 * @since 1.0.0
		 */
		public function get_request() {
			return new YITH_PayPal_EC_Request( $this->api_username, $this->api_password, $this->api_signature, $this->api_version, $this->api_subject );
		}

		/**
		 * Get the request method that for detaulf is POST
		 *
		 * @return mixed
		 * @since 1.0.0
		 */
		public function get_request_method() {
			return apply_filters( 'yith_paypal_ec_request_method', $this->request_method );
		}

		/**
		 * Get the request method that for detaulf is POST
		 *
		 * @return mixed
		 * @since 1.0.0
		 */
		public function get_request_http_version() {
			return apply_filters( 'yith_paypal_ec_request_http_version', $this->request_http_version );
		}

		/**
		 * Get user agent
		 *
		 * @return mixed
		 * @since 1.0.0
		 */
		public function get_request_user_agent() {
			return sprintf( '%s/%s', 'YITH_PayPal_EC', YITH_PAYPAL_EC_VERSION );
		}

		/**
		 * Get request headers
		 *
		 * @param boolean $sanitized Sanitized.
		 *
		 * @return mixed
		 * @since 1.2.0
		 */
		public function get_request_headers( $sanitized = false ) {
			$headers = $this->request_headers;

			if ( $sanitized && ! empty( $headers['Authorization'] ) ) {
				$headers['Authorization'] = str_repeat( '*', strlen( $headers['Authorization'] ) );
			}

			return apply_filters( 'yith_paypal_ec_request_headers', $headers, $sanitized );
		}

		/**
		 * Return if the order needs shipping.
		 *
		 * @param null|WC_Order $order Order.
		 *
		 * @return mixed|void
		 * @since 1.0.0
		 */
		public function needs_billing_agreement( $order = null ) {
			return apply_filters( 'yith_paypal_ec_needs_billing_agreements', false, $order );
		}

		/**
		 * Check if the cart need shipping.
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		protected function needs_shipping() {
			return WC()->cart->needs_shipping() ? 1 : 0;
		}

		/**
		 * Get the request uri
		 *
		 * @return string
		 * @since 1.2.0
		 */
		public function get_request_uri() {
			return apply_filters( 'yith_paypal_ec_request_uri', $this->request_uri );
		}

		/**
		 * Get the request method that for default is POST
		 *
		 * @return mixed
		 * @since 1.2.0
		 */
		public function get_response_handler() {
			return apply_filters( 'yith_paypal_ec_request_method', $this->response_handler );
		}

		/**
		 * Return callback
		 *
		 * @param string $key Key.
		 *
		 * @return string
		 * @since 1.2.0
		 */
		public function get_callback( $key ) {
			return add_query_arg( $key, WC()->api_request_url( 'yith_paypal_ec' ) );
		}
	}

}
