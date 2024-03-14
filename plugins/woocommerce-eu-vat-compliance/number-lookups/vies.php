<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access.');

// Purpose: Interface with the HMRC VAT number lookup service

if (!class_exists('WC_VAT_Number_Lookup_Service')) require_once(WC_VAT_COMPLIANCE_DIR.'/number-lookups/lookup-service.php');

// Conditional execution to deal with bugs on some old PHP versions with classes that extend classes not known until execution time
if (1==1):
class WC_VAT_Number_Lookup_Service_vies extends WC_VAT_Number_Lookup_Service {

	/**
	 * Return the name of this VAT-number checking service
	 *
	 * @return String
	 */
	public function get_service_name() {
		return 'VIES';
	}
	
	/**
	 * Return the name of this VAT-number checking service
	 *
	 * @return String
	 */
	public function get_service_description() {
		return __('the official EU service for verifying EU VAT numbers', 'woocommerce-eu-vat-compliance');
	}

	/**
	 * Get a list of regions that this service can look-up numbers in
	 *
	 * @return Array - A list of region codes as used by the plugin
	 */
	public function get_supported_region_codes() {
		return array('eu');
	}

	/**
	 * @param String  $vat_prefix	- the country prefix to the VAT number
	 * @param String  $vat_number	- the VAT number (already canonicalised), minus any country prefix
	 * @param Boolean $force_simple	- force a non-extended lookup, even if in the saved options there is a VAT ID for the store
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

		// Some code adapted from Diego Zanella, with contributions from Sven Auhagen

		// Enforce requirements of later versions of the nusoap library
		// if (version_compare(PHP_VERSION, '5.4', '<')) return new WP_Error('insufficient_php', 'This feature requires PHP 5.4 or later.');
		// require_once(WC_VAT_COMPLIANCE_DIR.'/vendor/autoload.php');
		
		if (!class_exists('nusoap_base')) require_once(WC_VAT_COMPLIANCE_DIR.'/nusoap/class.nusoap_base.php');
		
		$wsdl = new wsdl('https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl', '', '', '', '', 5);

		// Create SOAP client. HTTPS not supported (verified Jan 2015; still the case Aug 2018)
		$client = new nusoap_client($wsdl, 'wsdl');
		
		// March 2020: a change at the VIES server end means that using curl results in "Fault code: No such operation: (HTTP GET PATH_INFO: /taxation_customs/vies/checkVatService)" for some VAT numbers, including from Poland and Greece. (Next day: all seems well again)
		// Of interest: https://wordpress.org/support/topic/vat-number-not-validating-for-requester-country-greece/ , https://wordpress.org/support/topic/vat-number-not-validating-for-requester-country-poland/
 		if (function_exists('curl_exec')) $client->setUseCurl(true);
		
		// Check if any error occurred initialising the SOAP client. We can't continue in this case.
		$error = $client->getError();

		if ($error) return array('validated' => false, 'error_code' => 'wsdl_error', 'error_message' => 'Failed to initialise WSDL layer', 'data' => $error);
		
		$compliance = WooCommerce_EU_VAT_Compliance();
		
		$store_vat_id = $compliance->get_store_vat_number('eu');
		
		// Perform an extended check, unless it was forbidden by the parameter or prevented by lack of configuration
		if (!$force_simple && '' != $store_vat_id) {

			if (preg_match('/^([A-Z][A-Z])?([0-9A-Z]+)/i', str_replace(' ', '', $store_vat_id), $matches)) {

				if (empty($matches[1])) {
					// We look for the country code of the store
					$base_countries = $compliance->get_base_countries();
					$base_country = $base_countries[0];
					
					$eu_region = $compliance->get_vat_region_object('eu');
					
					$storevat_country = $eu_region->get_vat_number_prefix($base_country);
				} else {
					$storevat_country = strtoupper($matches[1]);
				}

				$storevat_id = $matches[2];
			}
		}
	
		/*
		For usefulness (since it's hard to emulate VIES being down for a specific country, on demand), here's what you get from VIES when a country's VIES connection is down:
		a:2:{s:9:"faultcode";s:11:"soap:Server";s:11:"faultstring";s:14:"MS_UNAVAILABLE";}
		i.e.
		Array
			(
				[faultcode] => soap:Server
				[faultstring] => MS_UNAVAILABLE
			)
		*/

		$client->soap_defencoding = 'UTF-8';
		$client->decode_utf8 = FALSE;
		
		if (!empty($storevat_id) && !empty($storevat_country)) {
			$response = $client->call('checkVatApprox', array(
				'countryCode' => $vat_prefix,
				'vatNumber' => $vat_number,
				// Shop Owners Data have to be sent in order to retrieve the requestIdentifier
				// they should be entered in a field in the woocommerce backend
				'requesterCountryCode' => $storevat_country,
				'requesterVatNumber' => $storevat_id,
			));
		} else {
			$response = $client->call('checkVat', array(
				'countryCode' => $vat_prefix,
				'vatNumber' => $vat_number,
			));
		}

		if (is_string($response) && strstr($response, 'SERVER_BUSY')) {
			return array('validated' => false, 'error_code' => 'SERVER_BUSY', 'error_message' => $this->fault_code_to_text('SERVER_BUSY'), 'data' => $response);
		}

		if (isset($response['valid']) && 'true' == $response['valid']) {
			return array('validated' => true, 'valid' => true, 'data' => $response);
		}
		
		if (isset($response['valid']) && 'false' == $response['valid']) {
			return array('validated' => true, 'valid' => false, 'data' => $response);
		}
		
		if (!empty($response['faultcode'])) {
			return array('validated' => false, 'error_code' => $response['faultstring'], 'error_message' => $this->fault_code_to_text($response['faultstring']), 'data' => $response);
		}
		
		return array('validated' => false, 'error_code' => 'no_result', 'error_message' => __('No result was returned from the network VAT number check.', 'woocommerce-eu-vat-compliance'), 'data' => $response);
		
		// We used to have a fallback service here, before it was discontinued
		// return wp_remote_get($this->validation_api_url . $vat_prefix . '/' . $vat_number . '/');

	}
	
	/**
	 * From self::$fault_code and self::$fault_text, produce a textual message
	 *
	 * @param String $fault_code
	 *
	 * @return String
	 */
	private function fault_code_to_text($fault_code) {
	
		switch ($fault_code) {
			case '':
			$info = '';
			case 'SERVER_BUSY':
			$info = __('The VIES server was too busy.', 'woocommerce-eu-vat-compliance'); 
			break;
			case 'MS_MAX_CONCURRENT_REQ':
			$info = __("The VIES service is currently unavailable (too many concurrent requests for member state on VIES's side).", 'woocommerce-eu-vat-compliance'); 
			break;
			case 'GLOBAL_MAX_CONCURRENT_REQ':
			$info = __("The VIES service is currently unavailable (too many concurrent requests globally on VIES's side).", 'woocommerce-eu-vat-compliance'); 
			break;
			case 'SERVICE_UNAVAILABLE':
			$info = __('The VIES service is currently unavailable.', 'woocommerce-eu-vat-compliance'); 
			break;
			case 'MS_UNAVAILABLE':
			$info = __("The member state's VIES service is currently unavailable.", 'woocommerce-eu-vat-compliance'); 
			break;
			default:
			$info = sprintf(__('Fault code: %s', 'woocommerce-eu-vat-compliance'), $fault_code); 
			break;
		}
		return $info;
	}
	
}
endif;
