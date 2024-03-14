<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('WC_Dojo_Problem_Details_Exception')) {
	require_once __DIR__ . '/../class-wc-dojo-utils.php';

	/**
	 * Dojo ApiClient
	 *
	 * @since 4.0.0
	 * @version 4.0.0
	 */
	class WC_Dojo_Problem_Details_Exception extends Exception
	{

		public $response_code;

		public function __construct($response_code, $json_array)
		{
			$this->response_code = $response_code;

			if ($this->response_code == 401) {
				$this->message = "Secret API Key provided is incorrect, deleted or has expired!";
			} else {
				$this->message = "Response code: $response_code, Problem details: ";
				$this->message .= WC_Dojo_Utils::convert_array_to_string($json_array, ', ');
			}
		}
	}
}
