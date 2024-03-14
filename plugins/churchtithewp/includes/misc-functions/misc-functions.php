<?php
/**
 * Church Tithe WP
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/Church Tithe WP
 * @copyright   Copyright (c) 2018, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the SVG icon to use for Church Tithe WP
 *
 * @param  string $fill_color The color to use as the fill for the svg.
 * @since  1.0.0.
 * @return string
 */
function church_tithe_wp_get_svg_icon( $fill_color = '#000000' ) {

	return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400" enable-background="new 0 0 400 400" fill="' . $fill_color . '"><title>Churchtithelogo253</title><g id="Layer_8" data-name="Layer 8"><path class="cls-1" d="M357.2,225.609l-98.8-34.487V169.916c0-1.388-.077-2.767-.214-4.135a25.953,25.953,0,0,0-1.958-17.075L208.616,49.984a9.567,9.567,0,0,0-17.232,0l-47.613,98.722a25.953,25.953,0,0,0-1.958,17.075c-.137,1.368-.214,2.747-.214,4.135v21.023L42.763,225.6A25.833,25.833,0,0,0,25.5,249.949v79.675a25.831,25.831,0,0,0,25.8,25.8H348.7a25.831,25.831,0,0,0,25.8-25.8V249.97A25.829,25.829,0,0,0,357.2,225.609ZM181.764,340.426V270.717a18.236,18.236,0,1,1,36.472,0v69.709ZM200,237.48a33.274,33.274,0,0,0-33.236,33.237v69.709H156.6V169.916a26.25,26.25,0,0,1,8.949-19.741l32.61-28.58a2.8,2.8,0,0,1,3.684,0l32.61,28.581a26.25,26.25,0,0,1,8.949,19.741v170.51H233.236V270.717A33.274,33.274,0,0,0,200,237.48Zm11.729-127.167a17.85,17.85,0,0,0-23.458,0l-16.162,14.166L200,66.649l27.891,57.83ZM77.675,340.426V305.032a18.236,18.236,0,1,1,36.472,0v35.394Zm51.472,0V305.032a33.236,33.236,0,1,0-66.472,0v35.394H51.3a10.814,10.814,0,0,1-10.8-10.8V249.949a10.813,10.813,0,0,1,7.227-10.192L141.6,206.835V340.426Zm156.706,0V305.032a18.236,18.236,0,1,1,36.472,0v35.394Zm73.647-10.8a10.814,10.814,0,0,1-10.8,10.8H337.325V305.032a33.236,33.236,0,1,0-66.472,0v35.394H258.4V207.01l93.857,32.761a10.813,10.813,0,0,1,7.242,10.2Z" transform="translate(-25.5 -44.575)"/><circle class="cls-1" cx="174.5" cy="140.909" r="24.085"/></g></svg>';
}

/**
 * Convert a value in cents, or the lowest possible unit in the currency, back to the normal amount and put the currency symbol at the start.
 *
 * @since 1.0.0.
 * @param int    $cents The number of cents being displayed.
 * @param string $currency The 3-letter currency in which they are being displayed.
 * @return bool
 */
function church_tithe_wp_get_visible_amount( $cents, $currency ) {

	// If this is a decimal currency (not a zero decimal currency) https://stripe.com/docs/currencies#zero-decimal.
	if ( ! church_tithe_wp_is_a_zero_decimal_currency( $currency ) ) {
		$amount = ( $cents / 100 );
	} else {
		$amount = $cents;
	}

	return html_entity_decode( church_tithe_wp_currency_symbol( $currency ) ) . $amount;
}

/**
 * Set the default value if the first value is empty
 *
 * @since    1.0.0
 * @param    array  $saved_settings The array of saved settings.
 * @param    string $key The setting we went to extract.
 * @param    string $default_value The default value to use if none exists.
 * @return   array
 */
function church_tithe_wp_get_saved_setting( $saved_settings, $key, $default_value = null ) {

	if ( isset( $saved_settings[ $key ] ) ) {

		// If the saved value is empty.
		if ( empty( $saved_settings[ $key ] ) ) {

			// If a default value was passed-in.
			if ( $default_value ) {
				return $default_value;
			} else {

				// If a default value was not passed in.
				return $saved_settings[ $key ];
			}
		} else {

			// If there is a saved value.
			return $saved_settings[ $key ];

		}
	} else {

		return $default_value;

	}
}

/**
 * Search for the currencies which match a given search term.
 *
 * @since 1.0
 * @param string $search_term The term being used to search for a currency.
 * @return array $currencies A list of the available currencies
 */
function church_tithe_wp_currency_search_results( $search_term ) {

	// Get all available values.
	$all_available_currencies = church_tithe_wp_get_currencies();

	$matching_currencies = array();

	// Search the array.
	foreach ( $all_available_currencies as $currency_key => $currency_value ) {
		if ( stripos( $currency_key, $search_term ) !== false || stripos( $currency_value, $search_term ) !== false ) {
			$matching_currencies[ $currency_key ] = $currency_value;
		}
	}

	return $matching_currencies;
}

/**
 * Get Currencies. This checks at Stripe for available currencies to this account and formats them into a nice readable array.
 *
 * @since 1.0
 * @return array $currencies A list of the available currencies
 */
function church_tithe_wp_get_currencies() {

	$all_currencies_that_exist_in_the_world = array(
		'AFN' => 'Afghan Afghani Afganistan',
		'ALL' => 'Albanian Lek Albania',
		'DZD' => 'Algerian Dinar Algeria',
		'AOA' => 'Angolan Kwanza Angola',
		'ARS' => 'Argentine Peso Argentina',
		'AMD' => 'Armenian Dram Armenia',
		'AWG' => 'Aruban Florin Aruba',
		'AUD' => 'Australian Dollar Australia',
		'AZN' => 'Azerbaijani Manat',
		'BSD' => 'Bahamian Dollar Bahai',
		'BDT' => 'Bangladeshi Taka Bangladesh',
		'BBD' => 'Barbadian Dollar Barbados',
		'BZD' => 'Belize Dollar Belize',
		'BMD' => 'Bermudian Dollar Bermuda',
		'BOB' => 'Bolivian Boliviano Bolibia',
		'BAM' => 'Bosnia & Herzegovina Convertible Mark',
		'BWP' => 'Botswana Pula',
		'BRL' => 'Brazilian Real Brazil',
		'GBP' => 'British Pound Great Britain England',
		'BND' => 'Brunei Dollar',
		'BGN' => 'Bulgarian Lev Bulgaria',
		'BIF' => 'Burundian Franc Burundi',
		'KHR' => 'Cambodian Riel Cambodia',
		'CAD' => 'Canadian Dollar Canada CDN',
		'CVE' => 'Cape Verdean Escudo Cape Verdea',
		'KYD' => 'Cayman Islands Dollar',
		'XAF' => 'Central African Cfa Franc Central Africa',
		'XPF' => 'Cfp Franc French overseas collectivities',
		'CLP' => 'Chilean Peso Chili',
		'CNY' => 'Chinese Renminbi Yuan China',
		'COP' => 'Colombian Peso Columbia',
		'KMF' => 'Comorian Franc Comoros',
		'CDF' => 'Congolese Franc',
		'CRC' => 'Costa Rican Colón',
		'HRK' => 'Croatian Kuna',
		'CZK' => 'Czech Koruna czechoslovakia',
		'DKK' => 'Danish Krone Denmark',
		'DJF' => 'Djiboutian Franc',
		'DOP' => 'Dominican Peso',
		'XCD' => 'East Caribbean Dollar',
		'EGP' => 'Egyptian Pound Egypt',
		'ETB' => 'Ethiopian Birr',
		'EUR' => 'Euro European Union Austria Belgium Cyprus Estonia Finland France Germany Greece Ireland Italy Latvia Lithuania Luxembourg Malta the Netherlands Holland Portugal Slovakia Slovenia Spain',
		'FKP' => 'Falkland Islands Pound',
		'FJD' => 'Fijian Dollar',
		'GMD' => 'Gambian Dalasi',
		'GEL' => 'Georgian Lari',
		'GIP' => 'Gibraltar Pound',
		'GTQ' => 'Guatemalan Quetzal',
		'GNF' => 'Guinean Franc',
		'GYD' => 'Guyanese Dollar Guyana',
		'HTG' => 'Haitian Gourde',
		'HNL' => 'Honduran Lempira Honduras',
		'HKD' => 'Hong Kong Dollar',
		'HUF' => 'Hungarian Forint Hungary',
		'ISK' => 'Icelandic Króna',
		'INR' => 'Indian Rupee',
		'IDR' => 'Indonesian Rupiah',
		'ILS' => 'Israeli New Sheqel',
		'JMD' => 'Jamaican Dollar',
		'JPY' => 'Japanese Yen',
		'KZT' => 'Kazakhstani Tenge',
		'KES' => 'Kenyan Shilling',
		'KGS' => 'Kyrgyzstani Som',
		'KRW' => 'South Korean won',
		'LAK' => 'Lao Kip',
		'LBP' => 'Lebanese Pound Lebanon',
		'LSL' => 'Lesotho Loti',
		'LRD' => 'Liberian Dollar',
		'MOP' => 'Macanese Pataca Macau',
		'MKD' => 'Macedonian Denar',
		'MGA' => 'Malagasy Ariary Madagascar',
		'MWK' => 'Malawian Kwacha',
		'MYR' => 'Malaysian Ringgit',
		'MVR' => 'Maldivian Rufiyaa Maldives',
		'MRO' => 'Mauritanian Ouguiya Mauritania',
		'MUR' => 'Mauritian Rupee Mauritius',
		'MXN' => 'Mexican Peso Mexico',
		'MDL' => 'Moldovan Leu',
		'MNT' => 'Mongolian Tögrög',
		'MAD' => 'Moroccan Dirham Morocco',
		'MZN' => 'Mozambican Metical',
		'MMK' => 'Myanmar Kyat',
		'NAD' => 'Namibian Dollar',
		'NPR' => 'Nepalese Rupee',
		'ANG' => 'Netherlands Antillean Gulden',
		'TWD' => 'New Taiwan Dollar',
		'NZD' => 'New Zealand Dollar',
		'NIO' => 'Nicaraguan Córdoba',
		'NGN' => 'Nigerian Naira',
		'NOK' => 'Norwegian Krone Norway',
		'PKR' => 'Pakistani Rupee',
		'PAB' => 'Panamanian Balboa',
		'PGK' => 'Papua New Guinean Kina',
		'PYG' => 'Paraguayan Guaraní',
		'PEN' => 'Peruvian Nuevo Sol',
		'PHP' => 'Philippine Peso Philippines',
		'PLN' => 'Polish Złoty Poland',
		'QAR' => 'Qatari Riyal',
		'RON' => 'Romanian Leu',
		'RUB' => 'Russian Ruble',
		'RWF' => 'Rwandan Franc',
		'STD' => 'São Tomé and Príncipe Dobra',
		'SHP' => 'Saint Helenian Pound Saint Helena',
		'SVC' => 'Salvadoran Colón El Salvador',
		'WST' => 'Samoan Tala',
		'SAR' => 'Saudi Riyal Saudi Arabia',
		'RSD' => 'Serbian Dinar',
		'SCR' => 'Seychellois Rupee',
		'SLL' => 'Sierra Leonean Leone',
		'SGD' => 'Singapore Dollar',
		'SBD' => 'Solomon Islands Dollar',
		'SOS' => 'Somali Shilling',
		'ZAR' => 'South African Rand',
		'KRW' => 'South Korean Won',
		'LKR' => 'Sri Lankan Rupee',
		'SRD' => 'Surinamese Dollar Suriname',
		'SZL' => 'Swazi Lilangeni Swaziland Eswatini',
		'SEK' => 'Swedish Krona Sweden',
		'CHF' => 'Swiss Franc Switzerland',
		'TJS' => 'Tajikistani Somoni',
		'TZS' => 'Tanzanian Shilling',
		'THB' => 'Thai Baht Thailand',
		'TOP' => 'Tongan Paʻanga Tongo',
		'TTD' => 'Trinidad and Tobago Dollar',
		'TRY' => 'Turkish Lira Turkey',
		'UGX' => 'Ugandan Shilling',
		'UAH' => 'Ukrainian Hryvnia',
		'AED' => 'United Arab Emirates Dirham',
		'USD' => 'United States Dollar America American',
		'UYU' => 'Uruguayan Peso',
		'UZS' => 'Uzbekistani Som',
		'VUV' => 'Vanuatu Vatu',
		'VND' => 'Vietnamese Đồng',
		'XOF' => 'West African Cfa Franc West Africa',
		'YER' => 'Yemeni Rial',
		'ZMW' => 'Zambian Kwacha',
	);

	$stripe_currencies = church_tithe_wp_stripe_get_available_currencies();

	$formatted_currency_array = array();

	// Here we will rebuild the array of currencies so that it is an associative array, since stripe only gives us currency codes but not names.
	foreach ( $stripe_currencies as $stripe_currency_code ) {
		$formatted_currency_array[ strtoupper( $stripe_currency_code ) ] = $all_currencies_that_exist_in_the_world[ strtoupper( $stripe_currency_code ) ];
	}

	return $formatted_currency_array;
}

/**
 * Return whether a given currency ode is a zero decimal currency
 * This list came from https://stripe.com/docs/currencies#zero-decimal
 *
 * @since 1.0
 * @param string $currency_code The 3 letter currency code in question.
 * @return bool true if zero decimal currency, false if not.
 */
function church_tithe_wp_is_a_zero_decimal_currency( $currency_code ) {

	$all_zero_decimal_currencies = church_tithe_wp_get_zero_decimal_currencies();

	// If the given curency code is in the list of all zero decimal currencies.
	if ( array_key_exists( strtoupper( $currency_code ), $all_zero_decimal_currencies ) ) {

		// Return true, indicating that it is a zero decimal currency.
		return true;

		// If not a zero decimal currency.
	} else {

		// Return false, indicating it is not a zero decimal currency.
		return false;
	}
}

/**
 * Get the currencies that are Zero Decimal Currencies.
 * This list came from https://stripe.com/docs/currencies#zero-decimal
 *
 * @since 1.0
 * @return array $currencies A list of zero decimal currencies
 */
function church_tithe_wp_get_zero_decimal_currencies() {

	$zero_decimal_currencies = array(
		'BIF' => 'Burundian Franc Burundi',
		'CLP' => 'Chilean Peso',
		'DJF' => 'Djiboutian Franc',
		'GNF' => 'Guinean Franc',
		'JPY' => 'Japanese Yen',
		'KMF' => 'Comorian franc',
		'KRW' => 'South Korean won',
		'MGA' => 'Malagasy Ariary',
		'PYG' => 'Paraguayan Guaraní',
		'RWF' => 'Rwandan Franc',
		'UGX' => 'Ugandan Shilling',
		'VUV' => 'Vanuatu Vatu',
		'VND' => 'Vietnamese Đồng',
		'XOF' => 'West African Cfa Franc',
		'XPF' => 'French overseas collectivities franc',
	);

	return $zero_decimal_currencies;

}

/**
 * Given a currency determine the symbol to use. If no currency given, site default is used.
 * If no symbol is determine, the currency string is returned.
 *
 * @since  1.0
 * @param  string $currency The currency string.
 * @return string           The symbol to use for the currency
 */
function church_tithe_wp_currency_symbol( $currency = '' ) {

	$currency_symbols = array(
		'AED' => '&#1583;.&#1573;', // ?
		'AFN' => '&#65;&#102;',
		'ALL' => '&#76;&#101;&#107;',
		'AMD' => '',
		'ANG' => '&#402;',
		'AOA' => '&#75;&#122;', // ?
		'ARS' => '&#36;',
		'AUD' => '&#36;',
		'AWG' => '&#402;',
		'AZN' => '&#1084;&#1072;&#1085;',
		'BAM' => '&#75;&#77;',
		'BBD' => '&#36;',
		'BDT' => '&#2547;', // ?
		'BGN' => '&#1083;&#1074;',
		'BHD' => '.&#1583;.&#1576;', // ?
		'BIF' => '&#70;&#66;&#117;', // ?
		'BMD' => '&#36;',
		'BND' => '&#36;',
		'BOB' => '&#36;&#98;',
		'BRL' => '&#82;&#36;',
		'BSD' => '&#36;',
		'BTN' => '&#78;&#117;&#46;', // ?
		'BWP' => '&#80;',
		'BYR' => '&#112;&#46;',
		'BZD' => '&#66;&#90;&#36;',
		'CAD' => '&#36;',
		'CDF' => '&#70;&#67;',
		'CHF' => '&#67;&#72;&#70;',
		'CLF' => '', // ?
		'CLP' => '&#36;',
		'CNY' => '&#165;',
		'COP' => '&#36;',
		'CRC' => '&#8353;',
		'CUP' => '&#8396;',
		'CVE' => '&#36;', // ?
		'CZK' => '&#75;&#269;',
		'DJF' => '&#70;&#100;&#106;', // ?
		'DKK' => '&#107;&#114;',
		'DOP' => '&#82;&#68;&#36;',
		'DZD' => '&#1583;&#1580;', // ?
		'EGP' => '&#163;',
		'ETB' => '&#66;&#114;',
		'EUR' => '&#8364;',
		'FJD' => '&#36;',
		'FKP' => '&#163;',
		'GBP' => '&#163;',
		'GEL' => '&#4314;', // ?
		'GHS' => '&#162;',
		'GIP' => '&#163;',
		'GMD' => '&#68;', // ?
		'GNF' => '&#70;&#71;', // ?
		'GTQ' => '&#81;',
		'GYD' => '&#36;',
		'HKD' => '&#36;',
		'HNL' => '&#76;',
		'HRK' => '&#107;&#110;',
		'HTG' => '&#71;', // ?
		'HUF' => '&#70;&#116;',
		'IDR' => '&#82;&#112;',
		'ILS' => '&#8362;',
		'INR' => '&#8377;',
		'IQD' => '&#1593;.&#1583;', // ?
		'IRR' => '&#65020;',
		'ISK' => '&#107;&#114;',
		'JEP' => '&#163;',
		'JMD' => '&#74;&#36;',
		'JOD' => '&#74;&#68;', // ?
		'JPY' => '&#165;',
		'KES' => '&#75;&#83;&#104;', // ?
		'KGS' => '&#1083;&#1074;',
		'KHR' => '&#6107;',
		'KMF' => '&#67;&#70;', // ?
		'KPW' => '&#8361;',
		'KRW' => '&#8361;',
		'KWD' => '&#1583;.&#1603;', // ?
		'KYD' => '&#36;',
		'KZT' => '&#1083;&#1074;',
		'LAK' => '&#8365;',
		'LBP' => '&#163;',
		'LKR' => '&#8360;',
		'LRD' => '&#36;',
		'LSL' => '&#76;', // ?
		'LTL' => '&#76;&#116;',
		'LVL' => '&#76;&#115;',
		'LYD' => '&#1604;.&#1583;', // ?
		'MAD' => '&#1583;.&#1605;.', // ?
		'MDL' => '&#76;',
		'MGA' => '&#65;&#114;', // ?
		'MKD' => '&#1076;&#1077;&#1085;',
		'MMK' => '&#75;',
		'MNT' => '&#8366;',
		'MOP' => '&#77;&#79;&#80;&#36;', // ?
		'MRO' => '&#85;&#77;', // ?
		'MUR' => '&#8360;', // ?
		'MVR' => '.&#1923;', // ?
		'MWK' => '&#77;&#75;',
		'MXN' => '&#36;',
		'MYR' => '&#82;&#77;',
		'MZN' => '&#77;&#84;',
		'NAD' => '&#36;',
		'NGN' => '&#8358;',
		'NIO' => '&#67;&#36;',
		'NOK' => '&#107;&#114;',
		'NPR' => '&#8360;',
		'NZD' => '&#36;',
		'OMR' => '&#65020;',
		'PAB' => '&#66;&#47;&#46;',
		'PEN' => '&#83;&#47;&#46;',
		'PGK' => '&#75;', // ?
		'PHP' => '&#8369;',
		'PKR' => '&#8360;',
		'PLN' => '&#122;&#322;',
		'PYG' => '&#71;&#115;',
		'QAR' => '&#65020;',
		'RON' => '&#108;&#101;&#105;',
		'RSD' => '&#1044;&#1080;&#1085;&#46;',
		'RUB' => '&#1088;&#1091;&#1073;',
		'RWF' => '&#1585;.&#1587;',
		'SAR' => '&#65020;',
		'SBD' => '&#36;',
		'SCR' => '&#8360;',
		'SDG' => '&#163;', // ?
		'SEK' => '&#107;&#114;',
		'SGD' => '&#36;',
		'SHP' => '&#163;',
		'SLL' => '&#76;&#101;', // ?
		'SOS' => '&#83;',
		'SRD' => '&#36;',
		'STD' => '&#68;&#98;', // ?
		'SVC' => '&#36;',
		'SYP' => '&#163;',
		'SZL' => '&#76;', // ?
		'THB' => '&#3647;',
		'TJS' => '&#84;&#74;&#83;', // ? TJS (guess).
		'TMT' => '&#109;',
		'TND' => '&#1583;.&#1578;',
		'TOP' => '&#84;&#36;',
		'TRY' => '&#8356;', // New Turkey Lira (old symbol used).
		'TTD' => '&#36;',
		'TWD' => '&#78;&#84;&#36;',
		'TZS' => '',
		'UAH' => '&#8372;',
		'UGX' => '&#85;&#83;&#104;',
		'USD' => '&#36;',
		'UYU' => '&#36;&#85;',
		'UZS' => '&#1083;&#1074;',
		'VEF' => '&#66;&#115;',
		'VND' => '&#8363;',
		'VUV' => '&#86;&#84;',
		'WST' => '&#87;&#83;&#36;',
		'XAF' => '&#70;&#67;&#70;&#65;',
		'XCD' => '&#36;',
		'XDR' => '',
		'XOF' => '',
		'XPF' => '&#70;',
		'YER' => '&#65020;',
		'ZAR' => '&#82;',
		'ZMK' => '&#90;&#75;', // ?
		'ZWL' => '&#90;&#36;',
	);

	$uppercase_currency = strtoupper( $currency );

	if ( ! isset( $currency_symbols[ $uppercase_currency ] ) ) {
		return $uppercase_currency;
	}

	return $currency_symbols[ strtoupper( $currency ) ];

}

/**
 * Get the statement descriptor we want to use.
 *
 * @since  1.0
 * @return string
 */
function church_tithe_wp_statement_descriptor() {

	// Get the saved options for Church Tithe WP.
	$settings = get_option( 'church_tithe_wp_settings' );

	// Check if a custom statement descriptor has been entered.
	$statement_descriptor = church_tithe_wp_get_saved_setting( $settings, 'statement_descriptor', get_bloginfo( 'name' ) );

	// If there is no statement descriptor, use the site's URL.
	if ( empty( $statement_descriptor ) ) {
		$statement_descriptor = get_bloginfo( 'url' );
	}

	return substr( $statement_descriptor, 0, 22 );
}

// Workaround for users on nginx, where getallheaders isn't a PHP function.
if ( ! function_exists( 'getallheaders' ) ) {
	/**
	 * Workaround for users on nginx, where getallheaders isn't a PHP function.
	 *
	 * @since  1.0
	 * @return array The headers array.
	 */
	function getallheaders() {
		$headers = [];
		foreach ( $_SERVER as $name => $value ) {
			if ( substr( $name, 0, 5 ) === 'HTTP_' ) {
				$headers[ str_replace( ' ', '-', ucwords( strtolower( str_replace( '_', ' ', substr( $name, 5 ) ) ) ) ) ] = $value;
			}
		}
		return $headers;
	}
}

/**
 * Tell Church Tithe WPs settings that emails are not successfully being received.
 *
 * @since  1.0
 * @return void
 */
function church_tithe_wp_unconfirm_wp_mail_health_check() {

	// Get the saved options for Church Tithe WP.
	$settings = get_option( 'church_tithe_wp_settings' );

	// Set the wp_mail check to be true, confirmed.
	$settings['wp_mail_confirmed'] = false;

	// Save the options.
	update_option( 'church_tithe_wp_settings', $settings );

}

/**
 * Check if the current website is a localhost
 *
 * @since  1.0
 * @return bool
 */
function church_tithe_wp_is_site_localhost() {

	$site_url = get_bloginfo( 'url' );

	$localhost_possibilities = array(
		'.local',
		'.dev',
		'.test',
	);

	// Loop through each possible localhost URL.
	foreach ( $localhost_possibilities as $localhost_possibility ) {
		// Check if the site ends with one of the $localhost_possibility strings.
		if ( church_tithe_wp_ends_with( $site_url, $localhost_possibility ) ) {
			// This is a localhost.
			return true;
		}
	}

	// If this is not a localhost.
	return false;

}

/**
 * Simple helper function to check what a string ends with
 *
 * @since  1.0
 * @param  string $haystack The full string we are wondering about.
 * @param  string $needle We are wondering if the string end with this.
 * @return bool
 */
function church_tithe_wp_ends_with( $haystack, $needle ) {
	$length = strlen( $needle );
	if ( 0 === $length ) {
		return true;
	}

	return ( substr( $haystack, -$length ) === $needle );
}

/**
 * Check if the current website is reachable over ssl
 *
 * @since  1.0
 * @return bool
 */
function church_tithe_wp_is_site_reachable_over_ssl() {

	// If this site is already running on SSL, we don't need to do this check, we know it is already.
	if ( is_ssl() ) {
		return true;
	}

	// It's possible that they have an SSL, but are just logged in over port 80. Try pinging their site over https.
	$response = wp_remote_post( str_replace( 'http://', 'https://', get_bloginfo( 'url' ) ) );

	// If we were not able to ping the site over https, no certificate exists.
	if ( is_wp_error( $response ) ) {

		// Set the default to false for the certificate's existence.
		$certificate_exists = false;

		// Loop through each error.
		foreach ( $response->errors as $wp_error_code => $wp_error_message ) {

			if ( 'http_request_failed' === $wp_error_code ) {
				// If this is a local domain, allow self-signed certs.
				if ( church_tithe_wp_is_site_localhost() ) {
					if ( false !== strpos( $wp_error_message[0], 'self signed certificate' ) ) {
						// Allow self-signed certificates if "local" is in the domain.
						$certificate_exists = true;
					}
				}
			}
		}
		// The site was pingable over https, so a good certificate is in place. It just needs to be used.
	} else {
		$certificate_exists = true;
	}

	return $certificate_exists;
}

/**
 * Check if the fee threshold has been reached.
 *
 * @since 1.0.0.4
 * @return bool
 */
function church_tithe_wp_fee_threshold_reached() {

	global $wpdb;

	$table_name = sanitize_text_field( $wpdb->prefix ) . 'church_tithe_wp_transactions';

	// Get the date when the threshold was last reset.
	$last_reset_timestamp = get_option( 'ctwp_threshold_reset' );

	if ( empty( $last_reset_timestamp ) ) {
		$last_reset_timestamp = time();
		update_option( 'ctwp_threshold_reset', $last_reset_timestamp );
	}

	$one_year_after_reset = strtotime( '+1 year', $last_reset_timestamp );

	// Get the sum of all the earnings (after fees are removed) plus the cost of fees, in the home currency of this site.
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT sum(earnings_hc+gateway_fee_hc) AS year_total FROM $table_name WHERE date_paid >= %s AND date_paid <= %s", date( 'Y/m/d', $last_reset_timestamp ) . ' 00:00:00', date( 'Y/m/d', $one_year_after_reset ) . ' 24:00:00' ) ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery

	// Extract the total value to a variable.
	$amount = $results[0]->year_total;

	if ( $amount >= 1999900 ) {
		return true;
	}

	return false;
}
