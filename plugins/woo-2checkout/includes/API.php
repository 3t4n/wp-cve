<?php

namespace StorePress\TwoCheckoutPaymentGateway;

defined( 'ABSPATH' ) || die( 'Keep Silent' );

class API {

	const POST   = 'POST';
	const GET    = 'GET';
	const PUT    = 'PUT';
	const DELETE = 'DELETE';

	protected $merchant_code;

	protected $secret_key;

	// https://verifone.cloud/docs/2checkout/API-Integration/Webhooks/IPN_and_LCN_URL_settings
	// https://verifone.cloud/docs/2checkout/API-Integration/01Start-using-the-2Checkout-API/2Checkout-API-general-information/Migration_guide_SHA2_SHA3
	// protected $hashing_algorithm = 'md5'; // md5 is old and not recommended. use sha256 or sha3-256

	public function __construct( $merchant_code, $secret_key ) {
		$this->merchant_code = $merchant_code;
		$this->secret_key    = $secret_key;
	}

	public static function instance( $merchant_code, $secret_key ) {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self( $merchant_code, $secret_key );
		}

		return $instance;
	}

	public function get_hashing_algorithm( $data = false ) {

		if ( $data && ! empty( $data['SIGNATURE_SHA2_256'] ) ) {
			return 'sha256';
		}

		if ( $data && ! empty( $data['SIGNATURE_SHA3_256'] ) ) {
			return 'sha3-256';
		}

		return 'md5';
	}

	public function get_returned_hash( $data = false ) {

		if ( $data && ! empty( $data['SIGNATURE_SHA2_256'] ) ) {
			return $data['SIGNATURE_SHA2_256'];
		}

		if ( $data && ! empty( $data['SIGNATURE_SHA3_256'] ) ) {
			return $data['SIGNATURE_SHA3_256'];
		}

		return $data['HASH'];
	}

	public function generate_jwt_token( $merchant_id, $iat, $exp, $buy_link_secret_word ) {

		$header    = $this->encode(
			wp_json_encode(
				array(
					'alg' => 'HS512',
					'typ' => 'JWT',
				)
			)
		);
		$payload   = $this->encode(
			wp_json_encode(
				array(
					'sub' => $merchant_id,
					'iat' => $iat,
					'exp' => $exp,
				)
			)
		);
		$signature = $this->encode( hash_hmac( 'sha512', "$header.$payload", $buy_link_secret_word, true ) );

		return implode(
			'.',
			array(
				$header,
				$payload,
				$signature,
			)
		);
	}

	private function encode( $data ) {
		return str_replace( '=', '', strtr( base64_encode( $data ), '+/', '-_' ) );
	}

	// https://verifone.cloud/docs/2checkout/Documentation/07Commerce/2Checkout-ConvertPlus/ConvertPlus_URL_parameters
	// https://verifone.cloud/docs/2checkout/Documentation/07Commerce/2Checkout-ConvertPlus/ConvertPlus_Buy-Links_Signature
	public function convertplus_buy_link_signature( $params, $buy_link_secret_word ) {

		// ConvertPlus parameters that require a signature.
		$signature_params = array(
			'return-url',
			'return-type',
			'expiration',
			'order-ext-ref',
			'item-ext-ref',
			'customer-ref',
			'customer-ref',
			'customer-ext-ref',
			// 'lock',
			'currency',
			'prod',
			'price',
			'qty',
			'type',
			'opt',
			'description',
			'recurrence',
			'duration',
			'renewal-price',
		);

		$filtered_params = array_filter(
			$params,
			function ( $key ) use ( $signature_params ) {
				return in_array( $key, $signature_params, true );
			},
			ARRAY_FILTER_USE_KEY
		);

		$serialize_string = $this->convertplus_serialize( $filtered_params );

		// Should use Algorithm sha256 here.
		return hash_hmac( 'sha256', $serialize_string, $buy_link_secret_word );
	}

	public function convertplus_buy_link( $params, $merchant_code, $buy_link_secret_word ) {

		$pre_data = array( 'merchant' => $merchant_code );
		$data     = array_merge( $pre_data, $params );

		if ( ! isset( $data['expiration'] ) ) {
			$data['expiration'] = absint( time() + ( HOUR_IN_SECONDS * 5 ) ); // 5 hours; 60 mins; 60 secs
		}

		$data['signature'] = $this->convertplus_buy_link_signature( $data, $buy_link_secret_word );

		return 'https://secure.2checkout.com/checkout/buy/?' . http_build_query( $data );
	}

	// https://knowledgecenter.2checkout.com/Documentation/07Commerce/2Checkout-ConvertPlus/How-to-use-2Checkout-Signature-Generation-API-Endpoint#PHP_23
	public function get_signature( $params, $buy_link_secret_word ) {

		$jwt_token = $this->generate_jwt_token( $this->merchant_code, time(), time() + 3600, $buy_link_secret_word );

		$response = wp_remote_post(
			'https://secure.2checkout.com/checkout/api/encrypt/generate/signature',
			array(
				'headers' => array(
					'content-type'   => 'application/json',
					'cache-control'  => 'no-cache',
					'merchant-token' => $jwt_token,
				),
				'body'    => json_encode( $params ),
			)
		);

		$response_body = wp_remote_retrieve_body( $response );
		$response_data = json_decode( $response_body );

		if ( isset( $response_data->signature ) ) {
			return $response_data->signature;
		}

		if ( isset( $response_data->error_code ) ) {
			wc_add_notice( $response_data->message, 'error' );

			return false;
		}

		wc_add_notice( '2Checkout: Unable to get signature response from signature generation API.', 'error' );

		return false;
	}

	public function convertplus_serialize( $params ) {

		ksort( $params );

		$map_data = array_map(
			function ( $value ) {
				return strlen( $value ) . $value;
			},
			$params
		);

		return implode( '', $map_data );
	}

	// https://verifone.cloud/docs/2checkout/API-Integration/Webhooks/06Instant_Payment_Notification_%2528IPN%2529/Calculate-the-IPN-HASH-signature
	public function is_valid_ipn_lcn_hash( $post_data, $secret_key ) {

		$ipn_hash = $this->get_returned_hash( $post_data );

		$generate_string = $this->generate_base_string_for_hash( $post_data );

		$get_algo    = $this->get_hashing_algorithm( $post_data );
		$server_hash = hash_hmac( $get_algo, $generate_string, $secret_key );

		return hash_equals( $server_hash, $ipn_hash );
	}

	public function generate_base_string_for_hash( $params ) {

		$string = '';

		unset( $params['HASH'], $params['SIGNATURE_SHA2_256'], $params['SIGNATURE_SHA3_256'] );

		foreach ( $params as $value ) {

			if ( is_array( $value ) ) {
				$string .= $this->generate_base_string_for_hash( $value );
			} else {
				$string .= strlen( $value ) . $value;
			}
		}

		return $string;
	}

	public function kses_receipt_response_allowed_html() {
		return array(
			'epayment' => array(),
			'sig'      => array(
				'algo' => array(),
				'date' => array(),
			),
		);
	}

	// https://verifone.cloud/docs/2checkout/API-Integration/Webhooks/08License_Change_Notification_%2528LCN%2529/LCN-read-receipt-response-for-2Checkout
	// https://verifone.cloud/docs/2checkout/API-Integration/Webhooks/IPN_and_LCN_URL_settings
	// https://verifone.cloud/docs/2checkout/API-Integration/Webhooks/06Instant_Payment_Notification_%2528IPN%2529/Calculate-the-IPN-HASH-signature
	public function ipn_receipt_response( $post_data, $secret_key = false ) {
		// <EPAYMENT>DATE|HASH</EPAYMENT>
		// <sig algo="sha256" date="DATE">HASH</sig>
		// <sig algo="sha3-256" date="DATE">HASH</sig>

		if ( empty( $post_data['IPN_PID'] ) || empty( $post_data['IPN_PNAME'] ) ) {
			return false;
		}

		// Response issuing date (server time) in the YmdHis format (ex: 20081117145935)
		$receipt_date = gmdate( 'YmdHis' );

		$ipn_receipt = array(
			$post_data['IPN_PID'][0],
			$post_data['IPN_PNAME'][0],
			$post_data['IPN_DATE'],
			// IPN date in the YmdHis format (ex: 20081117145935)
			$receipt_date,
		);

		// CUSTOM IPN AND LCN CONFIGURATIONS
		if ( ! $secret_key ) {
			$secret_key = $this->secret_key;
		}

		$receipt_return = implode(
			'',
			array_map(
				function ( $value ) {
					return strlen( stripslashes( $value ) ) . stripslashes( $value );
				},
				$ipn_receipt
			)
		);

		$get_algo     = $this->get_hashing_algorithm( $post_data );
		$receipt_hash = hash_hmac( $get_algo, $receipt_return, $secret_key );

		if ( $this->is_valid_ipn_lcn_hash( $post_data, $secret_key ) ) {

			if ( 'md5' === $get_algo ) {
				return sprintf( '<EPAYMENT>%s|%s</EPAYMENT>', $receipt_date, $receipt_hash );
			}

			// sha256 and sha3-256 .
			return sprintf( '<sig algo="%s" date="%s">%s</sig>', $get_algo, $receipt_date, $receipt_hash );

		} else {
			return false;
		}
	}

	// https://verifone.cloud/docs/2checkout/Documentation/07Commerce/2Checkout-ConvertPlus/Signature_validation_for_return_URL_via_ConvertPlus
	// https://verifone.cloud/docs/2checkout/Documentation/07Commerce/InLine-Checkout-Guide/Signature_validation_for_return_URL_via_InLine_checkout

	public function generate_return_signature( $params, $buy_link_secret_word ) {

		if ( empty( $params ) || empty( $params['signature'] ) ) {
			return false;
		}

		// Remove signature key from params list.
		unset( $params['signature'], $params['wc-api'] );
		$serialize_string = $this->convertplus_serialize( $params );

		// Should use Algorithm sha256 here.
		return hash_hmac( 'sha256', $serialize_string, $buy_link_secret_word );
	}

	public function is_valid_return_signature( $params, $buy_link_secret_word ) {

		if ( empty( $params ) || empty( $params['signature'] ) ) {
			return false;
		}

		$return_signature = sanitize_text_field( $params['signature'] );

		// Remove signature key from params list.
		unset( $params['signature'], $params['wc-api'] );
		$serialize_string = $this->convertplus_serialize( $params );
		// Should use Algorithm sha256 here.
		$generated_signature = hash_hmac( 'sha256', $serialize_string, $buy_link_secret_word );

		return hash_equals( $generated_signature, $return_signature );
	}
}
