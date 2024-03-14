<?php

class Quadpay_WC_Api
{
	/**
	 * @var Quadpay_WC_Api
	 */
	private static $instance;

	/**
	 * @return Quadpay_WC_Api
	 */
	public static function instance() {

		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @return stdClass|WP_Error
	 */
	public function configuration() {

		Quadpay_WC_Logger::log( __METHOD__ );

		$response = wp_remote_get(
			Quadpay_WC_Settings::instance()->get_api_url('/configuration'),
			['headers' => $this->get_headers()]
		);

		Quadpay_WC_Logger::log( $response, __METHOD__ );

		return $this->_handle_response( $response );
	}

	/**
	 * @param string $quadpay_order_id
	 * @return stdClass|WP_Error
	 */
	public function get_order( $quadpay_order_id ) {

		Quadpay_WC_Logger::log( func_get_args(), __METHOD__ );

		$response = wp_remote_get(
			Quadpay_WC_Settings::instance()->get_api_url("/order/$quadpay_order_id"),
			['headers' => $this->get_headers()]
		);

		Quadpay_WC_Logger::log( $response, __METHOD__ );

		return $this->_handle_response( $response );
	}

	/**
	 * @param array $data
	 * @return stdClass|WP_Error
	 */
	public function order( $data ) {

		Quadpay_WC_Logger::log( $data, __METHOD__ );

		$response = wp_remote_post(
			Quadpay_WC_Settings::instance()->get_api_url("/order"),
			[
				'headers' => $this->get_headers(),
				'body'    => json_encode( $data ),
				'timeout' => 15,
			]
		);

		Quadpay_WC_Logger::log( $response, __METHOD__ );

		return $this->_handle_response( $response );
	}

	/**
	 * @return stdClass|WP_Error
	 */
	public function refund( $quadpay_order_id, $amount, $merchant_refund_reference ) {

		Quadpay_WC_Logger::log( func_get_args(), __METHOD__ );

		$response = wp_remote_post(
			Quadpay_WC_Settings::instance()->get_api_url("/order/$quadpay_order_id/refund"),
			[
				'headers' => $this->get_headers(),
				'body' => json_encode(
					[
						'requestId'               => $merchant_refund_reference,
						'amount'                  => $amount,
						'merchantRefundReference' => $merchant_refund_reference
					]
				)
			]
		);

		Quadpay_WC_Logger::log( $response, __METHOD__ );

		return $this->_handle_response( $response );
	}

	/**
	 * @param string $quadpay_order_id
	 * @param float $amount
	 * @param string $merchant_reference
	 * @return stdClass|WP_Error
	 */
	public function capture( $quadpay_order_id, $amount, $merchant_reference ) {

		Quadpay_WC_Logger::log( func_get_args(), __METHOD__ );

		$response = wp_remote_post(
			Quadpay_WC_Settings::instance()->get_api_url("/order/$quadpay_order_id/capture"),
			[
				'headers' => $this->get_headers(),
				'body' => json_encode(
					[
						'amount' => $amount,
						'merchantReference'	=> $merchant_reference,
					]
				),
			]
		);

		return $this->_handle_response( $response );
	}

	/**
	 * @param string $quadpay_order_id
	 * @param float $amount
	 * @param string $merchant_reference
	 * @return stdClass|WP_Error
	 */
	public function void( $quadpay_order_id, $amount, $merchant_reference ) {

		Quadpay_WC_Logger::log( func_get_args(), __METHOD__ );

		$response = wp_remote_post(
			Quadpay_WC_Settings::instance()->get_api_url("/order/$quadpay_order_id/void"),
			[
				'headers' => $this->get_headers(),
				'body' => json_encode(
					[
						'amount' => $amount,
						'merchantReference'	=> $merchant_reference,
					]
				),
			]
		);

		Quadpay_WC_Logger::log( $response, __METHOD__ );

		return $this->_handle_response( $response );
	}

	/**
	 * @return string[]
	 */
	public function get_headers() {
		return [
			'Content-Type'  => 'application/json',
			'Authorization' => 'Bearer ' . $this->get_auth_token(),
		];
	}

	/**
	 * Request an order token from Zip
	 *
	 * @param bool $use_cache
	 * @return string
	 */
	public function get_auth_token( $use_cache = true ) {

		$settings = Quadpay_WC_Settings::instance();
		$client_id = $settings->get_option('client_id');
		$client_secret = $settings->get_option('client_secret');

		if ( !$client_id || !$client_secret ) {
			return '';
		}

		$access_token_cache_key = 'quadpay_access_token_' . md5( $client_id );

		if ( $use_cache && false !== ( $access_token = get_transient( $access_token_cache_key ) ) ) {
			Quadpay_WC_Logger::log( 'Cached access token', __METHOD__ );
			return $access_token;
		}

		$body = array(
			'client_id'     => $client_id,
			'client_secret' => $client_secret,
			'audience'      => $settings->get_environment( 'auth_audience' ),
			'grant_type'    => 'client_credentials',
		);

		$response = wp_remote_post(
			$settings->get_environment( 'auth_url' ),
			[
				'method' => 'POST',
				'headers' => [ 'Content-Type' => 'application/json' ],
				'body' =>  json_encode( $body )
			]
		);

		Quadpay_WC_Logger::log( $response, __METHOD__ );

		if ( is_wp_error( $response ) ) {
			return '';
		}

		if ( 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			return '';
		}

		$body = json_decode( wp_remote_retrieve_body( $response ) );

		//store token in cache
		if ( $use_cache ) {
			set_transient( $access_token_cache_key, $body->access_token, $body->expires_in - 300 );
		}

		Quadpay_WC_Logger::log( 'New access token', __METHOD__ );

		return $body->access_token;
	}

	/**
	 * @param $response
	 * @return array|WP_Error
	 */
	private function _handle_response( $response ) {
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );
		$body = json_decode( wp_remote_retrieve_body( $response ) );

		if ( !in_array( $code, [ 200, 201 ], true ) ) {
			return new \WP_Error('quadpay_error', $body ?: $code);
		}

		return $body;
	}

}
