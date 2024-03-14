<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access');

// Purpose: have up-to-date VAT rates

if (class_exists('WC_EU_VAT_Compliance_Rates')) return;
class WC_EU_VAT_Compliance_Rates {

	private $rates = array();

	private $known_rates;
	private $which_rate = 'standard_rate';

	// N.B. VatSense is also used/added in the code, if the user has entered a key
	private $sources = array(
		// euvatrates.com original is very out-of-date, no longer used
		'https://wceuvatcompliance.s3.amazonaws.com/rates.json',
		'https://raw.githubusercontent.com/DavidAnderson684/euvatrates.com/master/rates.json',
		'http://wceuvatcompliance.s3.amazonaws.com/rates.json',
		// Does not support our extended format, so use as a last resort
		// 'https://raw.githubusercontent.com/aelia-co/euvatrates.com/master/rates.json', // no longer maintained
	);

	private $wc;

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action('admin_init', array($this, 'admin_init'));
	}

	/**
	 * Runs upon the WP action admin_init
	 */
	public function admin_init() {

		// N.B. Any rate that is in the rates.json file will be accepted for use in the readiness tests - this array here does not need to include them.
		$this->known_rates = array(
			'standard_rate' => __('Standard Rate', 'woocommerce-eu-vat-compliance'),
			'reduced_rate' => __('Reduced Rate', 'woocommerce-eu-vat-compliance'),
// 			'reduced_rate_alt' => __('Reduced Rate (alternative)', 'woocommerce-eu-vat-compliance'),
		);

		global $pagenow;
		// wp-admin/admin.php?page=wc-settings&tab=tax&s=standard
		if ('admin.php' == $pagenow && !empty($_REQUEST['page']) && ('woocommerce_settings' == $_REQUEST['page'] || 'wc-settings' == $_REQUEST['page']) && !empty($_REQUEST['tab']) && 'tax' == $_REQUEST['tab'] && !empty($_REQUEST['section'])) {

			$this->which_rate = 'standard_rate';
			add_action('admin_footer', array($this, 'admin_footer'));
// 			if ('standard' == $_REQUEST['section']) {
// 			} else
			if ('reduced-rate' == $_REQUEST['section']) {
				$this->which_rate = 'reduced_rate';
			}
		}
		if (function_exists('WC')) $this->wc = WC();
	}

	/**
	 * Runs (when hooked) on the admin_footer WP action; used for injecting current values into the tax table UI elements.
	 */
	public function admin_footer() {
	
		$get_rates = $this->get_vat_rates();

		$rates = is_array($get_rates) ? $get_rates : array();
 		
 		$compliance = WooCommerce_EU_VAT_Compliance();
 		
 		$vat_region = $compliance->get_vat_region_object('eu');
 		
 		$region_title = $vat_region->get_region_title('adjective');
 		
		$rate_description = sprintf(__('Add / Update VAT Rates (%s)', 'woocommerce-eu-vat-compliance'), $region_title);
		
		$region_countries = $vat_region->get_countries();
		
		foreach (array_keys($rates) as $country_code) {
			if (!in_array($country_code, $region_countries)) {
				unset($rates[$country_code]);
			}
		}

		?>

		<script type="text/javascript">
			jQuery(function($) {

				var rates = <?php echo json_encode($rates); ?>;

				var availableCountries = [<?php
					$countries = array();
					foreach ($region_countries as $value => $label)
						$countries[] = '{ label: "' . $label . '", value: "' . $value . '" }';
					echo implode(', ', $countries);
				?>];

				// Unused
				var availableStates = [<?php
					$countries = array();
					foreach ($this->wc->countries->get_allowed_country_states() as $value => $label)
						foreach ( $label as $code => $state )
							$countries[] = '{ label: "' . $state . '", value: "' . $code . '" }';
					echo implode( ', ', $countries );
				?>];

				function wc_eu_vat_compliance_addrow(iso_code, tax_rate, tax_label) {

					// From WC_Settings_Tax::output_tax_rates (class-wc-settings-tax.php)
					var $taxrates_form = $('.wc_tax_rates');
					var $tbody = $taxrates_form.find('tbody');

					// How many rows are there currently?
					var size = $tbody.find('tr').length;

					// Does a line for this country already exist? If so, we want to update that
					var possible_existing_lines = $tbody.find('tr');
					var was_updated = false;
					$.each(possible_existing_lines, function (ind, line) {
						var p_iso = $(line).find('td.country input').first().val();
						if ('' == p_iso || p_iso != iso_code) { return; }
// 						var p_rate = jQuery(line).find('.wc_input_country_rate');
						var p_state = $(line).find('td.state input').first().val();
						var p_postcode = $(line).find('td.postcode input').first().val();
						var p_city = $(line).find('td.city input').first().val();
						if (p_iso == iso_code && (typeof p_state == 'undefined' || p_state == '') && (typeof p_postcode == 'undefined' || p_postcode == '') && (typeof p_city == 'undefined' || p_city == '')) {
							$(line).find('td.rate input').first().val(tax_rate).trigger('change');
							// Since the VAT amount is in the label, update that too
							$(line).find('td.name input').first().val(tax_label).trigger('change');
							was_updated = true;
							return;
						}
					});
					// If a row existed, and we updated it, then we're done - bail out
					if (true == was_updated) return;

					// No row existed - so, we shall add a new one
					/*
						Things have changed in WC 2.5 - Backbone.js is in use. So, we can't
						directly manipulate the DOM; we have to instead emulate clicking buttons
						and entering things.
						
						Thanks to Diego Zanella for finding this solution and sharing it.
						
						The previous (pre-WC-2.5) solution is also kept below, for the sake of not fixing what wasn't broken.
						
					*/

					$taxrates_form.find('.button.insert').trigger('click');
					var $new_row_parent = $tbody.find('tr[data-id^="new"] .country input[value=""]').first();
					var $new_row = $new_row_parent.parents('tr').first();

					$new_row.attr('country', iso_code);

					$new_row.find('.rate input').val(tax_rate).trigger('change');
					$new_row.find('.name input').val(tax_label).trigger('change');
					$new_row.find('.country input').val(iso_code).trigger('change');
					return false;
					
				}

				<?php
					$selector = 'a.remove_tax_rates';
					$vat_descr_info = esc_attr(__('Note: for any tax you enter below to be recognised as VAT, its name will need to contain one of the following words or phrases:', 'woocommerce-eu-vat-compliance')).' '.WooCommerce_EU_VAT_Compliance()->get_vat_matches('html-printable').'. <a href="?page='.$_REQUEST['page'].'&tab=tax">'.esc_attr(__('You can configure this list in the tax options.', 'woocommerce-eu-vat-compliance')).'<a>';
				?>

				var known_rates = [ "<?php echo implode('", "', array_keys($this->known_rates)); ?>" ];
				var known_rate_descriptions = [ "<?php echo implode('", "', array_values($this->known_rates)); ?>" ];

				var $foot = $('table.wc_tax_rates tfoot <?php echo $selector;?>').first();
				$foot.after('<a href="#" id="euvatcompliance-updaterates" class="button euvatcompliance-updaterates"><?php echo esc_js($rate_description);?></a>');

				var rate_selector = '<select id="euvatcompliance-whichrate">';
				for (i = 0; i < known_rates.length; i++) {
					rate_selector += '<option value="'+known_rates[i]+'">'+known_rate_descriptions[i]+'</option>';
				} 
				rate_selector = rate_selector + '</select>';

				var tax_description = ' <?php esc_attr_e('Description:', 'woocommerce-eu-vat-compliance');?> <input id="euvatcompliance-whatdescription" title="<?php esc_attr_e('The description that will be used when using the button for mass adding/updating of EU rates', 'woocommerce-eu-vat-compliance'); ?>" type="text" size="6" value="VAT">';

				$foot.after('<?php echo esc_js(__('Use rates:', 'woocommerce-eu-vat-compliance')); ?> '+rate_selector+tax_description);

				$('table.wc_tax_rates').first().before('<p><em><?php echo $vat_descr_info; ?></em></p>');

				$('table.wc_tax_rates').on('click', '.euvatcompliance-updaterates', function() {

					var which_rate = $('#euvatcompliance-whichrate').val();
					if (typeof which_rate == 'undefined' || '' == which_rate) { which_rate = '<?php echo $this->which_rate;?>'; }

// 					jQuery.blockUI({ message: "<h1><?php esc_attr_e('Adding tax rates...', 'woocommerce-eu-vat-compliance');?></h1>" });
					$.each(rates, function(iso, country) {
						var rate = country.standard_rate;
						if (which_rate == 'reduced_rate') {
							var reduced_rate = country.reduced_rate;
							if (typeof reduced_rate != 'boolean') { rate = reduced_rate; }
						}
						// VAT-compliant invoices must show the rate
						var name = jQuery('#euvatcompliance-whatdescription').val()+' ('+rate.toString()+'%)';
// 						var name = 'VAT ('+country.country+')';
						wc_eu_vat_compliance_addrow(iso, rate.toString(), name)
					});

// 					jQuery.unblockUI();

					return false;
				});
			});
		</script>
		<?php
	}

	/**
	 * Convert from ISO 3166-1 country code to country VAT code
	 *
	 * https://en.wikipedia.org/wiki/ISO_3166-1#Current_codes
	 * http://ec.europa.eu/taxation_customs/resources/documents/taxation/vat/how_vat_works/rates/vat_rates_en.pdf
	 *
	 * @param String $country
	 *
	 * return String
	 */
	public function get_vat_code($country) {
		$country_code = $country;

		// Deal with exceptions
		switch ($country) {
			case 'GR' :
				$country_code = 'EL';
			break;
			case 'IM' :
			case 'GB' :
				$country_code = 'UK';
			break;
			case 'MC' :
				$country_code = 'FR';
			break;
		}

		return $country_code;
	}

	/**
	 * Convert from country code used in the VAT data to ISO code (usually the same, with some exceptions)
	 *
	 * @param String $country - VAT country code
	 *
	 * @return String - ISO code
	 */
	public function get_iso_code($country) {
		$iso_code = $country;

		// Deal with exceptions
		switch ($country) {
			case 'EL' :
				$iso_code = 'GR';
			break;
			case 'UK' :
				$iso_code = 'GB';
			break;
		}

		return $iso_code;
	}

	// Takes an EU country code (see get_vat_code())
	// Available rates: standard_rate, reduced_rate (super_reduced_rate, parking_rate)
	// Commented out Aug 2020 as no consumers for this method were found
	// public function get_vat_rate_for_country($country_code, $rate = 'standard_rate') {
	//	$rates = $this->get_vat_rates();
	//	if (empty($rates) || !is_array($rates) || !isset($rates[$country_code])) return false;
	//	if (!isset($rates[$country_code][$rate])) return false;
	//	return $rates[$country_code][$rate];
	// }

	/**
	 * Fetch rates from the network. Though this is public, get_vat_rates() should always be used; it is only public for the purposes of the readiness test.
	 *
	 * @return Array|Boolean - returns false upon error; upon success, the rates are in the 'rates' key
	 */
	public function fetch_remote_vat_rates() {
		$new_rates = false;
		
		$vat_sense_api_key = get_option('woocommerce_vat_compliance_vat_sense_api_key', '');
		
		if ('' != $vat_sense_api_key) {
			$get = wp_remote_get('https://api.vatsense.com/1.0/rates', array('headers' => array('Authorization' => 'Basic '.base64_encode('user:'.$vat_sense_api_key))));
			
			if (!is_wp_error($get)) {
				$response_code = wp_remote_retrieve_response_code($get);
				if ('' !== $response_code && $response_code >= 200 || $response_code < 300) {
					$body = wp_remote_retrieve_body($get);
					if (!is_wp_error($body)) {
						$rates = json_decode($body, true);
						if (!empty($rates) && isset($rates['data']) && !empty($rates['success'])) {
							$new_rates = array();
							$new_rates['source'] = 'https://api.vatsense.com/1.0/rates';
							foreach ($rates['data'] as $obj) {
								if (empty($obj['standard']) || empty($obj['country_code'])) continue;
								$country_code = $obj['country_code'];
								if (empty($obj['eu']) && ('GB' !== $country_code && 'MC' !== $country_code)) continue;
								$new_rates['rates'][$country_code] = array(
									'country' => $obj['country_name'],
									'standard_rate' => $obj['standard']['rate'],
								);
								if (!empty($obj['other'])) {
									$other_rates = array();
									foreach ($obj['other'] as $rate) {
										$other_rates[] = $rate['rate'];
									}
									sort($other_rates);
									if (isset($other_rates[0])) $new_rates['rates'][$country_code]['reduced_rate'] = $other_rates[0];
									if (isset($other_rates[1])) $new_rates['rates'][$country_code]['reduced_rate_alt'] = $other_rates[1];
									if (isset($other_rates[2])) $new_rates['rates'][$country_code]['super_reduced_rate'] = $other_rates[2];
								}
							}
							return $new_rates;
						}
					}
				}
			}
		}
		
		foreach ($this->sources as $url) {
			$get = wp_remote_get($url, array('timeout' => 5));
			if (is_wp_error($get) || !is_array($get)) continue;
			if (!isset($get['response'])) continue;
			$response_code = wp_remote_retrieve_response_code($get);
			if ('' == $response_code || $response_code >= 300 || $response_code < 200) continue;
			$rates = json_decode(wp_remote_retrieve_body($get), true);
			if (empty($rates) || !isset($rates['rates'])) continue;
			$new_rates = $rates;
			$new_rates['source'] = $url;
			break;
		}
		return $new_rates;
	}

	/**
	 * Get "expected" VAT rates according to bundled or downloaded data
	 *
	 * @param Boolean $use_transient - whether to allow use of a previously cached result
	 *
	 * @return Array - rates, keyed by country code
	 */
	public function get_vat_rates($use_transient = true) {
	
		if (!empty($this->rates)) return $this->rates;
		$rates = $use_transient ? get_site_transient('wc_euro_vat_rates_by_iso') : false;
		
		if (is_array($rates) && !empty($rates['rates'])) {
			$new_rates = $rates;
		} else {
			$this->rates = false;
			$new_rates = $this->fetch_remote_vat_rates();
		}
		
		if (empty($new_rates) && (false != ($rates_from_file = file_get_contents(WC_VAT_COMPLIANCE_DIR.'/data/rates.json')))) {
			$rates = json_decode($rates_from_file, true);
			if (!empty($rates) && isset($rates['rates'])) {
				$new_rates = $rates;
				$new_rates['source'] = 'data/rates.json';
			}
		}

		// The array we return should use ISO country codes
		if (!empty($new_rates['rates'])) {
		
			$corrected_rates = array();
			$time_now = time();
			
			foreach ($new_rates['rates'] as $country => $rate_list) {
				if (!is_array($rate_list)) continue;
				
				$iso = $this->get_iso_code($country);
				
				// Substitute in time-changing values
				
				foreach ($rate_list as $key => $data) {
					if (!preg_match('/^(.*)_by_time$/', $key, $matches)) continue;
					if (!is_array($data)) continue;
					ksort($data);
					
					$current_value = false;
					foreach ($data as $time_from => $rate_value) {
						if ($time_from < $time_now) {
							$current_value = $rate_value;
						}
					}
					if (false !== $current_value) {
						$rate_list[$matches[1]] = $current_value;
					}
				}
				
				$corrected_rates[$iso] = $rate_list;
			}
			
			// Add in Monaco (common VAT area with France)
			if (isset($corrected_rates['FR'])) $corrected_rates['MC'] = $corrected_rates['FR'];
			
			// Add the Isle of Man (common VAT area with the UK)
			if (isset($corrected_rates['GB'])) {
				$corrected_rates['IM'] = $corrected_rates['GB'];
				$corrected_rates['IM']['country'] = __( 'Isle of Man', 'woocommerce-eu-vat-compliance' );
			}
			
			$this->rates = $corrected_rates;
			
			// Add meta-data to go in the transient
			set_site_transient('wc_euro_vat_rates_by_iso', array('rates' => $corrected_rates, 'source' => $new_rates['source'], 'fetched_at' => $time_now), 43200);
			
		}
		return $this->rates;
	}

}
