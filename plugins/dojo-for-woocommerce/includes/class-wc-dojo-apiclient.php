<?php

/**
 * Dojo ApiClient
 *
 * @package    Dojo_For_WooCommerce
 * @subpackage Dojo_For_WooCommerce/includes
 * @author     Dojo
 * @link       http://dojo.tech/
 */


if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('WC_Dojo_ApiClient')) {

	require_once __DIR__ . '/models/class-wc-dojo-payment-intent.php';
	require_once __DIR__ . '/models/class-wc-dojo-problem-details-exception.php';
	require_once __DIR__ . '/class-wc-dojo-logger.php';

	/**
	 * Dojo ApiClient
	 *
	 */
	class WC_Dojo_ApiClient
	{
		private const URL_ENTRY_POINT = 'https://api.dojo.tech';
		private const DOJO_API_VERSION = '2022-04-27';

		/**
		 * DOJO API requests
		 */
		private const API_REQUEST_POST_PAYMENT_INTENTS  = '/payment-intents';
		private const API_REQUEST_GET_PAYMENT_INTENTS   = '/payment-intents/%s';
		private const API_REQUEST_POST_REFUNDS          = '/payment-intents/%s/refunds';

		/**
		 * Dojo logger
		 *
		 * @var WC_Dojo_Logger
		 */
		private $logger;

		/**
		 * Dojo class constructor
		 */
		public function __construct()
		{
			$this->logger = new WC_Dojo_Logger();
		}

		/**
		 * Gets the API endpoint URL
		 *
		 * @param string $api_key to validate
		 * @return bool true, if $api_key provided is valid
		 */
		public function validate_api_key($api_key)
		{
			try {
				$this->create_payment_intent(
					$this->build_request_create_dummy_payment_intent(),
					$api_key
				);

				return true;
			} catch (Exception $ex) {
				$this->logger->log(
					"Error",
					"validate_api_key",
					$ex->getMessage(),
					$api_key
				);
			}

			return false;
		}

		/**
		 * Creates Payment Intent
		 *
		 * @param array  $post_fields The post data.
		 * @param string $api_key     API key to use. 
		 *
		 * @return WC_Dojo_Payment_Intent
		 */
		public function create_payment_intent($post_fields, $api_key)
		{
			$params = $this->build_request_params($post_fields);

			$args = array(
				'body'        => json_encode($params),
				'timeout'     => '30',
				'redirection' => '10',
				'data_format' => 'body',
				'headers'     => $this->build_request_headers($api_key)
			);

			$api_response = wp_remote_post($this->get_api_endpoint_url(self::API_REQUEST_POST_PAYMENT_INTENTS), $args);
			return $this->convert_api_response_to_payment_intent($api_response);
		}

		/**
		 * Gets Payment Intent
		 *
		 * @param string $pi       The payment intent ID.
		 * @param string $api_key  API key to use. 
		 *
		 * @return WC_Dojo_Payment_Intent
		 */
		public function get_payment_intent($pi, $api_key)
		{
			$url = $this->get_api_endpoint_url(self::API_REQUEST_GET_PAYMENT_INTENTS, $pi);
			$args = array(
				'timeout'     => '30',
				'redirection' => '10',
				'headers'     => $this->build_request_headers($api_key)
			);

			$api_response = wp_remote_get($url, $args);
			return $this->convert_api_response_to_payment_intent($api_response);
		}

		/**
		 * Performs refund
		 *
		 * @param array  $pi                   The payment intent ID.
		 * @param array  $post_fields          THe post data.
		 * @param string $idempotency_key      Idempotency key.
		 * @param string $api_key              API key to use. 
		 *
		 * @return array
		 */
		public function refund($pi, $post_fields, $idempotency_key, $api_key)
		{
			$params = $this->build_request_params($post_fields);

			$args = array(
				'body'        => json_encode($params),
				'timeout'     => '30',
				'redirection' => '10',
				'data_format' => 'body',
				'headers'     => $this->build_request_headers($api_key, $idempotency_key)
			);
			$url = $this->get_api_endpoint_url(self::API_REQUEST_POST_REFUNDS, $pi);
			$api_response = wp_remote_post($url, $args);

			$response_code = wp_remote_retrieve_response_code($api_response);

			if ($response_code != 200) {
				$json_array = json_decode(wp_remote_retrieve_body($api_response), true);
				throw new WC_Dojo_Problem_Details_Exception($response_code, $json_array);
			}
		}

		private function convert_api_response_to_payment_intent($api_response)
		{
			$json_array = json_decode(wp_remote_retrieve_body($api_response), true);
			$response_code = wp_remote_retrieve_response_code($api_response);

			if (is_wp_error($api_response)) {
				// Not sure when this can happen, prabably when can't connect to the service
				throw new Exception($api_response->get_error_message());
			}

			if ($response_code == 200) {
				return new WC_Dojo_Payment_Intent($json_array);
			}
			throw new WC_Dojo_Problem_Details_Exception($response_code, $json_array);
		}


		/**
		 * Gets the API endpoint URL
		 *
		 * @param string $request API request.
		 * @param string $param   Parameter of the API request.
		 *
		 * @return string
		 */
		private function get_api_endpoint_url($request, $param = null)
		{
			$result  = self::URL_ENTRY_POINT;
			$result .= (null !== $param) ? sprintf($request, $param) : $request;
			return sanitize_url($result);
		}

		/**
		 * Builds the HTTP headers for the API requests
		 *
		 * @param string $idempotency_key Idempotency key.
		 *
		 * @return array An associative array containing the HTTP headers
		 */
		private function build_request_headers($api_key, $idempotency_key = '')
		{
			$result = [
				'Authorization' => 'Basic ' . $api_key,
				'content-type' => 'application/json',
				'version' => self::DOJO_API_VERSION,
			];

			if ('' !== $idempotency_key) {
				$result['idempotencyKey'] = $idempotency_key;
			}

			return $result;
		}

		/**
		 * Builds the fields for the API requests by replacing the null values with empty strings
		 *
		 * @param array $fields An array containing the fields for the API request.
		 *
		 * @return array An array containing the fields for the API request
		 */
		private function build_request_params($fields)
		{
			return array_map(
				function ($value) {
					return null === $value ? '' : (is_string($value) ? sanitize_text_field($value) : $value);
				},
				$fields
			);
		}

		/**
		 * Builds the fields for a "dummy" create payment intent request
		 * for the purpose of the validation of the secret API key
		 *
		 * @return array An associative array containing the fields for the request
		 */
		private function build_request_create_dummy_payment_intent()
		{
			return [
				'amount'    => [
					'value'        => 100,
					'currencyCode' => 'GBP',
				],
				'reference' => 'Dummy request for validating API Key.',
			];
		}
	}
}
