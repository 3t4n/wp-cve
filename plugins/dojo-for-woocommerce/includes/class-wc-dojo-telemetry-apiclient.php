<?php

/**
 * Dojo Telemetry ApiClient
 *
 * @package    Dojo_For_WooCommerce
 * @subpackage Dojo_For_WooCommerce/includes
 * @author     Dojo
 * @link       http://dojo.tech/
 */


if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('WC_Dojo_TelemetryApiClient')) {

	require_once __DIR__ . '/models/class-wc-dojo-problem-details-exception.php';

	/**
	 * Dojo ApiClient
	 *
	 */
	class WC_Dojo_TelemetryApiClient
	{
		private const URL_ENTRY_POINT = 'https://api.dojo.tech/plugins/telemetry';
		private const DOJO_API_VERSION = '2024-01-15';


		/**
		 * Creates Payment Intent
		 *
		 * @param array  $post_fields The post data.
		 * @param string $api_key     API key to use. 
		 *
		 * @return WC_Dojo_Payment_Intent
		 */
		public function create_telemetry_log($post_fields, $api_key)
		{
			$params = $this->build_request_params($post_fields);

			$args = array(
				'body'        => json_encode($params),
				'timeout'     => '30',
				'redirection' => '10',
				'data_format' => 'body',
				'headers'     => $this->build_request_headers($api_key)
			);

			$api_response = wp_remote_post($this->get_api_endpoint_url(self::URL_ENTRY_POINT), $args);
			return $this->handle_api_response($api_response);
		}


		private function handle_api_response($api_response)
		{
			$json_array = json_decode(wp_remote_retrieve_body($api_response), true);
			$response_code = $api_response["response"]["code"];

			if ($response_code == 200) {
				return true;
			} else {
				return false;
			}
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
	}
}
