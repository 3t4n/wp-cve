<?php
/**
 * Purpose of this file: boot-strap the plugin. Also contains the main class.
 */

if (!defined('ABSPATH')) die('Access denied.');

if (class_exists('WC_EU_VAT_Compliance')) return;

define('WC_VAT_COMPLIANCE_DIR', dirname(__FILE__));
define('WC_VAT_COMPLIANCE_URL', plugins_url('', __FILE__));

$active_plugins = (array) get_option( 'active_plugins', array() );
if (is_multisite()) $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));

if (!in_array('woocommerce/woocommerce.php', $active_plugins ) && !array_key_exists('woocommerce/woocommerce.php', $active_plugins)) return;

// This plugin performs various distinct functions. So, we have separated the code accordingly.
// Not all of these files may be present, depending on whether this is the free or premium version or not
if ((!defined('WC_VAT_COMPLIANCE_FREE_ONLY') || !WC_VAT_COMPLIANCE_FREE_ONLY) && file_exists(WC_VAT_COMPLIANCE_DIR.'/vat-number.php')) {
	include_once(WC_VAT_COMPLIANCE_DIR.'/vat-number.php');
}

include_once(WC_VAT_COMPLIANCE_DIR.'/record-order-details.php');

if (file_exists(WC_VAT_COMPLIANCE_DIR.'/rates.php')) include_once(WC_VAT_COMPLIANCE_DIR.'/rates.php');

if (file_exists(WC_VAT_COMPLIANCE_DIR.'/includes/widgets.php')) include_once(WC_VAT_COMPLIANCE_DIR.'/includes/widgets.php');

if (file_exists(WC_VAT_COMPLIANCE_DIR.'/preselect-country.php')) include_once(WC_VAT_COMPLIANCE_DIR.'/preselect-country.php');

if ((!defined('WC_VAT_COMPLIANCE_FREE_ONLY') || !WC_VAT_COMPLIANCE_FREE_ONLY) && file_exists(WC_VAT_COMPLIANCE_DIR.'/premium.php')) {
	include_once(WC_VAT_COMPLIANCE_DIR.'/premium.php');
}

// Though the code is separated, some pieces are inter-dependent; the order also matters. So, don't assume you can just change this arbitrarily.
$potential_classes_to_activate = array(
	'WC_EU_VAT_Compliance',
	'WC_EU_VAT_Compliance_VAT_Number',
	'WC_EU_VAT_Compliance_Record_Order_Details',
	'WC_EU_VAT_Compliance_Rates',
	'WC_EU_VAT_Country_PreSelect_Widget',
	'WC_VAT_Compliance_Preselect_Country',
	'WC_EU_VAT_Compliance_Premium',
);

if (is_admin() || (defined('DOING_CRON') && DOING_CRON) || (defined('WC_VAT_LOAD_ALL_CLASSES') && WC_VAT_LOAD_ALL_CLASSES)) {
	include_once(WC_VAT_COMPLIANCE_DIR.'/reports.php');
	include_once(WC_VAT_COMPLIANCE_DIR.'/control-centre.php');
	$potential_classes_to_activate[] = 'WC_EU_VAT_Compliance_Reports';
	$potential_classes_to_activate[] = 'WC_EU_VAT_Compliance_Control_Centre';
}

$classes_to_activate = apply_filters('woocommerce_eu_vat_compliance_classes', $potential_classes_to_activate);

if (!class_exists('WC_EU_VAT_Compliance')):
class WC_EU_VAT_Compliance {

	private $default_vat_matches = 'VAT, V.A.T, IVA, I.V.A., Value Added Tax, TVA, T.V.A., BTW, B.T.W., Moms';
	public $wc;

	public $settings;

	private $wcpdf_order_id;

	public $data_sources = array();
	
	/**
	 * Plugin constructor
	 */
	public function __construct() {

		$this->data_sources = array(
			'HTTP_CF_IPCOUNTRY' => __('Cloudflare Geo-Location', 'woocommerce-eu-vat-compliance'),
			'woocommerce' => __('WooCommerce built-in geo-location', 'woocommerce-eu-vat-compliance'),
			'geoip_detect_get_info_from_ip_function_not_available' => __('MaxMind GeoIP database was not installed', 'woocommerce-eu-vat-compliance'),
			'geoip_detect_get_info_from_ip' => __('MaxMind GeoIP database', 'woocommerce-eu-vat-compliance'),
			'aelia-migrated' => __('Aelia EU VAT Assistant', 'woocommerce-eu-vat-compliance'),
		);

		add_action('before_woocommerce_init', array($this, 'before_woocommerce_init'), 1, 1);
		add_action('plugins_loaded', array($this, 'plugins_loaded'), 11);

		add_action('woocommerce_settings_tax_options_end', array($this, 'woocommerce_settings_tax_options_end'));
		add_action('woocommerce_update_options_tax', array( $this, 'woocommerce_update_options_tax'));

		add_filter('network_admin_plugin_action_links', array($this, 'plugin_action_links'), 10, 2);
		add_filter('plugin_action_links', array($this, 'plugin_action_links'), 10, 2);

		add_action('wpo_wcpdf_process_template_order', array($this, 'wpo_wcpdf_process_template_order'), 10, 2);

		add_filter('wpo_wcpdf_footer_settings_text', array($this, 'wpo_wcpdf_footer'), 10, 2);

		add_action('woocommerce_check_cart_items', array($this, 'woocommerce_check_cart_items'));
		add_action('woocommerce_checkout_process', array($this, 'woocommerce_check_cart_items'));
	
		// These are heavy-handed, downgrade the WooCommerce experience for everyone, and thereby harm the whole ecosystem
		add_filter('woocommerce_allow_marketplace_suggestions', '__return_false', 20);
		
		add_option('woocommerce_eu_vat_compliance_reporting_override', array());
		
		add_action('plugins_loaded', array($this, 'load_updater'), 0);
		
	}
	
	/**
	 * Runs upon the WP action plugins_loaded
	 */
	public function load_updater() {
		if (file_exists(WC_VAT_COMPLIANCE_DIR.'/wpo_update.php')) {
			require(WC_VAT_COMPLIANCE_DIR.'/wpo_update.php');
		} elseif (file_exists(WC_VAT_COMPLIANCE_DIR.'/updater.php')) {
			require(WC_VAT_COMPLIANCE_DIR.'/updater.php');
		}
	}
	
	/**
	 * Runs upon the WP action woocommerce_checkout_update_order_review. We use it to store information in the session if appropriate.
	 *
	 * @param String $form_data
	 */
	public function ajax_update_checkout_totals($form_data) {
		
		parse_str($form_data, $parsed_form_data);

		if (empty($parsed_form_data['billing_country']) && empty($parsed_form_data['shipping_country'])) return;

		if (empty($parsed_form_data['billing_state'])) $parsed_form_data['billing_state'] = '';
		if (empty($parsed_form_data['shipping_state'])) $parsed_form_data['shipping_state'] = '';
		if (empty($parsed_form_data['billing_country'])) $parsed_form_data['billing_country'] = '';
		if (empty($parsed_form_data['shipping_country'])) $parsed_form_data['shipping_country'] = '';
		
		$tax_based_on = get_option('woocommerce_tax_based_on');

		if ('shipping' == $tax_based_on && !$this->wc->cart->needs_shipping()) $tax_based_on = 'billing';

		if ('shipping' == $tax_based_on && empty($parsed_form_data['ship_to_different_address'])) $tax_based_on = 'billing';

		switch ($tax_based_on) {
			case 'billing':
			case 'base':
				$country = empty($parsed_form_data['billing_country']) ? '' : $parsed_form_data['billing_country'];
				$state = empty($parsed_form_data['billing_state']) ? '' : $parsed_form_data['billing_state'];
			break;
			case 'shipping':
				$country = empty($parsed_form_data['shipping_country']) ? $parsed_form_data['billing_country'] : $parsed_form_data['shipping_country'];
				$state = empty($parsed_form_data['shipping_state']) ? '' : $parsed_form_data['shipping_state'];
			break;
		}

		$this->wc->session->set('vat_country_checkout', $country);
		$this->wc->session->set('eu_vat_state_checkout', $state);
		
	}
	
	/**
	 * Get the conversion provider configured in the settings
	 *
	 * @param String|Country $for_country - if specified, then 
	 *
	 * @return String
	 */
	public function get_conversion_provider($for_country = null) {
	
		$conversion_provider = get_option('woocommerce_eu_vat_compliance_exchange_rate_provider', 'ecb');
	
		$overrides = (null === $for_country) ? array() : get_option('woocommerce_eu_vat_compliance_reporting_override', array());
		foreach ($overrides as $override) {
			if ($override['country'] === $for_country) {
				$conversion_provider = $override['provider'];
				break;
			}
		}
	
		return apply_filters('wc_eu_vat_get_conversion_provider', $conversion_provider, $for_country);
	}
	
	/**
	 * Gets a list of the recording currencies configured in the settings
	 *
	 * @param String	  $context	   - either 'recording' or 'reporting'; this affects what filter is used
	 * @param String|Null $for_country - if specified (an ISO code), then instead of always returning the default, also process per-country over-rides
	 *
	 * @return Array - numerically/sequentially indexed, starting from zero
	 */
	public function get_vat_recording_currencies($context = 'recording', $for_country = null) {
	
		$recording_currencies = get_option('woocommerce_eu_vat_compliance_vat_recording_currency');
		
		$overrides = get_option('woocommerce_eu_vat_compliance_reporting_override', array());
		foreach ($overrides as $override) {
			if ($override['country'] === $for_country) {
				$recording_currencies = array($override['currency']);
				break;
			}
		}
		
		$filter_to_use = ('recording' == $context) ? 'wc_eu_vat_vat_recording_currencies' : 'wc_eu_vat_vat_reporting_currency';
		$recording_currencies = apply_filters($filter_to_use, $recording_currencies, $for_country);
		
		if (empty($recording_currencies)) $recording_currencies = array();
		
		if (!is_array($recording_currencies)) $recording_currencies = array($recording_currencies);

		return array_values($recording_currencies);
	
	}
	
	/**
	 * Get a list of all unique VAT region codes (i.e. composite regions are omitted)
	 *
	 * @return Array
	 */
	public function get_vat_region_codes() {
		return array('eu', 'uk', 'norway');
	}
	
	/**
	 * Get a list of all unique VAT region codes (i.e. composite regions are omitted)
	 *
	 * @param String $context	- passed on to the get_region_title() method
	 * @param Boolean $uc_first - put the first character of each title in upper-case
	 *
	 * @return Array - keys are codes, and values are titles
	 */
	public function get_vat_region_codes_and_titles($context = 'noun', $uc_first = false) {
		$region_codes = $this->get_vat_region_codes();
		$results = array();
		foreach ($region_codes as $region_code) {
			$region = $this->get_vat_region_object($region_code);
			$results[$region_code] = $this->mb_ucfirst($region->get_region_title($context));
		}
		return $results;
	}
	
	/**
	 * Internationalised version of ucfirst()
	 *
	 * @param String $input
	 *
	 * @return String
	 */
	private function mb_ucfirst($input) {
		if (!function_exists('mb_substr') || !function_exists('mb_strtoupper')) return ucfirst($input);
		return mb_strtoupper(mb_substr($input, 0, 1)).mb_substr($input, 1);
	}
	
	/**
	 * Get the internal VAT region code for a specified country, or the "home" VAT region code(s)
	 *
	 * @param String|Null $country
	 *
	 * @return String|Array|Null - if a country is specified, then a string is returned; otherwise, a list (which may have zero, one or more entries)
	 */
	public function get_vat_regions($country = null) {
	
		if (null === $country) {
			$regions = get_option('woocommerce_eu_vat_compliance_vat_region', array('eu'));
			// New format (1.25.0+)
			if (is_array($regions)) return $regions;
			// Legacy format
			if ('uk_only' === $regions) return array('uk');
			if ('eu_and_uk' === $regions) return array('eu', 'uk');
			if ('norway' === $regions) return array('norway');
			return array('eu');
		}
		
		$region_codes = $this->get_vat_region_codes();
		
		foreach ($region_codes as $region_code) {
			if (in_array($country, $this->get_vat_region_object($region_code)->get_countries())) return $region_code;
		}
		
		return null;
		
	}
	
	/**
	 * Get the VAT region code to use when looking up VAT numbers for a specified country
	 *
	 * @param String $country	 - must be specified
	 *
	 * @return String|Null
	 */
	public function get_vat_region_when_looking_up_number_for($country) {
	
		$all_region_codes = $this->get_vat_region_codes();
		$matched_region_code = null;
	
		foreach ($all_region_codes as $region_code) {
			$region = $this->get_vat_region_object($region_code);
			if (in_array($country, $region->get_countries())) $matched_region_code = $region_code;
		}
	
		return apply_filters('wc_eu_vat_get_vat_region_when_looking_up_number_for', $matched_region_code, $country);
	}
	
	/**
	 * Get all available VAT number lookup services
	 *
	 * @return Array - a list of WC_VAT_Number_Lookup_Service objects
	 */
	public function get_vat_number_lookup_services() {
	
		// VAT Sense is listed last because accounts are limited by numbers of API lookups, so the others are prefered.
		$service_ids = apply_filters('wc_vat_get_vat_number_lookup_service_ids', array('hmrc', 'vies', 'vatsense'));
	
		$services = array();
	
		foreach ($service_ids as $service_id) {
			$services[$service_id] = $this->get_vat_number_lookup_service($service_id);
		}
		
		return $services;
	
	}
	
	/**
	 * Get a VAT number lookup object
	 *
	 * @param String  $service - service identifier (e.g. 'hmrc', 'vies')
	 * @param Boolean $require - if true, then failures will be fatal
	 *
	 * @return WC_VAT_Number_Lookup_Service|Boolean
	 */
	public function get_vat_number_lookup_service($service, $require = true) {
	
		static $number_lookup_objects = array();
		
		if (!class_exists('WC_VAT_Number_Lookup_Service')) require_once(WC_VAT_COMPLIANCE_DIR.'/number-lookups/lookup-service.php');

		if (empty($number_lookup_objects[$service])) {
		
			$lookup_service_class = 'WC_VAT_Number_Lookup_Service_'.$service;
			
			if (!class_exists($lookup_service_class)) {
				if ($require) {
					require_once(WC_VAT_COMPLIANCE_DIR.'/number-lookups/'.$service.'.php');
				} else {
					include_once(WC_VAT_COMPLIANCE_DIR.'/number-lookups/'.$service.'.php');
				}
			}
		
			$number_lookup_objects[$service] = class_exists($lookup_service_class) ? new $lookup_service_class : false;
		
		}
		
		return $number_lookup_objects[$service];
	}
	
	/**
	 * Get a VAT region object
	 *
	 * @param String  $region_code
	 * @param Boolean $require - if true, then failures will be fatal
	 *
	 * @return WC_VAT_Region|Boolean
	 */
	public function get_vat_region_object($region_code, $require = true) {
	
		static $region_objects = array();
		
		if (!class_exists('WC_VAT_Region')) require_once(WC_VAT_COMPLIANCE_DIR.'/regions/vat-region.php');

		if (empty($region_objects[$region_code])) {
		
			$region_class = 'WC_VAT_Region_'.$region_code;
			
			if (!class_exists($region_class)) {
				if ($require) {
					require_once(WC_VAT_COMPLIANCE_DIR.'/regions/'.$region_code.'.php');
				} else {
					include_once(WC_VAT_COMPLIANCE_DIR.'/regions/'.$region_code.'.php');
				}
			}
		
			$region_objects[$region_code] = class_exists($region_class) ? new $region_class : false;
		
		}
		
		return $region_objects[$region_code];
	}
	
	/**
	 * Get the store VAT ID, for a given region (or any, if not specified)
	 * Also, by funnelling all access to the option woocommerce_eu_vat_store_id through this variable, we can ensure it gets upgraded to the >= 1.17.7 format.
	 *
	 * @param String|Null $region
	 *
	 * @return String|Null
	 */
	public function get_store_vat_number($region = null) {
	
		$vat_id = get_option('woocommerce_eu_vat_store_id', null);
	
		if (!is_array($vat_id)) {
			$first_two = strtolower(substr(ltrim($vat_id), 0, 2));
			if ('gb' === $first_two || 'im' === $first_two) {
				$save_region = 'uk';
			} elseif (preg_match('#^[A-Z][A-Z]#i', trim($vat_id)) && 'gb' !== $first_two && 'im' !== $first_two && 'eu' != $region) {
				$save_region = 'eu';
			} else {
				$save_region = $this->get_vat_regions($this->wc->countries->get_base_country());
			}
			$new_option = ('' == $vat_id) ? array() : array($save_region => $vat_id);
			update_option('woocommerce_eu_vat_store_id', $new_option);
			if ($region !== $save_region) $vat_id = null;
		} else {
			// Read out the array value
			if (null === $region) {
				$regions = $this->get_vat_regions();
				foreach ($regions as $region) {
					if (isset($vat_id[$region])) {
						$vat_id = $vat_id[$region];
					}
				}
			} else {
				if (!empty($vat_id)) {
					$vat_id = isset($vat_id[$region]) ? $vat_id[$region] : null;
				}
			}
			if (!is_string($vat_id)) $vat_id = null;
		}
	
		return $vat_id;
	
	}
	
	/**
	 * Perform a VAT number look-up/validation. This is the preferred method for doing so (i.e. do not call the method with a region object directly)
	 *
	 * @param String  $country		- the country code for the VAT number (not necessarily the same as the VAT prefix)
	 * @param String  $vat_number	- the VAT number (already canonicalised), minus any country prefix
	 * @param Boolean $force_simple	- force a non-extended lookup, even if in the saved options there is a VAT ID for the store
	 *
	 * N.B. The return format has to be kept in sync with that for WC_VAT_Region::validate_vat_number()
	 *
	 * @return Array - keys are:
	 * (boolean) 'validated'  - whether a definitive result was obtained
	 * (boolean) 'valid'	  - if 'validated' is true, then this contains the validation result (otherwise, undefined)
	 * (string)	 'error_code' - if 'validated' is false, this contains an error code
	 * (string)	 'error_message' - is set if, and only if, there was an error_code
	 * (mixed)	 'data'		  - data - usually the raw result from the network
	 */
	public function validate_vat_number($country, $vat_number, $force_simple = false) {
	
		if (null !== ($filter_result = apply_filters('wc_vat_validate_vat_number', null, $country, $vat_number, $force_simple))) {
			return $filter_result;
		}

		$region_code = $this->get_vat_region_when_looking_up_number_for($country);
		
		if (null === $region_code) {
			return array(
				'validated' => false,
				'data' => 'unsupported_region',
			);
		}
		
		$region_object = $this->get_vat_region_object($region_code);
	
		$xi_special_case = false;
		// Special case: XI (Northern Ireland; and can be looked up on EU-based services as well as HMRC)
		if ('gb' == strtolower($country) && preg_match('/^xi/i', $vat_number)) {
			$xi_special_case = true;
			$vat_number = substr($vat_number, 2);
		}
	
		$vat_prefix = $xi_special_case ? 'XI' : $region_object->get_vat_number_prefix($country);
	
		static $first_result = null;
	
		$vat_number_lookup_objects = $this->get_vat_number_lookup_services();
		foreach ($vat_number_lookup_objects as $lookup_service_id => $lookup_object) {
			$supported_region_codes = $lookup_object->get_supported_region_codes();
			// XI can be looked up by EU providers as well as the default UK one
			if (!in_array($region_code, $supported_region_codes) && (!$xi_special_case || !in_array('eu', $supported_region_codes))) continue;
			$result = $lookup_object->get_validation_result_from_network($vat_prefix, $vat_number, $force_simple);
			if (null === $first_result) $first_result = $result;
			if (!empty($result['validated'])) {
				if ($xi_special_case && !in_array('uk', $supported_region_codes)) $region_code = 'eu';
				$result['service_used'] = $lookup_service_id;
				$result['region_used'] = $region_code;
				return $result;
			}
		}
			
		if (null === $first_result) {
			return array(
				'validated' => false,
				'data' => 'unsupported_region',
			);
		}
		
		$first_result['region_used'] = $region_code;
		
		// We prefer the first result because we attempt the official services first, and in particular don't want an error about having no third-party service configuration to be the thing that goes through to the user (which will mislead them about the cause of a total failure to contact validation services).
		return $first_result;
	
	}

	/**
	 * This method returns a (possibly empty) list of which regions to forbid checkout to when non-zero VAT is payable. It also abstracts away a change in format (in version 1.18.3) for the woocommerce_eu_vat_compliance_forbid_vatable_checkout option (from a boolean to a region list)
	 *
	 * @return Array
	 */
	public function forbid_vat_checkout_to_which_regions() {
	
		$regions = get_option('woocommerce_eu_vat_compliance_forbid_vatable_checkout', array());
		
		// Convert from legacy format
		if (is_string($regions)) {
			$regions = ('no' === $regions) ? array() : $this->get_vat_regions(null, true);
			update_option('woocommerce_eu_vat_compliance_forbid_vatable_checkout', $regions);
		}
		
		return $regions;
	}
	
	/**
	 * Get a list of countries for the VAT area(s)
	 *
	 * @param Array|String|Null $regions - region, or list of regions; if null, then used saved settings
	 *
	 * @return Array
	 */
	public function get_vat_region_countries($regions = null) {
	
		if (null === $regions) $regions = $this->get_vat_regions();
	
		if (is_string($regions)) return $this->get_vat_region_object($regions)->get_countries();
		
		$countries = array();
		foreach ($regions as $region) {
			$countries = array_merge($countries, $this->get_vat_region_object($region)->get_countries());
		}
		
		return array_values($countries);
	}
	
	/**
	 * Get the VAT region title
	 *
	 * @param String $context	  - 'noun', 'adjective'
	 * @param String $region_code - code
	 *
	 * @return String
	 */
	public function get_vat_region_title($context, $region_code) {
		return $this->get_vat_region_object($region_code)->get_region_title($context);
	}
	
	/**
	 * An abstraction function, allowing alteration of the base country, or multiple base countries (and implementing the fact that FR/MC and GB/IM are single VAT zones)
	 *
	 * @return Array
	 */
	public function get_base_countries() {
	
		$base_countries = array($this->wc->countries->get_base_country());
		
		// Countries which form unified VAT zones (such that transactions between one and the other should be counted as domestic supplies)
		$add_base_countries = array(
			'MC' => 'FR',
			'FR' => 'MC',
			'IM' => 'GB',
			'GB' => 'IM'
		);
		
		foreach ($add_base_countries as $country => $add_country) {
			if (in_array($country, $base_countries) && !in_array($add_country, $base_countries)) $base_countries[] = $add_country;
		}
		
		// Should return a numerically indexed array, beginning from 0. The first element should be the one considered most primary, should such a concept be needed anywhere.
		return apply_filters('wc_eu_vat_get_base_countries', $base_countries);
	
	}
	
	/**
	 * Get the taxable region code for the current customer
	 *
	 * @return String
	 */
	public function get_customer_taxable_region_code() {
	
		$taxable_address = $this->get_taxable_address();
		
		$taxation_country = (isset($taxable_address[0]) && is_string($taxable_address[0]) && '' !== $taxable_address[0]) ? $taxable_address[0] : $this->wc->countries->get_base_country();
		
		return $this->get_vat_regions($taxation_country);
	
	}
	
	/**
	 * This method checks that the cart's contents are not forbidden by configuration
	 *
	 * @return Boolean
	 */
	public function cart_is_permitted() {
	
		$cart = $this->wc->cart->get_cart();

		$is_permitted = !$this->product_list_has_relevant_products($cart);

		return apply_filters('wceuvat_cart_is_permitted', $is_permitted);
	
	}
	
	/**
	 * This method checks that the product list's contents are not forbidden by configuration
	 *
	 * @param Array $list - list of product items
	 *
	 * @return Boolean
	 */
	public function product_list_has_relevant_products($list) {
	
		$opts_classes = $this->get_region_vat_tax_classes();

		$has_relevant_products = false;
		$relevant_products_found = false;

		foreach ($list as $item) {
			if (empty($item['data'])) continue;
			$_product = $item['data'];
			$tax_status = $_product->get_tax_status();
			if ('taxable' != $tax_status) continue;
			$tax_class = $_product->get_tax_class();
			if (empty($tax_class)) $tax_class = 'standard';
			if (in_array($tax_class, $opts_classes)) {
				$has_relevant_products = true;
				break;
			}
		}
		
		return apply_filters('wceuvat_product_list_product_list_has_relevant_products', $has_relevant_products, $relevant_products_found, $list);
	
	}

	/**
	 * Runs upon the WP actions woocommerce_check_cart_items and woocommerce_checkout_process
	 *
	 * If VAT checkout is forbidden, then this function is where the work is done to prevent it
	 */
	public function woocommerce_check_cart_items() {

		// WooCommerce 3.0 runs both woocommerce_check_cart_items and woocommerce_checkout_process, which results in duplicate notices.
		static $we_already_did_this = false;
		if ($we_already_did_this) return;
		$we_already_did_this = true;
	
		// If taxes are not active on the store, then there's nothing more to do
		if ('yes' != get_option('woocommerce_calc_taxes')) return;
	
		// If the region is not in the forbidden list, then we can stop checking now
		$region_of_country = $this->get_customer_taxable_region_code();
		if (!in_array($region_of_country, $this->forbid_vat_checkout_to_which_regions())) return;
	
		if (!$this->cart_is_permitted()) {
			// If in cart, then warn - they still may select a different VAT country.
			$current_filter = current_filter();
			
			$region_object =  $this->get_vat_region_object($region_of_country);

			if ('woocommerce_checkout_process' != $current_filter && (!defined('WOOCOMMERCE_CHECKOUT') || !WOOCOMMERCE_CHECKOUT)) {
				// Cart: just warn
				echo "<p class=\"woocommerce-info\" id=\"wcvat_notpossible\">".apply_filters('wceuvat_euvatcart_message', sprintf(__('Depending on your country, it may not be possible to purchase all the items in this cart. This is because this store does not sell items liable to VAT to customers in %s.', 'woocommerce-eu-vat-compliance'), $region_object->get_region_title('noun')))."</p>";
			} else {
				// Attempting to check-out: prevent
				wc_add_notice(
					apply_filters('wceuvat_euvatcheckoutforbidden_message', sprintf(__('This order cannot be processed. Due to the costs of complying with VAT laws in %s, we do not sell items liable to VAT to customers in that VAT area.', 'woocommerce-eu-vat-compliance'), $region_object->get_region_title('noun'))), 'error'
				);
			}
		}

	}

	/**
	 * Get the taxable address for the specified or current customer
	 *
	 * @param WP_User|Boolean $customer - false to get the current customer
	 *
	 * @return Array
	 */
	public function get_taxable_address($customer = false) {

		$tax = new WC_Tax();
		if (false === $customer && !empty($this->wc->customer)) $customer = $this->wc->customer;

		if (method_exists($tax, 'get_tax_location')) {
			$taxable_address = $tax->get_tax_location($customer);
		} elseif (method_exists($customer, 'get_taxable_address')) {
			$taxable_address = $customer->get_taxable_address();
		} else {
			$taxable_address = array();
		}

		return $taxable_address;
	}
	
	/**
	 * Get a list of tax class identifiers and titles
	 *
	 * @return Array
	 */
	public function get_tax_classes() {
	
		$tax = new WC_Tax();
		$tax_classes = $tax->get_tax_classes();

		$classes_by_title = array('standard' => __('Standard Rate', 'woocommerce-eu-vat-compliance'));

		foreach ($tax_classes as $class) {
			$classes_by_title[sanitize_title($class)] = $class;
		}

		return $classes_by_title;
	}

	/**
	 * Get a list of tax classes for which the place of supply is the customer location
	 *
	 * @param Array|Boolean -  an array of slugs of default tax classes, if you have one; otherwise (if false), one will be obtained from self::get_tax_classes();
	 *
	 * @return Array
	 */
	public function get_region_vat_tax_classes($default = false) {

		if (false === $default) $default = array_keys($this->get_tax_classes());

		// Apply a default value, for if this is not set (people upgrading)
		$opts_classes = get_option('woocommerce_eu_vat_compliance_tax_classes', $default);

		return is_array($opts_classes) ? $opts_classes : $default;
	}

	/**
	 * Find out whether a particular product or tax class has variable VAT
	 *
	 * @param $product_or_tax_class String|WC_Product
	 *
	 * @return Boolean
	 */
	public function product_taxable_class_indicates_buyer_country_variable_vat($product_or_tax_class) {
		if (is_a($product_or_tax_class, 'WC_Product') && 'taxable' != $product_or_tax_class->get_tax_status()) return false;
		
		static $vat_classes = null;
		
		if (null == $vat_classes) $vat_classes = $this->get_region_vat_tax_classes();
		
		$tax_class = (is_a($product_or_tax_class, 'WC_Product')) ? $product_or_tax_class->get_tax_class() : $product_or_tax_class;
		
		// WC's handling of the 'default' tax class is rather ugly/non-intuitive - you need the secret knowledge of its name
		if (empty($tax_class)) $tax_class = 'standard';
		
		return in_array($tax_class, $vat_classes);
	}

	/**
	 * Given an order, get the customer's most likely region
	 *
	 * This uses a variety of methods to cope with historical changes in what is stored
	 *
	 * @param WC_Order|WC_Order_Refund $order
	 *
	 * @return String
	 */
	public function get_region_code_from_order($order) {

		if (is_a($order, 'WC_Order_Refund')) {
			$order = wc_get_order($order->get_parent_id());
		}
		
		$vies_data = $order->get_meta('VIES Response');
		if (!empty($vies_data)) return 'eu';
		
		$lookup_response = $order->get_meta('vat_lookup_response');
		if (!empty($lookup_response['region_used'])) return $lookup_response['region_used'];
		
		// None of the above helps if there was no VAT number lookup
		
		$country_info = $order->get_meta('vat_compliance_country_info', true);
		$vat_paid = $this->get_vat_paid($order, true, true);
		
		$has_variable_vat = !empty($vat_paid['total']);
		$has_any_vat = !empty($vat_paid['total']);
		if (!$has_variable_vat && !empty($vat_paid['by_rates'])) {
			foreach ($vat_paid['by_rates'] as $vat_info) {
				if (!empty($vat_info['items_total']) || !empty($vat_info['shipping_total'])) $has_any_vat = true;
				if (!empty($vat_info['is_variable_eu_vat'])) $has_variable_vat = true;
			}
		}
		
		// We used to do the next call in woocommerce_checkout_create_order, but when per-country exchange rate recording was introduced, we needed the info on taxes from record_meta_vat_paid() to be able to get the country right
		if (isset($country_info['taxable_address'])) $taxable_address = $country_info['taxable_address'];
		if (!empty($taxable_address[0])) $taxable_country = $taxable_address[0];
		
		if (!$has_variable_vat && $has_any_vat) {
			// If there was no variable VAT, then the place of supply was the shop base
			if (!empty($vat_paid['base_country'])) {
				$taxable_country = $vat_paid['base_country'];
			} else {
				$base_countries = array_values($this->get_base_countries());
				$taxable_country = array($base_countries[0]);
			}
		}

		// Get region from taxable country, or use a default
		if (isset($taxable_country)) {
			$vat_region = $this->get_vat_regions($taxable_country);
			if (is_array($vat_region)) $vat_region = array_pop($vat_region);
		} else {
			$regions = $this->get_vat_regions();
			$vat_region = is_array($regions) ? array_pop($regions) : 'eu';
		}
		
		return $vat_region;
	}
	
	/**
	 * WP filter wpo_wcpdf_footer_settings_text (was wpo_wcpdf_footer prior to 1.13.16 - see WPO HS#19569)
	 *
	 * @param String	  - pre-filter footer text
	 * @param Object|Null - Order_Document
	 *
	 * @return String
	 */
	public function wpo_wcpdf_footer($footer, $order_document = null) {

		$valid_vat_number = null;
		$vat_number_validated = null;
		$vat_number = null;
		$vat_paid = array();
		$new_footer = $footer;
		$text = '';
		$order = null;

		if (!empty($order_document) && !empty($order_document->order) && is_a($order_document->order, 'WC_Order')) {
			$order = $order_document->order;
		} elseif (!empty($this->wcpdf_order_id)) {
			$order = wc_get_order($this->wcpdf_order_id);
		}

		if (!empty($order) && is_a($order, 'WC_Order')) {

			$vat_paid = $this->get_vat_paid($order, true, true);

			$valid_vat_number = $order->get_meta('Valid VAT Number', true);
			$vat_number_validated = $order->get_meta('VAT number validated', true);
			$vat_number = $order->get_meta('VAT Number', true);
			
			// !empty used, because this is only for non-zero VAT
			if (is_array($vat_paid) && !empty($vat_paid['total'])) {
				$text = get_option('woocommerce_eu_vat_compliance_pdf_footer_b2c');

				if (!empty($text)) {
					$new_footer = wpautop(wptexturize($text)).$footer;
				}
			}

		}

		return apply_filters('wc_euvat_compliance_wpo_wcpdf_footer', $new_footer, $footer, $text, $vat_paid, $vat_number, $valid_vat_number, $vat_number_validated, $order);
	}

	public function wpo_wcpdf_process_template_order($template_id, $order_id) {
		$this->wcpdf_order_id = $order_id;
	}

	public function enqueue_jquery_ui_style() {
		global $wp_scripts;
		$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.9.2';
		wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/' . $jquery_version . '/themes/smoothness/jquery-ui.css', array(), WC_VERSION );
	}

	/**
	 * Get the plugin version
	 *
	 * @return String
	 */
	public function get_version() {

		static $version = null;
	
		if (null !== $version) return $version;

		$file = $this->is_premium() ? WC_VAT_COMPLIANCE_DIR.'/eu-vat-compliance-premium.php' : WC_VAT_COMPLIANCE_DIR.'/eu-vat-compliance.php';
		
		if ($fp = fopen($file, 'r')) {
			$file_data = fread($fp, 1024);
			if (preg_match("/Version: ([\d\.]+)(\r|\n)/", $file_data, $matches)) {
				$version = $matches[1];
			}
			fclose($fp);
		}

		return $version;
	}

	// Returns normalised data
	public function get_vat_matches($format = 'array') {
		$matches = get_option('woocommerce_eu_vat_compliance_vat_match', $this->default_vat_matches);
		if (!is_string($matches) || empty($matches)) $matches = $this->default_vat_matches;
		$arr = array_map('trim', explode(',', $matches));
		if ('regex' == $format) {
			$ret = '#(';
			foreach ($arr as $str) {
				$ret .= ($ret == '#(') ? preg_quote($str) : '|'.preg_quote($str);
			}
			$ret .= ')#i';
			return $ret;
		} elseif ('html-printable' == $format) {
			$ret = '';
			foreach ($arr as $str) {
				$ret .= ($ret == '') ? htmlspecialchars($str) : ', '.htmlspecialchars($str);
			}
			return $ret;
		} elseif ('sqlregex' == $format) {
			$ret = '';
			foreach ($arr as $str) {
				$ret .= ($ret == '') ? esc_sql($str) : '|'.esc_sql($str);
			}
			return $ret;
		}
		return $arr;
	}

	/**
	 * This function is for output - it will add on conversions into the indicate currencies
	 *
	 * @param Number  		 $amount
	 * @param Array			 $conversion_currencies
	 * @param Array			 $conversion_rates
	 * @param String		 $order_currency
	 * @param Boolean|String $paid
	 * @param Boolean		 $include_space
	 *
	 * @return String
	 */
	public function get_amount_in_conversion_currencies($amount, $conversion_currencies, $conversion_rates, $order_currency, $paid = false) {
		foreach ($conversion_currencies as $currency) {
			$rate = ($currency == $order_currency) ? 1 : (isset($conversion_rates['rates'][$currency]) ? $conversion_rates['rates'][$currency] : '??');
			if ('??' == $rate) continue;

			if ($paid !== false) {
				$paid .= ' / ';
			} else {
				$paid = '';
			}
			$paid .= get_woocommerce_currency_symbol($currency).' '.sprintf('%.02f', $amount * $rate);
		}
		return $paid;
	}

	/**
	 * @param Integer|WC_Order $order - Pass in a WC_Order object, or an order number
	 * @param Boolean $allow_quick - allow re-use of an already set/saved value
	 * @param Boolean $set_on_quick
	 * @param Boolean $quick_only
	 *
	 * @return Array|Boolean
	 */
	public function get_vat_paid($order, $allow_quick = false, $set_on_quick = false, $quick_only = false) {

		if (!is_a($order, 'WC_Order') && is_numeric($order)) $order = wc_get_order($order);
		
		if (!is_object($order)) return false;

		$order_id = $order->get_id();

		static $vat_paid_info = null;
		static $vat_paid_post_id = null;
		
		if ($allow_quick) {
			if (!empty($vat_paid_post_id) && $vat_paid_post_id == $order_id && !empty($vat_paid_info)) {
				$vat_paid = $vat_paid_info;
			} else {
				$vat_paid = $order->get_meta('vat_compliance_vat_paid', true);
			}
			if (!empty($vat_paid)) {
				$vat_paid = maybe_unserialize($vat_paid);
				// If by_rates is not set, then we need to update the version of the data by including that data asap
				if (isset($vat_paid['by_rates'])) return $vat_paid;
			}
			if ($quick_only) return false;
		}

		// This is the wrong approach, kept for the purposes of illustration only. What we actually need to do is to take the rate ID, and see what table that comes from. Tables are 1:1 in relationship with classes; thus, certain rate IDs just don't count.
		/*
		$items = $order->get_items();
		if (empty($items)) return false;

		foreach ($items as $item) {
			if (!is_array($item)) continue;
			$tax_class = (empty($item['tax_class'])) ? 'standard' : $item['tax_class'];
			if (!$this->product_taxable_class_indicates_buyer_country_variable_vat($tax_class)) {
				// New-style EU VAT does not apply to this product - do something
				
			}
		}
*/

		$taxes = $order->get_taxes();

		if (empty($taxes)) $taxes = array();

		// Get an array of string matches
		$vat_strings = $this->get_vat_matches('regex');

		// Not get_woocommerce_currency(), as currency switcher plugins filter that.
		$base_currency = get_option('woocommerce_currency');

		$currency = $order->get_currency();

		$vat_total = 0;
		$vat_shipping_total = 0;
		$vat_total_base_currency = 0;
		$vat_shipping_total_base_currency = 0;

		// N.B. In WC 3.0+, what is returned is an array of WC_Order_Item_Tax objects; that class implements a deprecated array-access interface; to avoid deprecation notices, we convert to an array
		$taxes = $this->convert_taxes_to_array($taxes);
		
		// Add extra information
		$taxes = $this->add_tax_rates_details($taxes);

		$by_rates = array();

		// Some amendments here in versions 1.5.5+ inspired by Diego Zanella
		foreach ($taxes as $tax) {

			// There used to be a !is_array($tax) check here - that fails on WC 2.7+, as we are now dealing with WC_Order_Item_Tax objects (with an array interface)
			if (!isset($tax['label']) || !preg_match($vat_strings, $tax['label'])) continue;
			
			$tax_rate_class = empty($tax['tax_rate_class']) ? 'standard' : $tax['tax_rate_class'];

			$is_country_of_consumption_vat = $this->product_taxable_class_indicates_buyer_country_variable_vat($tax_rate_class);

			$tax_rate_id = $tax['rate_id'];
			
			if (!isset($by_rates[$tax_rate_id])) {
				$by_rates[$tax_rate_id] = array(
					'is_variable_eu_vat' => $is_country_of_consumption_vat,
					'items_total' => 0,
					'shipping_total' => 0,
				);
				$by_rates[$tax_rate_id]['rate'] = $tax['tax_rate'];
				$by_rates[$tax_rate_id]['name'] = $tax['tax_rate_name'];
			}

			if (!empty($tax['tax_amount'])) $by_rates[$tax_rate_id]['items_total'] += $tax['tax_amount'];
			if (!empty($tax['shipping_tax_amount'])) $by_rates[$tax_rate_id]['shipping_total'] += $tax['shipping_tax_amount'];

			if ($is_country_of_consumption_vat) {
				if (!empty($tax['tax_amount'])) $vat_total += $tax['tax_amount'];
				if (!empty($tax['shipping_tax_amount'])) $vat_shipping_total += $tax['shipping_tax_amount'];

				// TODO: Remove all base_currency stuff from here - instead, we are using conversions at reporting time
				if ($currency != $base_currency) {
					if (empty($tax['tax_amount_base_currency'])) {
						// This will be wrong, of course, unless your conversion rate is 1:1
						if (!empty($tax['tax_amount'])) $vat_total_base_currency += $tax['tax_amount'];
						if (!empty($tax['shipping_tax_amount'])) $vat_shipping_total_base_currency += $tax['shipping_tax_amount'];
					} else {
						if (!empty($tax['tax_amount'])) $vat_total_base_currency += $tax['tax_amount_base_currency'];
						if (!empty($tax['shipping_tax_amount'])) $vat_shipping_total_base_currency += $tax['shipping_tax_amount_base_currency'];
					}
				} else {
					$vat_total_base_currency = $vat_total;
					$vat_shipping_total_base_currency = $vat_shipping_total;
				}
			}
		}

		// We may as well return the kitchen sink, since we've spent the cycles on getting it.
		$vat_paid = apply_filters('wc_vat_compliance_get_vat_paid', array(
			'by_rates' => $by_rates,
			'items_total' => $vat_total,
			'shipping_total' => $vat_shipping_total,
			'total' => $vat_total + $vat_shipping_total,
			'currency' => $currency,
			'base_country' => $this->wc->countries->get_base_country(), // Recorded at order-time since this can be changed later
			'base_currency' => $base_currency,
			'items_total_base_currency' => $vat_total_base_currency,
			'shipping_total_base_currency' => $vat_shipping_total_base_currency,
			'total_base_currency' => $vat_total_base_currency + $vat_shipping_total_base_currency,
		), $order, $taxes, $currency, $base_currency);

/*
e.g. (and remember, there may be other elements which are not VAT).

Array
(
    [62] => Array
        (
            [name] => GB-VAT (UNITED KINGDOM)-1
            [type] => tax
            [item_meta] => Array
                (
                    [rate_id] => Array
                        (
                            [0] => 28
                        )

                    [label] => Array
                        (
                            [0] => VAT (United Kingdom)
                        )

                    [compound] => Array
                        (
                            [0] => 1
                        )

                    [tax_amount_base_currency] => Array
                        (
                            [0] => 2
                        )

                    [tax_amount] => Array
                        (
                            [0] => 3.134
                        )

                    [shipping_tax_amount_base_currency] => Array
                        (
                            [0] => 2.8
                        )

                    [shipping_tax_amount] => Array
                        (
                            [0] => 4.39
                        )

                )

            [rate_id] => 28
            [label] => VAT (United Kingdom)
            [compound] => 1
            [tax_amount_base_currency] => 2
            [tax_amount] => 3.134
            [shipping_tax_amount_base_currency] => 2.8
            [shipping_tax_amount] => 4.39
        )


*/
		if ($set_on_quick) {
			$order->update_meta_data('vat_compliance_vat_paid', $vat_paid);
			$order->save();
		}

		$vat_paid_post_id = $order_id;
		$vat_paid_info = $vat_paid;

		return $vat_paid;

	}

	/**
	 * @param Array $input - array of WC_Order_Item_Tax items
	 *
	 * @return Array - converted to array format
	 */
	private function convert_taxes_to_array($input) {
		
		// The loop below also adds some other keys whose values come via other means
		$keys_we_want = array('tax_rate_class', 'tax_rate', 'tax_rate_name', 'tax_amount_base_currency', 'shipping_tax_amount_base_currency');
		
		$output = array();
		
		foreach ($input as $id => $tax) {
			$output[$id]['rate_id'] = $tax->get_rate_id();
			$output[$id]['label'] = $tax->get_label();
			$output[$id]['tax_amount'] = $tax->get_tax_total();
			$output[$id]['shipping_tax_amount'] = $tax->get_shipping_tax_total();
			foreach ($keys_we_want as $key) {
				$output[$id][$key] = $tax->get_meta($key);
			}
		}
		
		return $output;
	}
	
	// This is here as a funnel that can be changed in future, without needing to adapt everywhere that calls it
	public function round_amount($amount) {
		return apply_filters('wc_eu_vat_compliance_round_amount', round((float) $amount, 2), $amount);
	}

	/**
	 * This method is lightly adapted from the work of Diego Zanella
	 *
	 * @param Array $taxes
	 *
	 * @return Array
	 */
	protected function add_tax_rates_details($taxes) {
		global $wpdb, $table_prefix; // Not relevant to HPOS

		if (empty($taxes) || !is_array($taxes)) return $taxes;

		$tax_rate_ids = array();
		// Keep track of which tax ID corresponds to which ID within the order.
		foreach ($taxes as $order_tax_id => $tax) {
			// $tax would be an WC_Order_Item_Tax (but implements deprecated array access) if passed straight from WC_Order->get_taxes()
			// This information will be used to add the new information to the correct elements in the $taxes array
			$tax_rate_ids[(int)$tax['rate_id']] = $order_tax_id;
		}

		// No reason to record these here
		// 				,TR.tax_rate_country
		// 				,TR.tax_rate_state
		$sql = "
			SELECT
				TR.tax_rate_id
				,TR.tax_rate
				,TR.tax_rate_class
				,TR.tax_rate_name
			FROM
				".$table_prefix."woocommerce_tax_rates TR
			WHERE
				(TR.tax_rate_id IN (%s))
		";
		// We cannot use $wpdb::prepare(). We need the result of the implode()
		// call to be injected as is, while the prepare() method would wrap it in quotes.
		$sql = sprintf($sql, implode(',', array_keys($tax_rate_ids)));

		// Populate the original tax array with the tax details
		$tax_rates_info = $wpdb->get_results($sql, ARRAY_A);
		foreach ($tax_rates_info as $tax_rate_info) {
			// Find to which item the details belong, amongst the order taxes
			$order_tax_id = (int)$tax_rate_ids[$tax_rate_info['tax_rate_id']];
			
// 			$taxes[$order_tax_id]->update_meta_data('tax_rate', $tax_rate_info['tax_rate']);
// 			$taxes[$order_tax_id]->update_meta_data('tax_rate_name', $tax_rate_info['tax_rate_name']);
// 			$taxes[$order_tax_id]->update_meta_data('tax_rate_class', $tax_rate_info['tax_rate_class']);
			
			// Legacy array access style - deprecated since WC 4.4
			$taxes[$order_tax_id]['tax_rate'] = $tax_rate_info['tax_rate'];
			$taxes[$order_tax_id]['tax_rate_name'] = $tax_rate_info['tax_rate_name'];
			$taxes[$order_tax_id]['tax_rate_class'] = $tax_rate_info['tax_rate_class'];
			
			// Not needed
 			// $taxes[$order_tax_id]['tax_rate_country'] = $tax_rate_info['tax_rate_country'];
 			// $taxes[$order_tax_id]['tax_rate_state'] = $tax_rate_info['tax_rate_state'];

			// Attach the tax information to the original array, for convenience
			$taxes[$order_tax_id]['tax_info'] = $tax_rate_info;
// 			$taxes[$order_tax_id]->update_meta_data('tax_info', $tax_rate_info);
		}

		return $taxes;
	}

	/**
	 * Return either a list of rate exchange provider objects, or just a single specified object
	 *
	 * @param String|Boolean $just_this_one
	 *
	 * @return Array|Object
	 */
	public function get_rate_providers($just_this_one = false) {
		$provider_dirs = apply_filters('wc_eu_vat_rate_provider_dirs', array(WC_VAT_COMPLIANCE_DIR.'/rate-providers'));
		$classes = array();
		foreach ($provider_dirs as $dir) {
			$providers = apply_filters('wc_eu_vat_rate_providers_from_dir', false, $dir);
			if (false === $providers) {
				$providers = scandir($dir);
				foreach ($providers as $k => $file) {
					if ('.' == $file || '..' == $file || '.php' != strtolower(substr($file, -4, 4)) || 'base-' == strtolower(substr($file, 0, 5)) || !is_file($dir.'/'.$file)) unset($providers[$k]);
				}
			}
			foreach ($providers as $file) {
				$key = str_replace('-', '_', sanitize_title(basename(strtolower($file), '.php')));
				$class_name = 'WC_VAT_Compliance_Rate_Provider_'.$key;
				if (!class_exists($class_name)) include_once($dir.'/'.$file);
				if (class_exists($class_name)) $classes[$key] = new $class_name;
			}
		}
		if ($just_this_one) {
			return isset($classes[$just_this_one]) ? $classes[$just_this_one] : false;
		}
		return $classes;
	}

	/**
	 * Invoked by the WP filters plugin_action_links and network_admin_plugin_action_links
	 *
	 * @param Array	 $links
	 * @param String $files
	 *
	 * @return Array
	 */
	public function plugin_action_links($links, $file) {
		if (!is_array($links) || false === strpos($file, basename(WC_VAT_COMPLIANCE_DIR).'/eu-vat-compliance')) return $links;
		
		$settings_link = '<a href="'.admin_url('admin.php?page=wc_eu_vat_compliance_cc').'">'.__('VAT Compliance Dashboard', "woocommerce-eu-vat-compliance").'</a>';
		array_unshift($links, $settings_link);
		if (false === strpos($file, 'premium')) {
			$settings_link = '<a href="https://www.simbahosting.co.uk/s3/product/woocommerce-eu-vat-compliance/">'.__('Premium Version', 'woocommerce-eu-vat-compliance').'</a>';
			array_unshift($links, $settings_link);
		}

		return $links;
	}

	/**
	 * Called upon the WP action woocommerce_settings_tax_options_end
	 */
	public function woocommerce_settings_tax_options_end() {
		//woocommerce_admin_fields($this->settings);
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<?php _e('VAT-related settings', 'woocommerce-eu-vat-compliance');?>
			</th>
			<td>
				<?php printf(__('There are further tax-related settings in your %s', 'woocommerce-eu-vat-compliance'), '<a href="'.admin_url('admin.php?page=wc_eu_vat_compliance_cc').'">'.__('VAT Compliance dashboard', "woocommerce-eu-vat-compliance").'</a>');?>
			</td>
		</tr>
		<?php
		$this->enqueue_admin_js();
	}

	/**
	 * Enqueue admin-area JavaScript
	 */
	public function enqueue_admin_js() {
		$selected_recording_currency = get_option('woocommerce_eu_vat_cart_vat_exempt_above_currency');
		if (is_array($selected_recording_currency) && !empty($selected_recording_currency)) {
			$selected_recording_currency = array_values($selected_recording_currency);
			$selected_recording_currency = $selected_recording_currency[0];
		} elseif (!is_string($selected_recording_currency)) {
			// Format pre-1.19.8 - when unset, it was boolean false
			$selected_recording_currency = get_option('woocommerce_eu_vat_compliance_vat_recording_currency');
		}
		if (!is_string($selected_recording_currency)) $selected_recording_currency = get_option('woocommerce_currency');
		
		wp_enqueue_script('wc-vat-admin-common', WC_VAT_COMPLIANCE_URL.'/js/admin.js', array(), filemtime(WC_VAT_COMPLIANCE_DIR.'/js/admin.js'));
		
		wp_localize_script('wc-vat-admin-common', 'wc_vat_compliance', apply_filters('woocommerce_vat_compliance_admin_localisations', array(
			'delete_this_override' => __('Delete this over-ride...', 'woocommerce-eu-vat-compliance'),
			'in_region_use_policy' => __('For the %s VAT region, instead use the policy:', 'woocommerce-eu-vat-compliance'),
			'vat_number_policies' => array(
				'permit' => __('Permit', 'woocommerce-eu-vat-compliance'),
				'never' => __('Never', 'woocommerce-eu-vat-compliance'),
				'require' => __('Require', 'woocommerce-eu-vat-compliance'),
				'require_dependent_upon_cart' => __('Require conditionally', 'woocommerce-eu-vat-compliance'),
			),
			'selected_recording_currency' => $selected_recording_currency,
			'delete_this_translation_rule' => __('Delete this translation rule...', 'woocommerce-eu-vat-compliance'),
			'tax_class_list' => $this->get_tax_classes(),
			'tax_class_translation' => __('For products in the %s taxation class  being sold to a customer in the %s VAT region (but outside of your store base country), if the total year-to-date sales to that region is below %s, change the product taxation class to %s', 'woocommerce-eu-vat-compliance'),
			'currency_list' => $this->get_currency_code_options(),
			'region_list' => $this->get_vat_region_codes_and_titles('adjective', true),
		)));
	}
	
	/**
	 * Get a list of currency code options suitable for creating a drop-down with
	 *
	 * @return Array - keyed by currency code
	 */
	public function get_currency_code_options() { 
		$currency_code_options = get_woocommerce_currencies();
		
		foreach ($currency_code_options as $code => $name) {
			$currency_code_options[$code] = $name;
			$symbol = get_woocommerce_currency_symbol($code);
			if ($symbol) $currency_code_options[$code] .= ' (' . get_woocommerce_currency_symbol($code) . ')';
		}
		
		return $currency_code_options;
	}
	
	/**
	 * Called by the WP action woocommerce_update_options_tax
	 *
	 * @uses $_POST
	 */
	public function woocommerce_update_options_tax() {
		if (isset($_POST['woocommerce_eu_vat_compliance_vat_match'])) woocommerce_update_options($this->settings);
	}

	/**
	 * Convert an internal WC order status to user-suitable text. From WC 2.2.
	 *
	 * @param String $status
	 *
	 * @return String
	 */
	public function order_status_to_text($status) {
		$order_statuses = array(
			'wc-pending'    => _x('Pending Payment', 'Order status', 'woocommerce-eu-vat-compliance'),
			'wc-processing' => _x('Processing', 'Order status', 'woocommerce-eu-vat-compliance'),
			'wc-on-hold'    => _x('On Hold', 'Order status', 'woocommerce-eu-vat-compliance'),
			'wc-completed'  => _x('Completed', 'Order status', 'woocommerce-eu-vat-compliance'),
			'wc-cancelled'  => _x('Cancelled', 'Order status', 'woocommerce-eu-vat-compliance'),
			'wc-refunded'   => _x('Refunded', 'Order status', 'woocommerce-eu-vat-compliance'),
			'wc-failed'     => _x('Failed', 'Order status', 'woocommerce-eu-vat-compliance'),
		);
		
		$order_statuses = apply_filters( 'wc_order_statuses', $order_statuses );

		if (true === $status) return $order_statuses;

		if ('wc-' != substr($status, 0, 3)) $status = 'wc-'.$status;
		
		return isset($order_statuses[$status]) ? $order_statuses[$status] : __('Unknown', 'woocommerce-eu-vat-compliance').' ('.substr($status, 3).')';
	}

	/**
	 * Runs upon the WP action before_woocommerce_init
	 */
	public function before_woocommerce_init() {
		$this->wc = WC();
	}

	/**
	 * Perform any required data format upgrades
	 */
	private function run_any_upgrades() {
		// If not present, could be anything up to 1.19.6, when we introduced this
		$last_version = (string) get_option('woocommerce_vat_compliance_last_version', '0');
		$our_version = $this->get_version();
		
		if ($last_version === $our_version) return;
		
		if (version_compare($our_version, $last_version, '>')) {
			$this->version_1_19_7_rename_valid_eu_vat_meta_field();
		}
		
		if ('0' !== $last_version && version_compare($our_version, '1.27.0', '>=') && version_compare($last_version, '1.27.0', '<')) {
			// Save the default value for the GeoIP option reflecting the previous default behaviour
			update_option('woocommerce_vat_compliance_geo_locate', 'yes');
		}
		
		update_option('woocommerce_vat_compliance_last_version', $our_version);
	}
	
	/**
	 * Whether WooCommerce custom order tables are enabled or not (with a filter provided for over-riding when debugging)
	 *
	 * @return Boolean
	 */
	public function woocommerce_custom_order_tables_enabled() {

		if (!did_action('plugins_loaded') && !doing_action('plugins_loaded')) error_log('woocommerce_custom_order_tables_enabled() called too early: will always return false');
		
		if (class_exists('Automattic\WooCommerce\Utilities\OrderUtil') && is_callable(array('Automattic\WooCommerce\Utilities\OrderUtil', 'custom_orders_table_usage_is_enabled')) && \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled()) {
			return apply_filters('woocommerce_vat_compliance_custom_order_tables_enabled', true);
		}
		return false;
	}
	
	/**
	 * Rename the "Valid EU VAT Number" field to "Valid VAT Number"
	 */
	private function version_1_19_7_rename_valid_eu_vat_meta_field() {
	
		global $wpdb; // HPOS-compliant
		
		// Postmeta (non-HPOS) version
		$result = $wpdb->query("UPDATE {$wpdb->postmeta} SET meta_key='Valid VAT Number' WHERE meta_key='Valid EU VAT Number';");
		if (false === $result) {
			error_log("WooCommerce VAT compliance: version_1_19_7_rename_valid_eu_vat_meta_field() on postmeta table failed");
		} elseif ($result > 0) {
			error_log("WooCommerce VAT compliance: $result meta fields (postmeta table) were updated ('Valid EU VAT Number' -> 'Valid VAT Number')");
		}
		
		// HPOS version
		if ($this->woocommerce_custom_order_tables_enabled()) {
			$result = $wpdb->query("UPDATE {$wpdb->prefix}wc_orders_meta SET meta_key='Valid VAT Number' WHERE meta_key='Valid EU VAT Number';");
			if (false === $result) {
				error_log("WooCommerce VAT compliance: version_1_19_7_rename_valid_eu_vat_meta_field() on custom orders table failed (table may not exist if HPOS not active, which is not a problem)");
			} elseif ($result > 0) {
				error_log("WooCommerce VAT compliance: $result meta fields (custom orders table) were updated ('Valid EU VAT Number' -> 'Valid VAT Number')");
			}
		}
	
	}
	
	/**
	 * Runs upon the WP action plugins_loaded
	 */
	public function plugins_loaded() {

		$this->run_any_upgrades();
	
		// Request WooCommerce to download the GeoIP database periodically to keep it up to date, even if the base WC settings are not for geo-location (we may want it for our own usage)
		if (defined('WC_VERSION') && version_compare(WC_VERSION, '3.9', '>=')) {
			// From WC 3.9, woocommerce_geolocation_update_database_periodically is deprecated
			add_filter('woocommerce_maxmind_geolocation_update_database_periodically', '__return_true');
		} else {
			add_filter('woocommerce_geolocation_update_database_periodically', '__return_true');
		}
	
		add_filter('woocommerce_adjust_non_base_location_prices', array($this, 'woocommerce_adjust_non_base_location_prices'));
		
		load_plugin_textdomain('woocommerce-eu-vat-compliance', false, basename(WC_VAT_COMPLIANCE_DIR).'/languages');

		if (!apply_filters('wc_eu_vat_compliance_ajax_update_checkout_totals_handler', false)) add_action( 'woocommerce_checkout_update_order_review', array($this, 'ajax_update_checkout_totals')); // Check during ajax update totals
		
		// This call ensures the option is updated to current format prior to WooCommerce fetching it directly. (We have no use for the returned value).
		$this->forbid_vat_checkout_to_which_regions();
		
		// Load VAT number checking services (allowing them to register any actions, etc.)
		$this->get_vat_number_lookup_services();
		
		$this->settings = apply_filters('wc_eu_vat_compliance_settings_after_forbid_checkout', array(array(
			'name' => __('Forbid VAT checkout', 'woocommerce-eu-vat-compliance'),
			// Commented items are not used here.
			//'desc' => __("For each VAT region selected here, <strong>all</strong> check-outs by customers (whether consumer or business) in those VAT regions for orders which contain goods subject to variable-by-country VAT (whether the customer is exempt or not) will be forbidden.", 'woocommerce-eu-vat-compliance').' '.__('N.B. This is a multi-select box; use the Control/Command keys to select/de-select multiple regions.', 'woocommerce-eu-vat-compliance'),
			// 'desc_tip' 	=> __('This feature is intended only for sellers who wish to avoid issues from variable VAT regulations entirely, by not selling any qualifying goods to customers in the chosen regions (even ones who are potentially VAT exempt).', 'woocommerce-eu-vat-compliance' ).' '.__("Check-out will be forbidden if the cart contains any goods from the relevant tax classes indicated below, and if the customer's VAT country is part of a chosen region.", 'woocommerce-eu-vat-compliance'),
			'id' => 'woocommerce_eu_vat_compliance_forbid_vatable_checkout',
			'type' => 'wc_vat_forbid_vatable_checkout',
			//'css' => 'min-width: 350px;',
			//'options' => $this->get_vat_region_codes_and_titles('noun', true),
			'default' => array(),
		)));

		$vat_region_codes_and_titles = $this->get_vat_region_codes_and_titles();
		$this->settings[] = array(
			'name' 		=> __('VAT regions', 'woocommerce-eu-vat-compliance'),
			'desc' 		=> '', // Not sourced from here
			'id' 		=> 'woocommerce_eu_vat_compliance_vat_region',
			'type' 		=> 'wc_vat_regions',
			'default' => array('eu'),
		);
		
		$tax_settings_link = admin_url('admin.php?page=wc-settings&tab=tax');
		
		$this->settings[] = array(
			'name' => __('Phrase matches used to identify VAT', 'woocommerce-eu-vat-compliance'),
			'desc' => __('A comma-separated (optional spaces) list of strings (phrases) used to identify taxes which are VAT taxes.', 'woocommerce-eu-vat-compliance').' '.sprintf(__('One of these strings must be used in your tax name labels (i.e. the names used in %s) if you wish the tax to be identified as VAT.', 'woocommerce-eu-vat-compliance'), '<a target="_blank" href="'.$tax_settings_link.'">'.__('your tax tables', 'woocommerce-eu-vat-compliance').'</a>').' '.__('Omit labels used for non-VAT taxes.', 'woocommerce-eu-vat-compliance'),
			'id' => 'woocommerce_eu_vat_compliance_vat_match',
			'type' => 'text',
			'default' => $this->default_vat_matches,
			'css' => 'width:100%;'
		);

		$this->settings[] = array(
			'name' => __("Customer location place-of-supply tax classes", 'woocommerce-eu-vat-compliance'),
			'desc' => __("Select all tax classes which are used in your store for products sold under customer place-of-supply VAT regulations (i.e. where the deemed place of supply is the customer location)", 'woocommerce-eu-vat-compliance'),
			'id' => 'woocommerce_eu_vat_compliance_tax_classes',
			'type' => 'wcvat_tax_classes',
			'default' => 'yes'
		);
		
		$this->settings[] = array(
			'name' => __('Change taxation classes based upon destination thresholds', 'woocommerce-eu-vat-compliance'),
			'desc' => '',
			'id' => 'woocommerce_vat_compliance_tax_class_translations',
			'type' => 'wcvat_tax_class_translations'
		);
		
		if ((!defined('WC_EU_VAT_NOCOUNTRYPRESELECT') || !WC_EU_VAT_NOCOUNTRYPRESELECT) && (!defined('WC_VAT_NO_COUNTRY_PRESELECT') || !WC_VAT_NO_COUNTRY_PRESELECT)) {
		
			$this->settings[] = array(
				'name' => __('Geolocate visitor locations', 'woocommerce-eu-vat-compliance'),
				'desc' => __('This option will perform GeoIP lookups for visitors on the site and advise WooCommerce to use the resulting country for the taxation location (until more information is available, e.g. when countries are chosen at the checkout, or from a widget).', 'woocommerce-eu-vat-compliance').' <strong>'.__('Because WooCommerce now has its own option for this (in the "Customer Default Address") setting, it is recommended that you leave this off.', 'woocommerce-eu-vat-compliance').'</strong> '.__('Exceptions to this are if the geo-location option in WooCommerce is not working, or you have upgraded from a previous version and prefer not to change something that is already working.', 'woocommerce-eu-vat-compliance'),
				'id' => 'woocommerce_vat_compliance_geo_locate',
				'type' => 'checkbox',
				'default' => 'no'
			);
		
		}
		
		$this->settings[] = array(
			'name' => __('Same net prices everywhere', 'woocommerce-eu-vat-compliance'),
			'desc' => __("This turns on WooCommerce's experimental feature to change the base price of products in order to achieve the same final (after tax) price for buyers all locations (whatever their tax rate). Note that it is still WooCommerce core that performs all pricing calculations (this option just exposes the core feature); we cannot provide support for calculation issues.", 'woocommerce-eu-vat-compliance').' <a href="https://github.com/woocommerce/woocommerce/wiki/How-Taxes-Work-in-WooCommerce#prices-including-tax---experimental-behavior">'.__('More information', 'woocommerce-eu-vat-compliance').'</a>',
			'id' => 'woocommerce_eu_vat_compliance_same_prices',
			'type' => 'checkbox',
			'default' => 'no'
		);

		$this->settings[] = array(
			'name' => __('Invoice footer text (B2C)', 'woocommerce-eu-vat-compliance'),
			'desc' => __("Text to prepend to the footer of your PDF invoice for transactions with VAT paid and non-zero (for supported PDF invoicing plugins)", 'woocommerce-eu-vat-compliance'),
			'id' => 'woocommerce_eu_vat_compliance_pdf_footer_b2c',
			'type' => 'textarea',
			'css' => 'width:100%; height: 100px;'
		);

	}

	/**
	 * Called by the WP filter woocommerce_adjust_non_base_location_prices
	 *
	 * @param Boolean $adjust
	 *
	 * @return Boolean
	 */
	public function woocommerce_adjust_non_base_location_prices($adjust) {
	
		// https://github.com/woocommerce/woocommerce/wiki/How-Taxes-Work-in-WooCommerce#prices-including-tax---experimental-behavior
		return ('yes' == get_option('woocommerce_eu_vat_compliance_same_prices', 'no')) ? false : $adjust;
		
	}
	
	// Function adapted from Aelia Currency Switcher
	private function get_visitor_ip_address() {

		$forwarded_for = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : (!empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');

		// Field HTTP_X_FORWARDED_FOR may contain multiple addresses, separated by a comma. The first one is the real client, followed by intermediate proxy servers.

		$ff = explode(',', $forwarded_for);

		$forwarded_for = array_shift($ff);

		$visitor_ip = trim($forwarded_for);

		// The filter makes it easier to test without having to visit another country.
		return apply_filters('wc_eu_vat_compliance_visitor_ip', $visitor_ip, $forwarded_for);
	}

	/**
	 * Here's where the hard work is done - where we get the information on the visitor's IP-address-indicated country and how it was discerned
	 *
	 * @return Array - with keys 'source' (a string describing how the country was determined) and 'data' (a country code)
	 */
	public function get_visitor_country_info() {

		$ip = $this->get_visitor_ip_address();

		// If Cloudflare has already done the work, return their result (which is probably more accurate). See: https://developers.cloudflare.com/support/network/configuring-ip-geolocation/
		if (!empty($_SERVER['HTTP_CF_IPCOUNTRY']) && !in_array($_SERVER['HTTP_CF_IPCOUNTRY'], array('T1', 'XX'))) {
			$country_info = array(
				'source' => 'HTTP_CF_IPCOUNTRY',
				// April 2016 - saw a case of Cloudflare returning in lower-case, contrary to the ISO standard. Saw a changelog from Diego today that indicated he's seeing the same thing
				'data' => strtoupper($_SERVER["HTTP_CF_IPCOUNTRY"])
			);
		} elseif (class_exists('WC_Geolocation') && null !== ($data = WC_Geolocation::geolocate_ip()) && is_array($data) && isset($data['country'])) {
			$country_info = array(
				'source' => 'woocommerce',
				'data' => $data['country']
			);
		} elseif (!function_exists('geoip_detect_get_info_from_ip')) {
			$country_info = array(
				'source' => 'geoip_detect_get_info_from_ip_function_not_available',
				'data' => false
			);
		}

		// Get the GeoIP info even if Cloudflare has a country - store it
		if (function_exists('geoip_detect_get_info_from_ip')) {
			if (isset($country_info)) {
				$country_info_geoip = $this->construct_country_info($ip);
				if (is_array($country_info_geoip) && isset($country_info_geoip['meta'])) $country_info['meta'] = $country_info_geoip['meta'];
			} else {
				$country_info = $this->construct_country_info($ip);
			}

		}

		// Second parameter has been unused for some time (March 2023)
		return apply_filters('wc_eu_vat_compliance_get_visitor_country_info', $country_info, null, $ip);
	}

	// Make sure that function_exists('geoip_detect_get_info_from_ip') before calling this
	public function construct_country_info($ip) {
		$info = geoip_detect_get_info_from_ip($ip);
		if (!is_object($info) || empty($info->country_code)) {
			$country_info = array(
				'source' => 'geoip_detect_get_info_from_ip',
				'data' => false,
				'meta' => array('ip' => $ip, 'reason' => 'geoip_detect_get_info_from_ip failed')
			);
		} else {
			$country_info = array(
				'source' => 'geoip_detect_get_info_from_ip',
				'data' => $info->country_code,
				'meta' => array('ip' => $ip, 'info' => $info)
			);
		}
		return $country_info;
	}

	/**
	 * Indicates whether the running plugin is the Premium version, or not
	 *
	 * @return Boolean
	 */
	public function is_premium() {
		return is_object(WooCommerce_EU_VAT_Compliance('WC_EU_VAT_Compliance_Premium'));
	}
}
endif;

if (!function_exists('WooCommerce_EU_VAT_Compliance')):
function WooCommerce_EU_VAT_Compliance($class = 'WC_EU_VAT_Compliance') {
	global $woocommerce_eu_vat_compliance_classes;
	// Allow later loading for this class
	if (empty($woocommerce_eu_vat_compliance_classes[$class]) && 'WC_EU_VAT_Compliance_Reports' == $class) {
		include_once(WC_VAT_COMPLIANCE_DIR.'/reports.php');
		$woocommerce_eu_vat_compliance_classes[$class] = new WC_EU_VAT_Compliance_Reports;
	}
	return (!empty($woocommerce_eu_vat_compliance_classes[$class]) && is_object($woocommerce_eu_vat_compliance_classes[$class])) ? $woocommerce_eu_vat_compliance_classes[$class] : false;
}
endif;

global $woocommerce_eu_vat_compliance_classes;
$woocommerce_eu_vat_compliance_classes = array();
foreach ($classes_to_activate as $cl) {
	if (class_exists($cl) && empty($woocommerce_eu_vat_compliance_classes[$cl])) {
		$woocommerce_eu_vat_compliance_classes[$cl] = new $cl;
	}
}
