<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access.');

class WC_VAT_Region_eu extends WC_VAT_Region {

	/**
	 * Get a list of countries that define the region
	 *
	 * @return Array
	 */
	public function get_countries() {
	
		$eu_countries = WC()->countries->get_european_union_countries();
		// Monaco is not a member of the EU, but is part of a common VAT area with France
		$eu_countries[] = 'MC';
		return $eu_countries;
		
	}
	
	/**
	 * Return the title for the region
	 *
	 * @param String $context - 'noun', 'adjective'
	 *
	 * @return String
	 */
	public function get_region_title($context) {
		if ('adjective' == $context) return __('EU', 'woocommerce-eu-vat-compliance');
		return __('the EU', 'woocommerce-eu-vat-compliance');
	}
	
	/**
	 * Get an array listing the minimum number of characters in a valid VAT number for the region's countries (and a default)
	 *
	 * @return Array
	 */
	public function map_country_codes_to_minimum_characters() {
		// https://www.gov.uk/vat-eu-country-codes-vat-numbers-and-vat-in-other-languages
		return array(
			'RO' => 2,
			'CZ' => 8,
			'DK' => 8,
			'FI' => 8,
			'HU' => 8,
			'MT' => 8,
			'LU' => 8,
			'SI' => 8,
			'IE' => 8,
			'PL' => 10,
			'SK' => 10,
			'HR' => 11,
			'FR' => 11,
			'MC' => 11,
			'IT' => 11,
			'LV' => 11,
			'NL' => 12,
			'SE' => 12,
			// All others
			'default' => 9
		);
	}

	/**
	 * Given a VAT number in unspecified format, canonicalise it. This includes removing any standard country prefix.
	 *
	 * @param String $vat_number - the VAT number
	 * @param String $country	 - country the VAT number is intended for, if already definitely known
	 *
	 * @return String
	 */
	public function standardise_vat_number($vat_number, $country = '') {
	
		// Format the number canonically (remove spaces, hyphens, underscores, periods; make upper-case)
		$vat_number = parent::standardise_vat_number($vat_number);
	
		// Remove country prefix; including two possibilities for which the VAT prefix differs from the country code
		if (in_array(substr($vat_number, 0, 2), array_merge($this->get_countries(), array('EL', 'GB')))) {
			$vat_number = substr($vat_number, 2);
		}

		// https://www.gov.uk/vat-eu-country-codes-vat-numbers-and-vat-in-other-languages
		if ('BE' == $country && 9 == strlen($vat_number)) $vat_number = '0'.$vat_number;
	
		return $vat_number;
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
			case 'GR' :
				$vat_prefix = 'EL';
			break;
			case 'MC' :
				$vat_prefix = 'FR';
			break;
		}

		return $vat_prefix;
	}
	
}
