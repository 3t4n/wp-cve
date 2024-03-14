<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access.');

// Purpose: Official Norwegian National Bank exchange rates: e.g. https://www.norges-bank.no/en/topics/Statistics/exchange_rates/?tab=api

// Methods: info(), convert($from_currency, $to_currency, $amount, $the_time = false), settings_fields(), test()

if (!class_exists('WC_VAT_Compliance_Rate_Provider_base_generic')) require_once(WC_VAT_COMPLIANCE_DIR.'/rate-providers/base-generic.php');

// Conditional execution to deal with bugs on some old PHP versions with classes that extend classes not known until execution time
if (1==1):
class WC_VAT_Compliance_Rate_Provider_norwaycb extends WC_VAT_Compliance_Rate_Provider_base_generic {

	// Append things like: startPeriod=2021-06-25&endPeriod=2021-07-02
	protected $getbase = 'https://data.norges-bank.no/api/data/EXR/B..NOK.SP?format=csv&bom=include&locale=en';

	protected $rate_base_currency = 'DKK';

	// 21600 = 6 hours.
	protected $force_refresh_rates_every = 21600;

	protected $key = 'norwaycb';

	/**
	 * @return Array
	 */
	public function info() {
		return array(
			'title' => __('Norges Bank (central bank of Norway)', 'woocommerce-eu-vat-compliance'),
			'url' => 'https://www.norges-bank.no/en/topics/Statistics/exchange_rates/?tab=api',
			'description' => __('Official exchange rates from the Norwegian National Bank (Norges Bank).', 'woocommerce-eu-vat-compliance')
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
		
		$retrieved = false;

		if (empty($body)) {
	
			$date = gmdate('Y-m-d', $the_time);
			$yesterday = gmdate('Y-m-d', $the_time - 86400);
	
			// e.g. startPeriod=2021-06-25&endPeriod=2021-07-02
			$rates_url .= '&startPeriod='.$yesterday.'&endPeriod='.$date;

			$rates = $this->wp_remote_get($rates_url);
		
			if (is_wp_error($rates)) {
				error_log("WooCommerce VAT: Error fetching $rates_url: ".$rates->get_error_message());
				return false;
			}
			
			$body = wp_remote_retrieve_body($rates);
			
			$retrieved = true;
			
		}
		
		if (empty($body)) return false;
		
		$lines = explode("\n", $body);
		
		/*
		 * Example format
		 *
			FREQ;Frequency;BASE_CUR;Base Currency;QUOTE_CUR;Quote Currency;TENOR;Tenor;DECIMALS;CALCULATE
			D;UNIT_MULT;Unit Multiplier;COLLECTION;Collection Indicator;TIME_PERIOD;OBS_VALUE
			B;Business;AUD;Australian dollar;NOK;Norwegian krone;SP;Spot;4;false;0;Units;C;ECB concertati
			on time 14:15 CET;2021-06-25;6.4454
			B;Business;AUD;Australian dollar;NOK;Norwegian krone;SP;Spot;4;false;0;Units;C;ECB concertati
			on time 14:15 CET;2021-06-28;6.4599
			B;Business;AUD;Australian dollar;NOK;Norwegian krone;SP;Spot;4;false;0;Units;C;ECB concertati
			on time 14:15 CET;2021-06-29;6.4444

		 * ...
		 */

		$unrecognised = 0;
		$first_line = true;
		$headings = array();
		$result = false;
		
		foreach ($lines as $line) {
		
			$elements = explode(';', $line);
			
			if (count($elements) < 3) continue;
			
			if ($first_line) {
				$first_line = false;
				$headings = $elements;
				$needed = array('BASE_CUR', 'QUOTE_CUR', 'TIME_PERIOD', 'OBS_VALUE');
				foreach ($needed as $need_this) {
					if (!in_array($need_this, $headings)) {
						error_log("WooCommerce VAT: Unrecognised header line from norwaycb (missing: $need_this): $line");
						return false;
					}
				}
				$base = array_search('BASE_CUR', $headings);
				$quote = array_search('QUOTE_CUR', $headings);
				$time_period = array_search('TIME_PERIOD', $headings);
				$value = array_search('OBS_VALUE', $headings);
				continue;
			}
			
			if ($retrieved && $is_now) $this->save($body);
			if ($currency != $elements[$base] || 'NOK' != $elements[$quote]) continue;
			
			// We don't check the time period; we rely upon the returned data being for the period requested
			
			if (!empty($elements[$value])) $result = 1/$elements[$value];
			
			// Let the loop go round again - get the latest time entry in the file
			
		}
		
		return $result;
	}

}
endif;
