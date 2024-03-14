<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access.');

// Methods: info(), convert($from_currency, $to_currency, $amount, $the_time = false), settings_fields()

// Classes extending this one need to implement: info(), get_current_conversion_rate_from_time()

// Not yet used. Planned to use with: http://www.cnb.cz/cs/financni_trhy/devizovy_trh/kurzy_devizoveho_trhu/denni_kurz.txt?date=04.10.2017

// Note that the rate provider key when extending this class should be kept short, as it is used in option names
abstract class WC_VAT_Compliance_Rate_Provider_base_generic {

	// Default to hourly
	protected $force_refresh_rates_every = 3600;
	
	/**
	 * Perform a currency conversion
	 *
	 * @param String $from_currency
	 * @param String $to_currency
	 * @param Number $amount
	 * @param Integer|Boolean $the_time
	 *
	 * @return Number|Boolean - returns false on failure
	 */
	public function convert($from_currency, $to_currency, $amount, $the_time = false) {
		if (empty($amount)) return 0;
		if ($from_currency == $to_currency) return $amount;

		// Get the value of 1 unit of base currency
		$rate = $this->get_current_conversion_rate($to_currency, $the_time);

		if ($this->rate_base_currency == $from_currency) {
			return $amount * $rate;
		}

		if (false === $rate) return false;

		$rate2 = $this->get_current_conversion_rate($from_currency, $the_time);

		if (0 == $rate2) return false;

		if ($this->rate_base_currency == $to_currency) {
			return $amount / $rate2;
		}

		return $amount * ($rate / $rate2);
	}

	/**
	 * @return Array - with keys 'title' (a descriptive title string, shown to the user), 'url' (string, used as a user-shown link) and 'description (a description string, shown to the user)
	 */
	abstract public function info();
	
	/**
	 * @param String $currency - the currency code to convert from (relative to this method's 'base currency')
	 * @param Integer|Boolean $the_time - UNIX epoch time, or false for now
	 *
	 * @return Float|Boolean - the rate, or false if no conversion could be performed
	 */
	abstract protected function get_current_conversion_rate_from_time($currency, $the_time = false);
	
	public function settings_fields() {
		return '';
		// $info = $this->info(); // Use $info['url'] ?
		//return __('Using this rates provider requires no configuration.', 'woocommerce-eu-vat-compliance');
	}

	/**
	 * @return String
	 */
	protected function get_option_name_stub() {
		return "wcev_rates_".$this->key;
	}
	
	/**
	 * Get the time at which rates were last updated
	 *
	 * @return Integer - a timestamp (epoch time), or zero if not present/other
	 */
	public function get_last_updated() {
		$last_updated_option_name = $this->get_option_name_stub()."_last_updated";
		$last_updated = get_site_option($last_updated_option_name);
		if (empty($last_updated)) $last_updated = 0;
		return $last_updated;
	}

	protected function get_current_conversion_rate($currency, $the_time = false) {
		if ($this->rate_base_currency == $currency) return 1;
		
		if (false == $the_time) $the_time = time();

		return $this->get_current_conversion_rate_from_time($currency, $the_time);
	}
	
	protected function save($data) {
		$option_name_stub = $this->get_option_name_stub();
		update_site_option($option_name_stub."_last_updated", time());
		update_site_option($option_name_stub."_last_data", $data);
	}
	
	/**
	 * @param String $url - the URL to fetch
	 * @return Array
	 */
	protected function wp_remote_get($url) {
	
		// Some time in Feb 2017, HMRC (or their CDN) started blocking user agents that begin with WordPress/
		$http_options = array(
			'timeout' => 15,
			'user-agent' => 'WooCommerce VAT Compliance/'.WooCommerce_EU_VAT_Compliance()->get_version().' WooCommerce/'.WC_VERSION.' PHP/'.PHP_VERSION
		);

		return wp_remote_get($url, $http_options);
		
	}

}
