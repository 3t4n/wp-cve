<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access.');

abstract class WC_VAT_Number_Lookup_Service {

	/**
	 * Get a list of region codes that this service can look-up numbers in
	 *
	 * @return Array - A list of region codes as used by the plugin
	 */
	abstract public function get_supported_region_codes();
	
	/**
	 * Return the title for this service
	 *
	 * @return String
	 */
	abstract public function get_service_name();
	
	/**
	 * @param String  $vat_prefix	- the country prefix to the VAT number
	 * @param String  $vat_number	- the VAT number (already canonicalised), minus any country prefix
	 * @param Boolean $force_simple	- force a non-extended lookup, even if in the saved options there is a VAT ID for the store
	 *
	 * @return Array - keys are:
	 * (boolean) 'validated'  - whether a definitive result was obtained
	 * (boolean) 'valid'	  - if 'validated' is true, then this contains the validation result (otherwise, undefined)
	 * (string)	 'error_code' - if 'validated' is false, this contains an error code. The code 'not_configured' means that the service is not yet configured (and hence no lookup could take place).
	 * (string)	 'error_message' - is set if, and only if, there was an error_code
	 * (mixed)	 'data'		  - data - usually the raw result from the network
	 */
	abstract function get_validation_result_from_network($vat_prefix, $vat_number, $force_simple = false);

	/**
	 * Return a standardised "not configured" response (intended for use by get_validation_result_from_network())
	 *
	 * @see self::get_validation_result_from_network()
	 *
	 * @return Array
	 */
	protected function not_configured_response() {
		return array('validated' => false, 'error_code' => 'not_configured', 'error_message' => sprintf(__('The %s service cannot be used for VAT number lookups because the site owner has not configured it.', 'woocommerce-eu-vat-compliance'), $this->get_service_name())); 
	}
	
	/**
	 * Return whether or not the service is configured (defaults to assuming none is required, i.e. a public service)
	 *
	 * @return Boolean
	 */
	public function is_configured() {
		return true;
	}
	
	/**
	 * Return a service description, if desired
	 *
	 * @return String
	 */
	public function get_service_description() {
		return '';
	}
	
	/**
	 * Return a list of user-facing settings fields, in WooCommerce format
	 *
	 * @return Array
	 */
	public function user_editable_settings_fields() {
		return array();
	}
	
	/**
	 * Output settings HTML for the control centre
	 */
	public function do_settings_output() {
	
		$name = $this->get_service_name();
		$description = $this->get_service_description();
		$full_description = $description ? $name.' ('.$description.')' : $name;
	
		printf(__('%s does not require any authentication (i.e. is publicly available).', 'woocommerce-eu-vat-compliance'), $full_description);
	}
	
}
