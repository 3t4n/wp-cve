<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access.');

// Get rates from an XML source

// Classes extending this one need to implement: info(), get_current_conversion_rate_from_time() or get_current_conversion_rate(), get_leaf()

if (!class_exists('WC_VAT_Compliance_Rate_Provider_base_generic')) require_once(WC_VAT_COMPLIANCE_DIR.'/rate-providers/base-generic.php');

// Note that the rate provider key when extending this class should be kept short, as it is used in option names
abstract class WC_VAT_Compliance_Rate_Provider_base_xml extends WC_VAT_Compliance_Rate_Provider_base_generic {

	protected function get_option_name_stub() {
		return "wcev_xml_".$this->key;
	}
	
	protected function get_leaf($the_time) {
		return '';
	}
	
	// This function is intended to always return something, if possible - in the worst-case scenario, we fall back on whatever rates we got last, even if out-of-date. This is because people need *some* exchange rate to be recorded, and relying on the online services being up when a transient expires is too risky.
	protected function populate_rates_parsed_xml($the_time) {

		$last_updated_option_name = $this->get_option_name_stub()."_last_updated";
		$last_data_option_name = $this->get_option_name_stub()."_last_data";

		$xml_last_updated = get_site_option($last_updated_option_name);
		if (empty($xml_last_updated)) $xml_last_updated = 0;
		$xml_last_data = get_site_option($last_data_option_name);

		$last_updated_month = gmdate('m', $xml_last_updated);
		// N.B. This assumes that $the_time is in the current month - which is currently a correct assumption ($the_time is only for possible future expansion)
		$this_month = gmdate('m', $the_time);

		if (empty($xml_last_data) || (!empty($this->force_refresh_on_new_month) && $last_updated_month != $this_month) || $xml_last_updated + $this->force_refresh_rates_every <= time()) {

			$url_base = method_exists($this, 'get_base_url') ? $this->get_base_url($the_time) : $this->getbase;
			$url = $this->get_leaf($the_time);
			if (is_string($url)) $url = array($url);

			foreach ($url as $u) {
				if (!empty($new_xml)) continue;
				$fetch_url = $url_base.$u;
				$fetched = $this->wp_remote_get($fetch_url);
				if (!is_wp_error($fetched)) {
					if (!empty($fetched['response']) || $fetched['response']['code'] < 300) {
						$new_xml = $fetched['body'];
						if (strpos($new_xml, '<!DOCTYPE HTML') !== false) unset($new_xml);
					}
				}
			}
			if (empty($new_xml)) {
				// Try yesterday, in case we have a timezone issue, or data not yet uploaded, etc.
				$backup_url = $this->get_leaf($the_time - 86400);
				if (is_string($backup_url)) $backup_url = array($backup_url);
// 				if ($url != $backup_url) {
					// Always try again, in case the failure was transient
					foreach ($backup_url as $u) {
						if (!empty($new_xml)) continue;
						$fetched = $this->wp_remote_get($url_base.$u);
						if (!is_wp_error($fetched)) {
							if (!empty($fetched['response']) || $fetched['response']['code'] < 300) $new_xml = $fetched['body'];
							if (isset($new_xml) && false !== strpos(ltrim($new_xml), '<!DOCTYPE HTML')) {
								error_log("Exchange rates URL ($fetch_url) provided non-empty data, but looks like HTML rather than XML: ".substr($new_data, 0, 100));
								unset($new_xml);
							}
						}
					}
// 				}
			}

			if (empty($new_xml) && false != ($on_disk_file = apply_filters('wc_eu_vat_'.$this->key.'_file', false, $the_time)) && file_exists($on_disk_file)) {
				$new_xml = file_get_contents($on_disk_file);
			}
		}

		if (empty($new_xml) && empty($xml_last_data)) return false;

		if (!empty($new_xml)) {
			// Does it parse?
			$new_data = simplexml_load_string($new_xml);
			if ($new_data) {
				update_site_option($last_data_option_name, $new_xml);
				update_site_option($last_updated_option_name, time());
				return $new_data;
			} else {
				error_log("Exchange rates URL ($fetch_url) provided non-empty data, but did not parse: ".substr($new_xml, 0, 140));
			}
		}

		return simplexml_load_string($xml_last_data);

	}

}
