<?php

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2018.05.31.
 * Time: 11:09
 */
class MM_WPFS_Currencies {

	/**
	 * @param $currency
	 *
	 * @return mixed|null
	 */
	public static function getCurrencySymbolFor($currency ) {
		$currencyArray = MM_WPFS_Currencies::getCurrencyFor( $currency );
		if ( is_array( $currencyArray ) && array_key_exists( 'symbol', $currencyArray ) ) {
			return $currencyArray['symbol'];
		}

		return null;
	}

	/**
	 * @param $currency
	 *
	 * @return mixed|null
	 */
	public static function getCurrencyFor($currency ) {
		if ( isset( $currency ) ) {
			$currencyKey         = strtolower( $currency );
			$availableCurrencies = MM_WPFS_Currencies::getAvailableCurrencies();
			if ( isset( $availableCurrencies ) && array_key_exists( $currencyKey, $availableCurrencies ) ) {
				$currencyArray = $availableCurrencies[ $currencyKey ];
			} else {
				$currencyArray = null;
			}

			return $currencyArray;
		}

		return null;
	}

	/**
	 * @return array
	 */
	public static function getAvailableCurrencies() {
		return array(
			'aed' => array(
				'code'               => 'AED',
				'name'               => 'United Arab Emirates Dirham',
				'symbol'             => 'DH',
				'zeroDecimalSupport' => false
			),
			'afn' => array(
				'code'               => 'AFN',
				'name'               => 'Afghan Afghani',
				'symbol'             => '؋',
				'zeroDecimalSupport' => false
			),
			'all' => array(
				'code'               => 'ALL',
				'name'               => 'Albanian Lek',
				'symbol'             => 'L',
				'zeroDecimalSupport' => false
			),
			'amd' => array(
				'code'               => 'AMD',
				'name'               => 'Armenian Dram',
				'symbol'             => '֏',
				'zeroDecimalSupport' => false
			),
			'ang' => array(
				'code'               => 'ANG',
				'name'               => 'Netherlands Antillean Gulden',
				'symbol'             => 'ƒ',
				'zeroDecimalSupport' => false
			),
			'aoa' => array(
				'code'               => 'AOA',
				'name'               => 'Angolan Kwanza',
				'symbol'             => 'Kz',
				'zeroDecimalSupport' => false
			),
			'ars' => array(
				'code'               => 'ARS',
				'name'               => 'Argentine Peso',
				'symbol'             => '$',
				'zeroDecimalSupport' => false
			),
			'aud' => array(
				'code'               => 'AUD',
				'name'               => 'Australian Dollar',
				'symbol'             => '$',
				'zeroDecimalSupport' => false
			),
			'awg' => array(
				'code'               => 'AWG',
				'name'               => 'Aruban Florin',
				'symbol'             => 'ƒ',
				'zeroDecimalSupport' => false
			),
			'azn' => array(
				'code'               => 'AZN',
				'name'               => 'Azerbaijani Manat',
				'symbol'             => 'm.',
				'zeroDecimalSupport' => false
			),
			'bam' => array(
				'code'               => 'BAM',
				'name'               => 'Bosnia & Herzegovina Convertible Mark',
				'symbol'             => 'KM',
				'zeroDecimalSupport' => false
			),
			'bbd' => array(
				'code'               => 'BBD',
				'name'               => 'Barbadian Dollar',
				'symbol'             => 'Bds$',
				'zeroDecimalSupport' => false
			),
			'bdt' => array(
				'code'               => 'BDT',
				'name'               => 'Bangladeshi Taka',
				'symbol'             => '৳',
				'zeroDecimalSupport' => false
			),
			'bgn' => array(
				'code'               => 'BGN',
				'name'               => 'Bulgarian Lev',
				'symbol'             => 'лв',
				'zeroDecimalSupport' => false
			),
			'bif' => array(
				'code'               => 'BIF',
				'name'               => 'Burundian Franc',
				'symbol'             => 'FBu',
				'zeroDecimalSupport' => true
			),
			'bmd' => array(
				'code'               => 'BMD',
				'name'               => 'Bermudian Dollar',
				'symbol'             => 'BD$',
				'zeroDecimalSupport' => false
			),
			'bnd' => array(
				'code'               => 'BND',
				'name'               => 'Brunei Dollar',
				'symbol'             => 'B$',
				'zeroDecimalSupport' => false
			),
			'bob' => array(
				'code'               => 'BOB',
				'name'               => 'Bolivian Boliviano',
				'symbol'             => 'Bs.',
				'zeroDecimalSupport' => false
			),
			'brl' => array(
				'code'               => 'BRL',
				'name'               => 'Brazilian Real',
				'symbol'             => 'R$',
				'zeroDecimalSupport' => false
			),
			'bsd' => array(
				'code'               => 'BSD',
				'name'               => 'Bahamian Dollar',
				'symbol'             => 'B$',
				'zeroDecimalSupport' => false
			),
			'bwp' => array(
				'code'               => 'BWP',
				'name'               => 'Botswana Pula',
				'symbol'             => 'P',
				'zeroDecimalSupport' => false
			),
			'bzd' => array(
				'code'               => 'BZD',
				'name'               => 'Belize Dollar',
				'symbol'             => 'BZ$',
				'zeroDecimalSupport' => false
			),
			'cad' => array(
				'code'               => 'CAD',
				'name'               => 'Canadian Dollar',
				'symbol'             => '$',
				'zeroDecimalSupport' => false
			),
			'cdf' => array(
				'code'               => 'CDF',
				'name'               => 'Congolese Franc',
				'symbol'             => 'CF',
				'zeroDecimalSupport' => false
			),
			'chf' => array(
				'code'               => 'CHF',
				'name'               => 'Swiss Franc',
				'symbol'             => 'Fr',
				'zeroDecimalSupport' => false
			),
			'clp' => array(
				'code'               => 'CLP',
				'name'               => 'Chilean Peso',
				'symbol'             => 'CLP$',
				'zeroDecimalSupport' => true
			),
			'cny' => array(
				'code'               => 'CNY',
				'name'               => 'Chinese Renminbi Yuan',
				'symbol'             => '¥',
				'zeroDecimalSupport' => false
			),
			'cop' => array(
				'code'               => 'COP',
				'name'               => 'Colombian Peso',
				'symbol'             => 'COL$',
				'zeroDecimalSupport' => false
			),
			'crc' => array(
				'code'               => 'CRC',
				'name'               => 'Costa Rican Colón',
				'symbol'             => '₡',
				'zeroDecimalSupport' => false
			),
			'cve' => array(
				'code'               => 'CVE',
				'name'               => 'Cape Verdean Escudo',
				'symbol'             => 'Esc',
				'zeroDecimalSupport' => false
			),
			'czk' => array(
				'code'               => 'CZK',
				'name'               => 'Czech Koruna',
				'symbol'             => 'Kč',
				'zeroDecimalSupport' => false
			),
			'djf' => array(
				'code'               => 'DJF',
				'name'               => 'Djiboutian Franc',
				'symbol'             => 'Fr',
				'zeroDecimalSupport' => true
			),
			'dkk' => array(
				'code'               => 'DKK',
				'name'               => 'Danish Krone',
				'symbol'             => 'kr',
				'zeroDecimalSupport' => false
			),
			'dop' => array(
				'code'               => 'DOP',
				'name'               => 'Dominican Peso',
				'symbol'             => 'RD$',
				'zeroDecimalSupport' => false
			),
			'dzd' => array(
				'code'               => 'DZD',
				'name'               => 'Algerian Dinar',
				'symbol'             => 'DA',
				'zeroDecimalSupport' => false
			),
			'egp' => array(
				'code'               => 'EGP',
				'name'               => 'Egyptian Pound',
				'symbol'             => 'L.E.',
				'zeroDecimalSupport' => false
			),
			'etb' => array(
				'code'               => 'ETB',
				'name'               => 'Ethiopian Birr',
				'symbol'             => 'Br',
				'zeroDecimalSupport' => false
			),
			'eur' => array(
				'code'               => 'EUR',
				'name'               => 'Euro',
				'symbol'             => '€',
				'zeroDecimalSupport' => false
			),
			'fjd' => array(
				'code'               => 'FJD',
				'name'               => 'Fijian Dollar',
				'symbol'             => 'FJ$',
				'zeroDecimalSupport' => false
			),
			'fkp' => array(
				'code'               => 'FKP',
				'name'               => 'Falkland Islands Pound',
				'symbol'             => 'FK£',
				'zeroDecimalSupport' => false
			),
			'gbp' => array(
				'code'               => 'GBP',
				'name'               => 'British Pound',
				'symbol'             => '£',
				'zeroDecimalSupport' => false
			),
			'gel' => array(
				'code'               => 'GEL',
				'name'               => 'Georgian Lari',
				'symbol'             => 'ლ',
				'zeroDecimalSupport' => false
			),
			'gip' => array(
				'code'               => 'GIP',
				'name'               => 'Gibraltar Pound',
				'symbol'             => '£',
				'zeroDecimalSupport' => false
			),
			'gmd' => array(
				'code'               => 'GMD',
				'name'               => 'Gambian Dalasi',
				'symbol'             => 'D',
				'zeroDecimalSupport' => false
			),
			'gnf' => array(
				'code'               => 'GNF',
				'name'               => 'Guinean Franc',
				'symbol'             => 'FG',
				'zeroDecimalSupport' => true
			),
			'gtq' => array(
				'code'               => 'GTQ',
				'name'               => 'Guatemalan Quetzal',
				'symbol'             => 'Q',
				'zeroDecimalSupport' => false
			),
			'gyd' => array(
				'code'               => 'GYD',
				'name'               => 'Guyanese Dollar',
				'symbol'             => 'G$',
				'zeroDecimalSupport' => false
			),
			'hkd' => array(
				'code'               => 'HKD',
				'name'               => 'Hong Kong Dollar',
				'symbol'             => 'HK$',
				'zeroDecimalSupport' => false
			),
			'hnl' => array(
				'code'               => 'HNL',
				'name'               => 'Honduran Lempira',
				'symbol'             => 'L',
				'zeroDecimalSupport' => false
			),
			'hrk' => array(
				'code'               => 'HRK',
				'name'               => 'Croatian Kuna',
				'symbol'             => 'kn',
				'zeroDecimalSupport' => false
			),
			'htg' => array(
				'code'               => 'HTG',
				'name'               => 'Haitian Gourde',
				'symbol'             => 'G',
				'zeroDecimalSupport' => false
			),
			'huf' => array(
				'code'               => 'HUF',
				'name'               => 'Hungarian Forint',
				'symbol'             => 'Ft',
				'zeroDecimalSupport' => false
			),
			'idr' => array(
				'code'               => 'IDR',
				'name'               => 'Indonesian Rupiah',
				'symbol'             => 'Rp',
				'zeroDecimalSupport' => false
			),
			'ils' => array(
				'code'               => 'ILS',
				'name'               => 'Israeli New Sheqel',
				'symbol'             => '₪',
				'zeroDecimalSupport' => false
			),
			'inr' => array(
				'code'               => 'INR',
				'name'               => 'Indian Rupee',
				'symbol'             => '₹',
				'zeroDecimalSupport' => false
			),
			'isk' => array(
				'code'               => 'ISK',
				'name'               => 'Icelandic Króna',
				'symbol'             => 'ikr',
				'zeroDecimalSupport' => false
			),
			'jmd' => array(
				'code'               => 'JMD',
				'name'               => 'Jamaican Dollar',
				'symbol'             => 'J$',
				'zeroDecimalSupport' => false
			),
			'jpy' => array(
				'code'               => 'JPY',
				'name'               => 'Japanese Yen',
				'symbol'             => '¥',
				'zeroDecimalSupport' => true
			),
			'kes' => array(
				'code'               => 'KES',
				'name'               => 'Kenyan Shilling',
				'symbol'             => 'Ksh',
				'zeroDecimalSupport' => false
			),
			'kgs' => array(
				'code'               => 'KGS',
				'name'               => 'Kyrgyzstani Som',
				'symbol'             => 'COM',
				'zeroDecimalSupport' => false
			),
			'khr' => array(
				'code'               => 'KHR',
				'name'               => 'Cambodian Riel',
				'symbol'             => '៛',
				'zeroDecimalSupport' => false
			),
			'kmf' => array(
				'code'               => 'KMF',
				'name'               => 'Comorian Franc',
				'symbol'             => 'CF',
				'zeroDecimalSupport' => true
			),
			'krw' => array(
				'code'               => 'KRW',
				'name'               => 'South Korean Won',
				'symbol'             => '₩',
				'zeroDecimalSupport' => true
			),
			'kyd' => array(
				'code'               => 'KYD',
				'name'               => 'Cayman Islands Dollar',
				'symbol'             => 'CI$',
				'zeroDecimalSupport' => false
			),
			'kzt' => array(
				'code'               => 'KZT',
				'name'               => 'Kazakhstani Tenge',
				'symbol'             => '₸',
				'zeroDecimalSupport' => false
			),
			'lak' => array(
				'code'               => 'LAK',
				'name'               => 'Lao Kip',
				'symbol'             => '₭',
				'zeroDecimalSupport' => false
			),
			'lbp' => array(
				'code'               => 'LBP',
				'name'               => 'Lebanese Pound',
				'symbol'             => 'LL',
				'zeroDecimalSupport' => false
			),
			'lkr' => array(
				'code'               => 'LKR',
				'name'               => 'Sri Lankan Rupee',
				'symbol'             => 'SLRs',
				'zeroDecimalSupport' => false
			),
			'lrd' => array(
				'code'               => 'LRD',
				'name'               => 'Liberian Dollar',
				'symbol'             => 'L$',
				'zeroDecimalSupport' => false
			),
			'lsl' => array(
				'code'               => 'LSL',
				'name'               => 'Lesotho Loti',
				'symbol'             => 'M',
				'zeroDecimalSupport' => false
			),
			'mad' => array(
				'code'               => 'MAD',
				'name'               => 'Moroccan Dirham',
				'symbol'             => 'DH',
				'zeroDecimalSupport' => false
			),
			'mdl' => array(
				'code'               => 'MDL',
				'name'               => 'Moldovan Leu',
				'symbol'             => 'MDL',
				'zeroDecimalSupport' => false
			),
			'mga' => array(
				'code'               => 'MGA',
				'name'               => 'Malagasy Ariary',
				'symbol'             => 'Ar',
				'zeroDecimalSupport' => true
			),
			'mkd' => array(
				'code'               => 'MKD',
				'name'               => 'Macedonian Denar',
				'symbol'             => 'ден',
				'zeroDecimalSupport' => false
			),
			'mnt' => array(
				'code'               => 'MNT',
				'name'               => 'Mongolian Tögrög',
				'symbol'             => '₮',
				'zeroDecimalSupport' => false
			),
			'mop' => array(
				'code'               => 'MOP',
				'name'               => 'Macanese Pataca',
				'symbol'             => 'MOP$',
				'zeroDecimalSupport' => false
			),
			'mro' => array(
				'code'               => 'MRO',
				'name'               => 'Mauritanian Ouguiya',
				'symbol'             => 'UM',
				'zeroDecimalSupport' => false
			),
			'mur' => array(
				'code'               => 'MUR',
				'name'               => 'Mauritian Rupee',
				'symbol'             => 'Rs',
				'zeroDecimalSupport' => false
			),
			'mvr' => array(
				'code'               => 'MVR',
				'name'               => 'Maldivian Rufiyaa',
				'symbol'             => 'Rf.',
				'zeroDecimalSupport' => false
			),
			'mwk' => array(
				'code'               => 'MWK',
				'name'               => 'Malawian Kwacha',
				'symbol'             => 'MK',
				'zeroDecimalSupport' => false
			),
			'mxn' => array(
				'code'               => 'MXN',
				'name'               => 'Mexican Peso',
				'symbol'             => '$',
				'zeroDecimalSupport' => false
			),
			'myr' => array(
				'code'               => 'MYR',
				'name'               => 'Malaysian Ringgit',
				'symbol'             => 'RM',
				'zeroDecimalSupport' => false
			),
			'mzn' => array(
				'code'               => 'MZN',
				'name'               => 'Mozambican Metical',
				'symbol'             => 'MT',
				'zeroDecimalSupport' => false
			),
			'nad' => array(
				'code'               => 'NAD',
				'name'               => 'Namibian Dollar',
				'symbol'             => 'N$',
				'zeroDecimalSupport' => false
			),
			'ngn' => array(
				'code'               => 'NGN',
				'name'               => 'Nigerian Naira',
				'symbol'             => '₦',
				'zeroDecimalSupport' => false
			),
			'nio' => array(
				'code'               => 'NIO',
				'name'               => 'Nicaraguan Córdoba',
				'symbol'             => 'C$',
				'zeroDecimalSupport' => false
			),
			'nok' => array(
				'code'               => 'NOK',
				'name'               => 'Norwegian Krone',
				'symbol'             => 'kr',
				'zeroDecimalSupport' => false
			),
			'npr' => array(
				'code'               => 'NPR',
				'name'               => 'Nepalese Rupee',
				'symbol'             => 'NRs',
				'zeroDecimalSupport' => false
			),
			'nzd' => array(
				'code'               => 'NZD',
				'name'               => 'New Zealand Dollar',
				'symbol'             => 'NZ$',
				'zeroDecimalSupport' => false
			),
			'pab' => array(
				'code'               => 'PAB',
				'name'               => 'Panamanian Balboa',
				'symbol'             => 'B/.',
				'zeroDecimalSupport' => false
			),
			'pen' => array(
				'code'               => 'PEN',
				'name'               => 'Peruvian Nuevo Sol',
				'symbol'             => 'S/.',
				'zeroDecimalSupport' => false
			),
			'pgk' => array(
				'code'               => 'PGK',
				'name'               => 'Papua New Guinean Kina',
				'symbol'             => 'K',
				'zeroDecimalSupport' => false
			),
			'php' => array(
				'code'               => 'PHP',
				'name'               => 'Philippine Peso',
				'symbol'             => '₱',
				'zeroDecimalSupport' => false
			),
			'pkr' => array(
				'code'               => 'PKR',
				'name'               => 'Pakistani Rupee',
				'symbol'             => 'PKR',
				'zeroDecimalSupport' => false
			),
			'pln' => array(
				'code'               => 'PLN',
				'name'               => 'Polish Złoty',
				'symbol'             => 'zł',
				'zeroDecimalSupport' => false
			),
			'pyg' => array(
				'code'               => 'PYG',
				'name'               => 'Paraguayan Guaraní',
				'symbol'             => '₲',
				'zeroDecimalSupport' => true
			),
			'qar' => array(
				'code'               => 'QAR',
				'name'               => 'Qatari Riyal',
				'symbol'             => 'QR',
				'zeroDecimalSupport' => false
			),
			'ron' => array(
				'code'               => 'RON',
				'name'               => 'Romanian Leu',
				'symbol'             => 'RON',
				'zeroDecimalSupport' => false
			),
			'rsd' => array(
				'code'               => 'RSD',
				'name'               => 'Serbian Dinar',
				'symbol'             => 'дин',
				'zeroDecimalSupport' => false
			),
			'rub' => array(
				'code'               => 'RUB',
				'name'               => 'Russian Ruble',
				'symbol'             => 'руб',
				'zeroDecimalSupport' => false
			),
			'rwf' => array(
				'code'               => 'RWF',
				'name'               => 'Rwandan Franc',
				'symbol'             => 'FRw',
				'zeroDecimalSupport' => true
			),
			'sar' => array(
				'code'               => 'SAR',
				'name'               => 'Saudi Riyal',
				'symbol'             => 'SR',
				'zeroDecimalSupport' => false
			),
			'sbd' => array(
				'code'               => 'SBD',
				'name'               => 'Solomon Islands Dollar',
				'symbol'             => 'SI$',
				'zeroDecimalSupport' => false
			),
			'scr' => array(
				'code'               => 'SCR',
				'name'               => 'Seychellois Rupee',
				'symbol'             => 'SRe',
				'zeroDecimalSupport' => false
			),
			'sek' => array(
				'code'               => 'SEK',
				'name'               => 'Swedish Krona',
				'symbol'             => 'kr',
				'zeroDecimalSupport' => false
			),
			'sgd' => array(
				'code'               => 'SGD',
				'name'               => 'Singapore Dollar',
				'symbol'             => 'S$',
				'zeroDecimalSupport' => false
			),
			'shp' => array(
				'code'               => 'SHP',
				'name'               => 'Saint Helenian Pound',
				'symbol'             => '£',
				'zeroDecimalSupport' => false
			),
			'sll' => array(
				'code'               => 'SLL',
				'name'               => 'Sierra Leonean Leone',
				'symbol'             => 'Le',
				'zeroDecimalSupport' => false
			),
			'sos' => array(
				'code'               => 'SOS',
				'name'               => 'Somali Shilling',
				'symbol'             => 'Sh.So.',
				'zeroDecimalSupport' => false
			),
			'std' => array(
				'code'               => 'STD',
				'name'               => 'São Tomé and Príncipe Dobra',
				'symbol'             => 'Db',
				'zeroDecimalSupport' => false
			),
			'srd' => array(
				'code'               => 'SRD',
				'name'               => 'Surinamese Dollar',
				'symbol'             => 'SRD',
				'zeroDecimalSupport' => false
			),
			'svc' => array(
				'code'               => 'SVC',
				'name'               => 'Salvadoran Colón',
				'symbol'             => '₡',
				'zeroDecimalSupport' => false
			),
			'szl' => array(
				'code'               => 'SZL',
				'name'               => 'Swazi Lilangeni',
				'symbol'             => 'E',
				'zeroDecimalSupport' => false
			),
			'thb' => array(
				'code'               => 'THB',
				'name'               => 'Thai Baht',
				'symbol'             => '฿',
				'zeroDecimalSupport' => false
			),
			'tjs' => array(
				'code'               => 'TJS',
				'name'               => 'Tajikistani Somoni',
				'symbol'             => 'TJS',
				'zeroDecimalSupport' => false
			),
			'top' => array(
				'code'               => 'TOP',
				'name'               => 'Tongan Paʻanga',
				'symbol'             => '$',
				'zeroDecimalSupport' => false
			),
			'try' => array(
				'code'               => 'TRY',
				'name'               => 'Turkish Lira',
				'symbol'             => '₺',
				'zeroDecimalSupport' => false
			),
			'ttd' => array(
				'code'               => 'TTD',
				'name'               => 'Trinidad and Tobago Dollar',
				'symbol'             => 'TT$',
				'zeroDecimalSupport' => false
			),
			'twd' => array(
				'code'               => 'TWD',
				'name'               => 'New Taiwan Dollar',
				'symbol'             => 'NT$',
				'zeroDecimalSupport' => false
			),
			'tzs' => array(
				'code'               => 'TZS',
				'name'               => 'Tanzanian Shilling',
				'symbol'             => 'TSh',
				'zeroDecimalSupport' => false
			),
			'uah' => array(
				'code'               => 'UAH',
				'name'               => 'Ukrainian Hryvnia',
				'symbol'             => '₴',
				'zeroDecimalSupport' => false
			),
			'ugx' => array(
				'code'               => 'UGX',
				'name'               => 'Ugandan Shilling',
				'symbol'             => 'USh',
				'zeroDecimalSupport' => false
			),
			'usd' => array(
				'code'               => 'USD',
				'name'               => 'United States Dollar',
				'symbol'             => '$',
				'zeroDecimalSupport' => false
			),
			'uyu' => array(
				'code'               => 'UYU',
				'name'               => 'Uruguayan Peso',
				'symbol'             => '$U',
				'zeroDecimalSupport' => false
			),
			'uzs' => array(
				'code'               => 'UZS',
				'name'               => 'Uzbekistani Som',
				'symbol'             => 'UZS',
				'zeroDecimalSupport' => false
			),
			'vnd' => array(
				'code'               => 'VND',
				'name'               => 'Vietnamese Đồng',
				'symbol'             => '₫',
				'zeroDecimalSupport' => true
			),
			'vuv' => array(
				'code'               => 'VUV',
				'name'               => 'Vanuatu Vatu',
				'symbol'             => 'VT',
				'zeroDecimalSupport' => true
			),
			'wst' => array(
				'code'               => 'WST',
				'name'               => 'Samoan Tala',
				'symbol'             => 'WS$',
				'zeroDecimalSupport' => false
			),
			'xaf' => array(
				'code'               => 'XAF',
				'name'               => 'Central African Cfa Franc',
				'symbol'             => 'FCFA',
				'zeroDecimalSupport' => true
			),
			'xcd' => array(
				'code'               => 'XCD',
				'name'               => 'East Caribbean Dollar',
				'symbol'             => 'EC$',
				'zeroDecimalSupport' => false
			),
			'xof' => array(
				'code'               => 'XOF',
				'name'               => 'West African Cfa Franc',
				'symbol'             => 'CFA',
				'zeroDecimalSupport' => true
			),
			'xpf' => array(
				'code'               => 'XPF',
				'name'               => 'Cfp Franc',
				'symbol'             => 'F',
				'zeroDecimalSupport' => true
			),
			'yer' => array(
				'code'               => 'YER',
				'name'               => 'Yemeni Rial',
				'symbol'             => '﷼',
				'zeroDecimalSupport' => false
			),
			'zar' => array(
				'code'               => 'ZAR',
				'name'               => 'South African Rand',
				'symbol'             => 'R',
				'zeroDecimalSupport' => false
			),
			'zmw' => array(
				'code'               => 'ZMW',
				'name'               => 'Zambian Kwacha',
				'symbol'             => 'ZK',
				'zeroDecimalSupport' => false
			)
		);
	}

    /**
     * @deprecated
     *
     * @param $currency
     * @param $amount
     * @param bool $decimalsForInteger
     *
     * @return null|string
     */
    public static function format_amount_with_currency( $currency, $amount, $decimalsForInteger = true ) {
        return self::formatAmountInternal( $currency, $amount, $decimalsForInteger, true );
    }

    /**
	 * @deprecated
	 *
	 * @param $currency
	 * @param $amount
	 * @param $decimalsForInteger
	 * @param $showCurrencySymbol
	 *
	 * @return null|string
	 */
	protected static function formatAmountInternal($currency, $amount, $decimalsForInteger, $showCurrencySymbol ) {
		$currencyArray = MM_WPFS_Currencies::getCurrencyFor( $currency );
		if ( is_array( $currencyArray ) ) {
			if ( $currencyArray['zeroDecimalSupport'] == true ) {
				$theAmount = is_numeric( $amount ) ? $amount : 0;
				$pattern   = '%0d';
			} else {
				$theAmount = is_numeric( $amount ) ? ( $amount / 100.0 ) : 0;
				// tnagy check if theAmount is whole number
				if ( false === $decimalsForInteger && intval( $theAmount ) == ( $theAmount + 0 ) ) {
					$pattern = '%0d';
				} else {
					$pattern = '%0.2f';
				}
			}

			if ( $showCurrencySymbol ) {
				$pattern = '%s' . $pattern;
				$result  = sprintf( $pattern, $currencyArray['symbol'], $theAmount );
			} else {
				$result = sprintf( $pattern, $theAmount );
			}

			return $result;
		}

		return null;
	}

	/**
	 * @param $currency
	 * @param $amount
	 * @param bool $decimalsForInteger
	 *
	 * @return null|string
	 */
	public static function formatAmount($currency, $amount, $decimalsForInteger = true ) {
	    //todo: rename this function to make sure it's understood that it must format currencies always with dot as decimal separator
		return self::formatAmountInternal( $currency, $amount, $decimalsForInteger, false );
	}

	/**
     * @param $context MM_WPFS_StaticContext
	 * @param $currency
	 * @param $amount
	 * @param bool $decimalsForInteger
	 * @param bool $showCurrency
	 *
	 * @return string|void
	 */
	public static function formatAndEscape( $context, $currency, $amount, $decimalsForInteger = true, $showCurrency = true ) {
		$decimalSeparatorSymbol                = self::getGlobalDecimalSeparatorSymbolOption( $context );
		$showCurrencySymbolInsteadOfCode       = self::getGlobalShowCurrencySymbolInsteadOfCode( $context );
		$showCurrencySignAtFirstPosition       = self::getGlobalShowCurrencySignAtFirstPosition( $context );
		$putWhitespaceBetweenCurrencyAndAmount = self::getGlobalPutWhitespaceBetweenCurrencyAndAmount( $context );
		$currencyFormat                        = self::createCurrencyFormat(
			$currency,
			$decimalSeparatorSymbol,
			$showCurrencySymbolInsteadOfCode,
			$showCurrencySignAtFirstPosition,
			$putWhitespaceBetweenCurrencyAndAmount
		);

		return $currencyFormat->formatAndEscape( $amount, $decimalsForInteger, $showCurrency );
	}

	/**
	 * Returns the name of the selected decimal separator symbol according to WPFS options or the default value
	 * if not set.
	 * @see MM_WPFS_CurrencyFormat::DECIMAL_SEPARATOR_COMMA
	 * @see MM_WPFS_CurrencyFormat::DECIMAL_SEPARATOR_DOT
     *
     * @param $context MM_WPFS_StaticContext
     *
	 * @return string
	 */
	public static function getGlobalDecimalSeparatorSymbolOption( $context ) {
        $decimalSeparatorSymbol = $context->getOptions()->get( MM_WPFS_Options::OPTION_DECIMAL_SEPARATOR_SYMBOL );
        if ( MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_COMMA == $decimalSeparatorSymbol ) {
            return MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_COMMA;
        }

		return MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_DOT;
	}

	/**
	 * Returns the WPFS option whether to show currency symbol instead of code or the default value if not set.
     *
     * @param $context MM_WPFS_StaticContext
     *
	 * @return bool
	 */
	public static function getGlobalShowCurrencySymbolInsteadOfCode( $context ) {
        $showCurrencySymbolInsteadOfCode = $context->getOptions()->get( MM_WPFS_Options::OPTION_SHOW_CURRENCY_SYMBOL_INSTEAD_OF_CODE );
        if ( '1' == $showCurrencySymbolInsteadOfCode ) {
            return true;
        }

		return false;
	}

	/**
	 * Returns the WPFS option whether to show currency sign first or the default value if not set.
     *
     * @param $context MM_WPFS_StaticContext
     *
	 * @return bool
	 */
	public static function getGlobalShowCurrencySignAtFirstPosition( $context ) {
        $showCurrencySignFirst = $context->getOptions()->get( MM_WPFS_Options::OPTION_SHOW_CURRENCY_SIGN_AT_FIRST_POSITION );
        if ( '1' == $showCurrencySignFirst ) {
            return true;
        }

		return false;
	}

	/**
	 * Returns the WPFS option whether to show currency symbol instead of code or the default value if not set.
     *
     * @param $context MM_WPFS_StaticContext
     *
	 * @return bool
	 */
	public static function getGlobalPutWhitespaceBetweenCurrencyAndAmount( $context ) {
        $putWhitespaceBetweenCurrencyAndAmount = $context->getOptions()->get( MM_WPFS_Options::OPTION_PUT_WHITESPACE_BETWEEN_CURRENCY_AND_AMOUNT );
        if ( '1' == $putWhitespaceBetweenCurrencyAndAmount ) {
            return true;
        }

		return false;
	}

	/**
	 * @param $currency
	 *
	 * @param $decimalSeparatorSymbol
	 * @param $showCurrencySymbolInsteadOfCode
	 * @param $showCurrencySignAtFirstPosition
	 * @param $putWhitespaceBetweenCurrencyAndAmount
	 *
	 * @return MM_WPFS_CurrencyFormat
	 * @throws Exception
	 */
	protected static function createCurrencyFormat(
		$currency,
		$decimalSeparatorSymbol,
		$showCurrencySymbolInsteadOfCode,
		$showCurrencySignAtFirstPosition,
		$putWhitespaceBetweenCurrencyAndAmount
	) {
		$currencyArray = MM_WPFS_Currencies::getCurrencyFor( $currency );
		if ( is_null( $currencyArray ) ) {
			throw new Exception( 'Unknown currency: ' . $currency );
		}
		if (
			MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_DOT !== $decimalSeparatorSymbol
			&& MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_COMMA !== $decimalSeparatorSymbol
		) {
			throw new Exception( 'decimalSeparatorSymbol has an invalid value: ' . $decimalSeparatorSymbol );
		}
		$currencyObject = MM_WPFS_Currency::fromArray( $currencyArray );
		if ( MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_COMMA === $decimalSeparatorSymbol ) {
			$decimalSeparator = MM_WPFS_CurrencyFormat::DECIMAL_SEPARATOR_COMMA;
		} else {
			$decimalSeparator = MM_WPFS_CurrencyFormat::DECIMAL_SEPARATOR_DOT;
		}
		$currencyFormat = new MM_WPFS_CurrencyFormat(
			$currencyObject,
			$decimalSeparator,
			$showCurrencySymbolInsteadOfCode,
			$showCurrencySignAtFirstPosition,
			$putWhitespaceBetweenCurrencyAndAmount
		);

		return $currencyFormat;
	}

	/**
	 * @param $form
	 * @param $currency
	 * @param $amount
	 * @param bool $decimalsForInteger
	 * @param bool $showCurrency
	 *
	 * @return string|void
	 */
	public static function formatAndEscapeByForm( $form, $currency, $amount, $decimalsForInteger = true, $showCurrency = true ) {
		$currencyFormat = self::createCurrencyFormatByForm(
			$form,
			$currency
		);

		return $currencyFormat->formatAndEscape( $amount, $decimalsForInteger, $showCurrency );
	}

	/**
	 * @param $form
	 * @param $currency
	 *
	 * @return MM_WPFS_CurrencyFormat
	 * @throws Exception
	 */
	protected static function createCurrencyFormatByForm( $form, $currency ) {
		if ( is_null( $form ) ) {
			throw new Exception( 'form is null' );
		}
		$currencyFormat = self::createCurrencyFormat(
			$currency,
			$form->decimalSeparator,
			1 == $form->showCurrencySymbolInsteadOfCode ? true : false,
			1 == $form->showCurrencySignAtFirstPosition ? true : false,
			1 == $form->putWhitespaceBetweenCurrencyAndAmount ? true : false
		);

		return $currencyFormat;
	}

    /**
     * @param $context MM_WPFS_StaticContext
     * @param $currency
     * @param $amount
     * @param bool $decimalsForInteger
     * @param bool $showCurrency
     *
     * @return string|void
     */
    public static function formatAndEscapeByAdmin( $context, $currency, $amount, $decimalsForInteger = true, $showCurrency = true ) {
        $currencyFormat = self::createCurrencyFormatByAdmin(
            $context,
            $currency
        );

        return $currencyFormat->formatAndEscape( $amount, $decimalsForInteger, $showCurrency );
    }

    /**
     * @param $context MM_WPFS_StaticContext
     * @param $currency
     *
     * @return MM_WPFS_CurrencyFormat
     * @throws Exception
     */
    protected static function createCurrencyFormatByAdmin( $context, $currency ) {
        $options = $context->getOptions();

        $currencyFormat = self::createCurrencyFormat(
            $currency,
            $options->get( MM_WPFS_Options::OPTION_DECIMAL_SEPARATOR_SYMBOL ),
            1 == $options->get( MM_WPFS_Options::OPTION_SHOW_CURRENCY_SYMBOL_INSTEAD_OF_CODE ),
            1 == $options->get( MM_WPFS_Options::OPTION_SHOW_CURRENCY_SIGN_AT_FIRST_POSITION ),
            1 == $options->get( MM_WPFS_Options::OPTION_PUT_WHITESPACE_BETWEEN_CURRENCY_AND_AMOUNT )
        );

        return $currencyFormat;
    }

    /**
     * @param $currency
     * @param $amount
     * @param bool $decimalsForInteger
     * @param bool $showCurrency
     *
     * @return string|void
     */
    public static function formatAndEscapeByMyAccount( $currency, $amount, $decimalsForInteger = true, $showCurrency = true ) {
        $currencyFormat = self::createCurrencyFormatByMyAccount(
            $currency
        );

        return $currencyFormat->formatAndEscape( $amount, $decimalsForInteger, $showCurrency );
    }

    /**
     * @param $currency
     *
     * @return MM_WPFS_CurrencyFormat
     * @throws Exception
     */
    protected static function createCurrencyFormatByMyAccount( $currency ) {
        $currencyFormat = self::createCurrencyFormat(
            $currency,
            MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_DOT,
            true,
            true,
            false
        );

        return $currencyFormat;
    }


    /**
     * @param $context MM_WPFS_StaticContext
	 * @param $currency
	 * @param $amount
	 * @param bool $decimalsForInteger
	 * @param bool $showCurrency
	 *
	 * @return null|string
	 * @throws Exception
	 */
	public static function format( $context, $currency, $amount, $decimalsForInteger = true, $showCurrency = true ) {
		$decimalSeparatorSymbol                = self::getGlobalDecimalSeparatorSymbolOption( $context );
		$showCurrencySymbolInsteadOfCode       = self::getGlobalShowCurrencySymbolInsteadOfCode( $context );
		$showCurrencySignAtFirstPosition       = self::getGlobalShowCurrencySignAtFirstPosition( $context );
		$putWhitespaceBetweenCurrencyAndAmount = self::getGlobalPutWhitespaceBetweenCurrencyAndAmount( $context );
		$currencyFormat                        = self::createCurrencyFormat(
			$currency,
			$decimalSeparatorSymbol,
			$showCurrencySymbolInsteadOfCode,
			$showCurrencySignAtFirstPosition,
			$putWhitespaceBetweenCurrencyAndAmount
		);

		return $currencyFormat->format( $amount, $decimalsForInteger, $showCurrency );
	}

	public static function formatByForm( $form, $currency, $amount, $decimalsForInteger = true, $showCurrency = true ) {
		$currencyFormat = self::createCurrencyFormatByForm(
			$form,
			$currency
		);

		return $currencyFormat->format( $amount, $decimalsForInteger, $showCurrency );
	}

	public static function parseByForm( $form, $currency, $amount ) {
		$currencyFormat = self::createCurrencyFormatByForm( $form, $currency );

		return $currencyFormat->parse( $amount );
	}

}

class MM_WPFS_Currency {

	const ARRAY_KEY_CODE = 'code';
	const ARRAY_KEY_NAME = 'name';
	const ARRAY_KEY_SYMBOL = 'symbol';
	const ARRAY_KEY_ZERO_DECIMAL_SUPPORT = 'zeroDecimalSupport';

	/**
	 * @var string
	 */
	protected $code;
	/**
	 * @var string
	 */
	protected $name;
	/**
	 * @var string
	 */
	protected $symbol;
	/**
	 * @var bool
	 */
	protected $zeroDecimalSupport;

	/**
	 * MM_WPFS_Currency constructor.
	 *
	 * @param string $code
	 * @param string $name
	 * @param string $symbol
	 * @param bool $zeroDecimalSupport
	 *
	 * @throws Exception
	 */
	public function __construct( $code, $name, $symbol, $zeroDecimalSupport ) {
		if ( empty( $code ) ) {
			throw new Exception( 'code is empty' );
		}
		if ( empty( $name ) ) {
			throw new Exception( 'name is empty' );
		}
		if ( empty( $symbol ) ) {
			throw new Exception( 'symbol is empty' );
		}
		if ( is_null( $zeroDecimalSupport ) ) {
			throw new Exception( 'zeroDecimalSupport is null' );
		}
		$this->code               = $code;
		$this->name               = $name;
		$this->symbol             = $symbol;
		$this->zeroDecimalSupport = $zeroDecimalSupport;
	}

	/**
	 * @param array $currencyArray
	 *
	 * @return MM_WPFS_Currency
	 * @throws Exception
	 */
	public static function fromArray( array $currencyArray ) {
		if ( is_array( $currencyArray ) ) {
			$code               = null;
			$name               = null;
			$symbol             = null;
			$zeroDecimalSupport = null;
			if ( array_key_exists( self::ARRAY_KEY_CODE, $currencyArray ) ) {
				$code = $currencyArray[ self::ARRAY_KEY_CODE ];
			}
			if ( array_key_exists( self::ARRAY_KEY_NAME, $currencyArray ) ) {
				$name = $currencyArray[ self::ARRAY_KEY_NAME ];
			}
			if ( array_key_exists( self::ARRAY_KEY_SYMBOL, $currencyArray ) ) {
				$symbol = $currencyArray[ self::ARRAY_KEY_SYMBOL ];
			}
			if ( array_key_exists( self::ARRAY_KEY_ZERO_DECIMAL_SUPPORT, $currencyArray ) ) {
				$zeroDecimalSupport = $currencyArray[ self::ARRAY_KEY_ZERO_DECIMAL_SUPPORT ];
			}

			return new MM_WPFS_Currency( $code, $name, $symbol, $zeroDecimalSupport );
		}
		throw new Exception( 'parameter is not an array' );
	}

	/**
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getSymbol() {
		return $this->symbol;
	}

	/**
	 * @return boolean
	 */
	public function hasZeroDecimalSupport() {
		return $this->zeroDecimalSupport;
	}

	/**
	 * @return string
	 */
	public function getKey() {
		return strtolower( $this->code );
	}
}

class MM_WPFS_CurrencyFormat {

	/**
	 * Comma character used as decimal separator
	 */
	const DECIMAL_SEPARATOR_COMMA = ',';
	/**
	 * Dot character used as decimal separator
	 */
	const DECIMAL_SEPARATOR_DOT = '.';

	const GROUP_SEPARATOR_EMPTY = '';
	const GROUP_SEPARATOR_WHITESPACE = ' ';
	const GROUP_SEPARATOR_COMMA = ',';

	/**
	 * @var MM_WPFS_Currency
	 */
	protected $currency;
	protected $decimalSeparator;
	protected $groupSeparatorSymbol;
	protected $showCurrencySymbolInsteadOfCode;
	protected $showCurrencySignAtFirstPosition;
	protected $putWhitespaceBetweenCurrencyAndAmount;

	/**
	 * MM_WPFS_CurrencyFormat constructor.
	 *
	 * @param MM_WPFS_Currency $currency
	 * @param string $decimalSeparator
	 * @param bool $showCurrencySymbolInsteadOfCode
	 * @param bool $showCurrencySignAtFirstPosition
	 * @param bool $putWhitespaceBetweenCurrencyAndAmount
	 *
	 * @throws Exception
	 */
	public function __construct(
		$currency,
		$decimalSeparator = self::DECIMAL_SEPARATOR_DOT,
		$showCurrencySymbolInsteadOfCode = true,
		$showCurrencySignAtFirstPosition = true,
		$putWhitespaceBetweenCurrencyAndAmount = false
	) {
		if ( is_null( $currency ) ) {
			throw new Exception( 'currency is null' );
		}
		if (
			self::DECIMAL_SEPARATOR_DOT !== $decimalSeparator
			&& self::DECIMAL_SEPARATOR_COMMA !== $decimalSeparator
		) {
			throw new Exception( 'decimalSeparator has an invalid value: ' . $decimalSeparator );
		}
		if ( ! is_bool( $showCurrencySymbolInsteadOfCode ) ) {
			throw new Exception( 'showCurrencySymbolInsteadOfCode is not a boolean: ' . $showCurrencySymbolInsteadOfCode );
		}
		if ( ! is_bool( $showCurrencySignAtFirstPosition ) ) {
			throw new Exception( 'showCurrencySignAtFirstPosition is not a boolean: ' . $showCurrencySignAtFirstPosition );
		}
		if ( ! is_bool( $putWhitespaceBetweenCurrencyAndAmount ) ) {
			throw new Exception( 'putWhitespaceBetweenCurrencyAndAmount is not a boolean: ' . $putWhitespaceBetweenCurrencyAndAmount );
		}
		$this->currency                              = $currency;
		$this->decimalSeparator                      = $decimalSeparator;
		$this->groupSeparatorSymbol                  = self::GROUP_SEPARATOR_EMPTY;
		$this->showCurrencySymbolInsteadOfCode       = $showCurrencySymbolInsteadOfCode;
		$this->showCurrencySignAtFirstPosition       = $showCurrencySignAtFirstPosition;
		$this->putWhitespaceBetweenCurrencyAndAmount = $putWhitespaceBetweenCurrencyAndAmount;
	}

	/**
	 * @param $amount
	 * @param $decimalsForInteger
	 * @param $showCurrency
	 *
	 * @return string|void
	 * @throws Exception
	 */
	public function formatAndEscape( $amount, $decimalsForInteger, $showCurrency ) {
		return esc_attr( $this->format( $amount, $decimalsForInteger, $showCurrency ) );
	}

	/**
	 * @param $amount
	 * @param bool $decimalsForInteger
	 * @param bool $showCurrency
	 *
	 * @return null|string
	 * @throws Exception
	 */
	public function format( $amount, $decimalsForInteger = true, $showCurrency = true ) {
		if ( ! is_numeric( $amount ) ) {
			throw new Exception( 'amount is not a number: ' . print_r( $amount, true ) );
		}
		if ( $this->currency->hasZeroDecimalSupport() ) {
			// tnagy we don't use decimals
			$amountString = number_format( $amount, 0, $this->decimalSeparator, $this->groupSeparatorSymbol );
		} else {
			$normalizedAmount = $amount / 100.0;
			if ( false === $decimalsForInteger && intval( $normalizedAmount ) == ( $normalizedAmount + 0 ) ) {
				// tnagy we use decimals but the current amount is an integer
				// tnagy we pass decimalSeparatorSymbol too because we pass groupSeparator too
				$amountString = number_format( $normalizedAmount, 0, $this->decimalSeparator, $this->groupSeparatorSymbol );
			} else {
				// tnagy we use decimals with the selected decimal separator and group separator
				$amountString = number_format( $normalizedAmount, 2, $this->decimalSeparator, $this->groupSeparatorSymbol );
			}
		}
		// tnagy assemble the pattern
		$pattern = '%s';
		if ( $showCurrency ) {
			// tnagy currency symbol or code
			$currencyString = $this->showCurrencySymbolInsteadOfCode ? $this->currency->getSymbol() : $this->currency->getCode();
			// tnagy currency and amount with or without whitespace
			$whitespaceString = $this->putWhitespaceBetweenCurrencyAndAmount ? ' ' : '';
			if ( $this->showCurrencySignAtFirstPosition ) {
				// tnagy currency sign at first position
				$pattern = '%s' . $whitespaceString . $pattern;
				// tnagy format currency amount string
				$result = sprintf( $pattern, $currencyString, $amountString );
			} else {
				// tnagy currency sign at last position
				$pattern = $pattern . $whitespaceString . '%s';
				// tnagy format currency amount string
				$result = sprintf( $pattern, $amountString, $currencyString );
			}
		} else {
			// tnagy format currency amount string
			$result = sprintf( $pattern, $amountString );
		}

		return $result;

	}

	/**
	 * Parse an amount with the current decimal separator
	 *
	 * @param $amount
	 *
	 * @return mixed|null
	 */
	public function parse( $amount ) {
		$parsedAmount = null;
		if ( ! is_null( $amount ) ) {
			if ( self::DECIMAL_SEPARATOR_COMMA === $this->decimalSeparator ) {
				$parsedAmount = preg_replace( '/[^0-9,]+/', '', $amount );
				$parsedAmount = preg_replace( '/[,]+/', '.', $parsedAmount );
			} else {
				$parsedAmount = preg_replace( '/[^0-9.]+/', '', $amount );
			}
		}

		return $parsedAmount;
	}

}