<?php 

/**
 * Misc Functions
 * 
 * Functions related to the plugin
 * 
 * @package Loan Calculator
 * @since 1.0.1
 */


/**
 * Get Base Currency Code.
 *
 * @package Loan Calculator
 * @since 1.0.1
 */
function ww_loan_get_currency() {

	$loan_calculator_option_data = get_option( 'ww_loan_option' );
	
	$ww_loan_currency = !empty( $loan_calculator_option_data['ww_loan_currency'] ) ? $loan_calculator_option_data['ww_loan_currency'] : 'USD';
	
	return apply_filters( 'ww_loan_currency', $ww_loan_currency );
}

/**
 * Get full list of currency codes.
 * 
 * @package Loan Calculator
 * @since 1.0.1
 */
function ww_loan_get_currencies() {

	static $currencies;

	if ( ! isset( $currencies ) ) {
		$currencies = array_unique(
			apply_filters(
				'ww_loan_currencies',
				array(
					'AED' => __( 'United Arab Emirates dirham', 'loan-calculator-wp' ),
					'AFN' => __( 'Afghan afghani', 'loan-calculator-wp' ),
					'ALL' => __( 'Albanian lek', 'loan-calculator-wp' ),
					'AMD' => __( 'Armenian dram', 'loan-calculator-wp' ),
					'ANG' => __( 'Netherlands Antillean guilder', 'loan-calculator-wp' ),
					'AOA' => __( 'Angolan kwanza', 'loan-calculator-wp' ),
					'ARS' => __( 'Argentine peso', 'loan-calculator-wp' ),
					'AUD' => __( 'Australian dollar', 'loan-calculator-wp' ),
					'AWG' => __( 'Aruban florin', 'loan-calculator-wp' ),
					'AZN' => __( 'Azerbaijani manat', 'loan-calculator-wp' ),
					'BAM' => __( 'Bosnia and Herzegovina convertible mark', 'loan-calculator-wp' ),
					'BBD' => __( 'Barbadian dollar', 'loan-calculator-wp' ),
					'BDT' => __( 'Bangladeshi taka', 'loan-calculator-wp' ),
					'BGN' => __( 'Bulgarian lev', 'loan-calculator-wp' ),
					'BHD' => __( 'Bahraini dinar', 'loan-calculator-wp' ),
					'BIF' => __( 'Burundian franc', 'loan-calculator-wp' ),
					'BMD' => __( 'Bermudian dollar', 'loan-calculator-wp' ),
					'BND' => __( 'Brunei dollar', 'loan-calculator-wp' ),
					'BOB' => __( 'Bolivian boliviano', 'loan-calculator-wp' ),
					'BRL' => __( 'Brazilian real', 'loan-calculator-wp' ),
					'BSD' => __( 'Bahamian dollar', 'loan-calculator-wp' ),
					'BTC' => __( 'Bitcoin', 'loan-calculator-wp' ),
					'BTN' => __( 'Bhutanese ngultrum', 'loan-calculator-wp' ),
					'BWP' => __( 'Botswana pula', 'loan-calculator-wp' ),
					'BYR' => __( 'Belarusian ruble (old)', 'loan-calculator-wp' ),
					'BYN' => __( 'Belarusian ruble', 'loan-calculator-wp' ),
					'BZD' => __( 'Belize dollar', 'loan-calculator-wp' ),
					'CAD' => __( 'Canadian dollar', 'loan-calculator-wp' ),
					'CDF' => __( 'Congolese franc', 'loan-calculator-wp' ),
					'CHF' => __( 'Swiss franc', 'loan-calculator-wp' ),
					'CLP' => __( 'Chilean peso', 'loan-calculator-wp' ),
					'CNY' => __( 'Chinese yuan', 'loan-calculator-wp' ),
					'COP' => __( 'Colombian peso', 'loan-calculator-wp' ),
					'CRC' => __( 'Costa Rican col&oacute;n', 'loan-calculator-wp' ),
					'CUC' => __( 'Cuban convertible peso', 'loan-calculator-wp' ),
					'CUP' => __( 'Cuban peso', 'loan-calculator-wp' ),
					'CVE' => __( 'Cape Verdean escudo', 'loan-calculator-wp' ),
					'CZK' => __( 'Czech koruna', 'loan-calculator-wp' ),
					'DJF' => __( 'Djiboutian franc', 'loan-calculator-wp' ),
					'DKK' => __( 'Danish krone', 'loan-calculator-wp' ),
					'DOP' => __( 'Dominican peso', 'loan-calculator-wp' ),
					'DZD' => __( 'Algerian dinar', 'loan-calculator-wp' ),
					'EGP' => __( 'Egyptian pound', 'loan-calculator-wp' ),
					'ERN' => __( 'Eritrean nakfa', 'loan-calculator-wp' ),
					'ETB' => __( 'Ethiopian birr', 'loan-calculator-wp' ),
					'EUR' => __( 'Euro', 'loan-calculator-wp' ),
					'FJD' => __( 'Fijian dollar', 'loan-calculator-wp' ),
					'FKP' => __( 'Falkland Islands pound', 'loan-calculator-wp' ),
					'GBP' => __( 'Pound sterling', 'loan-calculator-wp' ),
					'GEL' => __( 'Georgian lari', 'loan-calculator-wp' ),
					'GGP' => __( 'Guernsey pound', 'loan-calculator-wp' ),
					'GHS' => __( 'Ghana cedi', 'loan-calculator-wp' ),
					'GIP' => __( 'Gibraltar pound', 'loan-calculator-wp' ),
					'GMD' => __( 'Gambian dalasi', 'loan-calculator-wp' ),
					'GNF' => __( 'Guinean franc', 'loan-calculator-wp' ),
					'GTQ' => __( 'Guatemalan quetzal', 'loan-calculator-wp' ),
					'GYD' => __( 'Guyanese dollar', 'loan-calculator-wp' ),
					'HKD' => __( 'Hong Kong dollar', 'loan-calculator-wp' ),
					'HNL' => __( 'Honduran lempira', 'loan-calculator-wp' ),
					'HRK' => __( 'Croatian kuna', 'loan-calculator-wp' ),
					'HTG' => __( 'Haitian gourde', 'loan-calculator-wp' ),
					'HUF' => __( 'Hungarian forint', 'loan-calculator-wp' ),
					'IDR' => __( 'Indonesian rupiah', 'loan-calculator-wp' ),
					'ILS' => __( 'Israeli new shekel', 'loan-calculator-wp' ),
					'IMP' => __( 'Manx pound', 'loan-calculator-wp' ),
					'INR' => __( 'Indian rupee', 'loan-calculator-wp' ),
					'IQD' => __( 'Iraqi dinar', 'loan-calculator-wp' ),
					'IRR' => __( 'Iranian rial', 'loan-calculator-wp' ),
					'IRT' => __( 'Iranian toman', 'loan-calculator-wp' ),
					'ISK' => __( 'Icelandic kr&oacute;na', 'loan-calculator-wp' ),
					'JEP' => __( 'Jersey pound', 'loan-calculator-wp' ),
					'JMD' => __( 'Jamaican dollar', 'loan-calculator-wp' ),
					'JOD' => __( 'Jordanian dinar', 'loan-calculator-wp' ),
					'JPY' => __( 'Japanese yen', 'loan-calculator-wp' ),
					'KES' => __( 'Kenyan shilling', 'loan-calculator-wp' ),
					'KGS' => __( 'Kyrgyzstani som', 'loan-calculator-wp' ),
					'KHR' => __( 'Cambodian riel', 'loan-calculator-wp' ),
					'KMF' => __( 'Comorian franc', 'loan-calculator-wp' ),
					'KPW' => __( 'North Korean won', 'loan-calculator-wp' ),
					'KRW' => __( 'South Korean won', 'loan-calculator-wp' ),
					'KWD' => __( 'Kuwaiti dinar', 'loan-calculator-wp' ),
					'KYD' => __( 'Cayman Islands dollar', 'loan-calculator-wp' ),
					'KZT' => __( 'Kazakhstani tenge', 'loan-calculator-wp' ),
					'LAK' => __( 'Lao kip', 'loan-calculator-wp' ),
					'LBP' => __( 'Lebanese pound', 'loan-calculator-wp' ),
					'LKR' => __( 'Sri Lankan rupee', 'loan-calculator-wp' ),
					'LRD' => __( 'Liberian dollar', 'loan-calculator-wp' ),
					'LSL' => __( 'Lesotho loti', 'loan-calculator-wp' ),
					'LYD' => __( 'Libyan dinar', 'loan-calculator-wp' ),
					'MAD' => __( 'Moroccan dirham', 'loan-calculator-wp' ),
					'MDL' => __( 'Moldovan leu', 'loan-calculator-wp' ),
					'MGA' => __( 'Malagasy ariary', 'loan-calculator-wp' ),
					'MKD' => __( 'Macedonian denar', 'loan-calculator-wp' ),
					'MMK' => __( 'Burmese kyat', 'loan-calculator-wp' ),
					'MNT' => __( 'Mongolian t&ouml;gr&ouml;g', 'loan-calculator-wp' ),
					'MOP' => __( 'Macanese pataca', 'loan-calculator-wp' ),
					'MRU' => __( 'Mauritanian ouguiya', 'loan-calculator-wp' ),
					'MUR' => __( 'Mauritian rupee', 'loan-calculator-wp' ),
					'MVR' => __( 'Maldivian rufiyaa', 'loan-calculator-wp' ),
					'MWK' => __( 'Malawian kwacha', 'loan-calculator-wp' ),
					'MXN' => __( 'Mexican peso', 'loan-calculator-wp' ),
					'MYR' => __( 'Malaysian ringgit', 'loan-calculator-wp' ),
					'MZN' => __( 'Mozambican metical', 'loan-calculator-wp' ),
					'NAD' => __( 'Namibian dollar', 'loan-calculator-wp' ),
					'NGN' => __( 'Nigerian naira', 'loan-calculator-wp' ),
					'NIO' => __( 'Nicaraguan c&oacute;rdoba', 'loan-calculator-wp' ),
					'NOK' => __( 'Norwegian krone', 'loan-calculator-wp' ),
					'NPR' => __( 'Nepalese rupee', 'loan-calculator-wp' ),
					'NZD' => __( 'New Zealand dollar', 'loan-calculator-wp' ),
					'OMR' => __( 'Omani rial', 'loan-calculator-wp' ),
					'PAB' => __( 'Panamanian balboa', 'loan-calculator-wp' ),
					'PEN' => __( 'Sol', 'loan-calculator-wp' ),
					'PGK' => __( 'Papua New Guinean kina', 'loan-calculator-wp' ),
					'PHP' => __( 'Philippine peso', 'loan-calculator-wp' ),
					'PKR' => __( 'Pakistani rupee', 'loan-calculator-wp' ),
					'PLN' => __( 'Polish z&#x142;oty', 'loan-calculator-wp' ),
					'PRB' => __( 'Transnistrian ruble', 'loan-calculator-wp' ),
					'PYG' => __( 'Paraguayan guaran&iacute;', 'loan-calculator-wp' ),
					'QAR' => __( 'Qatari riyal', 'loan-calculator-wp' ),
					'RON' => __( 'Romanian leu', 'loan-calculator-wp' ),
					'RSD' => __( 'Serbian dinar', 'loan-calculator-wp' ),
					'RUB' => __( 'Russian ruble', 'loan-calculator-wp' ),
					'RWF' => __( 'Rwandan franc', 'loan-calculator-wp' ),
					'SAR' => __( 'Saudi riyal', 'loan-calculator-wp' ),
					'SBD' => __( 'Solomon Islands dollar', 'loan-calculator-wp' ),
					'SCR' => __( 'Seychellois rupee', 'loan-calculator-wp' ),
					'SDG' => __( 'Sudanese pound', 'loan-calculator-wp' ),
					'SEK' => __( 'Swedish krona', 'loan-calculator-wp' ),
					'SGD' => __( 'Singapore dollar', 'loan-calculator-wp' ),
					'SHP' => __( 'Saint Helena pound', 'loan-calculator-wp' ),
					'SLL' => __( 'Sierra Leonean leone', 'loan-calculator-wp' ),
					'SOS' => __( 'Somali shilling', 'loan-calculator-wp' ),
					'SRD' => __( 'Surinamese dollar', 'loan-calculator-wp' ),
					'SSP' => __( 'South Sudanese pound', 'loan-calculator-wp' ),
					'STN' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'loan-calculator-wp' ),
					'SYP' => __( 'Syrian pound', 'loan-calculator-wp' ),
					'SZL' => __( 'Swazi lilangeni', 'loan-calculator-wp' ),
					'THB' => __( 'Thai baht', 'loan-calculator-wp' ),
					'TJS' => __( 'Tajikistani somoni', 'loan-calculator-wp' ),
					'TMT' => __( 'Turkmenistan manat', 'loan-calculator-wp' ),
					'TND' => __( 'Tunisian dinar', 'loan-calculator-wp' ),
					'TOP' => __( 'Tongan pa&#x2bb;anga', 'loan-calculator-wp' ),
					'TRY' => __( 'Turkish lira', 'loan-calculator-wp' ),
					'TTD' => __( 'Trinidad and Tobago dollar', 'loan-calculator-wp' ),
					'TWD' => __( 'New Taiwan dollar', 'loan-calculator-wp' ),
					'TZS' => __( 'Tanzanian shilling', 'loan-calculator-wp' ),
					'UAH' => __( 'Ukrainian hryvnia', 'loan-calculator-wp' ),
					'UGX' => __( 'Ugandan shilling', 'loan-calculator-wp' ),
					'USD' => __( 'United States (US) dollar', 'loan-calculator-wp' ),
					'UYU' => __( 'Uruguayan peso', 'loan-calculator-wp' ),
					'UZS' => __( 'Uzbekistani som', 'loan-calculator-wp' ),
					'VEF' => __( 'Venezuelan bol&iacute;var', 'loan-calculator-wp' ),
					'VES' => __( 'Bol&iacute;var soberano', 'loan-calculator-wp' ),
					'VND' => __( 'Vietnamese &#x111;&#x1ed3;ng', 'loan-calculator-wp' ),
					'VUV' => __( 'Vanuatu vatu', 'loan-calculator-wp' ),
					'WST' => __( 'Samoan t&#x101;l&#x101;', 'loan-calculator-wp' ),
					'XAF' => __( 'Central African CFA franc', 'loan-calculator-wp' ),
					'XCD' => __( 'East Caribbean dollar', 'loan-calculator-wp' ),
					'XOF' => __( 'West African CFA franc', 'loan-calculator-wp' ),
					'XPF' => __( 'CFP franc', 'loan-calculator-wp' ),
					'YER' => __( 'Yemeni rial', 'loan-calculator-wp' ),
					'ZAR' => __( 'South African rand', 'loan-calculator-wp' ),
					'ZMW' => __( 'Zambian kwacha', 'loan-calculator-wp' ),
				)
			)
		);
	}

	return $currencies;
}

/**
 * Get all available Currency symbols.
 *
 * Currency symbols and names should follow the Unicode CLDR recommendation (http://cldr.unicode.org/translation/currency-names)
 *
 * @since 4.1.0
 * @return array
 */
function ww_loan_get_currency_symbols() {

	$symbols = apply_filters(
		'ww_loan_currency_symbols',
		array(
			'AED' => '&#x62f;.&#x625;',
			'AFN' => '&#x60b;',
			'ALL' => 'L',
			'AMD' => 'AMD',
			'ANG' => '&fnof;',
			'AOA' => 'Kz',
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'AWG' => 'Afl.',
			'AZN' => 'AZN',
			'BAM' => 'KM',
			'BBD' => '&#36;',
			'BDT' => '&#2547;&nbsp;',
			'BGN' => '&#1083;&#1074;.',
			'BHD' => '.&#x62f;.&#x628;',
			'BIF' => 'Fr',
			'BMD' => '&#36;',
			'BND' => '&#36;',
			'BOB' => 'Bs.',
			'BRL' => '&#82;&#36;',
			'BSD' => '&#36;',
			'BTC' => '&#3647;',
			'BTN' => 'Nu.',
			'BWP' => 'P',
			'BYR' => 'Br',
			'BYN' => 'Br',
			'BZD' => '&#36;',
			'CAD' => '&#36;',
			'CDF' => 'Fr',
			'CHF' => '&#67;&#72;&#70;',
			'CLP' => '&#36;',
			'CNY' => '&yen;',
			'COP' => '&#36;',
			'CRC' => '&#x20a1;',
			'CUC' => '&#36;',
			'CUP' => '&#36;',
			'CVE' => '&#36;',
			'CZK' => '&#75;&#269;',
			'DJF' => 'Fr',
			'DKK' => 'DKK',
			'DOP' => 'RD&#36;',
			'DZD' => '&#x62f;.&#x62c;',
			'EGP' => 'EGP',
			'ERN' => 'Nfk',
			'ETB' => 'Br',
			'EUR' => '&euro;',
			'FJD' => '&#36;',
			'FKP' => '&pound;',
			'GBP' => '&pound;',
			'GEL' => '&#x20be;',
			'GGP' => '&pound;',
			'GHS' => '&#x20b5;',
			'GIP' => '&pound;',
			'GMD' => 'D',
			'GNF' => 'Fr',
			'GTQ' => 'Q',
			'GYD' => '&#36;',
			'HKD' => '&#36;',
			'HNL' => 'L',
			'HRK' => 'kn',
			'HTG' => 'G',
			'HUF' => '&#70;&#116;',
			'IDR' => 'Rp',
			'ILS' => '&#8362;',
			'IMP' => '&pound;',
			'INR' => '&#8377;',
			'IQD' => '&#x639;.&#x62f;',
			'IRR' => '&#xfdfc;',
			'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
			'ISK' => 'kr.',
			'JEP' => '&pound;',
			'JMD' => '&#36;',
			'JOD' => '&#x62f;.&#x627;',
			'JPY' => '&yen;',
			'KES' => 'KSh',
			'KGS' => '&#x441;&#x43e;&#x43c;',
			'KHR' => '&#x17db;',
			'KMF' => 'Fr',
			'KPW' => '&#x20a9;',
			'KRW' => '&#8361;',
			'KWD' => '&#x62f;.&#x643;',
			'KYD' => '&#36;',
			'KZT' => '&#8376;',
			'LAK' => '&#8365;',
			'LBP' => '&#x644;.&#x644;',
			'LKR' => '&#xdbb;&#xdd4;',
			'LRD' => '&#36;',
			'LSL' => 'L',
			'LYD' => '&#x644;.&#x62f;',
			'MAD' => '&#x62f;.&#x645;.',
			'MDL' => 'MDL',
			'MGA' => 'Ar',
			'MKD' => '&#x434;&#x435;&#x43d;',
			'MMK' => 'Ks',
			'MNT' => '&#x20ae;',
			'MOP' => 'P',
			'MRU' => 'UM',
			'MUR' => '&#x20a8;',
			'MVR' => '.&#x783;',
			'MWK' => 'MK',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'MZN' => 'MT',
			'NAD' => 'N&#36;',
			'NGN' => '&#8358;',
			'NIO' => 'C&#36;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'OMR' => '&#x631;.&#x639;.',
			'PAB' => 'B/.',
			'PEN' => 'S/',
			'PGK' => 'K',
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PRB' => '&#x440;.',
			'PYG' => '&#8370;',
			'QAR' => '&#x631;.&#x642;',
			'RMB' => '&yen;',
			'RON' => 'lei',
			'RSD' => '&#1088;&#1089;&#1076;',
			'RUB' => '&#8381;',
			'RWF' => 'Fr',
			'SAR' => '&#x631;.&#x633;',
			'SBD' => '&#36;',
			'SCR' => '&#x20a8;',
			'SDG' => '&#x62c;.&#x633;.',
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'SHP' => '&pound;',
			'SLL' => 'Le',
			'SOS' => 'Sh',
			'SRD' => '&#36;',
			'SSP' => '&pound;',
			'STN' => 'Db',
			'SYP' => '&#x644;.&#x633;',
			'SZL' => 'L',
			'THB' => '&#3647;',
			'TJS' => '&#x405;&#x41c;',
			'TMT' => 'm',
			'TND' => '&#x62f;.&#x62a;',
			'TOP' => 'T&#36;',
			'TRY' => '&#8378;',
			'TTD' => '&#36;',
			'TWD' => '&#78;&#84;&#36;',
			'TZS' => 'Sh',
			'UAH' => '&#8372;',
			'UGX' => 'UGX',
			'USD' => '&#36;',
			'UYU' => '&#36;',
			'UZS' => 'UZS',
			'VEF' => 'Bs F',
			'VES' => 'Bs.S',
			'VND' => '&#8363;',
			'VUV' => 'Vt',
			'WST' => 'T',
			'XAF' => 'CFA',
			'XCD' => '&#36;',
			'XOF' => 'CFA',
			'XPF' => 'Fr',
			'YER' => '&#xfdfc;',
			'ZAR' => '&#82;',
			'ZMW' => 'ZK',
		)
	);

	return $symbols;
}

/**
 * Get Currency symbol
 *
 * @package Loan Calculator
 * @since 1.0.1
 */
function ww_loan_get_currency_symbol( $currency = '' ) {

	if ( ! $currency ) {
		$currency = ww_loan_get_currency();
	}

	$symbols = ww_loan_get_currency_symbols();

	$currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';

	return apply_filters( 'ww_loan_currency_symbol', $currency_symbol, $currency );
}