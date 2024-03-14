<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access.');

class WC_VAT_Region_norway extends WC_VAT_Region {

	/**
	 * Get a list of countries that define the region
	 *
	 * @return Array
	 */
	public function get_countries() {
		return array('NO');
	}
	
	/**
	 * Return the title for the region
	 *
	 * @param String $context - 'noun', 'adjective'
	 *
	 * @return String
	 */
	public function get_region_title($context) {
		if ('adjective' == $context) return __('Norwegian', 'woocommerce-eu-vat-compliance');
		return __('Norway', 'woocommerce-eu-vat-compliance');
	}

	/**
	 * Get an array listing the minimum number of characters in a valid VAT number for the region's countries (and a default)
	 *
	 * @return Integer
	 */
	public function map_country_codes_to_minimum_characters() {
		// https://www.avalara.com/vatlive/en/eu-vat-rules/eu-vat-number-registration/eu-vat-number-formats.html - thre it is stated that the minimum length is 12, but user feedback indicates that it's common to omit the "MVA" suffix; and the services we've tested on handle the number without this.
		return array('default' => 9);
	}
	
}
