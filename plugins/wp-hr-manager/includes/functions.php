<?php

/**
 * Processes all wphr actions sent via REQUEST by looking for the 'wphr-action'
 * request and running do_action() to call the function
 *
 * @return void
 */
function wphr_process_actions() {
	if (isset($_REQUEST['wphr-action'])) {
		do_action('wphr_action_' . sanitize_text_field($_REQUEST['wphr-action']), $_REQUEST);
	}
}

/*
 * Load HR Front end module  (By Twisha)
 */
function wphr_process_init_actions() {
	/* add code for creating page on hr frontend activate */
	$is_hrfrontend_activated = wphr_is_module_active('wphr-hr-frontend');
	if ($is_hrfrontend_activated) {
		if (class_exists("WPHR\HR_MANAGER\HR\Frontend\WPHR_HR_Frontend")) {

			WPHR\HR_MANAGER\HR\Frontend\WPHR_HR_Frontend::init();
			WPHR\HR_MANAGER\HR\Frontend\WPHR_HR_Frontend::activate();
		} else {
			require_once WPHR_MODULES . "/wp-hr-frontend/wp-hr-frontend.php";
			//WPHR\HR_MANAGER\HR\Frontend\WPHR_HR_Frontend::init();
		}
	}
}

/*
 * Redirect employee according to settings (By Twisha)
 *
 */

function wphr_login_redirect($redirect_to, $request, $user) {
	if (isset($user->roles) && is_array($user->roles)) {
		if (in_array('employee', $user->roles)) {
			$enable_profile_redirect = get_user_meta($user->ID, 'enable_profile_redirect', true);
			if ($enable_profile_redirect == "1") {
				$page = get_option('wphr_settings_hr-frontend-page');
				return get_permalink($page['emp_profile']);
			} else {
				$wphr_profile_redirect = wphr_get_option('wphr_profile_redirect', 'wphr_settings_general', '0');
				if ($wphr_profile_redirect == "yes") {
					$page = get_option('wphr_settings_hr-frontend-page');
					return get_permalink($page['emp_profile']);
				} else {
					return $redirect_to;
				}

			}
			return $redirect_to;
		} else {
			return home_url();
		}
	} else {
		return $redirect_to;
	}
}

/**
 * Return the WPHR Manager version
 *
 * @return string The WPHR Manager version
 */
function wphr_get_version() {
	return wphr()->version;
}

/**
 * Maps various caps to built in WordPress caps
 *
 *
 * @param array $caps Capabilities for meta capability
 * @param string $cap Capability name
 * @param int $user_id User id
 * @param mixed $args Arguments
 */
function wphr_map_meta_caps($caps = array(), $cap = '', $user_id = 0, $args = array()) {
	return apply_filters('wphr_map_meta_caps', $caps, $cap, $user_id, $args);
}

/**
 * Get full list of currency codes.
 *
 * @since 1.0.0
 * @since 1.1.14 Add most of the current circulating currencies
 *
 * @return array
 */
function wphr_get_currencies() {
	return apply_filters('wphr_currencies', [
		'AFN' => __('Afghan Afghani', 'wphr'),
		'ALL' => __('Albanian Lek', 'wphr'),
		'DZD' => __('Algerian Dinar', 'wphr'),
		'ADP' => __('Andorran Peseta', 'wphr'),
		'AOA' => __('Angolan Kwanza', 'wphr'),
		'ARA' => __('Argentine Austral', 'wphr'),
		'ARS' => __('Argentine Peso', 'wphr'),
		'AMD' => __('Armenian Dram', 'wphr'),
		'AWG' => __('Aruban Florin', 'wphr'),
		'AUD' => __('Australian Dollar', 'wphr'),
		'ATS' => __('Austrian Schilling', 'wphr'),
		'AZN' => __('Azerbaijani Manat', 'wphr'),
		'BSD' => __('Bahamian Dollar', 'wphr'),
		'BHD' => __('Bahraini Dinar', 'wphr'),
		'BDT' => __('Bangladeshi Taka', 'wphr'),
		'BBD' => __('Barbadian Dollar', 'wphr'),
		'BYR' => __('Belarusian Ruble', 'wphr'),
		'BEF' => __('Belgian Franc', 'wphr'),
		'BZD' => __('Belize Dollar', 'wphr'),
		'BMD' => __('Bermudan Dollar', 'wphr'),
		'BTN' => __('Bhutanese Ngultrum', 'wphr'),
		'BOB' => __('Bolivian Boliviano', 'wphr'),
		'BOV' => __('Bolivian Mvdol', 'wphr'),
		'BOP' => __('Bolivian Peso', 'wphr'),
		'BAM' => __('Bosnia-Herzegovina Convertible Mark', 'wphr'),
		'BWP' => __('Botswanan Pula', 'wphr'),
		'BRL' => __('Brazilian Real', 'wphr'),
		'GBP' => __('British Pound Sterling', 'wphr'),
		'BND' => __('Brunei Dollar', 'wphr'),
		'BGN' => __('Bulgarian Lev', 'wphr'),
		'BUK' => __('Burmese Kyat', 'wphr'),
		'BIF' => __('Burundian Franc', 'wphr'),
		'KHR' => __('Cambodian Riel', 'wphr'),
		'CAD' => __('Canadian Dollar', 'wphr'),
		'CVE' => __('Cape Verdean Escudo', 'wphr'),
		'KYD' => __('Cayman Islands Dollar', 'wphr'),
		'XOF' => __('CFA Franc BCEAO', 'wphr'),
		'XAF' => __('CFA Franc BEAC', 'wphr'),
		'XPF' => __('CFP Franc', 'wphr'),
		'CLP' => __('Chilean Peso', 'wphr'),
		'CNY' => __('Chinese Yuan', 'wphr'),
		'COP' => __('Colombian Peso', 'wphr'),
		'KMF' => __('Comorian Franc', 'wphr'),
		'CDF' => __('Congolese Franc', 'wphr'),
		'CRC' => __('Costa Rican Colón', 'wphr'),
		'HRK' => __('Croatian Kuna', 'wphr'),
		'CUP' => __('Cuban Peso', 'wphr'),
		'CYP' => __('Cypriot Pound', 'wphr'),
		'CZK' => __('Czech Republic Koruna', 'wphr'),
		'DKK' => __('Danish Krone', 'wphr'),
		'DJF' => __('Djiboutian Franc', 'wphr'),
		'DOP' => __('Dominican Peso', 'wphr'),
		'NLG' => __('Dutch Guilder', 'wphr'),
		'XCD' => __('East Caribbean Dollar', 'wphr'),
		'ECS' => __('Ecuadorian Sucre', 'wphr'),
		'EGP' => __('Egyptian Pound', 'wphr'),
		'GQE' => __('Equatorial Guinean Ekwele', 'wphr'),
		'ERN' => __('Eritrean Nakfa', 'wphr'),
		'EEK' => __('Estonian Kroon', 'wphr'),
		'ETB' => __('Ethiopian Birr', 'wphr'),
		'EUR' => __('Euro', 'wphr'),
		'FKP' => __('Falkland Islands Pound', 'wphr'),
		'FJD' => __('Fijian Dollar', 'wphr'),
		'FIM' => __('Finnish Markka', 'wphr'),
		'FRF' => __('French Franc', 'wphr'),
		'GMD' => __('Gambian Dalasi', 'wphr'),
		'GEL' => __('Georgian Lari', 'wphr'),
		'DEM' => __('German Mark', 'wphr'),
		'GHS' => __('Ghanaian Cedi', 'wphr'),
		'GIP' => __('Gibraltar Pound', 'wphr'),
		'GRD' => __('Greek Drachma', 'wphr'),
		'GTQ' => __('Guatemalan Quetzal', 'wphr'),
		'GWP' => __('Guinea-Bissau Peso', 'wphr'),
		'GNF' => __('Guinean Franc', 'wphr'),
		'GYD' => __('Guyanaese Dollar', 'wphr'),
		'HTG' => __('Haitian Gourde', 'wphr'),
		'HNL' => __('Honduran Lempira', 'wphr'),
		'HKD' => __('Hong Kong Dollar', 'wphr'),
		'HUF' => __('Hungarian Forint', 'wphr'),
		'ISK' => __('Icelandic Króna', 'wphr'),
		'INR' => __('Indian Rupee', 'wphr'),
		'IDR' => __('Indonesian Rupiah', 'wphr'),
		'IRR' => __('Iranian Rial', 'wphr'),
		'IQD' => __('Iraqi Dinar', 'wphr'),
		'IEP' => __('Irish Pound', 'wphr'),
		'ILS' => __('Israeli New Sheqel', 'wphr'),
		'ITL' => __('Italian Lira', 'wphr'),
		'JMD' => __('Jamaican Dollar', 'wphr'),
		'JPY' => __('Japanese Yen', 'wphr'),
		'JOD' => __('Jordanian Dinar', 'wphr'),
		'KZT' => __('Kazakhstani Tenge', 'wphr'),
		'KES' => __('Kenyan Shilling', 'wphr'),
		'KWD' => __('Kuwaiti Dinar', 'wphr'),
		'KGS' => __('Kyrgystani Som', 'wphr'),
		'LAK' => __('Laotian Kip', 'wphr'),
		'LVL' => __('Latvian Lats', 'wphr'),
		'LBP' => __('Lebanese Pound', 'wphr'),
		'LSL' => __('Lesotho Loti', 'wphr'),
		'LRD' => __('Liberian Dollar', 'wphr'),
		'LYD' => __('Libyan Dinar', 'wphr'),
		'LTL' => __('Lithuanian Litas', 'wphr'),
		'LTT' => __('Lithuanian Talonas', 'wphr'),
		'LUF' => __('Luxembourgian Franc', 'wphr'),
		'MOP' => __('Macanese Pataca', 'wphr'),
		'MKD' => __('Macedonian Denar', 'wphr'),
		'MGA' => __('Malagasy Ariary', 'wphr'),
		'MWK' => __('Malawian Kwacha', 'wphr'),
		'MYR' => __('Malaysian Ringgit', 'wphr'),
		'MVR' => __('Maldivian Rufiyaa', 'wphr'),
		'MLF' => __('Malian Franc', 'wphr'),
		'MTL' => __('Maltese Lira', 'wphr'),
		'MRO' => __('Mauritanian Ouguiya', 'wphr'),
		'MUR' => __('Mauritian Rupee', 'wphr'),
		'MXN' => __('Mexican Peso', 'wphr'),
		'MDL' => __('Moldovan Leu', 'wphr'),
		'MCF' => __('Monegasque Franc', 'wphr'),
		'MNT' => __('Mongolian Tugrik', 'wphr'),
		'MAD' => __('Moroccan Dirham', 'wphr'),
		'MZN' => __('Mozambican Metical', 'wphr'),
		'MMK' => __('Myanmar Kyat', 'wphr'),
		'NAD' => __('Namibian Dollar', 'wphr'),
		'NPR' => __('Nepalese Rupee', 'wphr'),
		'ANG' => __('Netherlands Antillean Guilder', 'wphr'),
		'TWD' => __('New Taiwan Dollar', 'wphr'),
		'NZD' => __('New Zealand Dollar', 'wphr'),
		'NIO' => __('Nicaraguan Córdoba', 'wphr'),
		'NGN' => __('Nigerian Naira', 'wphr'),
		'KPW' => __('North Korean Won', 'wphr'),
		'NOK' => __('Norwegian Krone', 'wphr'),
		'OMR' => __('Omani Rial', 'wphr'),
		'PKR' => __('Pakistani Rupee', 'wphr'),
		'PAB' => __('Panamanian Balboa', 'wphr'),
		'PGK' => __('Papua New Guinean Kina', 'wphr'),
		'PYG' => __('Paraguayan Guarani', 'wphr'),
		'PEI' => __('Peruvian Inti', 'wphr'),
		'PHP' => __('Philippine Peso', 'wphr'),
		'PLN' => __('Polish Zloty', 'wphr'),
		'PTE' => __('Portuguese Escudo', 'wphr'),
		'QAR' => __('Qatari Rial', 'wphr'),
		'RHD' => __('Rhodesian Dollar', 'wphr'),
		'RON' => __('Romanian Leu', 'wphr'),
		'RUB' => __('Russian Ruble', 'wphr'),
		'RWF' => __('Rwandan Franc', 'wphr'),
		'SVC' => __('Salvadoran Colón', 'wphr'),
		'WST' => __('Samoan Tala', 'wphr'),
		'STD' => __('São Tomé & Príncipe Dobra', 'wphr'),
		'SAR' => __('Saudi Riyal', 'wphr'),
		'RSD' => __('Serbian Dinar', 'wphr'),
		'SCR' => __('Seychellois Rupee', 'wphr'),
		'SLL' => __('Sierra Leonean Leone', 'wphr'),
		'SGD' => __('Singapore Dollar', 'wphr'),
		'SKK' => __('Slovak Koruna', 'wphr'),
		'SIT' => __('Slovenian Tolar', 'wphr'),
		'SBD' => __('Solomon Islands Dollar', 'wphr'),
		'SOS' => __('Somali Shilling', 'wphr'),
		'ZAR' => __('South African Rand', 'wphr'),
		'KRW' => __('South Korean Won', 'wphr'),
		'SSP' => __('South Sudanese Pound', 'wphr'),
		'ESP' => __('Spanish Peseta', 'wphr'),
		'LKR' => __('Sri Lankan Rupee', 'wphr'),
		'SHP' => __('St. Helena Pound', 'wphr'),
		'SDG' => __('Sudanese Pound', 'wphr'),
		'SRD' => __('Surinamese Dollar', 'wphr'),
		'SZL' => __('Swazi Lilangeni', 'wphr'),
		'SEK' => __('Swedish Krona', 'wphr'),
		'CHF' => __('Swiss Franc', 'wphr'),
		'SYP' => __('Syrian Pound', 'wphr'),
		'TJS' => __('Tajikistani Somoni', 'wphr'),
		'TZS' => __('Tanzanian Shilling', 'wphr'),
		'THB' => __('Thai Baht', 'wphr'),
		'TPE' => __('Timorese Escudo', 'wphr'),
		'TOP' => __('Tongan Paʻanga', 'wphr'),
		'TTD' => __('Trinidad & Tobago Dollar', 'wphr'),
		'TND' => __('Tunisian Dinar', 'wphr'),
		'TRY' => __('Turkish Lira', 'wphr'),
		'TMT' => __('Turkmenistani Manat', 'wphr'),
		'UGX' => __('Ugandan Shilling', 'wphr'),
		'UAH' => __('Ukrainian Hryvnia', 'wphr'),
		'AED' => __('United Arab Emirates Dirham', 'wphr'),
		'UYU' => __('Uruguayan Peso', 'wphr'),
		'USD' => __('US Dollar', 'wphr'),
		'UZS' => __('Uzbekistan Som', 'wphr'),
		'VUV' => __('Vanuatu Vatu', 'wphr'),
		'VEF' => __('Venezuelan Bolívar', 'wphr'),
		'VND' => __('Vietnamese Dong', 'wphr'),
		'YER' => __('Yemeni Rial', 'wphr'),
		'ZMW' => __('Zambian Kwacha', 'wphr'),
		'ZWL' => __('Zimbabwean Dollar', 'wphr'),
	]);
}

/**
 * Get full list of currency ISO with symbol label.
 *
 * @return array
 */
function wphr_get_currency_list_with_symbol() {
	$currencies = wphr_get_currencies();
	$currency_symbols = wphr_get_currency_symbol();
	$currency_list = [];

	foreach ($currencies as $iso => $currency) {
		$symbol = isset($currency_symbols[$iso]) ? $currency_symbols[$iso] : $iso;

		$currency_list[$iso] = sprintf('%1$s (%2$s)', $currency, $symbol);
	}

	return $currency_list;
}

/**
 * [wphr_get_currencies_dropdown description]
 *
 * @param  string  [description]
 *
 * @return string
 */
function wphr_get_currencies_dropdown($selected = '') {
	$options = '';
	$currencies = wphr_get_currencies();

	foreach ($currencies as $key => $value) {
		$select = ($key == $selected) ? ' selected="selected"' : '';
		$options .= sprintf("<option value='%s'%s>%s</option>\n", esc_attr($key), $select, $value);
	}

	return $options;
}

/**
 * Get global currency
 *
 * @since 1.1.6
 *
 * @return string
 */
function wphr_get_currency() {
	return wphr_get_option('wphr_currency', 'wphr_settings_general', 'USD');
}

/**
 * Get Currency symbol.
 *
 * @since 1.0.0
 * @since 1.1.14 Add most of the current circulating currencies.
 *               If no $currency provided, full symbol list will be returned.
 * @since 1.2.1  Fix symbol for South African rand
 *
 * @param string $currency
 *
 * @return string|array
 */
function wphr_get_currency_symbol($currency = '') {

	/**
	 * Source: https://en.wikipedia.org/wiki/List_of_circulating_currencies
	 *
	 * In wikipedia some of the symbols are in SVG image like 'AMD'
	 * or not supported by UTF-8 like 'GEL'. For those symbols currency codes
	 * are used as symbols
	 */

	$currency_symbols = [
		'AED' => 'د.إ',
		'AFN' => '؋',
		'ALL' => 'L',
		'AMD' => 'AMD',
		'ANG' => 'ƒ',
		'AOA' => 'Kz',
		'ARS' => '$',
		'AUD' => '$',
		'AWG' => 'ƒ',
		'AZN' => '₼',
		'BAM' => 'KM',
		'BBD' => '$',
		'BDT' => '৳',
		'BGN' => 'лв',
		'BHD' => '.د.ب',
		'BIF' => 'Fr',
		'BMD' => '$',
		'BND' => '$',
		'BOB' => 'Bs.',
		'BRL' => 'R$',
		'BSD' => '$',
		'BTN' => 'Nu.',
		'BWP' => 'P',
		'BYN' => 'Br',
		'BYR' => 'Br',
		'BZD' => '$',
		'CAD' => '$',
		'CDF' => 'Fr',
		'CHF' => 'Fr',
		'CLP' => '$',
		'CNY' => '¥',
		'COP' => '$',
		'CRC' => '₡',
		'CUC' => '$',
		'CUP' => '$',
		'CVE' => '$',
		'CZK' => 'Kč',
		'DJF' => 'Fr',
		'DKK' => 'kr',
		'DOP' => '$',
		'DZD' => 'د.ج',
		'EGP' => '£',
		'ERN' => 'Nfk',
		'ETB' => 'Br',
		'EUR' => '€',
		'FJD' => '$',
		'FKP' => '£',
		'GBP' => '£',
		'GEL' => 'GEL',
		'GGP' => '£',
		'GHS' => '₵',
		'GIP' => '£',
		'GMD' => 'D',
		'GNF' => 'Fr',
		'GTQ' => 'Q',
		'GYD' => '$',
		'HKD' => '$',
		'HNL' => 'L',
		'HRK' => 'kn',
		'HTG' => 'G',
		'HUF' => 'Ft',
		'IDR' => 'Rp',
		'ILS' => '₪',
		'IMP' => '£',
		'INR' => '₹',
		'IQD' => 'ع.د',
		'IRR' => '﷼',
		'ISK' => 'kr',
		'JEP' => '£',
		'JMD' => '$',
		'JOD' => 'د.ا',
		'JPY' => '¥',
		'KES' => 'Sh',
		'KGS' => 'с',
		'KHR' => '៛',
		'KMF' => 'Fr',
		'KPW' => '₩',
		'KRW' => '₩',
		'KWD' => 'د.ك',
		'KYD' => '$',
		'KZT' => 'KZT',
		'LAK' => '₭',
		'LBP' => 'ل.ل',
		'LKR' => 'Rs',
		'LRD' => '$',
		'LSL' => 'L',
		'LYD' => 'ل.د',
		'MAD' => 'د.م.',
		'MDL' => 'L',
		'MGA' => 'Ar',
		'MKD' => 'ден',
		'MMK' => 'Ks',
		'MNT' => '₮',
		'MOP' => 'P',
		'MRO' => 'UM',
		'MUR' => '₨',
		'MVR' => 'MVR',
		'MWK' => 'MK',
		'MXN' => '$',
		'MYR' => 'RM',
		'MZN' => 'MT',
		'NAD' => '$',
		'NGN' => '₦',
		'NIO' => 'C$',
		'NOK' => 'kr',
		'NPR' => '₨',
		'NZD' => '$',
		'OMR' => 'ر.ع.',
		'PAB' => 'B/.',
		'PEN' => 'S/.',
		'PGK' => 'K',
		'PHP' => '₱',
		'PKR' => '₨',
		'PLN' => 'zł',
		'PRB' => 'р.',
		'PYG' => '₲',
		'QAR' => 'ر.ق',
		'RON' => 'lei',
		'RSD' => 'дин',
		'RUB' => '₽',
		'RWF' => 'Fr',
		'SAR' => 'ر.س',
		'SBD' => '$',
		'SCR' => '₨',
		'SDG' => 'ج.س.',
		'SEK' => 'kr',
		'SGD' => '$',
		'SHP' => '£',
		'SLL' => 'Le',
		'SOS' => 'Sh',
		'SRD' => '$',
		'SSP' => '£',
		'STD' => 'Db',
		'SYP' => '£',
		'SZL' => 'L',
		'THB' => '฿',
		'TJS' => 'ЅМ',
		'TMT' => 'm',
		'TND' => 'د.ت',
		'TOP' => 'T$',
		'TRY' => 'TRY',
		'TTD' => '$',
		'TVD' => '$',
		'TWD' => '$',
		'TZS' => 'Sh',
		'UAH' => '₴',
		'UGX' => 'Sh',
		'USD' => '$',
		'UYU' => '$',
		'UZS' => 'UZS',
		'VEF' => 'Bs',
		'VND' => '₫',
		'VUV' => 'Vt',
		'WST' => 'T',
		'XAF' => 'Fr',
		'XCD' => '$',
		'XOF' => 'Fr',
		'XPF' => 'Fr',
		'YER' => '﷼',
		'ZAR' => 'R',
		'ZMW' => 'ZK',
		'ZWL' => '$',
	];

	if (!empty($currency)) {
		$symbol = !empty($currency_symbols[$currency]) ? $currency_symbols[$currency] : $currency;
		return apply_filters('wphr_currency_symbol', $symbol, $currency);
	} else {
		return apply_filters('wphr_currency_symbol_list', $currency_symbols);
	}

}

/**
 * Embed a JS template page with its ID
 *
 * @param  string  the file path of the file
 * @param  string  the script id
 *
 * @return void
 */
function wphr_get_js_template($file_path, $id) {
	if (file_exists($file_path)) {
		echo '<script type="text/html" id="tmpl-' . $id . '">' . "\n";
		include_once apply_filters('wphr_crm_js_template_file_path', $file_path, $id);
		echo "\n" . '</script>' . "\n";
	}
}

/**
 * Embed a Vue Component template page with its ID
 *
 * @param  string  the file path of the file
 * @param  string  the script id
 *
 * @return void
 */
function wphr_get_vue_component_template($file_path, $id) {
	if (file_exists($file_path)) {
		echo '<script type="text/x-template" id="' . $id . '">' . "\n";
		include_once $file_path;
		echo "\n" . '</script>' . "\n";
	}
}

if (!function_exists('strip_tags_deep')) {

	/**
	 * Strip tags from string or array
	 *
	 * @param  mixed  array or string to strip
	 *
	 * @return mixed  stripped value
	 */
	function strip_tags_deep($value) {
		if (is_array($value)) {
			foreach ($value as $key => $val) {
				$value[$key] = strip_tags_deep($val);
			}
		} elseif (is_string($value)) {
			$value = strip_tags($value);
		}

		return $value;
	}
}

if (!function_exists('trim_deep')) {

	/**
	 * Trim from string or array
	 *
	 * @param  mixed  array or string to trim
	 *
	 * @return mixed  timmed value
	 */
	function trim_deep($value) {
		if (is_array($value)) {
			foreach ($value as $key => $val) {
				$value[$key] = trim_deep($val);
			}
		} elseif (is_string($value)) {
			$value = trim($value);
		}

		return $value;
	}
}

/**
 * Helper function to print a label and value with a separator
 *
 * @since 1.0.0
 * @since 1.1.14 Add $type param
 * @since 1.1.16 Apply if-else condition to set `$value`
 *
 * @param  string  $label the label
 * @param  string  $value the value to print
 * @param  string  $sep   separator
 * @param  string  $type  field type
 *
 * @return void
 */
function wphr_print_key_value($label, $value, $sep = ' : ', $type = 'text') {
	if (empty($value)) {
		$value = '&mdash;';

	} else {
		switch ($type) {
		case 'email':
		case 'url':
		case 'phone':
			$value = wphr_get_clickable($type, $value);
			break;
		}
	}

	printf('<label>%s</label> <span class="sep">%s</span> <span class="value">%s</span>', $label, $sep, $value);
}

/**
 * Get a clickable phone or email address link
 *
 * @param  string  type. e.g: email|phone|url
 * @param  string  the value
 *
 * @return string  the link
 */
function wphr_get_clickable($type = 'email', $value = '') {
	if ('email' == $type) {
		return sprintf('<a href="mailto:%1$s">%1$s</a>', $value);
	} elseif ('url' == $type) {
		return sprintf('<a target="_blank" href="%1$s">%1$s</a>', $value);
	} elseif ('phone' == $type) {
		return sprintf('<a href="tel:%1$s">%1$s</a>', $value);
	}
}

/**
 * Get a formatted date from WordPress format
 *
 * @param  string  $date the date
 *
 * @return string  formatted date
 */
function wphr_format_date($date, $format = false) {
	if (!$format) {
		$format = wphr_get_option('date_format', 'wphr_settings_general', 'd-m-Y');
	}

	$time = strtotime($date);
	if ($time < 0) {
		return '';
	}
	return date_i18n($format, $time);
}

/**
 * Extract dates between two date range
 *
 * @param  string  $start_date example: 2016-12-16 00:00:00
 * @param  string  $end_date   example: 2016-12-16 23:59:59
 *
 * @return array
 */
function wphr_extract_dates($start_date, $end_date, $format = 'Y-m-d') {
	// if start date has no time set, then add 00:00:00 or 12:00 AM
	if (!preg_match('/\d{2}:\d{2}:\d{2}$/', $start_date)) {
		$start_date = $start_date . ' 00:00:00';
	}

	// if end date has no time set, then add 23:59:59 or 11:59 PM
	if (!preg_match('/\d{2}:\d{2}:\d{2}$/', $end_date)) {
		$end_date = $end_date . ' 23:59:59';
	}

	$start_date = new DateTime($start_date);
	$end_date = new DateTime($end_date);
	$diff = $start_date->diff($end_date);

	// we got a negative date
	if ($diff->invert) {
		return new WP_Error('invalid-date', __('Invalid date provided', 'wphr'));
	}

	$interval = DateInterval::createFromDateString('1 day');
	$period = new DatePeriod($start_date, $interval, $end_date);

	// prepare the periods
	$dates = array();
	foreach ($period as $dt) {
		$dates[] = $dt->format($format);
	}

	return $dates;
}

/**
 * Convert an two dimentational array to one dimentional array object
 *
 * @param  array   $array array of arrays
 *
 * @return array
 */
function wphr_array_to_object($array = []) {
	$new_array = [];

	if ($array) {
		foreach ($array as $key => $value) {
			$new_array[] = (object) $value;
		}
	}

	return $new_array;
}

/**
 * Check date in range or not
 *
 * @param  date   $start_date
 * @param  date   $end_date
 * @param  date   $date_from_user
 *
 * @return boolen
 */
function wphr_check_date_in_range($start_date, $end_date, $date_from_user) {
	// Convert to timestamp
	$start_ts = strtotime($start_date);
	$end_ts = strtotime($end_date);
	$user_ts = strtotime($date_from_user);

	// Check that user date is between start & end
	if (($user_ts >= $start_ts) && ($user_ts <= $end_ts)) {
		return true;
	}

	return false;
}

/**
 * Check date range any point in range or not
 *
 * @param  date   $start_date
 * @param  date   $end_date
 * @param  date   $user_date_start
 * @param  date   $user_date_end
 *
 * @return boolen
 */
function wphr_check_date_range_in_range_exist($start_date, $end_date, $user_date_start, $user_date_end) {

	if (wphr_check_date_in_range($start_date, $end_date, $user_date_start)) {
		return true;
	}

	if (wphr_check_date_in_range($start_date, $end_date, $user_date_end)) {
		return true;
	}

	return false;
}

/**
 * Get durataion between two date
 *
 * @param  date   $start_date
 * @param  date   $end_date
 *
 * @return integer
 */
function wphr_date_duration($start_date, $end_date) {
	$datetime1 = new DateTime($start_date);
	$datetime2 = new DateTime($end_date);
	$interval = $datetime1->diff($datetime2);

	return $interval->format('%a');
}

/**
 * Performance rating elemet
 *
 * @since 0.1
 *
 * @param  string $selected
 *
 * @return array
 */
function wphr_performance_rating($selected = '') {

	$rating = apply_filters('wphr_performance_rating', array(
		'1' => __('Very Bad', 'wphr'),
		'2' => __('Poor', 'wphr'),
		'3' => __('Average', 'wphr'),
		'4' => __('Good', 'wphr'),
		'5' => __('Excellent', 'wphr'),
	));

	if ($selected) {
		return isset($rating[$selected]) ? $rating[$selected] : '';
	}

	return $rating;
}

/**
 * Get wphr option from settings framework
 *
 * @param  sting  $option_name name of the option
 * @param  string $section     name of the section. if it's a separate option, don't provide any
 * @param  string $default     default option
 *
 * @return string
 */
function wphr_get_option($option_name, $section = false, $default = '') {

	if ($section) {
		$option = get_option($section);

		if (isset($option[$option_name])) {
			return $option[$option_name];
		} else {
			return $default;
		}
	} else {
		return get_option($option_name, $default);
	}
}

/**
 * Get wphr logo
 *
 * @return string url of the logo
 */
function wphr_get_site_logo() {
	$logo = (int) wphr_get_option('logo', 'wphr_settings_design');

	if ($logo) {
		$logo_url = wp_get_attachment_url($logo);

		return $logo_url;
	}
}

/**
 * Get month array
 *
 * @param string $title
 *
 * @since  0.1
 *
 * @return array
 */
function wphr_months_dropdown($title = false) {

	$months = [];

	if ($title) {
		$months['-1'] = $title;
	}

	for ($m = 1; $m <= 12; $m++) {
		$months[$m] = date('F', mktime(0, 0, 0, $m));
	}

	return $months;

}

/**
 * Get day array
 *
 * @param string $title
 *
 * @since  1.6
 *
 * @return array
 */
function wphr_day_dropdown() {
	$day = [];

	for ($m = 1; $m <= 31; $m++) {
		$text = $m;
		switch ($m) {
		case 1:
			$text .= 'st';
			break;
		case 2:
			$text .= 'nd';
			break;
		case 3:
			$text .= 'rd';
			break;
		default:
			$text .= 'th';
			break;
		}
		$day[$m] = $text;
	}

	return $day;
}
/**
 * Get Company financial start date
 *
 * @since  0.1
 * @since 1.2.0 Using `wphr_get_financial_year_dates` function
 *
 * @return string date
 */
function wphr_financial_start_date() {
	$financial_year_dates = wphr_get_financial_year_dates();
	return $financial_year_dates['start'];
}

/**
 * Get Company financial end date
 *
 * @since  0.1
 * @since 1.2.0 Using `wphr_get_financial_year_dates` function
 *
 * @return string date
 */
function wphr_financial_end_date() {
	$financial_year_dates = wphr_get_financial_year_dates();
	return $financial_year_dates['end'];
}

/**
 * Get all modules inserted in log table
 *
 * @since 0.1
 *
 * @return array
 */
function wphr_get_audit_log_modules() {
	return \WPHR\HR_MANAGER\Admin\Models\Audit_Log::select('component')->distinct()->get()->toArray();
}

/**
 * Get all modules inserted in log table
 *
 * @since 0.1
 *
 * @return array
 */
function wphr_get_audit_log_sub_component() {
	return \WPHR\HR_MANAGER\Admin\Models\Audit_Log::select('sub_component')->distinct()->get()->toArray();
}

/**
 * wphr Logging functions
 *
 * @since 0.1
 *
 * @return instance
 */
function wphr_log() {
	return \WPHR\HR_MANAGER\Log::instance();
}

/**
 * A file based logging function for debugging
 *
 * @since 0.1
 *
 * @param  string  $message
 * @param  string  $type
 *
 * @return void
 */
function wphr_file_log($message, $type = '') {
	if (!empty($type)) {
		$message = sprintf("[%s][%s] %s\n", date('d.m.Y h:i:s'), $type, $message);
	} else {
		$message = sprintf("[%s] %s\n", date('d.m.Y h:i:s'), $message);
	}

	error_log($message, 3, dirname(WPHR_FILE) . '/debug.log');
}

/**
 * Get people types from various components
 *
 * @return array
 */
function wphr_get_people_types() {
	return apply_filters('wphr_people_types', []);
}

/**
 * Get Country name by country code
 *
 * @since 1.0
 *
 * @param  string $code
 *
 * @return string
 */
function wphr_get_country_name($country) {

	$load_cuntries_states = \WPHR\HR_MANAGER\Countries::instance();
	$countries = $load_cuntries_states->countries;

	// Handle full country name
	if ('-1' != $country) {
		$full_country = (isset($countries[$country])) ? $countries[$country] : $country;
	} else {
		$full_country = '—';
	}

	return $full_country;
}

/**
 * Get State name by country and state code
 *
 * @since 1.0
 *
 * @param  string $country
 * @param  string $state
 *
 * @return string
 */
function wphr_get_state_name($country, $state) {
	$load_cuntries_states = \WPHR\HR_MANAGER\Countries::instance();
	$states = $load_cuntries_states->states;

	// Handle full state name
	$full_state = ($country && $state && isset($states[$country][$state])) ? $states[$country][$state] : $state;

	return $full_state;
}

/**
 * Cron Intervel
 *
 * @since 1.0
 *
 * @param  array $schedules
 *
 * @return array
 */
function wphr_cron_intervals($schedules) {

	$schedules['per_minute'] = array(
		'interval' => MINUTE_IN_SECONDS,
		'display' => __('Every Minute', 'wphr'),
	);

	$schedules['two_min'] = array(
		'interval' => MINUTE_IN_SECONDS * 2,
		'display' => __('Every 2 Minutes', 'wphr'),
	);

	$schedules['five_min'] = array(
		'interval' => MINUTE_IN_SECONDS * 5,
		'display' => __('Every 5 Minutes', 'wphr'),
	);

	$schedules['ten_min'] = array(
		'interval' => MINUTE_IN_SECONDS * 10,
		'display' => __('Every 10 Minutes', 'wphr'),
	);

	$schedules['fifteen_min'] = array(
		'interval' => MINUTE_IN_SECONDS * 15,
		'display' => __('Every 15 Minutes', 'wphr'),
	);

	$schedules['thirty_min'] = array(
		'interval' => MINUTE_IN_SECONDS * 30,
		'display' => __('Every 30 Minutes', 'wphr'),
	);

	$schedules['weekly'] = array(
		'interval' => DAY_IN_SECONDS * 7,
		'display' => __('Once Weekly', 'wphr'),
	);

	return (array) $schedules;
}

/**
 * Show user own media attachment
 *
 * @since 1.0
 *
 * @param  string $query
 *
 * @return string
 */
function wphr_show_users_own_attachments($query) {
	if (!is_user_logged_in()) {
		return $query;
	}

	$id = get_current_user_id();

	if (!current_user_can('manage_options')) {
		if (current_user_can('wphr_hr_manager')
			|| current_user_can('employee')
			|| current_user_can('wphr_crm_manager')
			|| current_user_can('wphr_crm_agent')
		) {
			$query['author'] = $id;
		}
	}

	return $query;
}

/**
 * Get all registered addons for licensing
 *
 * @since 1.0
 *
 * @return array
 */
function wphr_addon_licenses() {
	$licenses = [];

	return apply_filters('wphr_settings_licenses', $licenses);
}

/**
 * Show a readable message about the license status
 *
 * @since 1.0
 *
 * @param  array  $addon
 *
 * @return string
 */
function wphr_get_license_status($addon) {
	if (!is_object($addon['status'])) {
		return false;
	}

	$messages = [];
	$html = '';
	$license = $addon['status'];
	$status_class = 'has-error';

	if (false === $license->success) {

		switch ($license->error) {

		case 'expired':

			$messages[] = sprintf(
				__('Your license key expired on %s. Please <a href="%s" target="_blank" title="Renew your license key">renew your license key</a>.', 'wphr'),
				date_i18n(get_option('date_format'), strtotime($license->expires, current_time('timestamp'))),
				'https://wphrmanager.com/checkout/?edd_license_key=' . $addon['license'] . '&utm_campaign=admin&utm_source=licenses&utm_medium=expired'
			);
			break;

		case 'missing':

			$messages[] = sprintf(
				__('Invalid license. Please <a href="%s" target="_blank" title="Visit account page">visit your account page</a> and verify it.', 'wphr'),
				'https://wphrmanager.com/my-account?utm_campaign=admin&utm_source=licenses&utm_medium=missing'
			);
			break;

		case 'invalid':
		case 'site_inactive':

			$messages[] = sprintf(
				__('Your %s is not active for this URL. Please <a href="%s" target="_blank" title="Visit account page">visit your account page</a> to manage your license key URLs.', 'wphr'),
				$addon['name'],
				'https://wphrmanager.com/my-account?utm_campaign=admin&utm_source=licenses&utm_medium=invalid'
			);
			break;

		case 'item_name_mismatch':

			$messages[] = sprintf(__('This is not a %s.', 'wphr'), $addon['name']);
			break;

		case 'no_activations_left':
			$messages[] = sprintf(__('Your license key has reached its activation limit. <a href="%s">View possible upgrades</a> now.', 'wphr'), 'https://wphrmanager.com/my-account/');
			break;

		}
	} else {

		switch ($license->license) {
		case 'expired':

			$messages[] = sprintf(
				__('Your license key expired on %s. Please <a href="%s" target="_blank" title="Renew your license key">renew your license key</a>.', 'wphr'),
				date_i18n(get_option('date_format'), strtotime($license->expires, current_time('timestamp'))),
				'https://wphrmanager.com/checkout/?edd_license_key=' . $addon['license'] . '&utm_campaign=admin&utm_source=licenses&utm_medium=expired'
			);
			break;

		case 'valid':
			$status_class = 'no-error';
			$now = current_time('timestamp');
			$expiration = strtotime($license->expires, current_time('timestamp'));

			if ('lifetime' === $license->expires) {

				$messages[] = __('License key never expires.', 'wphr');

			} elseif ($expiration > $now && $expiration - $now < (DAY_IN_SECONDS * 30)) {

				$messages[] = sprintf(
					__('Your license key expires soon! It expires on %s. <a href="%s" target="_blank" title="Renew license">Renew your license key</a>.', 'wphr'),
					date_i18n(get_option('date_format'), strtotime($license->expires, current_time('timestamp'))),
					'https://wphrmanager.com/checkout/?edd_license_key=' . $value . '&utm_campaign=admin&utm_source=licenses&utm_medium=renew'
				);

			} else {

				$messages[] = sprintf(
					__('Your license key expires on %s.', 'wphr'),
					date_i18n(get_option('date_format'), strtotime($license->expires, current_time('timestamp')))
				);

			}
			break;
		}

	}

	if (!empty($messages)) {
		foreach ($messages as $message) {

			$html .= '<div class="wphr-license-status ' . $status_class . '">';
			$html .= '<p class="help">' . $message . '</p>';
			$html .= '</div>';

		}
	}

	return $html;
}

/**
 * Get all fields for import/export operation.
 *
 * @return array
 */
function wphr_get_import_export_fields() {
	$wphr_fields = [
		'contact' => [
			'required_fields' => [
				'first_name',
			],
			'fields' => [
				'first_name',
				'last_name',
				'email',
				'phone',
				'mobile',
				'other',
				'website',
				'fax',
				'notes',
				'street_1',
				'street_2',
				'city',
				'state',
				'postal_code',
				'country',
				'currency',
			],
		],
		'company' => [
			'required_fields' => [
				'email',
				'company',
			],
			'fields' => [
				'email',
				'company',
				'phone',
				'mobile',
				'other',
				'website',
				'fax',
				'notes',
				'street_1',
				'street_2',
				'city',
				'state',
				'postal_code',
				'country',
				'currency',
			],
		],
		'employee' => [
			'required_fields' => [
				'first_name',
				'last_name',
				'user_email',
			],
			'fields' => [
				'first_name',
				'middle_name',
				'last_name',
				'user_email',
				'role',
				'department',
				'location',
				'hiring_source',
				'hiring_date',
				'date_of_birth',
				'reporting_to',
				'pay_rate',
				'pay_type',
				'type',
				'status',
				'other_email',
				'phone',
				'work_phone',
				'mobile',
				'address',
				'gender',
				'marital_status',
				'nationality',
				'driving_license',
				'hobbies',
				'user_url',
				'description',
				'street_1',
				'street_2',
				'city',
				'country',
				'state',
				'postal_code',
			],
		],
	];

	return apply_filters('wphr_import_export_csv_fields', $wphr_fields);
}

/**
 * wphr Import/Export JavaScript enqueue.
 *
 * @since  1.0
 *
 * @return void
 */
function wphr_import_export_javascript() {
	global $current_screen;
	$hook = str_replace(sanitize_title(__('wphr Settings', 'wphr')), 'wphr-settings', $current_screen->base);

	if ('wphr-settings_page_wphr-tools' !== $hook) {
		return;
	}

	if (!isset($_GET['tab']) || !in_array(sanitize_text_field($_GET['tab']), ['import', 'export'])) {
		return;
	}

	$wphr_fields = wphr_get_import_export_fields();
	?>
    <script type="text/javascript">
        jQuery( document ).ready( function( $ ) {

            function wphr_str_title_case( string ) {
                var str = string.replace( /_/g, ' ' );

                return str.toLowerCase().split( ' ' ).map( function ( word ) {
                    return ( word.charAt( 0 ).toUpperCase() + word.slice( 1 ) );
                } ).join(' ');
            }

            function wphr_csv_field_mapper( file_selector, fields_selector ) {
                var file = file_selector.files[0];

                var reader = new FileReader();

                var first5000 = file.slice( 0, 5000 );
                reader.readAsText( first5000 );

                reader.onload = function( e ) {
                    var csv = reader.result;
                    // Split the input into lines
                    lines = csv.split('\n'),
                    // Extract column names from the first line
                    columnNamesLine = lines[0];
                    columnNames = columnNamesLine.split(',');

                    var html = '';

                    html += '<option value="">&mdash; Select Field &mdash;</option>';
                    columnNames.forEach( function( item, index ) {
                        item = item.replace( /"/g, "" );

                        html += '<option value="' + index + '">' + item + '</option>';
                    } );

                    if ( html ) {
                        $( fields_selector ).html( html );

                        var field, field_label;
                        $( fields_selector ).each( function() {
                            field_label = $( this ).parent().parent().find( 'label' ).text();

                            var options = $( this ).find( 'option' );
                            var targetOption = $( options ).filter ( function () {
                                var option_text = $( this ).html();

                                var re = new RegExp( field_label, 'i' );

                                return re.test( option_text );
                            } );

                            if ( targetOption ) {
                                $( options ).removeAttr( "selected" );
                                $( this ).val( $( targetOption ).val() );
                            }
                        } );
                    }
                };
            }

            function wphr_csv_importer_field_handler( file_selector ) {
                $( '#fields_container' ).show();

                var fields_html = '';

                var type = $( 'form#import_form #type' ).val();

                fields = wphr_fields[ type ] ? wphr_fields[ type ].fields : [];
                required_fields = wphr_fields[ type ] ? wphr_fields[ type ].required_fields : [];

                var required = '';
                var red_span = '';
                for ( var i = 0;  i < fields.length; i++ ) {

                    if ( required_fields.indexOf( fields[i] ) !== -1 ) {
                        required = 'required';
                        red_span = ' <span class="required">*</span>';
                    } else {
                        required = '';
                        red_span = '';
                    }

                    fields_html += `
                        <tr>
                            <th>
                                <label for="fields[` + fields[i] + `]" class="csv_field_labels">` + wphr_str_title_case( fields[i] ) + red_span + `</label>
                            </th>
                            <td>
                                <select name="fields[` + fields[i] + `]" class="csv_fields" ` + required + `>
                                </select>
                            </td>
                        </tr>`;
                }

                $( '#fields_container' ).html( fields_html );

                wphr_csv_field_mapper( file_selector, '.csv_fields' );
            }

            var fields = [];
            var required_fields = [];

            var wphr_fields = <?php echo json_encode($wphr_fields); ?>;

            var type = $( 'form#export_form #type' ).val();

            fields = wphr_fields[ type ] ? wphr_fields[ type ].fields : [];

            var html = '<ul class="wphr-list list-inline">';
            for ( var i = 0;  i < fields.length; i++ ) {
                html += '<li><label><input type="checkbox" name="fields[]" value="' + fields[i] + '"> ' + wphr_str_title_case( fields[i] ) + '</label></li>';
            }

            html += '<ul>';

            if ( html ) {
                $( '#fields' ).html( html );
            }

            $( 'form#export_form #type' ).on( 'change', function( e ) {
                e.preventDefault();

                $( "#export_form #selecctall" ).prop( 'checked', false );
                var type = $( this ).val();
                fields = wphr_fields[ type ] ? wphr_fields[ type ].fields : [];

                html = '<ul class="wphr-list list-inline">';
                for ( var i = 0;  i < fields.length; i++ ) {
                    html += '<li><label><input type="checkbox" name="fields[]" value="' + fields[i] + '"> ' + wphr_str_title_case( fields[i] ) + '</label></li>';
                }

                html += '<ul>';

                if ( html ) {
                    $( 'form#export_form #fields' ).html( html );
                }
            });

            $( 'form#import_form #csv_file' ).on( 'change', function( e ) {
                e.preventDefault();

                if ( ! this ) {
                    return;
                }

                wphr_csv_importer_field_handler( this );
            });

            if ( $( 'form#import_form' ).find( '#type' ).val() == 'employee' ) {
                $( 'form#import_form' ).find( '#crm_contact_lifestage_owner_wrap' ).hide();
            } else {
                $( 'form#import_form' ).find( '#crm_contact_lifestage_owner_wrap' ).show();
            }

            $( 'form#import_form #type' ).on( 'change', function( e ) {
                $( '#fields_container' ).html( '' );
                $( '#fields_container' ).hide();

                if ( $( this ).val() == 'employee' ) {
                    $( 'form#import_form' ).find( '#crm_contact_lifestage_owner_wrap' ).hide();
                } else {
                    $( 'form#import_form' ).find( '#crm_contact_lifestage_owner_wrap' ).show();
                }

                var sample_csv_url = $( 'form#import_form' ).find( '#download_sample_wrap input' ).val();
                $( 'form#import_form' ).find( '#download_sample_wrap a' ).attr( 'href', sample_csv_url + '&type=' + $( this ).val() );

                if ( $( 'form#import_form #csv_file' ).val() == "" ) {
                    return;
                } else {
                    wphr_csv_importer_field_handler( $( 'form#import_form #csv_file' ).get(0) );
                }
            } );

            $( "#export_form #selecctall" ).change( function(e) {
                e.preventDefault();

                $( "#export_form #fields input[type=checkbox]" ).prop( 'checked', $(this).prop( "checked" ) );
            });

            $( "#users_import_form" ).on( 'submit', function(e) {
                e.preventDefault();
                statusDiv = $( "div#import-status-indicator" );

                statusDiv.show();

                var form = $(this),
                    submit = form.find( 'input[type=submit]' );
                submit.attr( 'disabled', 'disabled' );

                var data = {
                    'action': 'wphr_import_users_as_contacts',
                    'user_role': $(this).find('select[name=user_role]').val(),
                    'contact_owner': $(this).find('select[name=contact_owner]').val(),
                    'life_stage': $(this).find('select[name=life_stage]').val(),
                    'contact_group': $(this).find('select[name=contact_group]').val(),
                    '_wpnonce': $(this).find('input[name=_wpnonce]').val()
                };

                var total_items = 0, left = 0, imported = 0, exists = 0, percent = 0, type = 'success', message = '';

                $.post( ajaxurl, data, function(response) {
                    if ( response.success ) {
                        total_items = response.data.total_items;
                        left = response.data.left;
                        exists = response.data.exists;
                        imported = total_items - left;
                        done = imported - exists;

                        if ( imported > 0 || total_items > 0 ) {
                            percent = Math.floor( ( 100 / total_items ) * ( imported ) );

                            type = 'success';
                            message = 'Successfully imported all users!';
                        } else {
                            message = 'No users found to import!';
                            type = 'error';
                        }

                        statusDiv.find( '#progress-total' ).html( percent + '%' );
                        statusDiv.find( '#progressbar-total' ).val( percent );
                        statusDiv.find( '#completed-total' ).html( 'Imported ' + done + ' out of ' + response.data.total_items );
                        if ( exists > 0 ) {
                            statusDiv.find( '#failed-total' ).html( 'Already Exist ' + exists );
                        }

                        if ( response.data.left > 0 ) {
                            form.submit();
                            return;
                        } else {
                            submit.removeAttr( 'disabled' );

                            swal({
                                title: '',
                                text: message,
                                type: type,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#008ec2'
                            });
                        }
                    }
                });
            });

        });
    </script>
    <?php
}

/**
 * Process or handle import/export submit.
 *
 * @since 1.0.0
 * @since 1.1.15 Declare `field_builder_contacts_fields` with empty an array
 * @since 1.1.18 Handle exporting when no field is given.
 *               Introduce `WPHR_IS_IMPORTING` while importing data
 * @since 1.1.19 Import partial people data in case of existing people
 *
 * @return void
 */
function wphr_process_import_export() {
	if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field($_REQUEST['_wpnonce']), 'wphr-import-export-nonce')) {
		return;
	}

	$is_crm_activated = wphr_is_module_active('crm');
	$is_hrm_activated = wphr_is_module_active('hrm');

	$departments = $is_hrm_activated ? wphr_hr_get_departments_dropdown_raw() : [];
	$designations = $is_hrm_activated ? wphr_hr_get_designation_dropdown_raw() : [];

	$field_builder_contact_options = get_option('wphr-contact-fields');
	$field_builder_contacts_fields = [];

	if (!empty($field_builder_contact_options)) {
		foreach ($field_builder_contact_options as $field) {
			$field_builder_contacts_fields[] = $field['name'];
		}
	}

	$field_builder_employee_options = get_option('wphr-employee-fields');
	$field_builder_employees_fields = [];
	if (!empty($field_builder_employee_options)) {
		foreach ($field_builder_employee_options as $field) {
			$field_builder_employees_fields[] = $field['name'];
		}
	}

	if (isset($_POST['wphr_import_csv'])) {
		define('WPHR_IS_IMPORTING', true);

		$fields = !empty($_POST['fields']) ? custom_sanitize_array($_POST['fields']) : [];
		$type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';

		if (empty($type)) {
			return;
		}

		$data = ['type' => $type, 'fields' => $fields, 'file' => sanitize_text_field($_FILES['csv_file'])];

		do_action('wphr_tool_import_csv_action', $data);

		if (!in_array($type, ['contact', 'company', 'employee'])) {
			return;
		}

		$employee_fields = [
			'work' => [
				'designation',
				'department',
				'location',
				'hiring_source',
				'hiring_date',
				'date_of_birth',
				'reporting_to',
				'pay_rate',
				'pay_type',
				'type',
				'status',
			],
			'personal' => [
				'photo_id',
				'user_id',
				'first_name',
				'middle_name',
				'last_name',
				'other_email',
				'phone',
				'work_phone',
				'mobile',
				'address',
				'gender',
				'marital_status',
				'nationality',
				'driving_license',
				'hobbies',
				'user_url',
				'description',
				'street_1',
				'street_2',
				'city',
				'country',
				'state',
				'postal_code',
			],
		];

		require_once WPHR_INCLUDES . '/lib/parsecsv.lib.php';

		$csv = new parseCSV(sanitize_text_field($_FILES['csv_file']['tmp_name']));

		if (empty($csv->data)) {
			wp_redirect(admin_url("admin.php?page=wphr-tools&tab=import"));
			exit;
		}

		$csv_data = [];

		$csv_data[] = array_keys($csv->data[0]);

		foreach ($csv->data as $data_item) {
			$csv_data[] = array_values($data_item);
		}

		if (!empty($csv_data)) {
			$count = 0;

			foreach ($csv_data as $line) {
				if (empty($line)) {
					continue;
				}

				$line_data = [];

				foreach ($fields as $key => $value) {

					if (!empty($line[$value]) && is_numeric($value)) {
						if ($type == 'employee') {
							if (in_array($key, $employee_fields['work'])) {
								if ($key == 'designation') {
									$line_data['work'][$key] = array_search($line[$value], $designations);
								} else if ($key == 'department') {
									$line_data['work'][$key] = array_search($line[$value], $departments);
								} else {
									$line_data['work'][$key] = $line[$value];
								}

							} else if (in_array($key, $employee_fields['personal'])) {
								$line_data['personal'][$key] = $line[$value];
							} else {
								$line_data[$key] = $line[$value];
							}
						} else {
							$line_data[$key] = isset($line[$value]) ? $line[$value] : '';
							$line_data['type'] = $type;
						}
					}

				}

				if ($type == 'employee' && $is_hrm_activated) {
					if (!isset($line_data['work']['status'])) {
						$line_data['work']['status'] = 'active';
					}

					$item_insert_id = wphr_hr_employee_create($line_data);

					if (is_wp_error($item_insert_id)) {
						continue;
					}
				}

				if (($type == 'contact' || $type == 'company') && $is_crm_activated) {
					$people = wphr_insert_people($line_data, true);

					if (is_wp_error($people)) {
						continue;
					} else {
						$contact = new \WPHR\HR_MANAGER\CRM\Contact(absint($people->id), 'contact');
						$contact_owner = isset($_POST['contact_owner']) ? sanitize_text_field(absint($_POST['contact_owner'])) : wphr_crm_get_default_contact_owner();
						$life_stage = isset($_POST['life_stage']) ? sanitize_key($_POST['life_stage']) : '';

						if (!$people->existing) {
							$contact->update_meta('life_stage', $life_stage);
							$contact->update_meta('contact_owner', $contact_owner);

						} else {
							if (!$contact->get_life_stage()) {
								$contact->update_meta('life_stage', $life_stage);
							}

							if (!$contact->get_contact_owner()) {
								$contact->update_meta('contact_owner', $contact_owner);
							}
						}

						if (!empty($_POST['contact_group'])) {
							$contact_group = sanitize_text_field(absint($_POST['contact_group']));

							$existing_data = \WPHR\HR_MANAGER\CRM\Models\ContactSubscriber::where(['group_id' => $contact_group, 'user_id' => $people->id])->first();

							if (empty($existing_data)) {
								$hash = sha1(microtime() . 'wphr-subscription-form' . $contact_group . $people->id);

								wphr_crm_create_new_contact_subscriber([
									'group_id' => $contact_group,
									'user_id' => $people->id,
									'status' => 'subscribe',
									'subscribe_at' => current_time('mysql'),
									'unsubscribe_at' => null,
									'hash' => $hash,
								]);
							}
						}

						if (!empty($field_builder_contacts_fields)) {
							foreach ($field_builder_contacts_fields as $field) {
								if (isset($line_data[$field])) {
									wphr_people_update_meta($people->id, $field, $line_data[$field]);
								}
							}
						}
					}
				}

				++$count;
			}

		}

		wp_redirect(admin_url("admin.php?page=wphr-tools&tab=import&imported=$count"));
		exit;
	}

	if (isset($_POST['wphr_export_csv'])) {
		if (!empty($_POST['type']) && !empty($_POST['fields'])) {
			$type = sanitize_text_field($_POST['type']);
			$fields = custom_sanitize_array($_POST['fields']);

			if ($type == 'employee' && $is_hrm_activated) {
				$args = [
					'number' => -1,
					'status' => 'all',
				];

				$items = wphr_hr_get_employees($args);
			}

			if (($type == 'contact' || $type == 'company') && $is_crm_activated) {
				$args = [
					'type' => $type,
					'count' => true,
				];
				$total_items = wphr_get_peoples($args);

				$args = [
					'type' => $type,
					'offset' => 0,
					'number' => -1,
				];
				$items = wphr_get_peoples($args);
			}

			//@todo do_action()

			$csv_items = [];

			$x = 0;
			foreach ($items as $item) {

				if (empty($fields)) {
					continue;
				}

				foreach ($fields as $field) {
					if ($type == 'employee') {

						if (in_array($field, $field_builder_employees_fields)) {
							$csv_items[$x][$field] = get_user_meta($item->id, $field, true);
						} else {
							switch ($field) {
							case 'department':
								$csv_items[$x][$field] = $item->get_department_title();
								break;

							case 'role':
								$csv_items[$x][$field] = $item->get_job_title();
								break;

							default:
								$csv_items[$x][$field] = $item->{$field};
								break;
							}
						}

					} else {
						if (in_array($field, $field_builder_contacts_fields)) {
							$csv_items[$x][$field] = wphr_people_get_meta($item->id, $field, true);
						} else {
							if (isset($item->{$field})) {
								$csv_items[$x][$field] = $item->{$field};
							}
						}
					}
				}

				$x++;
			}

			$file_name = 'export_' . date('d_m_Y') . '.csv';

			wphr_make_csv_file($csv_items, $file_name);

		} else {
			wp_redirect(admin_url("admin.php?page=wphr-tools&tab=export"));
			exit();
		}
	}
}

/**
 * Display importer tool notice.
 *
 *
 * @return void
 */
function wphr_importer_notices() {
	if (!isset($_REQUEST['page']) || sanitize_text_field($_REQUEST['page']) != 'wphr-tools' || !isset($_REQUEST['tab']) || sanitize_text_field($_REQUEST['tab']) != 'import') {
		return;
	}

	if (isset($_REQUEST['imported'])) {
		if (intval(sanitize_text_field($_REQUEST['imported'])) == 0) {
			$message = __('Nothing to import or items are already exists.', 'wphr');
			echo "<div class='notice error'><p>{$message}</p></div>";
		} else {
			$message = sprintf(__('%s items successfully imported.', 'wphr'), number_format_i18n(sanitize_text_field($_REQUEST['imported'])));
			echo "<div class='notice updated'><p>{$message}</p></div>";
		}
	}
}

/**
 * Merge user defined arguments into defaults array.
 *
 * This function is similiar to wordpress wp_parse_args().
 * It's support multidimensional array.
 *
 * @param  array $args
 * @param  array $defaults Optional.
 *
 * @return array
 */
function wphr_parse_args_recursive(&$args, $defaults = []) {
	$args = (array) $args;
	$defaults = (array) $defaults;
	$r = $defaults;

	foreach ($args as $k => &$v) {
		if (is_array($v) && isset($r[$k])) {
			$r[$k] = wphr_parse_args_recursive($v, $r[$k]);
		} else {
			$r[$k] = $v;
		}
	}

	return $r;
}

/**
 * wphr Email sender
 *
 * @since 1.1.0
 * @since 1.1.17 Use site name instead of current user name for default From header
 * @since 1.2.0  Always return true during any importing process
 *
 * @param string|array $to
 * @param string       $subject
 * @param string       $message
 * @param string|array $headers
 * @param array        $attachments
 * @param array        $custom_headers
 *
 * @return boolean
 */
function wphr_mail($to, $subject, $message, $headers = '', $attachments = [], $custom_headers = []) {

	if (defined('WPHR_IS_IMPORTING') && WPHR_IS_IMPORTING) {
		return true;
	}

	$callback = function ($phpmailer) use ($custom_headers) {
		$wphr_email_settings = get_option('wphr_settings_wphr-email_general', []);
		$wphr_email_smtp_settings = get_option('wphr_settings_wphr-email_smtp', []);

		if (!isset($wphr_email_settings['from_email'])) {
			$from_email = get_option('admin_email');
		} else {
			$from_email = $wphr_email_settings['from_email'];
		}

		if (!isset($wphr_email_settings['from_name'])) {
			$from_name = get_bloginfo('name');
		} else {
			$from_name = $wphr_email_settings['from_name'];
		}

		$content_type = 'text/html';

		$phpmailer->From = apply_filters('wphr_mail_from', $from_email);
		$phpmailer->FromName = apply_filters('wphr_mail_from_name', $from_name);
		$phpmailer->ContentType = apply_filters('wphr_mail_content_type', $content_type);

		//Return-Path
		$phpmailer->Sender = apply_filters('wphr_mail_return_path', $phpmailer->From);

		if (!empty($custom_headers)) {
			foreach ($custom_headers as $key => $value) {
				$phpmailer->addCustomHeader($key, $value);
			}
		}

		if (isset($wphr_email_smtp_settings['debug']) && $wphr_email_smtp_settings['debug'] == 'yes') {
			$phpmailer->SMTPDebug = true;
		}

		if (isset($wphr_email_smtp_settings['enable_smtp']) && $wphr_email_smtp_settings['enable_smtp'] == 'yes') {
			$phpmailer->Mailer = 'smtp'; //'smtp', 'mail', or 'sendmail'

			$phpmailer->Host = $wphr_email_smtp_settings['mail_server'];
			$phpmailer->SMTPSecure = ($wphr_email_smtp_settings['authentication'] != '') ? $wphr_email_smtp_settings['authentication'] : 'smtp';
			$phpmailer->Port = $wphr_email_smtp_settings['port'];

			if ($wphr_email_smtp_settings['authentication'] != '') {
				$phpmailer->SMTPAuth = true;
				$phpmailer->Username = $wphr_email_smtp_settings['username'];
				$phpmailer->Password = $wphr_email_smtp_settings['password'];
			}
		}
	};

	add_action('phpmailer_init', $callback);
	ob_start();
	$is_mail_sent = wp_mail($to, $subject, $message, $headers, $attachments);
	$debug_log = ob_get_clean();
	if (!$is_mail_sent) {
		error_log($debug_log);
	}

	// ob_start();
	//  if(isset($to[0]) && !empty($to[0])){
	//     $is_mail_sent = wp_mail( $to[0], $subject, $message, $headers, $attachments );
	// }else{
	//     $is_mail_sent = wp_mail( $to, $subject, $message, $headers, $attachments );
	// }
	// $debug_log = ob_get_clean();
	// if ( ! $is_mail_sent ) {
	//     error_log( $debug_log );
	// }

	remove_action('phpmailer_init', $callback);

	return $is_mail_sent;
}

/**
 * Email JavaScript enqueue.
 *
 * @return void
 */
function wphr_email_settings_javascript() {
	wp_enqueue_style('wphr-sweetalert');
	wp_enqueue_script('wphr-sweetalert');
	wp_enqueue_style('wphr-timepicker');
	wp_enqueue_script('wphr-timepicker');

	?>
    <script type="text/javascript">
        jQuery( document ).ready( function($) {
            $( "a#smtp-test-connection" ).click( function(e) {
                e.preventDefault();
                $( "a#smtp-test-connection" ).attr( 'disabled', 'disabled' );
                $( "a#smtp-test-connection" ).parent().find( '.wphr-loader' ).show();

                var data = {
                    'action': 'wphr_smtp_test_connection',
                    'enable_smtp': $('input[name=enable_smtp]:checked').val(),
                    'mail_server': $('input[name=mail_server]').val(),
                    'port': $('input[name=port]').val(),
                    'authentication': $('select[name=authentication]').val(),
                    'username': $('input[name=username]').val(),
                    'password': $('input[name=password]').val(),
                    'to' : $('#smtp_test_email_address').val(),
                    '_wpnonce': '<?php echo wp_create_nonce("wphr-smtp-test-connection-nonce"); ?>'
                };

                $.post( ajaxurl, data, function(response) {
                    $( "a#smtp-test-connection" ).removeAttr( 'disabled' );
                    $( "a#smtp-test-connection" ).parent().find( '.wphr-loader' ).hide();

                    var type = response.success ? 'success' : 'error';

                    if (response.data) {
                        swal({
                            title: '',
                            text: response.data,
                            type: type,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#008ec2'
                        });
                    }
                });
            });
        });

        jQuery( document ).ready( function($) {
            $( "a#imap-test-connection" ).click( function(e) {
                e.preventDefault();
                $( "a#imap-test-connection" ).attr( 'disabled', 'disabled' );
                $( "a#imap-test-connection" ).parent().find( '.wphr-loader' ).show();

                var data = {
                    'action': 'wphr_imap_test_connection',
                    'mail_server': $('input[name=mail_server]').val(),
                    'username': $('input[name=username]').val(),
                    'password': $('input[name=password]').val(),
                    'protocol': $('select[name=protocol]').val(),
                    'port': $('input[name=port]').val(),
                    'authentication': $('select[name=authentication]').val(),
                    '_wpnonce': '<?php echo wp_create_nonce("wphr-imap-test-connection-nonce"); ?>'
                };

                $.post( ajaxurl, data, function(response) {
                    $( "a#imap-test-connection" ).removeAttr( 'disabled' );
                    $( "a#imap-test-connection" ).parent().find( '.wphr-loader' ).hide();

                    var type = response.success ? 'success' : 'error';

                    if ( response.data ) {
                        var status = response.success ? 1 : 0;
                        $('#imap_status').val(status);

                        swal({
                            title: '',
                            text: response.data,
                            type: type,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#008ec2'
                        });
                    }
                });
            });
        });
    </script>
    <?php
}

/**
 * Determine if the inbound/imap mail feature is active or not.
 *
 * @return boolean
 */
function wphr_is_imap_active() {
	$options = get_option('wphr_settings_wphr-email_imap', []);

	$imap_status = (boolean) isset($options['imap_status']) ? $options['imap_status'] : 0;
	$enable_imap = (isset($options['enable_imap']) && $options['enable_imap'] == 'yes') ? true : false;

	if ($enable_imap && $imap_status) {
		return true;
	}

	return false;
}

/**
 * Check if the wphr Email SMTP settings is enabled or not
 *
 * @since 1.1.6
 *
 * @return boolean
 */
function wphr_is_smtp_enabled() {
	$wphr_email_smtp_settings = get_option('wphr_settings_wphr-email_smtp', []);

	if (isset($wphr_email_smtp_settings['enable_smtp']) && filter_var($wphr_email_smtp_settings['enable_smtp'], FILTER_VALIDATE_BOOLEAN)) {
		return true;
	}

	return false;
}

/**
 * Determine if the module is active or not.
 *
 * @return boolean
 */
function wphr_is_module_active($module_key) {
	$modules = get_option('wphr_modules', []);

	return isset($modules[$module_key]);
}

/**
 * Make csv file from array and force download
 *
 * @param array   $items
 * @param boolean $field_data (optional)
 *
 * @param string  $file_name
 */
function wphr_make_csv_file($items, $file_name, $field_data = true) {
	$file_name = (!empty($file_name)) ? $file_name : 'csv_' . date('d_m_Y') . '.csv';

	if (empty($items)) {
		return;
	}

	$columns = array_keys($items[0]);

	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=' . $file_name);

	$output = fopen('php://output', 'w');

	$columns = array_map(function ($column) {
		$column = ucwords(str_replace('_', ' ', $column));

		return $column;
	}, $columns);

	fputcsv($output, $columns);

	if ($field_data) {
		foreach ($items as $item) {
			$csv_row = array_map(function ($item_val) {

				if (is_array($item_val)) {
					return implode(', ', $item_val);
				}

				return $item_val;

			}, $item);

			fputcsv($output, $csv_row);
		}
	}

	exit();
}

/**
 * Import/Export sample CSV download action hook
 *
 * @param void
 */
function wphr_import_export_download_sample_action() {
	if (!isset($_GET['action']) || sanitize_text_field($_GET['action']) != 'download_sample') {
		return;
	}

	if (!wp_verify_nonce($_GET['_wpnonce'], 'wphr-emport-export-sample-nonce')) {
		return;
	}

	if (!isset($_GET['type'])) {
		return;
	}

	$type = strtolower(sanitize_text_field($_GET['type']));
	$fields = wphr_get_import_export_fields();

	if (isset($fields[$type])) {
		$keys = $fields[$type]['fields'];
		$keys = array_flip($keys);
		$file_name = "sample_csv_{$type}.csv";

		wphr_make_csv_file([$keys], $file_name, false);
	}

	return;
}

/**
 * Enqueue locale scripts for fullcalendar
 *
 * @since 1.0.0
 *
 * @return void
 */
function wphr_enqueue_fullcalendar_locale() {
	$locale = get_locale();
	$script = '';

	// no need to add locale for en_US
	if ('en_US' === $locale) {
		return;
	}

	$locale = explode('_', $locale);

	// make sure we have two segments - 1.lang, 2.country
	if (count($locale) < 2) {
		return;
	}

	$lang = $locale[0];
	$country = strtolower($locale[1]);

	if ($lang === $country) {
		$script = $lang;
	} else {
		$script = $lang . '-' . $country;
	}

	if (file_exists(WPHR_PATH . "/assets/vendor/fullcalendar/lang/{$script}.js")) {
		wp_enqueue_script('wphr-fullcalendar-locale', WPHR_PATH . "/vendor/fullcalendar/lang/{$script}.js", array('wphr-fullcalendar'), null, true);
	}

}

/**
 * Generate random key
 *
 * @since 1.1.8
 *
 * @return string
 */
function wphr_generate_key() {
	if (function_exists('openssl_random_pseudo_bytes')) {
		$key = bin2hex(openssl_random_pseudo_bytes(20));
	} else {
		$key = sha1(wp_rand());
	}

	return $key;
}

/**
 * Include required HTML form wphr-popup
 *
 * @since 1.1.12
 *
 * @return void
 */
function wphr_include_popup_markup() {
	include_once WPHR_INCLUDES . '/admin/views/wphr-modal.php';
	wphr_get_js_template(WPHR_INCLUDES . '/admin/views/address.php', 'wphr-address');
}

/**
 * Dequeue/Deregister select2 from other plugins
 *
 * @since 1.1.13
 *
 * @return void
 */
function wphr_dequeue_other_select2_sources() {
	// select2 handle is used by woocommerce
	wp_deregister_script('select2');
	wp_dequeue_script('select2');
}

/**
 * Remove select2 enqueued by other plugins
 *
 * Whenever enqueue wphr-select2, call this function to
 * make sure only one select2 is loaded
 *
 * @since 1.1.13
 *
 * @return void
 */
function wphr_remove_other_select2_sources() {
	add_action('admin_enqueue_scripts', 'wphr_dequeue_other_select2_sources', 999999);
	add_action('wp_enqueue_scripts', 'wphr_dequeue_other_select2_sources', 999999);
}

/**
 * Returns a word in plural form.
 *
 * @since 1.1.16
 *
 * @param string $word The word in singular form.
 *
 * @return string The word in plural form.
 */
function wphr_pluralize($word) {
	return \Doctrine\Common\Inflector\Inflector::pluralize($word);
}

/**
 * Get the client IP address
 *
 * @since 1.1.16
 *
 * @return string
 */
function wphr_get_client_ip() {
	$ipaddress = '';

	if (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	} else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	} else if (isset($_SERVER['HTTP_FORWARDED'])) {
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	} else if (isset($_SERVER['REMOTE_ADDR'])) {
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	} else {
		$ipaddress = 'UNKNOWN';
	}

	return $ipaddress;
}

/**
 * Converts any value to boolean true or false
 *
 * @since 1.2.0
 *
 * @param mixed $value
 *
 * @return boolean
 */
function wphr_validate_boolean($value) {
	return filter_var($value, FILTER_VALIDATE_BOOLEAN);
}

/**
 * Get financial year start and end dates
 *
 * @since 1.2.0
 *
 * @return array
 */
function wphr_get_financial_year_dates() {
	$start_month = wphr_get_option('gen_financial_month', 'wphr_settings_general', 1);

	$year = date('Y');
	$current_month = date('n');

	/**
	 * Suppose, $start_month is July and today is May 2017. Then we should get
	 * start = 2016-07-01 00:00:00 and end = 2017-06-30 23:59:59.
	 *
	 * On the other hand, if $start_month = January, then we should get
	 * start = 2017-01-01 00:00:00 and end = 2017-12-31 23:59:59.
	 */
	if ($current_month < $start_month) {
		$year = $year - 1;
	}

	$months = wphr_months_dropdown();
	$start = date('Y-m-d 00:00:00', strtotime("first day of $months[$start_month] $year"));
	$end = date('Y-m-d 23:59:59', strtotime("$start + 12 months - 1 day"));

	return [
		'start' => $start,
		'end' => $end,
	];
}

/**
 * Get finanicial start and end years that a date belongs to
 *
 * @since 1.2.0
 *
 * @param string $date
 *
 * @return array
 */
function get_financial_year_from_date($date) {
	$fy_start_month = wphr_get_option('gen_financial_month', 'wphr_settings_general', 1);
	$fy_start_month = absint($fy_start_month);

	$date_timestamp = strtotime($date);
	$date_year = absint(date('Y', $date_timestamp));
	$date_month = absint(date('n', $date_timestamp));

	if (1 === $fy_start_month) {
		return [
			'start' => $date_year, 'end' => $date_year,
		];

	} else if ($date_month <= ($fy_start_month - 1)) {
		return [
			'start' => ($date_year - 1), 'end' => $date_year,
		];

	} else {
		return [
			'start' => $date_year, 'end' => ($date_year + 1),
		];
	}
}
/**
 * Get office timing options
 *
 * @since 1.4.0
 *
 * @return array
 */
function get_office_timing() {
	global $wpdb;
	$time_slot = array();
	$user = wp_get_current_user();
	$office_end_time = $office_start_time = 0;
	if (in_array('employee', (array) $user->roles)) {
		$user_id = get_current_user_id();
		$location_id = \WPHR\HR_MANAGER\HRM\Models\Employee::select('location')->where('user_id', $user_id)->get()->toArray();
		if (count($location_id)) {
			$location_id = $location_id[0]['location'];
		} else {
			$location_id = 0;
		}
		$office_timing = WPHR\HR_MANAGER\Admin\Models\Company_Locations::select('*')->from($wpdb->prefix . 'wphr_company_locations')->where('id', $location_id)->get()->toArray();
		if (is_array($office_timing)) {
			$office_start_time = isset($office_timing[0]['office_start_time']) ? strtotime($office_timing[0]['office_start_time']) : 0;
			$office_end_time = isset($office_timing[0]['office_end_time']) ? strtotime($office_timing[0]['office_end_time']) : 0;
		}
	}

	$start = '12:00AM';
	$end = '11:59PM';
	$interval = '+15 minutes';

	$start_str = strtotime($start);
	$end_str = strtotime($end);
	$now_str = $start_str;
	$time_slot = array();
	while ($now_str <= $end_str) {
		$timevalue = date('H:i:s', $now_str);
		if ($office_start_time && $office_end_time) {
			if ($office_start_time <= $now_str && $office_end_time >= $now_str) {
				$time_slot[$timevalue] = date('h:i A', $now_str);
			}
		} else {
			$time_slot[$timevalue] = date('h:i A', $now_str);
		}
		$now_str = strtotime($interval, $now_str);
	}

	return $time_slot;
}

/**
 * Get emp working hours
 *
 * @since 1.4.0
 *
 * @param int $employee_id
 *
 * @return int
 */
function get_employee_working_hours($employee_id) {
	global $wpdb;
	$query = "SELECT TIMESTAMPDIFF(HOUR, L.office_start_time, L.office_end_time) AS working_hours";
	$query .= " FROM `{$wpdb->prefix}wphr_company_locations` AS L LEFT JOIN {$wpdb->prefix}wphr_hr_employees AS E ON L.id = E.location";
	$query .= " WHERE E.user_id = %d";
	$query = "SELECT L.office_working_hours AS working_hours";
	$query .= " FROM `{$wpdb->prefix}wphr_company_locations` AS L LEFT JOIN {$wpdb->prefix}wphr_hr_employees AS E ON L.id = E.location";
	$query .= " WHERE E.user_id = %d";

	$working_hours = $wpdb->get_var($wpdb->prepare($query, $employee_id));
	$working_hours = $working_hours ? $working_hours : 9;

	return $working_hours;
}

/**
 * Get office timing options
 *
 * @since 1.12
 *
 * @return array
 */
function get_office_timezone() {
	$structure = array();
	$offset_range = array(-12, -11.5, -11, -10.5, -10, -9.5, -9, -8.5, -8, -7.5, -7, -6.5, -6, -5.5, -5, -4.5, -4, -3.5, -3, -2.5, -2, -1.5, -1, -0.5, 0, 0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5, 5.5, 5.75, 6, 6.5, 7, 7.5, 8, 8.5, 8.75, 9, 9.5, 10, 10.5, 11, 11.5, 12, 12.75, 13, 13.75, 14);
	foreach ($offset_range as $offset) {
		$offset_value = $offset;
		if (0 <= $offset) {
			$offset_name = '+' . $offset;
		} else {
			$offset_name = (string) $offset;
		}

		$offset_name = str_replace(array('.25', '.5', '.75'), array(':15', ':30', ':45'), $offset_name);
		$offset_name = 'UTC' . $offset_name;
		$structure[esc_attr($offset_value)] = esc_html($offset_name);

	}
	return $structure;
}

/**
 * Get financial year by user location
 *
 * @since 1.2.0
 *
 * @return array
 */
function wphr_get_financial_year_dates_by_user($user_id, $leave_date = false) {

	global $wpdb;

	$employee = new \WPHR\HR_MANAGER\HRM\Employee(intval($user_id));

	$office_financial_year_start = $employee->get_leave_year();
	$user_location = $employee->get_work_location_id();
	$office_financial_day_start = 1;
	if (!$employee->get_apply_leave_year()) {
		if (!$user_location) {
			return false;
		}
		$office_financial_year_start = $wpdb->get_var($wpdb->prepare("SELECT office_financial_year_start FROM `{$wpdb->prefix}wphr_company_locations` WHERE id = %d", $user_location));
	}
	if ($user_location) {
		$start_day = $wpdb->get_var($wpdb->prepare("SELECT office_financial_day_start FROM `{$wpdb->prefix}wphr_company_locations` WHERE id = %d", $user_location));
		if ($start_day) {
			$office_financial_day_start = $start_day;
		}
	}
	/**
	 * Check finacial year is set or not
	 */
	if (!$office_financial_year_start && !$leave_date) {
		return false;
	}
	if (!$office_financial_year_start) {
		$office_financial_year_start = wphr_get_option('gen_financial_month', 'wphr_settings_general', 1);
	}
	$start_month = $office_financial_year_start;

	$year = date('Y');
	$current_month = date('n');
	$current_day = date('j');

	/**
	 * Suppose, $start_month is July and today is May 2017. Then we should get
	 * start = 2016-07-01 00:00:00 and end = 2017-06-30 23:59:59.
	 *
	 * On the other hand, if $start_month = January, then we should get
	 * start = 2017-01-01 00:00:00 and end = 2017-12-31 23:59:59.
	 */
	if ($current_month < $start_month) {
		$year = $year - 1;
	}
	if ($current_month == $start_month && $office_financial_day_start > $current_day) {
		$year = $year - 1;
	}

	$months = wphr_months_dropdown();
	$start = date('Y-m-' . $office_financial_day_start . ' 00:00:00', strtotime("first day of $months[$start_month] $year"));
	$end = date('Y-m-d 23:59:59', strtotime("$start + 12 months - 1 day"));
	$start = date('Y-m-d 00:00:00', strtotime($start));
	if (!$office_financial_year_start) {
		$financial_year_dates = wphr_get_financial_year_dates();
		$start = $financial_year_dates['start'];
		$end = $financial_year_dates['end'];
	}
	$from_date = $start;
	$to_date = $end;
	if ($leave_date) {
		$leave_date = date('Y-m-d 00:00:00', strtotime($leave_date));
		$start = date('Y-m-' . $office_financial_day_start . ' 00:00:00', strtotime("first day of $months[$start_month] $year"));
		if ($leave_date > $to_date || $leave_date < $from_date) {
			$year = date('Y', strtotime($leave_date));
			$start = date('Y-m-' . $office_financial_day_start . ' 00:00:00', strtotime("first day of $months[$start_month] $year"));
			if ($start > $leave_date) {
				$year--;
				$start = date('Y-m-' . $office_financial_day_start . ' 00:00:00', strtotime("first day of $months[$start_month] $year"));
			}
		}
		$end = date('Y-m-d 23:59:59', strtotime("$start + 12 months - 1 day"));
	}
	return [
		'start' => date('Y-m-d 00:00:00', strtotime($start)),
		'end' => $end,
	];

}

/**
 * This function is used to check if column is exist or not
 *
 * On adding column this function will be used to check if column is already exist
 *  and returns true if column exist else false
 *
 *
 * @return bool true/false on checking existence of column
 */
function check_table_column_exists($table_name, $column_name) {
	global $wpdb;
	$column = $wpdb->get_col($wpdb->prepare(
		"SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ", $wpdb->dbname, $table_name, $column_name
	));

	if (!empty($column)) {
		return true;
	}
	return false;
}

/**
 * Get list of leave year userwise
 *
 * @since 1.2
 *
 * @param  int    $user_id
 *
 * @return array
 */

function get_user_leave_years($user_id) {
	global $wpdb;
	$sql = $wpdb->prepare("SELECT DISTINCT YEAR(start_date) AS date FROM `{$wpdb->prefix}wphr_hr_leave_requests`  WHERE user_id = %d  AND status = 1", $user_id);
	$start_date_list = $wpdb->get_col($sql);
	$sql = $wpdb->prepare("SELECT DISTINCT YEAR(end_date) AS date FROM `{$wpdb->prefix}wphr_hr_leave_requests` WHERE user_id = %d AND status = 1", $user_id);
	$end_date_list = $wpdb->get_col($sql);
	//print_r($end_date_list);
	return array_unique(array_merge((array) $start_date_list, (array) $end_date_list));
}

/**
 * Change the label "Employee" by departmen
 *
 * @since 2.1
 *
 * @param varchar $title
 *
 * @return string
 *
 */
function wphr_user_profile_label($title) {
	if (!is_page()) {
		return $title;
	}
	global $post;
	if (has_shortcode($post->post_content, 'wp-hr-employee-profile')) {
		$user_id = isset($_GET['id']) && sanitize_text_field($_GET['id']) ? sanitize_text_field($_GET['id']) : 0;
		if (!$user_id) {
			$user_id = get_current_user_id();
		}
		if (!$user_id) {
			return $title;
		}
		$employee = new WPHR\HR_MANAGER\HRM\Employee(intval($user_id));
		$emp_label = $employee->get_department_emp_label();
		if ($emp_label && strtolower($post->post_title) == strtolower($title)) {
			return $emp_label . ' ' . __('Profile', 'wphr');
		}
	}

	//if wordpress can't find the title return the default
	return $title;
}

/**
 * Remove title filter for menus
 *
 * @since 2.1
 *
 * @param array $nav_menu
 *
 * @return array
 *
 */
function wphr_remove_title_filter($nav_menu) {
	remove_filter('the_title', 'wphr_user_profile_label');
	return $nav_menu;
}

/**
 * Add title filter after menu generated
 *
 * @since 2.1
 *
 * @param array $nav_menu
 *
 * @return array
 *
 */
function wphr_add_title_filter($nav_menu) {
	add_filter('the_title', 'wphr_user_profile_label');
	return $nav_menu;
}

/**
 * Custom sanitization function for array
 *
 * @since 2.9.2
 *
 * @param array $array
 *
 * @return array
 *
 */
function custom_sanitize_array(&$array) {
	foreach ($array as &$value) {
		if (!is_array($value)) {
			$value = sanitize_text_field($value);
		} else {
			custom_sanitize_array($value);
		}
	}
	return $array;
}