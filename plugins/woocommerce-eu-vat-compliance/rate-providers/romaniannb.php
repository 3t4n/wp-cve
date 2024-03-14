<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access.');

// Purpose: Official Romanian National Bank exchange rates

// Methods: info(), convert($from_currency, $to_currency, $amount, $the_time = false), settings_fields(), test()

if (!class_exists('WC_VAT_Compliance_Rate_Provider_base_xml')) require_once(WC_VAT_COMPLIANCE_DIR.'/rate-providers/base-xml.php');

// Conditional execution to deal with bugs on some old PHP versions with classes that extend classes not known until execution time
if (1==1):
class WC_VAT_Compliance_Rate_Provider_romaniannb extends WC_VAT_Compliance_Rate_Provider_base_xml {

	// e.g. https://www.bnr.ro/files/xml/years/nbrfxrates2022.xml
	protected $getbase = 'https://www.bnr.ro/files/xml/years/nbrfxrates';

	protected $rate_base_currency = 'RON';

	// The rates change daily, and are published at approx 1pm CET. Setting something less than 9 hours ensures that we will get the latest rates before the end of the day. 21600 = 6 hours.
	protected $force_refresh_rates_every = 21600;

	protected $key = 'romaniannb';

	public function info() {
		return array(
			'title' => __('Romanian National Bank', 'woocommerce-eu-vat-compliance'),
			'url' => 'https://www.bnr.ro/Exchange-rates-15192.aspx',
			'description' => __('Official exchange rates from the Romanian National Bank.', 'woocommerce-eu-vat-compliance')
		);
	}
	
	protected function get_leaf($the_time) {
		$the_year = gmdate('Y', $the_time);
		return $the_year.'.xml';
	}

	public function get_current_conversion_rate_from_time($currency, $the_time = false) {
		$the_date = gmdate('Y-m-d', $the_time);

		$parsed = $this->populate_rates_parsed_xml($the_time);
		if (empty($parsed)) return false;
		
		$most_recent_date_found = 0;
		$most_recent_value = false;

		if (is_object($parsed) && isset($parsed->Body) && isset($parsed->Body->Cube)) {
			foreach ($parsed->Body->Cube as $cube) {

				$date_matches = (isset($cube['date']) && $cube['date'] == $the_date);
				
				$date_found = strtotime($cube['date']);
				if ($date_found > $most_recent_date_found) $most_recent_date_found = $date_found;
				
				foreach ($cube as $rate) {
					
					$xml_currency = $rate['currency'];
					if (strtoupper($xml_currency) !== strtoupper($currency)) continue;
					
					$value = $rate->__tostring();
					
					if (!empty($rate['multiplier'])) $value = $value / $rate['multiplier'];
					
					if ($date_matches) return (0 == $value) ? false : 1/$value;
					
					if ($most_recent_date_found == $date_found) $most_recent_value = (0 == $value) ? false : 1/$value;
					
				}
				
			} 
		}

		return $most_recent_value;
	}
	
	/**
	 * @param String $url - the URL to fetch
	 *
	 * @return Array
	 */
	protected function wp_remote_get($url) {
		
		// The Romanian National Bank server rejected the requests when "PHP/" appeared in the user agent, so we have a modified user agent here not including that
		$http_options = array(
			'timeout' => 15,
			'user-agent' => 'WooCommerce VAT Compliance/'.WooCommerce_EU_VAT_Compliance()->get_version().' WooCommerce/'.WC_VERSION
		);
		
		return wp_remote_get($url, $http_options);
		
	}

}
endif;
