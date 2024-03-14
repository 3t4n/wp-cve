<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access.');

class WC_VAT_Region_uk extends WC_VAT_Region {

	/**
	 * Get a list of countries that define the region
	 *
	 * @return Array
	 */
	public function get_countries() {
		// Great Britain (this is the ISO code - and includes Northern Ireland and other territories), Isle of Man
		return array('GB', 'IM');
	}
	
	/**
	 * Return the title for the region
	 *
	 * @param String $context - 'noun', 'adjective'
	 *
	 * @return String
	 */
	public function get_region_title($context) {
		if ('adjective' == $context) return __('UK', 'woocommerce-eu-vat-compliance');
		return __('the UK', 'woocommerce-eu-vat-compliance');
	}

	/**
	 * Get an array listing the minimum number of characters in a valid VAT number for the region's countries (and a default)
	 *
	 * @return Integer
	 */
	public function map_country_codes_to_minimum_characters() {
		// https://www.gov.uk/vat-eu-country-codes-vat-numbers-and-vat-in-other-languages
		return array('default' => 9);
	}
	
	/**
	 * Return the VAT number prefix for the given country
	 *
	 * @param String $country
	 *
	 * @return String - the prefix
	 */
	public function get_vat_number_prefix($country) {
	
		$vat_prefix = $country;

		// Deal with exceptions
		switch ($country) {
			case 'IM' :
				$vat_prefix = 'GB';
			break;
		}

		return $vat_prefix;
	}
	
}
