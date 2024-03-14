<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access.');

// Purpose: Official HMRC exchange rates: https://www.trade-tariff.service.gov.uk/exchange_rates (until Oct 2023, at https://www.gov.uk/government/collections/exchange-rates-for-customs-and-vat)

// Methods: info(), convert($from_currency, $to_currency, $amount, $the_time = false), settings_fields(), test()

if (!class_exists('WC_VAT_Compliance_Rate_Provider_base_xml')) require_once(WC_VAT_COMPLIANCE_DIR.'/rate-providers/base-xml.php');

// Conditional execution to deal with bugs on some old PHP versions with classes that extend classes not known until execution time
if (1==1):
class WC_VAT_Compliance_Rate_Provider_hmrc_uk extends WC_VAT_Compliance_Rate_Provider_base_xml {
		
	protected $rate_base_currency = 'GBP';

	# The rates change monthly
	protected $force_refresh_rates_every = 2678400;
	protected $force_refresh_on_new_month = true;

	protected $key = 'hmrc_uk';

	public function info() {
		return array(
			'title' => __('HM Revenue & Customs (UK)', 'woocommerce-eu-vat-compliance'),
			'url' => 'https://www.trade-tariff.service.gov.uk/exchange_rates',
			'description' => __('Official exchange rates from HM Revenue & Customs (UK).', 'woocommerce-eu-vat-compliance')
		);
	}

	protected function get_base_url($the_time = false) {
		if (false == $the_time) $the_time = time();
		$full_year = gmdate('Y', $the_time);
		
		if ($full_year >= 2021) {
			return 'https://www.trade-tariff.service.gov.uk/api/v2/exchange_rates/files/';
		}
		
		return 'http://www.hmrc.gov.uk/softwaredevelopers/rates/';
		
	}
	
	protected function get_leaf($the_time = false) {
		if (false == $the_time) $the_time = time();

		$mon = gmdate('m', $the_time);
		$yer = gmdate('y', $the_time);
		$full_year = gmdate('Y', $the_time);

		if ($yer >= 21) {
			// N.B. These years also have a different leaf
			// 'n' is the month number without the leading zero
			return 'monthly_xml_'.$full_year.'-'.gmdate('n', $the_time).'.xml';
		} elseif ($yer >= 15) {
			// Jan 2015: Just why did they do this? Grrr...
			// Feb 2015: Again!!
			// Mar 2015: Uses previous (Dec 2014) format, so change order to save cycles.
			return array(
				"exrates-monthly-$mon$yer.xml",
				"excrates_monthly$mon$yer.xml",
				"exrates_monthly_$mon$yer.xml",
			);
		} else {
			return "exrates-monthly-$mon$yer.xml";
		}
	}

	public function get_current_conversion_rate_from_time($currency, $the_time = false) {

		$parsed = $this->populate_rates_parsed_xml($the_time);
		if (empty($parsed)) return false;

		foreach ($parsed as $cur) {
			if (isset($cur->currencyCode) && $currency == $cur->currencyCode && isset($cur->rateNew)) {
				return (float)$cur->rateNew;
			}
		}

		return false;
	}

}
endif;
