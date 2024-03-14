<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access.');

// Purpose: Interface with the number lookup service at vatsense.com

if (!class_exists('WC_VAT_Number_Lookup_Service')) require_once(WC_VAT_COMPLIANCE_DIR.'/number-lookups/lookup-service.php');

// Conditional execution to deal with bugs on some old PHP versions with classes that extend classes not known until execution time
if (1==1):
class WC_VAT_Number_Lookup_Service_vatsense extends WC_VAT_Number_Lookup_Service {

	const SIGNUP_URL = 'https://vatsense.com/signup?referral=SIMBAHOSTING';

	const OPTION_NAME = 'woocommerce_vat_compliance_vat_sense_api_key';
	
	/**
	 * Return the name of this VAT-number checking service
	 *
	 * @return String
	 */
	public function get_service_name() {
		return 'VAT Sense API';
	}
	
	/**
	 * Return the name of this VAT-number checking service
	 *
	 * @return String
	 */
	public function get_service_description() {
		return __('VAT number validation by vatsense.com', 'woocommerce-eu-vat-compliance');
	}
	
	/**
	 * Constructor for the class.
	 */
	public function __construct() {
		add_option($this::OPTION_NAME, '');
	}
	
	/**
	 * Return a list of user-facing settings fields, in WooCommerce format
	 *
	 * @return Array
	 */
	public function user_editable_settings_fields() {
		return array(array('type' => 'text', 'desc' => '', 'id' => $this::OPTION_NAME, 'value_when_missing' => ''));
	}

	/**
	 * Get a list of regions that this service can look-up numbers in
	 *
	 * @return Array - A list of region codes as used by the plugin
	 */
	public function get_supported_region_codes() {
		return array('uk', 'eu', 'norway');
	}
	
	/**
	 * Get the API key saved by the user
	 *
	 * @return String
	 */
	private function get_api_key_option() {
	
		return get_option($this::OPTION_NAME, '');
	
	}
	
	/**
	 * Return whether or not the service is configured
	 *
	 * @return Boolean
	 */
	public function is_configured() {
		$api_key = $this->get_api_key_option();
		return ('' !== $api_key);
	}
	
	/**
	 * Output settings HTML for the control centre
	 */
	public function do_settings_output() {
		
		echo '<input name="woocommerce_vat_compliance_vat_sense_api_key" type="text" placeholder="'.__('(Paste API key here)', 'woocommerce-eu-vat-compliance').'" size="36" value="'.esc_attr($this->get_api_key_option()).'">';
		echo '<br> <br>';
		echo __('VAT Sense is a third-party provider of VAT validation capabilities (commercial, but with a free tier).', 'woocommerce-eu-vat-compliance').' '.__('Enabling it may enhance reliability by allowing you to still complete look-ups if your network route to the official VIES/HMRC service is down; it also provides support for Norwegian VAT numbers.', 'woocommerce-eu-vat-compliance').' '.__('If you provide an API key then it will also be used as the source for current VAT rate data.', 'woocommerce-eu-vat-compliance').' <a href="'.$this::SIGNUP_URL.'">'.__('Follow this link to sign up for a vatsense.com account.', 'woocommerce-eu-vat-compliance').'</a>';

	}
	
	/**
	 * @param String  $vat_prefix	- the country prefix to the VAT number
	 * @param String  $vat_number	- the VAT number (already canonicalised), minus any country prefix
	 * @param Boolean $force_simple	- force a non-extended lookup, even if in the saved options there is a VAT ID for the store. Has no effect 
	 *
	 * N.B. The return format has to be kept in sync with that for WC_EU_VAT_Compliance
	 *
	 * @return Array - keys are:
	 * (boolean) 'validated'  - whether a definitive result was obtained
	 * (boolean) 'valid'	  - if 'validated' is true, then this contains the validation result (otherwise, undefined)
	 * (string)	 'error_code' - if 'validated' is false, this contains an error code
	 * (string)	 'error_message' - is set if, and only if, there was an error_code
	 * (mixed)	 'data'		  - data - usually the raw result from the network
	 */
	public function get_validation_result_from_network($vat_prefix, $vat_number, $force_simple = false) {

		if (!$this->is_configured()) return $this->not_configured_response();
	
		$response = wp_remote_get('https://api.vatsense.com/1.0/validate?vat_number='.$vat_prefix.$vat_number, array('headers' => array('Authorization' => 'Basic '.base64_encode('user:'.$this->get_api_key_option()))));
		
		$http_response_code = wp_remote_retrieve_response_code($response);
		$http_response_body = wp_remote_retrieve_body($response);
		
		if ($http_response_code >= 400 && $http_response_code < 500) {
			return array('validated' => false, 'error_code' => 'authorisation_failure', 'error_message' => 'The remote API service rejected the supplied credentials (check your API key)', 'data' => array('http_response_code' => $http_response_code, 'http_response_body' => $http_response_body));
		}
		
		if ($http_response_code >= 300) {
			return array('validated' => false, 'error_code' => 'remote_server_error', 'error_message' => 'Remote API server error', 'data' => array('http_response_code' => $http_response_code, 'http_response_body' => $http_response_body));
		}
		
		$api_result = json_decode($http_response_body, true);
		
		if (!is_array($api_result) || empty($api_result['success']) || !isset($api_result['data']['valid'])) {
			
			if (is_array($api_result) && !empty($api_result['data']) && is_array($api_result['data']) && !empty($api_result['data']['code']) && !empty($api_result['data']['error']) && isset($api_result['data']['error']['title']) && false !== stristr($api_result['data']['error']['title'], 'invalid')) {
				return array('validated' => true, 'valid' => false, 'data' => $api_result);
			}
			
			return array('validated' => false, 'error_code' => 'invalid_response', 'error_message' => 'The remote API server returned an invalid response', 'data' => array('http_response_code' => $http_response_code, 'http_response_body' => $http_response_body));
		}
		
		return array('validated' => true, 'valid' => (bool) $api_result['data']['valid'], 'data' => $api_result);
	}
}
endif;
