<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access.');

abstract class WC_VAT_Region {

	/**
	 * Get a list of countries that define the region
	 *
	 * @return Array
	 */
	abstract public function get_countries();
	
	/**
	 * Return the title for the region
	 *
	 * @param String $context - 'noun', 'adjective'
	 *
	 * @return String
	 */
	abstract public function get_region_title($context);
	
	/**
	 * Return the name of the VAT-number checking service for the region
	 *
	 * @return String
	 */
	public function get_service_name() {
		// Provide a default if the method is not provided in the child
		return $this->get_region_title('noun');
	}
	
	/**
	 * Given a country, return the minimum number of characters in a valid VAT number for that country
	 * Generally a child class will not need to re-implement this (but map_country_codes_to_minimum_characters())
	 *
	 * @param String $country_code - the country code
	 *
	 * @return Integer
	 */
	public function get_vat_number_minimum_characters($country_code) {
		$mapping = $this->map_country_codes_to_minimum_characters();
		return isset($mapping[$country_code]) ? $mapping[$country_code] : $mapping['default'];
	}
	
	/**
	 * Get an array listing the minimum number of characters in a valid VAT number for the region's countries (and a default)
	 *
	 * @return Array
	 */
	public function map_country_codes_to_minimum_characters() {
		// Some small default - but this is expected to be over-ridden
		return array('default' => 6);
	}
	
	/**
	 * Given a VAT number in unspecified format, canonicalise it. This includes removing any standard country prefix.
	 * The routine here will perform a basic removal of extraneous characters, of any prefixes that match the region's countries, and upper-case the number. Child classes may want to call this first before doing their own further processing.
	 *
	 * @param String $vat_number - the VAT number
	 *
	 * @return String
	 */
	public function standardise_vat_number($vat_number) {
		$vat_number = strtoupper(str_replace(array(' ', '-', '_', '.'), '', $vat_number));
		
		if (in_array(substr($vat_number, 0, 2), $this->get_countries())) {
			$vat_number = substr($vat_number, 2);
		}
		
		return $vat_number;
		
	}
	
	/**
	 * Return the VAT number prefix for the given country code. Over-ride this if the region has discrepancies.
	 *
	 * @param String $country - country code
	 *
	 * @return String - the prefix
	 **/
	public function get_vat_number_prefix($country) {
		return $country;
	}

}
