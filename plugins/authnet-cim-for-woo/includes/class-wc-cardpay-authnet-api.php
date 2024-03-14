<?php
/**
 * Class WC_Cardpay_Authnet_API file.
 *
 * @package Authorize.Net CIM for WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WC_Cardpay_Authnet_API
 */
class WC_Cardpay_Authnet_API {

	/**
	 * Stores the gateway url.
	 *
	 * @var string
	 */
	private $url;

	/**
	 * Stores the api login.
	 *
	 * @var string
	 */
	private $api_login;

	/**
	 * Stores the transaction key.
	 *
	 * @var string
	 */
	private $transaction_key;

	/**
	 * Determines if the WC version is less than 3.0.0.
	 *
	 * @var bool
	 */
	public $wc_pre_30;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->wc_pre_30 = version_compare( WC_VERSION, '3.0.0', '<' );
	}

	/**
	 * Authorize function
	 *
	 * @param WC_Cardpay_Authnet_Gateway $gateway Gateway object.
	 * @param WC_Order                   $order Order object.
	 * @param float                      $amount Order amount.
	 * @param array                      $card Card array.
	 *
	 * @return mixed
	 */
	public function authorize( $gateway, $order, $amount, $card ) {
		$payload  = $this->get_payload( $gateway, $order, $amount, 'authOnlyTransaction', $card );
		$response = $this->post_transaction( $payload );
		return $response;
	}

	/**
	 * Purchase function
	 *
	 * @param WC_Cardpay_Authnet_Gateway $gateway Gateway object.
	 * @param WC_Order                   $order Order object.
	 * @param float                      $amount Order amount.
	 * @param array                      $card Card array.
	 *
	 * @return mixed
	 */
	public function purchase( $gateway, $order, $amount, $card ) {
		$payload  = $this->get_payload( $gateway, $order, $amount, 'authCaptureTransaction', $card );
		$response = $this->post_transaction( $payload );
		return $response;
	}

	/**
	 * Capture function
	 *
	 * @param WC_Cardpay_Authnet_Gateway $gateway Gateway object.
	 * @param WC_Order                   $order Order object.
	 * @param float                      $amount Order amount.
	 *
	 * @return mixed
	 */
	public function capture( $gateway, $order, $amount ) {
		$payload  = $this->get_payload( $gateway, $order, $amount, 'priorAuthCaptureTransaction' );
		$response = $this->post_transaction( $payload );
		return $response;
	}

	/**
	 * Refund function
	 *
	 * @param WC_Cardpay_Authnet_Gateway $gateway Gateway object.
	 * @param WC_Order                   $order Order object.
	 * @param float                      $amount Order amount.
	 *
	 * @return mixed
	 */
	public function refund( $gateway, $order, $amount ) {
		$payload  = $this->get_payload( $gateway, $order, $amount, 'refundTransaction' );
		$response = $this->post_transaction( $payload );
		return $response;
	}

	/**
	 * Void function
	 *
	 * @param WC_Cardpay_Authnet_Gateway $gateway Gateway object.
	 * @param WC_Order                   $order Order object.
	 * @param float                      $amount Order amount.
	 *
	 * @return mixed
	 */
	public function void( $gateway, $order, $amount ) {
		$payload  = $this->get_payload( $gateway, $order, $amount, 'voidTransaction' );
		$response = $this->post_transaction( $payload );
		return $response;
	}

	/**
	 * Verify function
	 *
	 * @param WC_Cardpay_Authnet_Gateway $gateway Gateway object.
	 *
	 * @return mixed
	 */
	public function create_profile( $gateway ) {
		$payload  = $this->get_token_payload( $gateway );
		$response = $this->post_transaction( $payload );
		return $response;
	}

	/**
	 * Get_payload function
	 *
	 * @param WC_Cardpay_Authnet_Gateway $gateway Gateway object.
	 * @param WC_Order                   $order Order object.
	 * @param float                      $amount Order amount.
	 * @param string                     $transaction_type Transaction type.
	 * @param array                      $card Card array.
	 *
	 * @return string
	 */
	public function get_payload( $gateway, $order, $amount, $transaction_type, $card = '' ) {
		$order_number       = $this->wc_pre_30 ? $order->id : $order->get_id();
		$billing_first_name = $this->wc_pre_30 ? $order->billing_first_name : $order->get_billing_first_name();
		$billing_last_name  = $this->wc_pre_30 ? $order->billing_last_name : $order->get_billing_last_name();
		$billing_address    = $this->wc_pre_30 ? $order->billing_address_1 : $order->get_billing_address_1();
		$billing_city       = $this->wc_pre_30 ? $order->billing_city : $order->get_billing_city();
		$billing_state      = $this->wc_pre_30 ? $order->billing_state : $order->get_billing_state();
		$billing_postcode   = $this->wc_pre_30 ? $order->billing_postcode : $order->get_billing_postcode();
		$billing_country    = $this->wc_pre_30 ? $order->billing_country : $order->get_billing_country();
		$billing_email      = $this->wc_pre_30 ? $order->billing_email : $order->get_billing_email();
		$tax_amount         = $this->wc_pre_30 ? $order->order_tax : $order->get_total_tax();
		$ip_address         = $this->wc_pre_30 ? $order->customer_ip_address : $order->get_customer_ip_address();
		$cardholder_name    = $billing_first_name . ' ' . $billing_last_name;

		if ( 'yes' === $gateway->sandbox ) {
			$this->url             = 'https://apitest.authorize.net/xml/v1/request.api';
			$this->api_login       = '57Uqk3stH8';
			$this->transaction_key = '8yy229eR9S643mSz';
		} else {
			$this->url             = 'https://api.authorize.net/xml/v1/request.api';
			$this->api_login       = $gateway->api_login;
			$this->transaction_key = $gateway->transaction_key;
		}

		if ( 'authOnlyTransaction' === $transaction_type || 'authCaptureTransaction' === $transaction_type ) {
			if ( ! empty( $card ) ) {
				$token_array = explode( '|', $card->get_token() );
				$customer_id = $token_array[0];
				$payment_id  = $token_array[1];
				$data        = array(
					'createTransactionRequest' => array(
						'merchantAuthentication' => array(
							'name'           => $this->api_login,
							'transactionKey' => $this->transaction_key,
						),
						'refId'                  => wc_clean( $order_number ),
						'transactionRequest'     => array(
							'transactionType' => wc_clean( $transaction_type ),
							'amount'          => wc_clean( $amount ),
							'profile'         => array(
								'customerProfileId' => $customer_id,
								'paymentProfile'    => array(
									'paymentProfileId' => $payment_id,
								),
							),
							'order'           => array(
								'invoiceNumber' => wc_clean( $order_number ),
							),
							'tax'             => array(
								'amount' => number_format( $tax_amount, '2', '.', '' ),
								'name'   => 'Sales Tax',
							),
							'customerIP'      => wc_clean( $ip_address ),
						),
					),
				);
			} else {
				$card_raw       = isset( $_POST['authnet-card-number'] ) ? sanitize_text_field( wp_unslash( $_POST['authnet-card-number'] ) ) : '';
				$card_number    = str_replace( ' ', '', $card_raw );
				$exp_raw        = isset( $_POST['authnet-card-expiry'] ) ? sanitize_text_field( wp_unslash( $_POST['authnet-card-expiry'] ) ) : '';
				$exp_date_array = explode( '/', $exp_raw );
				$exp_month      = trim( $exp_date_array[0] );
				$exp_year       = trim( $exp_date_array[1] );
				$exp_date       = $exp_month . substr( $exp_year, -2 );
				$cvc            = isset( $_POST['authnet-card-cvc'] ) ? sanitize_text_field( wp_unslash( $_POST['authnet-card-cvc'] ) ) : '';
				$data           = array(
					'createTransactionRequest' => array(
						'merchantAuthentication' => array(
							'name'           => wc_clean( $this->api_login ),
							'transactionKey' => wc_clean( $this->transaction_key ),
						),
						'refId'                  => wc_clean( $order_number ),
						'transactionRequest'     => array(
							'transactionType' => wc_clean( $transaction_type ),
							'amount'          => wc_clean( $amount ),
							'payment'         => array(
								'creditCard' => array(
									'cardNumber'     => wc_clean( $card_number ),
									'expirationDate' => wc_clean( $exp_date ),
									'cardCode'       => wc_clean( $cvc ),
								),
							),
							'profile'         => array(
								'createProfile' => true,
							),
							'order'           => array(
								'invoiceNumber' => wc_clean( $order_number ),
							),
							'tax'             => array(
								'amount' => number_format( $tax_amount, '2', '.', '' ),
								'name'   => 'Sales Tax',
							),
							'customer'        => array(
								'type'  => 'individual',
								'id'    => uniqid(),
								'email' => wc_clean( $billing_email ),
							),
							'billTo'          => array(
								'firstName' => wc_clean( $billing_first_name ),
								'lastName'  => wc_clean( $billing_last_name ),
								'address'   => wc_clean( substr( $billing_address, 0, 30 ) ),
								'city'      => wc_clean( substr( $billing_city, 0, 40 ) ),
								'state'     => wc_clean( substr( $billing_state, 0, 40 ) ),
								'zip'       => wc_clean( substr( $billing_postcode, 0, 10 ) ),
								'country'   => wc_clean( substr( $billing_country, 0, 60 ) ),
							),
							'customerIP'      => wc_clean( $ip_address ),
						),
					),
				);
			}
		} else {
			$tran_meta = $order->get_meta( '_authnet_transaction', true );
			if ( 'refundTransaction' === $transaction_type ) {
				$data = array(
					'createTransactionRequest' => array(
						'merchantAuthentication' => array(
							'name'           => wc_clean( $this->api_login ),
							'transactionKey' => wc_clean( $this->transaction_key ),
						),
						'refId'                  => wc_clean( $order_number ),
						'transactionRequest'     => array(
							'transactionType' => wc_clean( $transaction_type ),
							'amount'          => wc_clean( $amount ),
							'payment'         => array(
								'creditCard' => array(
									'cardNumber'     => wc_clean( $tran_meta['cc_last4'] ),
									'expirationDate' => wc_clean( $tran_meta['cc_expiry'] ),
								),
							),
							'refTransId'      => wc_clean( $tran_meta['transaction_id'] ),
							'order'           => array(
								'invoiceNumber' => wc_clean( $order_number ),
							),
						),
					),
				);
			} else {
				$data = array(
					'createTransactionRequest' => array(
						'merchantAuthentication' => array(
							'name'           => wc_clean( $this->api_login ),
							'transactionKey' => wc_clean( $this->transaction_key ),
						),
						'refId'                  => wc_clean( $order_number ),
						'transactionRequest'     => array(
							'transactionType' => wc_clean( $transaction_type ),
							'amount'          => wc_clean( $amount ),
							'refTransId'      => wc_clean( $tran_meta['transaction_id'] ),
							'order'           => array(
								'invoiceNumber' => wc_clean( $order_number ),
							),
						),
					),
				);
			}
		}
		return wp_json_encode( $data );
	}

	/**
	 * Get_token_payload function
	 *
	 * @param WC_Cardpay_Authnet_Gateway $gateway Gateway object.
	 *
	 * @return string
	 */
	public function get_token_payload( $gateway ) {
		if ( 'yes' === $gateway->sandbox ) {
			$this->url             = 'https://apitest.authorize.net/xml/v1/request.api';
			$this->api_login       = '57Uqk3stH8';
			$this->transaction_key = '8yy229eR9S643mSz';
		} else {
			$this->url             = 'https://api.authorize.net/xml/v1/request.api';
			$this->api_login       = $gateway->api_login;
			$this->transaction_key = $gateway->transaction_key;
		}
		$customer_id    = get_current_user_id();
		$card_raw       = isset( $_POST['authnet-card-number'] ) ? sanitize_text_field( wp_unslash( $_POST['authnet-card-number'] ) ) : '';
		$card_number    = str_replace( ' ', '', $card_raw );
		$exp_raw        = isset( $_POST['authnet-card-expiry'] ) ? sanitize_text_field( wp_unslash( $_POST['authnet-card-expiry'] ) ) : '';
		$exp_date_array = explode( '/', $exp_raw );
		$exp_month      = trim( $exp_date_array[0] );
		$exp_year       = trim( $exp_date_array[1] );
		$exp_date       = $exp_month . substr( $exp_year, -2 );
		$cvc            = isset( $_POST['authnet-card-cvc'] ) ? sanitize_text_field( wp_unslash( $_POST['authnet-card-cvc'] ) ) : '';
		$data           = array(
			'createCustomerProfileRequest' => array(
				'merchantAuthentication' => array(
					'name'           => wc_clean( $this->api_login ),
					'transactionKey' => wc_clean( $this->transaction_key ),
				),
				'profile'                => array(
					'merchantCustomerId' => uniqid(),
					'email'              => wc_clean( get_user_meta( $customer_id, 'billing_email', true ) ),
					'paymentProfiles'    => array(
						'customerType' => 'individual',
						'billTo'       => array(
							'firstName' => wc_clean( get_user_meta( $customer_id, 'billing_first_name', true ) ),
							'lastName'  => wc_clean( get_user_meta( $customer_id, 'billing_last_name', true ) ),
							'address'   => wc_clean( get_user_meta( $customer_id, 'billing_address_1', true ) ),
							'city'      => wc_clean( get_user_meta( $customer_id, 'billing_city', true ) ),
							'state'     => wc_clean( get_user_meta( $customer_id, 'billing_state', true ) ),
							'zip'       => wc_clean( get_user_meta( $customer_id, 'billing_postcode', true ) ),
							'country'   => wc_clean( get_user_meta( $customer_id, 'billing_country', true ) ),
						),
						'payment'      => array(
							'creditCard' => array(
								'cardNumber'     => wc_clean( $card_number ),
								'expirationDate' => wc_clean( $exp_date ),
								'cardCode'       => wc_clean( $cvc ),
							),
						),
					),
				),
				'validationMode' => $gateway->validation_mode,
			),
		);
		return wp_json_encode( $data );
	}

	/**
	 * Post_transaction function
	 *
	 * @param string $payload Payload json.
	 *
	 * @return string|WP_Error
	 */
	public function post_transaction( $payload ) {
		$args     = array(
			'headers' => array(
				'Content-Type' => 'application/json',
			),
			'body'    => $payload,
			'method'  => 'POST',
			'timeout' => 70,
		);
		$response = wp_remote_post( $this->url, $args );

		if ( is_wp_error( $response ) || empty( $response['body'] ) ) {
			return new WP_Error( 'authnet_error', __( 'There was a problem connecting to the payment gateway.', 'woocommerce-cardpay-authnet' ) );
		}

		$parsed_response = json_decode( preg_replace( '/\xEF\xBB\xBF/', '', $response['body'] ) );

		if ( ! empty( $parsed_response->transactionResponse->errors ) ) {
			$error_msg = __( 'Payment errors: ', 'woocommerce-cardpay-authnet' ) . $parsed_response->transactionResponse->errors[0]->errorText;
			return new WP_Error( 'authnet_error', $error_msg );
		} else {
			return $parsed_response;
		}
	}

	/**
	 * Get_card_type function
	 *
	 * @param string $number Card number.
	 *
	 * @return string
	 */
	public function get_card_type( $number ) {
		if ( preg_match( '/^4\d{12}(\d{3})?(\d{3})?$/', $number ) ) {
			return 'Visa';
		} elseif ( preg_match( '/^3[47]\d{13}$/', $number ) ) {
			return 'American Express';
		} elseif ( preg_match( '/^(5[1-5]\d{4}|677189|222[1-9]\d{2}|22[3-9]\d{3}|2[3-6]\d{4}|27[01]\d{3}|2720\d{2})\d{10}$/', $number ) ) {
			return 'MasterCard';
		} elseif ( preg_match( '/^(6011|65\d{2}|64[4-9]\d)\d{12}|(62\d{14})$/', $number ) ) {
			return 'Discover';
		} elseif ( preg_match( '/^35(28|29|[3-8]\d)\d{12}$/', $number ) ) {
			return 'JCB';
		} elseif ( preg_match( '/^3(0[0-5]|[68]\d)\d{11}$/', $number ) ) {
			return 'Diners Club';
		}
	}
}
