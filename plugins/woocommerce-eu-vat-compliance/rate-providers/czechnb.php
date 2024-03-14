<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access.');

// Purpose: Official Czech National Bank exchange rates: e.g. https://www.cnb.cz/cs/financni_trhy/devizovy_trh/kurzy_devizoveho_trhu/denni_kurz.txt?date=04.10.2017

// Methods: info(), convert($from_currency, $to_currency, $amount, $the_time = false), settings_fields(), test()

if (!class_exists('WC_VAT_Compliance_Rate_Provider_base_generic')) require_once(WC_VAT_COMPLIANCE_DIR.'/rate-providers/base-generic.php');

// Conditional execution to deal with bugs on some old PHP versions with classes that extend classes not known until execution time
if (1==1):
class WC_VAT_Compliance_Rate_Provider_czechnb extends WC_VAT_Compliance_Rate_Provider_base_generic {

	protected $getbase = 'https://www.cnb.cz/cs/financni_trhy/devizovy_trh/kurzy_devizoveho_trhu/denni_kurz.txt';

	protected $rate_base_currency = 'CZK';

	// The rates change daily, but I have no information upon at what time. 21600 = 6 hours.
	protected $force_refresh_rates_every = 21600;

	protected $key = 'czechnb';

	public function info() {
		return array(
			'title' => __('Czech National Bank', 'woocommerce-eu-vat-compliance'),
			'url' => 'http://www.cnb.cz/cs/financni_trhy/devizovy_trh/kurzy_devizoveho_trhu/denni_kurz.jsp',
			'description' => __('Official exchange rates from the Czech National Bank (CNB).', 'woocommerce-eu-vat-compliance')
		);
	}

	public function get_current_conversion_rate_from_time($currency, $the_time = false) {

		$rates_url = $this->getbase;
		
		if (false == $the_time) $the_time = time();
		
		$is_now = (time() - $the_time < 100) ? true : false;
	
		if ($is_now) {
			$last_updated_option_name = $this->get_option_name_stub()."_last_updated";
			$last_data_option_name = $this->get_option_name_stub()."_last_data";

			$last_updated = get_site_option($last_updated_option_name);
			if (empty($last_updated)) $last_updated = 0;
			$last_data = get_site_option($last_data_option_name);

			if (!empty($last_data) && $last_updated + $this->force_refresh_rates_every >= time()) {
				$body = $last_data;
			}
		}

		if (empty($body)) {
	
			// e.g. ?date=04.10.2017
			$rates_url .= '?date='.gmdate('%d.%m.%Y', $the_time);
			
			$rates = $this->wp_remote_get($rates_url);
		
			if (is_wp_error($rates)) {
				error_log("WooCommerce VAT: Error fetching $rates_url: ".$rates->get_error_message());
				return false;
			}
			
			$body = wp_remote_retrieve_body($rates);
			
		}
		
		if (empty($body)) return false;

		$retrieved = true;
		
		$lines = explode("\n", $body);
		
		/*
		 * Example format
		 *
		 * 04.10.2017 #191
		 * země|měna|množství|kód|kurz
		 * Austrálie|dolar|1|AUD|17,277
		 * Brazílie|real|1|BRL|7,014
		 * ...
		 */

		$unrecognised = 0;
		foreach ($lines as $line) {
		
			$elements = explode('|', $line);
			
			if (5 != count($elements) && count($elements) > 0) {
				$unrecognised++;
				if (2 == $unrecognised) error_log("WooCommerce VAT: Unrecognised line from czechnb: $line");
				continue;
			}
			
			if ($currency != $elements[3]) continue;
			
			$value = (float)str_replace(',', '.', $elements[4]);
			
			if ($retrieved && $is_now) $this->save($body);
			
			return ($value > 0) ? 1/$value : $value;
		}
		
		return false;
	}

}
endif;
