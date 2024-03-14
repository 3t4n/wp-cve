<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class CBCurrencyConverterHelper
 *
 * @since  2.2
 */
class CBCurrencyConverterHelper {
	/**
	 * Currency rates api methods
	 */
	public static function currency_rates_api() {
		$rates_api = [];

		$rates_api['exchangeratehost'] = [
			'title'  => esc_html__( 'Exchangerate.host(Free Api)', 'cbcurrencyconverter' ),
			'class'  => 'CBCurrencyConverterExchangeratehost',
			'method' => 'api_method',
		];

		$rates_api['alphavantage'] = [
			'title'  => esc_html__( 'Alphavantage.co (Premimum Plan) ', 'cbcurrencyconverter' ),
			'class'  => 'CBCurrencyConverterAlphavantage',
			'method' => 'api_method',
		];

		$rates_api['openexchangerates_free'] = [
			'title'  => esc_html__( 'Openexchangerates.org Free Plan(only base USD)', 'cbcurrencyconverter' ),
			'class'  => 'CBCurrencyConverterOpenexchangeratesFree',
			'method' => 'api_method',
		];

		$rates_api['currencylayer_free'] = [
			'title'  => esc_html__( 'Currencylayer.com Free Plan(only base USD)', 'cbcurrencyconverter' ),
			'class'  => 'CBCurrencyConverterCurrencyLayerFree',
			'method' => 'api_method',
		];

		return apply_filters( 'cbcurrencyconverter_rates_apis', $rates_api );
	}//end currency_rates_api

	/**
	 * Currency rates api methods titles
	 */
	public static function currency_rates_api_titles() {
		$rates_api = self::currency_rates_api();

		$rates_api_titles = [];

		foreach ( $rates_api as $key => $api ) {
			$rates_api_titles[ $key ] = $api['title'];
		}

		return apply_filters( 'cbcurrencyconverter_rates_api_titles', $rates_api_titles );
	}//end currency_rates_api_titles


	/**
	 * Get display layouts
	 *
	 * @return mixed|void
	 */
	public static function get_layouts() {
		$layout = [
			'cal'               => esc_html__( 'Calculator', 'cbcurrencyconverter' ),
			'list'              => esc_html__( 'List', 'cbcurrencyconverter' ),
			'calwithlistbottom' => esc_html__( 'Calc with List Bottom', 'cbcurrencyconverter' ),
			'calwithlisttop'    => esc_html__( 'Calc with List Top', 'cbcurrencyconverter' ),
		];

		return apply_filters( 'cbcurrencyconverter_layout', $layout );
	}//end get_layouts

	/**
	 * Get available layout style  in reverse key value
	 *
	 * @return mixed|void
	 */
	public static function get_layouts_r() {
		$layout = CBCurrencyConverterHelper::get_layouts();
		/*$layout_r = array();

		foreach ( $layout as $key => $value ) {
			$layout_r[ $value ] = $key;
		}*/

		$layout_r = array_flip( $layout );

		return apply_filters( 'cbcurrencyconverter_layout_r', $layout_r );
	}//end get_styles_r

	/**
	 * List of all transient cache names used in this plugin
	 *
	 * @return array
	 */
	public static function getAllTransientCacheNames() {
		$transient_caches = [];

		$transient_caches[] = 'cbcurrencyconverter_exchangeratehost';
		$transient_caches[] = 'cbcurrencyconverter_alphavantage';
		$transient_caches[] = 'cbcurrencyconverter_openexchangeratesfree';
		$transient_caches[] = 'cbcurrencyconverter_currencylayerfree';


		return apply_filters( 'cbcurrencyconverter_transient_names', $transient_caches );
	}//getAllTransientCacheNames

	/**
	 * Calculator view template
	 *
	 * @param  string  $reference
	 * @param  array  $instance
	 *
	 * @return string
	 * @since  2.2
	 *
	 */
	public static function cbxcccalcview( $reference = 'shortcode', $instance = [] ) {

		//adding the reference to $instance
		$instance['reference'] = $reference;
		if ( ! isset( $instance['all_currencies'] ) ) {
			$instance['all_currencies'] = self::getCurrencyList();
		}


		//take care array related properties
		if ( isset( $instance['calc_from_currencies'] ) && is_string( $instance['calc_from_currencies'] ) ) {
			$instance['calc_from_currencies'] = explode( ',', $instance['calc_from_currencies'] );
		}

		if ( isset( $instance['calc_to_currencies'] ) && is_string( $instance['calc_to_currencies'] ) ) {
			$instance['calc_to_currencies'] = explode( ',', $instance['calc_to_currencies'] );
		}


		$settings             = new CBCurrencyconverterSetting();
		$instance['settings'] = $settings;

		return cbcurrencyconverter_get_template_html( 'calculator.php', $instance );
	}//end of method cbxcccalcview

	/**
	 * List view template
	 *
	 * @param  string  $reference
	 * @param  array  $instance
	 *
	 * @return string
	 * @since  2.2
	 *
	 */
	public static function cbxcclistview( $reference = 'shortcode', $instance = [] ) {
		extract( $instance );

		//adding the reference to $instance
		$instance['reference'] = $reference;
		if ( ! isset( $instance['all_currencies'] ) ) {
			$instance['all_currencies'] = self::getCurrencyList();
		}


		if ( isset( $instance['list_to_currencies'] ) && is_string( $instance['list_to_currencies'] ) ) {
			$instance['list_to_currencies'] = explode( ',', $instance['list_to_currencies'] );
		}

		$settings             = new CBCurrencyconverterSetting();
		$instance['settings'] = $settings;

		return cbcurrencyconverter_get_template_html( 'list.php', $instance );
	}//end cbxcclistview

	/**
	 * Calculator inline view(from currency select field is hidden)
	 *
	 * @param  string  $reference
	 * @param  array  $instance
	 *
	 * @return string
	 *
	 * @since  2.2
	 *
	 */
	public static function cbxcccalcinline( $reference = 'shortcode', $instance = [] ) {
		//adding the reference to $instance
		$instance['reference'] = $reference;

		if ( ! isset( $instance['all_currencies'] ) ) {
			$instance['all_currencies'] = self::getCurrencyList();
		}

		//take care array related properties
		if ( isset( $instance['calc_from_currencies'] ) && is_string( $instance['calc_from_currencies'] ) ) {
			$instance['calc_from_currencies'] = explode( ',', $instance['calc_from_currencies'] );
		}

		if ( isset( $instance['calc_to_currencies'] ) && is_string( $instance['calc_to_currencies'] ) ) {
			$instance['calc_to_currencies'] = explode( ',', $instance['calc_to_currencies'] );
		}

		if ( isset( $instance['list_to_currencies'] ) && is_string( $instance['list_to_currencies'] ) ) {
			$instance['list_to_currencies'] = explode( ',', $instance['list_to_currencies'] );
		}

		return cbcurrencyconverter_get_template_html( 'inline.php', $instance );
	}//end of method cbxcccalcinline

	/**
	 * Get all currency list
	 *
	 * @return mixed|void
	 * @since  2.2
	 *
	 */
	public static function getCurrencyList() {
		$currency_list = [
			'AED' => __( 'United Arab Emirates dirham', 'cbcurrencyconverter' ),
			'ALL' => __( 'Albania Lek', 'cbcurrencyconverter' ),
			'AFN' => __( 'Afghanistan Afghani', 'cbcurrencyconverter' ),
			'ARS' => __( 'Argentina Peso', 'cbcurrencyconverter' ),
			'AWG' => __( 'Aruba Guilder', 'cbcurrencyconverter' ),
			'AUD' => __( 'Australia Dollar', 'cbcurrencyconverter' ),
			'AZN' => __( 'Azerbaijan New Manat', 'cbcurrencyconverter' ),
			'BSD' => __( 'Bahamas Dollar', 'cbcurrencyconverter' ),
			'BBD' => __( 'Barbados Dollar', 'cbcurrencyconverter' ),
			'BDT' => __( 'Bangladeshi Taka', 'cbcurrencyconverter' ),
			'BYR' => __( 'Belarus Ruble', 'cbcurrencyconverter' ),
			'BZD' => __( 'Belize Dollar', 'cbcurrencyconverter' ),
			'BMD' => __( 'Bermuda Dollar', 'cbcurrencyconverter' ),
			'BOB' => __( 'Bolivia Boliviano', 'cbcurrencyconverter' ),
			'BAM' => __( 'Bosnia and Herzegovina BAM', 'cbcurrencyconverter' ),
			'BWP' => __( 'Botswana Pula', 'cbcurrencyconverter' ),
			'BGN' => __( 'Bulgaria Lev', 'cbcurrencyconverter' ),
			'BRL' => __( 'Brazil Real', 'cbcurrencyconverter' ),
			'BHD' => __( 'Bahrain Dinar', 'cbcurrencyconverter' ),
			'BND' => __( 'Brunei Darussalam Dollar', 'cbcurrencyconverter' ),
			'KHR' => __( 'Cambodia Riel', 'cbcurrencyconverter' ),
			'CAD' => __( 'Canada Dollar', 'cbcurrencyconverter' ),
			'KYD' => __( 'Cayman Islands Dollar', 'cbcurrencyconverter' ),
			'CLP' => __( 'Chile Peso', 'cbcurrencyconverter' ),
			'CNY' => __( 'China Yuan Renminbi', 'cbcurrencyconverter' ),
			'COP' => __( 'Colombia Peso', 'cbcurrencyconverter' ),
			'CRC' => __( 'Costa Rica Colon', 'cbcurrencyconverter' ),
			'HRK' => __( 'Croatia Kuna', 'cbcurrencyconverter' ),
			'CUP' => __( 'Cuba Peso', 'cbcurrencyconverter' ),
			'CZK' => __( 'Czech Republic Koruna', 'cbcurrencyconverter' ),
			'DKK' => __( 'Denmark Krone', 'cbcurrencyconverter' ),
			'DOP' => __( 'Dominican Republic Peso', 'cbcurrencyconverter' ),
			'XCD' => __( 'East Caribbean Dollar', 'cbcurrencyconverter' ),
			'EGP' => __( 'Egypt Pound', 'cbcurrencyconverter' ),
			'SVC' => __( 'El Salvador Colon', 'cbcurrencyconverter' ),
			'EEK' => __( 'Estonia Kroon', 'cbcurrencyconverter' ),
			'EUR' => __( 'Euro Member Countries', 'cbcurrencyconverter' ),
			'FKP' => __( 'Falkland Islands Pound', 'cbcurrencyconverter' ),
			'FJD' => __( 'Fiji Dollar', 'cbcurrencyconverter' ),
			'GEL' => __( 'Georgian Lari', 'cbcurrencyconverter' ),
			'GHC' => __( 'Ghana Cedis', 'cbcurrencyconverter' ),
			'GIP' => __( 'Gibraltar Pound', 'cbcurrencyconverter' ),
			'GTQ' => __( 'Guatemala Quetzal', 'cbcurrencyconverter' ),
			'GGP' => __( 'Guernsey Pound', 'cbcurrencyconverter' ),
			'GYD' => __( 'Guyana Dollar', 'cbcurrencyconverter' ),
			'HNL' => __( 'Honduras Lempira', 'cbcurrencyconverter' ),
			'HKD' => __( 'Hong Kong Dollar', 'cbcurrencyconverter' ),
			'HUF' => __( 'Hungary Forint', 'cbcurrencyconverter' ),
			'ISK' => __( 'Iceland Krona', 'cbcurrencyconverter' ),
			'INR' => __( 'India Rupee', 'cbcurrencyconverter' ),
			'IDR' => __( 'Indonesia Rupiah', 'cbcurrencyconverter' ),
			'IRR' => __( 'Iran Rial', 'cbcurrencyconverter' ),
			'IMP' => __( 'Isle of Man Pound', 'cbcurrencyconverter' ),
			'ILS' => __( 'Israel Shekel', 'cbcurrencyconverter' ),
			'JMD' => __( 'Jamaica Dollar', 'cbcurrencyconverter' ),
			'JPY' => __( 'Japan Yen', 'cbcurrencyconverter' ),
			'JEP' => __( 'Jersey Pound', 'cbcurrencyconverter' ),
			'KZT' => __( 'Kazakhstan Tenge', 'cbcurrencyconverter' ),
			'KPW' => __( 'Korea (North) Won', 'cbcurrencyconverter' ),
			'KRW' => __( 'Korea (South) Won', 'cbcurrencyconverter' ),
			'KGS' => __( 'Kyrgyzstan Som', 'cbcurrencyconverter' ),
			'KES' => __( 'Kenya Shilling', 'cbcurrencyconverter' ),
			'LAK' => __( 'Laos Kip', 'cbcurrencyconverter' ),
			'LVL' => __( 'Latvia Lat', 'cbcurrencyconverter' ),
			'LBP' => __( 'Lebanon Pound', 'cbcurrencyconverter' ),
			'LRD' => __( 'Liberia Dollar', 'cbcurrencyconverter' ),
			'LTL' => __( 'Lithuania Litas', 'cbcurrencyconverter' ),
			'MKD' => __( 'Macedonia Denar', 'cbcurrencyconverter' ),
			'MYR' => __( 'Malaysia Ringgit', 'cbcurrencyconverter' ),
			'MUR' => __( 'Mauritius Rupee', 'cbcurrencyconverter' ),
			'MXN' => __( 'Mexico Peso', 'cbcurrencyconverter' ),
			'MNT' => __( 'Mongolia Tughrik', 'cbcurrencyconverter' ),
			'MZN' => __( 'Mozambique Metical', 'cbcurrencyconverter' ),
			'NAD' => __( 'Namibia Dollar', 'cbcurrencyconverter' ),
			'NPR' => __( 'Nepal Rupee', 'cbcurrencyconverter' ),
			'ANG' => __( 'Netherlands Antilles Guilder', 'cbcurrencyconverter' ),
			'NZD' => __( 'New Zealand Dollar', 'cbcurrencyconverter' ),
			'NIO' => __( 'Nicaragua Cordoba', 'cbcurrencyconverter' ),
			'NGN' => __( 'Nigeria Naira', 'cbcurrencyconverter' ),
			'NOK' => __( 'Norway Krone', 'cbcurrencyconverter' ),
			'OMR' => __( 'Oman Rial', 'cbcurrencyconverter' ),
			'PKR' => __( 'Pakistan Rupee', 'cbcurrencyconverter' ),
			'PAB' => __( 'Panama Balboa', 'cbcurrencyconverter' ),
			'PYG' => __( 'Paraguay Guarani', 'cbcurrencyconverter' ),
			'PEN' => __( 'Peru Nuevo Sol', 'cbcurrencyconverter' ),
			'PGK' => __( 'Papua New Guinean Kina', 'cbcurrencyconverter' ),
			'PHP' => __( 'Philippines Peso', 'cbcurrencyconverter' ),
			'PLN' => __( 'Poland Zloty', 'cbcurrencyconverter' ),
			'QAR' => __( 'Qatar Riyal', 'cbcurrencyconverter' ),
			'RON' => __( 'Romania New Leu', 'cbcurrencyconverter' ),
			'RUB' => __( 'Russia Ruble', 'cbcurrencyconverter' ),
			'SHP' => __( 'Saint Helena Pound', 'cbcurrencyconverter' ),
			'SAR' => __( 'Saudi Arabia Riyal', 'cbcurrencyconverter' ),
			'RSD' => __( 'Serbia Dinar', 'cbcurrencyconverter' ),
			'SCR' => __( 'Seychelles Rupee', 'cbcurrencyconverter' ),
			'SGD' => __( 'Singapore Dollar', 'cbcurrencyconverter' ),
			'SBD' => __( 'Solomon Islands Dollar', 'cbcurrencyconverter' ),
			'SOS' => __( 'Somalia Shilling', 'cbcurrencyconverter' ),
			'ZAR' => __( 'South Africa Rand', 'cbcurrencyconverter' ),
			'LKR' => __( 'Sri Lanka Rupee', 'cbcurrencyconverter' ),
			'SEK' => __( 'Sweden Krona', 'cbcurrencyconverter' ),
			'CHF' => __( 'Switzerland Franc', 'cbcurrencyconverter' ),
			'SRD' => __( 'Suriname Dollar', 'cbcurrencyconverter' ),
			'SYP' => __( 'Syria Pound', 'cbcurrencyconverter' ),
			'TWD' => __( 'Taiwan New Dollar', 'cbcurrencyconverter' ),
			'THB' => __( 'Thailand Baht', 'cbcurrencyconverter' ),
			'TTD' => __( 'Trinidad and Tobago Dollar', 'cbcurrencyconverter' ),
			'TRY' => __( 'Turkey Lira', 'cbcurrencyconverter' ),
			'TRL' => __( 'Turkey Lira', 'cbcurrencyconverter' ),
			'TVD' => __( 'Tuvalu Dollar', 'cbcurrencyconverter' ),
			'UAH' => __( 'Ukraine Hryvna', 'cbcurrencyconverter' ),
			'GBP' => __( 'United Kingdom Pound', 'cbcurrencyconverter' ),
			'USD' => __( 'United States Dollar', 'cbcurrencyconverter' ),
			'UYU' => __( 'Uruguay Peso', 'cbcurrencyconverter' ),
			'UGX' => __( 'Ugandan Shilling', 'cbcurrencyconverter' ),
			'UZS' => __( 'Uzbekistan Som', 'cbcurrencyconverter' ),
			'VEF' => __( 'Venezuela Bolivar', 'cbcurrencyconverter' ),
			'VND' => __( 'Viet Nam Dong', 'cbcurrencyconverter' ),
			'YER' => __( 'Yemen Rial', 'cbcurrencyconverter' ),
			'ZWD' => __( 'Zimbabwe Dollar', 'cbcurrencyconverter' ),
			'XOF' => __( 'West African CFA franc', 'cbcurrencyconverter' ),
			'XAF' => __( 'Central African CFA franc', 'cbcurrencyconverter' )
		];

		$currency_list = apply_filters( 'cbcurrencyconverter_currency_list', $currency_list );

		return $currency_list;
	}//end getCurrencyList

	/**
	 * Get Currency symbols based on currency code
	 *
	 * @return mixed|null
	 * @since 3.0.9
	 *
	 */
	public static function getCurrencySymbols() {
		$currency_list = [
			'AED' => 'د.إ',
			'AFN' => '؋',
			'ALL' => 'L',
			'AMD' => '֏',
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
			'BIF' => 'FBu',
			'BMD' => '$',
			'BND' => '$',
			'BOB' => '$b',
			'BRL' => 'R$',
			'BSD' => '$',
			'BTC' => '฿',
			'BTN' => 'Nu.',
			'BWP' => 'P',
			'BYR' => 'Br',
			'BYN' => 'Br',
			'BZD' => 'BZ$',
			'CAD' => '$',
			'CDF' => 'FC',
			'CHF' => 'CHF',
			'CLP' => '$',
			'CNY' => '¥',
			'COP' => '$',
			'CRC' => '₡',
			'CUC' => '$',
			'CUP' => '₱',
			'CVE' => '$',
			'CZK' => 'Kč',
			'DJF' => 'Fdj',
			'DKK' => 'kr',
			'DOP' => 'RD$',
			'DZD' => 'دج',
			'EEK' => 'kr',
			'EGP' => '£',
			'ERN' => 'Nfk',
			'ETB' => 'Br',
			'ETH' => 'Ξ',
			'EUR' => '€',
			'FJD' => '$',
			'FKP' => '£',
			'GBP' => '£',
			'GEL' => '₾',
			'GGP' => '£',
			'GHC' => '₵',
			'GHS' => 'GH₵',
			'GIP' => '£',
			'GMD' => 'D',
			'GNF' => 'FG',
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
			'JMD' => 'J$',
			'JOD' => 'JD',
			'JPY' => '¥',
			'KES' => 'KSh',
			'KGS' => 'лв',
			'KHR' => '៛',
			'KMF' => 'CF',
			'KPW' => '₩',
			'KRW' => '₩',
			'KWD' => 'KD',
			'KYD' => '$',
			'KZT' => 'лв',
			'LAK' => '₭',
			'LBP' => '£',
			'LKR' => '₨',
			'LRD' => '$',
			'LSL' => 'M',
			'LTC' => 'Ł',
			'LTL' => 'Lt',
			'LVL' => 'Ls',
			'LYD' => 'LD',
			'MAD' => 'MAD',
			'MDL' => 'lei',
			'MGA' => 'Ar',
			'MKD' => 'ден',
			'MMK' => 'K',
			'MNT' => '₮',
			'MOP' => 'MOP$',
			'MRO' => 'UM',
			'MRU' => 'UM',
			'MUR' => '₨',
			'MVR' => 'Rf',
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
			'OMR' => '﷼',
			'PAB' => 'B/.',
			'PEN' => 'S/.',
			'PGK' => 'K',
			'PHP' => '₱',
			'PKR' => '₨',
			'PLN' => 'zł',
			'PYG' => 'Gs',
			'QAR' => '﷼',
			'RMB' => '￥',
			'RON' => 'lei',
			'RSD' => 'Дин.',
			'RUB' => '₽',
			'RWF' => 'R₣',
			'SAR' => '﷼',
			'SBD' => '$',
			'SCR' => '₨',
			'SDG' => 'ج.س.',
			'SEK' => 'kr',
			'SGD' => '$',
			'SHP' => '£',
			'SLL' => 'Le',
			'SOS' => 'S',
			'SRD' => '$',
			'SSP' => '£',
			'STD' => 'Db',
			'STN' => 'Db',
			'SVC' => '$',
			'SYP' => '£',
			'SZL' => 'E',
			'THB' => '฿',
			'TJS' => 'SM',
			'TMT' => 'T',
			'TND' => 'د.ت',
			'TOP' => 'T$',
			'TRL' => '₤',
			'TRY' => '₺',
			'TTD' => 'TT$',
			'TVD' => '$',
			'TWD' => 'NT$',
			'TZS' => 'TSh',
			'UAH' => '₴',
			'UGX' => 'USh',
			'USD' => '$',
			'UYU' => '$U',
			'UZS' => 'лв',
			'VEF' => 'Bs',
			'VND' => '₫',
			'VUV' => 'VT',
			'WST' => 'WS$',
			'XAF' => 'FCFA',
			'XBT' => 'Ƀ',
			'XCD' => '$',
			'XOF' => 'CFA',
			'XPF' => '₣',
			'YER' => '﷼',
			'ZAR' => 'R',
			'ZWD' => 'Z$'
		];

		$currency_list = apply_filters( 'cbcurrencyconverter_currency_symbols', $currency_list );

		return $currency_list;
	}//end getCurrencySymbols

	/**
	 * Get symbold for a currency using currency code
	 *
	 * @param  string  $currency_code
	 *
	 * @return mixed|string
	 * @since 3.0.9
	 *
	 */
	public static function getCurrencySymbol( $currency_code = '' ) {
		if ( $currency_code == '' ) {
			return '';
		}

		$currency_symbols = self::getCurrencySymbols();

		return isset( $currency_symbols[ $currency_code ] ) ? $currency_symbols[ $currency_code ] : '';
	}//end method getCurrencySymbol

	/**
	 * Get all currency list for to
	 *
	 * @return mixed|void
	 * @since  2.2
	 */
	public static function getCurrencyList_to() {
		$currency_list = self::getCurrencyList();

		$currency_list_to = array_flip( $currency_list );

		return apply_filters( 'cbcurrencyconverter_currency_list_to', $currency_list_to );
	} //end getCurrencyList_to

	/**
	 * Get available style reverser
	 *
	 * @return mixed|void
	 */
	public static function getCurrencyList_r() {
		$CurrencyList   = CBCurrencyConverterHelper::getCurrencyList();
		$CurrencyList_r = [];

		foreach ( $CurrencyList as $key => $value ) {
			$CurrencyList_r[ $value ] = $key;
		}

		return apply_filters( 'cbcurrencyconverter_currency_list_r', $CurrencyList_r );
	}//end getCurrencyList_r

	/**
	 * List all global option name with prefix cbxwpbookmark_
	 */
	public static function getAllOptionNames() {
		global $wpdb;

		$prefix       = 'cbcurrencyconverter_';
		$option_names = $wpdb->get_results( "SELECT * FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'", ARRAY_A );

		return apply_filters( 'cbcurrencyconverter_option_names', $option_names );
	}//end getAllOptionNames

	/**
	 * Shortcode builder for display and copy paste purpose
	 *
	 * @return string
	 * @throws Exception
	 */
	public static function shortcode_builder() {

		$attr = $default_values = CBCurrencyConverterHelper::global_default_values();

		$attr = apply_filters( 'cbcurrencyconverter_shortcode_builder_attr', $attr );

		$attr_html = '';

		foreach ( $attr as $key => $value ) {
			$attr_html .= ' ' . $key . '="' . $value . '" ';
		}

		return '[cbcurrencyconverter ' . $attr_html . ']';
	}//end shortcode_builder

	/**
	 * Add utm params to any url
	 *
	 * @param  string  $url
	 *
	 * @return string
	 */
	public static function url_utmy( $url = '' ) {
		if ( $url == '' ) {
			return $url;
		}

		$url = add_query_arg( [
			'utm_source'   => 'plgsidebarinfo',
			'utm_medium'   => 'plgsidebar',
			'utm_campaign' => 'wpfreemium',
		], $url );

		return $url;
	}//end url_utmy

	/**
	 * Get Convertion rate
	 *
	 * @param $price
	 * @param $convertfrom
	 * @param $convertto
	 * @param  int  $decimal_point
	 *
	 * @return mixed|void
	 */
	public static function cbcurrencyconverter_get_rate( $price = 0, $convertfrom = 'USD', $convertto = 'CAD', $decimal_point = 2 ) {
		$conversion_value           = CBCurrencyConverterHelper::getCurrencyRate( $price, $convertfrom, $convertto, $decimal_point );
		$conversion_value           = str_replace( ',', '', $conversion_value );
		$conversion_value_formatted = number_format_i18n( $conversion_value, $decimal_point );

		$response = apply_filters( 'cbxconvertcurrency_conversion_value_formatted', $conversion_value_formatted, $conversion_value, $price, $convertfrom, $convertto, $decimal_point, 'api' );

		return $response;
	}//end method cbcurrencyconverter_get_rate

	/**
	 * Explode and trim
	 *
	 * @param  string  $string
	 *
	 * @return array
	 */
	public static function extract_trim( $string ) {
		if ( is_string( $string ) ) {
			$extract = explode( ',', $string );
		} elseif ( is_array( $string ) ) {
			$extract = array_filter( $string );
		} else {
			return [];
		}

		$trimmed = array_map( 'trim', $extract );

		return array_filter( $trimmed );
	}

	/*
	 * Formatted currencies for show
	 *
	 * @param array $array
	 */
	public static function formatted_currencies( $array ) {
		$currencies_list     = self::getCurrencyList();
		$cal_currencies_from = array_intersect_key( $currencies_list, array_flip( $array ) );

		return $cal_currencies_from;
	}//end method formatted_currencies

	/*
	 * Explode and formatting
	 *
	 * $param string $string Like USA,AUD
	 */
	public static function explode_format( $string ) {

		$result = [];

		$calc_currencies_from_arr = isset( $string ) ? $string : [];

		if ( ! is_array( $calc_currencies_from_arr ) ) {
			$calc_currencies_from_arr = explode( ',', $string );
		}

		if ( ! empty( $calc_currencies_from_arr ) ) {
			$result = self::formatted_currencies( $calc_currencies_from_arr );
		}

		return array_filter( $result );
	}

	/*
	 * Global default values
	 */
	public static function global_default_values() {
		$default_values = [];

		//get options for three main global options
		$global     = get_option( 'cbcurrencyconverter_global' );
		$calculator = get_option( 'cbcurrencyconverter_calculator' );
		$list       = get_option( 'cbcurrencyconverter_list' );


		//get default values to pass in shortcode
		//general setting
		$layout        = ( isset( $global['default_layout'] ) ) ? $global['default_layout'] : 'cal';
		$decimal_point = ( isset( $global['decimal_point'] ) ) ? $global['decimal_point'] : 2;


		//calculator setting
		$calc_title          = ( isset( $calculator['title'] ) ) ? $calculator['title'] : esc_html__( 'Currency Calculator', 'cbcurrencyconverter' );
		$calc_default_amount = ( isset( $calculator['default_amount'] ) ) ? floatval( $calculator['default_amount'] ) : 1;

		$calc_from_currencies = ( isset( $calculator['enabled_from_currencies'] ) ) ? $calculator['enabled_from_currencies'] : [ 'USD' ];
		$calc_from_currency   = ( isset( $calculator['from_currency'] ) ) ? $calculator['from_currency'] : 'USD';

		$calc_to_currencies = ( isset( $calculator['enabled_to_currencies'] ) ) ? $calculator['enabled_to_currencies'] : [ 'GBP', 'CAD', 'AUD' ];
		$calc_to_currency   = ( isset( $calculator['to_currency'] ) ) ? $calculator['to_currency'] : 'CAD';


		//list setting
		$list_title          = ( isset( $list['title'] ) ) ? $list['title'] : esc_html__( 'Currency Latest Rates', 'cbcurrencyconverter' ); //list title
		$list_default_amount = ( isset( $list['default_amount'] ) ) ? $list['default_amount'] : 1;                                          //default amount

		$list_from_currency = ( isset( $list['from_currency'] ) ) ? $list['from_currency'] : 'USD';                                   //we need to set something as default currency to make the list work
		$list_to_currencies = ( isset( $list['to_currencies'] ) ) ? $list['to_currencies'] : [ 'GBP', 'CAD', 'AUD' ];


		//confirm array and have no empty values
		if ( ! is_array( $calc_from_currencies ) ) {
			$calc_from_currencies = [];
		}
		if ( ! is_array( $calc_to_currencies ) ) {
			$calc_to_currencies = [];
		}
		if ( ! is_array( $list_to_currencies ) ) {
			$list_to_currencies = [];
		}

		$calc_from_currencies = array_values( array_filter( $calc_from_currencies ) );
		$calc_to_currencies   = array_values( array_filter( $calc_to_currencies ) );
		$list_to_currencies   = array_values( array_filter( $list_to_currencies ) );

		//if cal from or to currencies are empty then fill manually
		if ( sizeof( $calc_from_currencies ) == 0 ) {
			$calc_from_currencies = [ 'USD' ];
		}
		if ( sizeof( $calc_to_currencies ) == 0 ) {
			$calc_to_currencies = [ 'GBP', 'CAD', 'AUD' ];
		}
		if ( sizeof( $list_to_currencies ) == 0 ) {
			$list_to_currencies = [ 'GBP', 'CAD', 'AUD' ];
		}

		if ( ! in_array( $calc_from_currency, $calc_from_currencies ) || $calc_from_currency == '' ) {
			$calc_from_currency = cbcurrencyconverter_first_value( $calc_from_currencies );
		}
		if ( ! in_array( $calc_to_currency, $calc_to_currencies ) || $calc_to_currency == '' ) {
			$calc_to_currency = cbcurrencyconverter_first_value( $calc_to_currencies );
		}
		if ( $list_from_currency == '' ) {
			$list_from_currency = 'USD';
		}


		//convert array to comma sep string for from and to currencies
		$calc_from_currencies = implode( ',', $calc_from_currencies );
		$calc_to_currencies   = implode( ',', $calc_to_currencies );
		$list_to_currencies   = implode( ',', $list_to_currencies );

		$default_values['layout']        = $layout;
		$default_values['decimal_point'] = $decimal_point;

		$default_values['calc_title']           = $calc_title;
		$default_values['calc_default_amount']  = $calc_default_amount;
		$default_values['calc_from_currencies'] = $calc_from_currencies;
		$default_values['calc_from_currency']   = $calc_from_currency;
		$default_values['calc_to_currencies']   = $calc_to_currencies;
		$default_values['calc_to_currency']     = $calc_to_currency;


		$default_values['list_title']          = $list_title;
		$default_values['list_default_amount'] = $list_default_amount;
		$default_values['list_to_currencies']  = $list_to_currencies;
		$default_values['list_from_currency']  = $list_from_currency;

		return $default_values;
	}//end method global_defaults

	/**
	 * Convert Currency ajax based main method
	 *
	 * @param  float  $price
	 * @param  string  $convertfrom
	 * @param  string  $convertto
	 * @param  int  $decimal_point
	 *
	 * @return string
	 * @since 3.0.0
	 *
	 */
	public static function getCurrencyRate( $price = 0, $convertfrom = 'USD', $convertto = 'CAD', $decimal_point = 2 ) {
		$conversion_value = 0;

		return apply_filters( 'cbxcc_convertion_method', $conversion_value, $price, $convertfrom, $convertto, $decimal_point );
	}//end method getCurrencyRate

	/**
	 * Migration of core plugin from 2.8.4 to 2.9.0
	 */
	public static function core_migrate_284_290() {
		//handle option 'cbcurrencyconverter_custom_rates'
		$old_option = get_option( 'cbcurrencyconverter_calculator_settings', [] );

		if ( is_array( $old_option ) && isset( $old_option['cbcurrencyconverter_enabledcurrencies_calculator'] ) ) {
			//copy of the old from currencies and set as for currencies which we added new
			$old_option['cbcurrencyconverter_enabled_currencies_calculator_to'] = $old_option['cbcurrencyconverter_enabledcurrencies_calculator'];

			update_option( 'cbcurrencyconverter_calculator_settings', $old_option );
		}

	}//end method core_migrate_284_290

	/**
	 * Migration of core plugin from 2.9.0 to 3.0.0
	 */
	public static function core_migrate_290_300() {
		$sections = [
			'cbcurrencyconverter_global_settings'     => 'cbcurrencyconverter_global',
			'cbcurrencyconverter_calculator_settings' => 'cbcurrencyconverter_calculator',
			'cbcurrencyconverter_list_settings'       => 'cbcurrencyconverter_list',
			'cbcurrencyconverter_tools'               => 'cbcurrencyconverter_tools',
		];

		$fields = [
			'cbcurrencyconverter_global_settings' => [
				'cbcurrencyconverter_defaultlayout' => 'default_layout',
				'cbcurrencyconverter_decimalpoint'  => 'decimal_point',
				'cbcurrencyconverter_ratecache'     => 'rate_cache',
				'cbcurrencyconverter_cachetime'     => 'cache_time',
				'api_source'                        => 'api_source',
				'apikey'                            => 'apikey_alpha'
			],
			'cbcurrencyconverter_global_settings' => [
				'cbcurrencyconverter_title_cal'                        => 'title',
				'cbcurrencyconverter_defaultamount_for_calculator'     => 'default_amount',
				'cbcurrencyconverter_enabledcurrencies_calculator'     => 'enabled_from_currencies',
				'cbcurrencyconverter_enabled_currencies_calculator_to' => 'enabled_to_currencies',
				'cbcurrencyconverter_fromcurrency'                     => 'from_currency',
				'cbcurrencyconverter_tocurrency'                       => 'to_currency',
			],
			'cbcurrencyconverter_list_settings'   => [
				'cbcurrencyconverter_title_list'           => 'title',
				'cbcurrencyconverter_decimalpoint'         => 'default_amount',
				'cbcurrencyconverter_tocurrency_list'      => 'to_currencies',
				'cbcurrencyconverter_defaultcurrency_list' => 'from_currency',
			],
			'cbcurrencyconverter_tools'           => [
				'cbcurrencyconverter_delete_options' => 'delete_options',
			]
		];

		foreach ( $sections as $section_old => $section_new ) {
			$old_option = get_option( $section_old );

			$fields = isset( $fields[ $section_old ] ) ? $fields[ $section_old ] : [];

			foreach ( $fields as $old_key => $new_key ) {
				if ( isset( $old_option[ $old_key ] ) ) {
					$old_option[ $new_key ] = $old_option[ $old_key ];
					unset( $old_option[ $old_key ] );
				}
			}

			//if section old name and new  name are not same then delete
			if ( $section_old != $section_new ) {
				delete_option( $section_old );
			}

			if ( $section_new == 'cbcurrencyconverter_tools' ) {
				if ( isset( $old_option['cbcurrencyconverter_decimalpoint'] ) ) {
					unset( $old_option['cbcurrencyconverter_decimalpoint'] );
				}
			}
			//update section

			update_option( $section_new, $old_option );

		}

		//option key deleted
		//cbcurrencyconverter_decimalpoint
	}//end method core_migrate_290_300

	/**
	 * HTML elements, attributes, and attribute values will occur in your output
	 *
	 * @return array
	 * @since 3.0.9
	 *
	 */
	public static function allowedHtmlTags() {
		$allowed_html_tags = [
			'a'      => [
				'href'  => [],
				'title' => [],
				//'class' => array(),
				//'data'  => array(),
				//'rel'   => array(),
			],
			'br'     => [],
			'em'     => [],
			'ul'     => [//'class' => array(),
			],
			'ol'     => [//'class' => array(),
			],
			'li'     => [//'class' => array(),
			],
			'strong' => [],
			'p'      => [
				//'class' => array(),
				//'data'  => array(),
				//'style' => array(),
			],
			'span'   => [
				//					'class' => array(),
				//'style' => array(),
			],
		];

		return apply_filters( 'cbcurrencyconverter_allowed_html_tags', $allowed_html_tags );
	}//end method allowedHtmlTags

	/**
	 * Kses wysiwyg html
	 *
	 * @param  string  $html
	 *
	 * @return mixed|string
	 *
	 * @since 3.0.9
	 *
	 */
	public static function sanitize_wp_kses( $html = '' ) {
		return wp_kses( $html, CBCurrencyConverterHelper::allowedHtmlTags() );
	}//end method sanitize_wp_kses

	/**
	 * Get setting sections
	 *
	 * @return mixed|null
	 * @since 3.0.9
	 *
	 */
	public static function settings_sections() {
		$sections = [
			[
				'id'    => 'cbcurrencyconverter_global',
				'title' => esc_html__( 'General', 'cbcurrencyconverter' ),
			],
			[
				'id'    => 'cbcurrencyconverter_calculator',
				'title' => esc_html__( 'Calculator Default', 'cbcurrencyconverter' ),

			],
			[
				'id'    => 'cbcurrencyconverter_list',
				'title' => esc_html__( 'List Default', 'cbcurrencyconverter' ),

			],
			[
				'id'    => 'cbcurrencyconverter_tools',
				'title' => esc_html__( 'Tools', 'cbcurrencyconverter' ),
			]
		];

		return apply_filters( 'cbcurrencyconverter_sections', $sections );
	}//end method settings_sections

	/**
	 * @return mixed|null
	 * @throws Exception
	 */
	public static function settings_fields() {
		$ajax_nonce           = wp_create_nonce( 'cbcurrencyconverter_nonce' );
		$reset_data_link      = add_query_arg( [ 'cbcurrencyconverter_fullreset' => 1, 'security' => $ajax_nonce ], admin_url( 'options-general.php?page=cbcurrencyconverter' ) );
		$reset_transient_link = add_query_arg( [ 'cbcurrencyconverter_transientreset' => 1, 'security' => $ajax_nonce ], admin_url( 'options-general.php?page=cbcurrencyconverter' ) );

		$table_html = '<div id="cbcurrencyconverter_resetinfo_wrap">' . esc_html__( 'Loading ...', 'cbcurrencyconverter' ) . '</div>';

		$transient_caches     = CBCurrencyConverterHelper::getAllTransientCacheNames();
		$table_counter        = 1;
		$transient_table_html = '<p><strong>' . esc_html__( 'Following transient caches are stored by this plugin', 'cbcurrencyconverter' ) . '</strong></p>';
		foreach ( $transient_caches as $value ) {
			$transient_table_html .= '<p>' . str_pad( $table_counter, 2, '0', STR_PAD_LEFT ) . '. ' . $value . ' </p>';
			$table_counter ++;
		}

		$all_currencies = CBCurrencyConverterHelper::getCurrencyList();
		$setting_cal    = get_option( 'cbcurrencyconverter_calculator' );

		$enabled_from_currencies = isset( $setting_cal['enabled_from_currencies'] ) ? $setting_cal['enabled_from_currencies'] : [];
		$enabled_to_currencies   = isset( $setting_cal['enabled_to_currencies'] ) ? $setting_cal['enabled_to_currencies'] : [];

		$cal_from_to_list_currency    = [];
		$cal_from_to_list_currency_to = [];

		if ( is_array( $enabled_from_currencies ) && sizeof( $enabled_from_currencies ) > 0 ) {
			foreach ( $all_currencies as $key => $value ) {
				if ( in_array( $key, $enabled_from_currencies ) ) {
					$cal_from_to_list_currency[ $key ] = $value . '-' . $key;
				}
			}
		}

		if ( is_array( $enabled_to_currencies ) && sizeof( $enabled_to_currencies ) > 0 ) {
			foreach ( $all_currencies as $key => $value ) {
				if ( in_array( $key, $enabled_to_currencies ) ) {
					$cal_from_to_list_currency_to[ $key ] = $value . '-' . $key;
				}
			}
		}

		foreach ( $all_currencies as $key => $value ) {
			$all_currencies[ $key ] = $value . ' - ' . $key . '';
		}

		/*foreach ( $cal_from_to_list_currency as $key => $value ) {
			$cal_from_to_list_currencyp[ $key ] = $value . ' - ' . $key;
		}*/

		$global_settings    = get_option( 'cbcurrencyconverter_global', [] );
		$calculator_setting = get_option( 'cbcurrencyconverter_calculator', [] );
		$list_settings      = get_option( 'cbcurrencyconverter_list', [] );


		$rates_api_titles = CBCurrencyConverterHelper::currency_rates_api_titles();

		$global_settings = [
			'general_heading'         => [
				'name'    => 'general_heading',
				'label'   => esc_html__( 'Default Settings', 'cbcurrencyconverter' ),
				'type'    => 'heading',
				'default' => '',
			],
			'default_layout'          => [
				'name'    => 'default_layout',
				'label'   => esc_html__( 'Layout', 'cbcurrencyconverter' ),
				'desc'    => esc_html__( 'Select layout', 'cbcurrencyconverter' ),
				'type'    => 'select',
				'default' => 'cal',
				'options' => [
					'cal'               => esc_html__( 'Calculator', 'cbcurrencyconverter' ),
					'list'              => esc_html__( 'List', 'cbcurrencyconverter' ),
					'calwithlistbottom' => esc_html__( 'Calculator with List at bottom', 'cbcurrencyconverter' ),
					'calwithlisttop'    => esc_html__( 'Calculator with List at top', 'cbcurrencyconverter' ),
				],
			],
			'decimal_point'           => [
				'name'    => 'decimal_point',
				'label'   => esc_html__( 'Decimal Point', 'cbcurrencyconverter' ),
				'desc'    => esc_html__( 'decimal point position', 'cbcurrencyconverter' ),
				'type'    => 'number',
				'default' => '2',
			],
			'show_symbol'             => [
				'name'    => 'show_symbol',
				'label'   => esc_html__( 'Show Symbol', 'cbcurrencyconverter' ),
				'desc'    => esc_html__( 'Show currency symbol where applicable. If symbol used then without full currency name Currency code and symbol will be used in some places.', 'cbcurrencyconverter' ),
				'type'    => 'checkbox',
				'default' => 'on',
			],
			'show_flag'               => [
				'name'    => 'show_flag',
				'label'   => esc_html__( 'Show Flag', 'cbcurrencyconverter' ),
				'desc'    => esc_html__( 'Show flag based on currency belongs to origin country.', 'cbcurrencyconverter' ),
				'type'    => 'checkbox',
				'default' => 'on',
			],
			'rate_cache'              => [
				'name'    => 'rate_cache',
				'label'   => esc_html__( 'Use Rate Api Caching', 'cbcurrencyconverter' ),
				'desc'    => esc_html__( 'Rate api caching is recommended', 'cbcurrencyconverter' ),
				'type'    => 'radio',
				'default' => 1,
				'options' => [
					'1' => esc_html__( 'Enable', 'cbcurrencyconverter' ),
					'0' => esc_html__( 'Disable', 'cbcurrencyconverter' ),
				],
			],
			'cache_time'              => [
				'name'    => 'cache_time',
				'label'   => esc_html__( 'Rate Api Cache Time(Hr)', 'cbcurrencyconverter' ),
				'desc'    => sprintf( __( 'Time is in hour, default is 2 hours, rate api cache can be reset from <a target="_blank" href="%s">tools setting</a>', 'cbcurrencyconverter' ), admin_url( 'options-general.php?page=cbcurrencyconverter#cbcurrencyconverter_tools' ) ),
				'type'    => 'number',
				'default' => '2',
				'min'     => 0,
				'max'     => 720,
				'step'    => .01,

			],
			'api_source'              => [
				'name'    => 'api_source',
				'label'   => esc_html__( 'Api Source', 'cbcurrencyconverter' ),
				'desc'    => __( 'Different api sources gives different rates based on their historical data. If you are using pro api then check the <strong>Pro & Extended Apis</strong> tab for api key configuration.', 'cbcurrencyconverter' ),
				'type'    => 'select',
				'default' => 'exchangeratehost',
				'options' => $rates_api_titles
			],
			'apikey_exchangeratehost' => [
				'name'    => 'apikey_exchangeratehost',
				'label'   => esc_html__( 'Exchangeratehost Api Key(Free Api)', 'cbcurrencyconverter' ),
				'desc'    => __( 'Please collect your key from <a href="https://exchangerate.host/product" target="_blank">exchangerate.host</a>, it\'s <strong>free</strong>, you can try their pro also', 'cbcurrencyconverter' ),
				'type'    => 'text',
				'default' => '',
			],
			'apikey_alpha'            => [
				'name'    => 'apikey_alpha',
				'label'   => esc_html__( 'Alphavantage Api Key(Paid)', 'cbcurrencyconverter' ),
				'desc'    => __( 'Please collect your key from <a href="https://www.alphavantage.co/support/#api-key" target="_blank">alphavantage.co</a>, it\'s <strong>free</strong>, you can try their pro also', 'cbcurrencyconverter' ),
				'type'    => 'text',
				'default' => '',
			],
			'apikey_open_exg_free'    => [
				'name'    => 'apikey_open_exg_free',
				'label'   => esc_html__( 'Openexchangerates Api Key(Free)', 'cbcurrencyconverter' ),
				'desc'    => __( 'Please collect your key from <a href="https://openexchangerates.org/" target="_blank">openexchangerates.org</a>, it has both free and pro api', 'cbcurrencyconverter' ),
				'type'    => 'text',
				'default' => '',
			],
			'apikey_clayer_free'      => [
				'name'    => 'apikey_clayer_free',
				'label'   => esc_html__( 'Currencylayer Api Key(Free)', 'cbcurrencyconverter' ),
				'desc'    => __( 'Please collect your key from <a href="https://currencylayer.com/" target="_blank">currencylayer.com</a>, it has both free and pro api', 'cbcurrencyconverter' ),
				'type'    => 'text',
				'default' => '',
			],

			'shortcode_demo' => [
				'name'    => 'shortcode_demo',
				'label'   => esc_html__( 'Shortcode & Demo', 'cbcurrencyconverter' ),
				'desc'    => esc_html__( 'Shortcode and demo based on default setting, please save once to check change.', 'cbcurrencyconverter' ),
				'type'    => 'shortcode',
				'class'   => 'cbcurrencyconverter_demo_copy',
				'default' => CBCurrencyConverterHelper::shortcode_builder(),
			],
		];

		$calculator_settings = [
			'calculator_heading'      => [
				'name'    => 'calculator_heading',
				'label'   => esc_html__( 'Calculator Default Settings', 'cbcurrencyconverter' ),
				'type'    => 'heading',
				'default' => '',
			],
			'title'                   => [
				'name'    => 'title',
				'label'   => esc_html__( 'Calculator Heading', 'cbcurrencyconverter' ),
				'desc'    => esc_html__( 'Title to show in calculator', 'cbcurrencyconverter' ),
				'type'    => 'text',
				'default' => esc_html__( 'Currency Calculator', 'cbcurrencyconverter' ),
			],
			'default_amount'          => [
				'name'    => 'default_amount',
				'label'   => esc_html__( 'Default Amount', 'cbcurrencyconverter' ),
				'desc'    => esc_html__( 'What Will Be Your Default Amount of Currency For Calculating', 'cbcurrencyconverter' ),
				'type'    => 'number',
				'default' => '1',
			],
			'enabled_from_currencies' => [
				'name'    => 'enabled_from_currencies',
				'label'   => esc_html__( 'From Currencies', 'cbcurrencyconverter' ),
				'desc'    => esc_html__( 'Currency list to convert and show in Calculator Dropdown', 'cbcurrencyconverter' ),
				'type'    => 'select',
				'multi'   => 1,
				'default' => [ 'USD' ],
				'options' => $all_currencies
			],
			'from_currency'           => [
				'name'    => 'from_currency',
				'label'   => esc_html__( 'From default currency', 'cbcurrencyconverter' ),
				'desc'    => esc_html__( 'What Will Be Your Default  Currency To Convert From', 'cbcurrencyconverter' ),
				'type'    => 'select',
				'default' => 'USD',
				'options' => $cal_from_to_list_currency
			],
			'enabled_to_currencies'   => [
				'name'    => 'enabled_to_currencies',
				'label'   => esc_html__( 'To Currencies', 'cbcurrencyconverter' ),
				'desc'    => esc_html__( 'Currency list to convert and show in Calculator Dropdown', 'cbcurrencyconverter' ),
				'type'    => 'select',
				'multi'   => 1,
				'default' => [ 'GBP', 'CAD', 'AUD' ],
				'options' => $all_currencies
			],
			'to_currency'             => [
				'name'    => 'to_currency',
				'label'   => esc_html__( 'To default currency', 'cbcurrencyconverter' ),
				'desc'    => esc_html__( 'What Will Be Your Default To Currency', 'cbcurrencyconverter' ),
				'type'    => 'select',
				'default' => 'CAD',
				'options' => $cal_from_to_list_currency_to
			]
		];


		$list_settings = [
			'list_heading'   => [
				'name'    => 'list_heading',
				'label'   => esc_html__( 'List Default Settings', 'cbcurrencyconverter' ),
				'type'    => 'heading',
				'default' => ''
			],
			'title'          => [
				'name'    => 'title',
				'label'   => esc_html__( 'List Heading', 'cbcurrencyconverter' ),
				'desc'    => esc_html__( 'Title to show in listing', 'cbcurrencyconverter' ),
				'type'    => 'text',
				'default' => esc_html__( 'Currency Latest Rates', 'cbcurrencyconverter' )
			],
			'default_amount' => [
				'name'    => 'default_amount',
				'label'   => esc_html__( 'Default Amount', 'cbcurrencyconverter' ),
				'desc'    => esc_html__( 'Default amount for listing', 'cbcurrencyconverter' ),
				'type'    => 'number',
				'default' => 1
			],
			'from_currency'  => [
				'name'    => 'from_currency',
				'label'   => esc_html__( 'Default From Currency', 'cbcurrencyconverter' ),
				'desc'    => esc_html__( 'What Will Be Your Default Currency For Listing', 'cbcurrencyconverter' ),
				'type'    => 'select',
				'default' => 'USD',
				'options' => $all_currencies
			],
			'to_currencies'  => [
				'name'    => 'to_currencies',
				'label'   => esc_html__( 'To Currencies', 'cbcurrencyconverter' ),
				'desc'    => esc_html__( 'Currency list to convert and show in listing', 'cbcurrencyconverter' ),
				'type'    => 'select',
				'multi'   => 1,
				'default' => [ 'GBP', 'CAD', 'AUD' ],
				'options' => $all_currencies
			],

		];

		$tools_settings = [
			'tool_heading'    => [
				'name'    => 'tool_heading',
				'label'   => esc_html__( 'Tools Settings', 'cbcurrencyconverter' ),
				'type'    => 'heading',
				'default' => '',
			],
			'delete_options'  => [
				'name'    => 'delete_options',
				'label'   => esc_html__( 'Remove Data on Uninstall?', 'cbcurrencyconverter' ),
				'desc'    => __( 'Check this box if you would like <strong>CBX Currency Converter</strong> to completely remove all of its data when the plugin is deleted.', 'cbcurrencyconverter' ),
				'type'    => 'checkbox',
				'default' => '',

			],
			'reset_data'      => [
				'name'     => 'reset_data',
				'label'    => esc_html__( 'Reset All Settings', 'cbcurrencyconverter' ),
				'desc'     => $table_html . '<p>' . esc_html__( 'Reset option values and all tables created by this plugin', 'cbcurrencyconverter' ) . '<a data-busy="0" class="button secondary ml-20" id="reset_data_trigger"  href="#">' . esc_html__( 'Reset Data', 'cbcurrencyconverter' ) . '</a></p>',
				'type'     => 'html',
				'default'  => '',
				'desc_tip' => true,
			],
			'reset_transient' => [
				'name'     => 'reset_transient',
				'label'    => esc_html__( 'Reset Currency Rate Cache', 'cbcurrencyconverter' ),
				'desc'     => esc_html__( 'Api request are stored as wordpress transient cache.', 'cbcurrencyconverter' ) . '<a id="reset_transient_trigger" data-confirm-title="' . esc_html__( 'Reset transient cache', 'cbcurrencyconverter' ) . '" data-confirm="' . esc_html__( 'Are you sure to reset currency rate cache, this process can not be undone?', 'cbcurrencyconverter' ) . '" class="button secondary" href="' . esc_url( $reset_transient_link ) . '">' . esc_html__( 'Reset transient cache',
						'cbcurrencyconverter' ) . '</a>' . $transient_table_html,
				'type'     => 'html',
				'default'  => '',
				'desc_tip' => true,
			],
		];

		$fields = [
			'cbcurrencyconverter_global'     => $global_settings,
			'cbcurrencyconverter_calculator' => $calculator_settings,
			'cbcurrencyconverter_list'       => $list_settings,
			'cbcurrencyconverter_tools'      => $tools_settings,
		];

		return apply_filters( 'cbcurrencyconverter_fields', $fields );
	}//end method settings_fields

	/**
	 * Plugin reset html table
	 *
	 * @return string
	 * @since 3.0.9
	 *
	 */
	public static function setting_reset_html_table() {
		$option_values = CBCurrencyConverterHelper::getAllOptionNames();

		$table_html = '<div id="cbcurrencyconverter_resetinfo"';
		$table_html .= '<p style="margin-bottom: 15px;" id="cbcurrencyconverter_plg_gfig_info"><strong>' . esc_html__( 'Following option values created by this plugin(including addon) from WordPress core option table', 'cbcurrencyconverter' ) . '</strong></p>';

		$table_html .= '<table class="widefat widethin cbcurrencyconverter_table_data">
	<thead>
	<tr>
		<th class="row-title">' . esc_attr__( 'Option Name', 'cbcurrencyconverter' ) . '</th>
		<th>' . esc_attr__( 'Option ID', 'cbcurrencyconverter' ) . '</th>		
	</tr>
	</thead>';

		$table_html .= '<tbody>';

		$i = 0;
		foreach ( $option_values as $key => $value ) {
			$alternate_class = ( $i % 2 == 0 ) ? 'alternate' : '';
			$i ++;
			$table_html .= '<tr class="' . esc_attr( $alternate_class ) . '">
									<td class="row-title"><input checked class="magic-checkbox reset_options" type="checkbox" name="reset_options[' . $value['option_name'] . ']" id="reset_options_' . esc_attr( $value['option_name'] ) . '" value="' . $value['option_name'] . '" />
  <label for="reset_options_' . esc_attr( $value['option_name'] ) . '">' . esc_attr( $value['option_name'] ) . '</td>
									<td>' . esc_attr( $value['option_id'] ) . '</td>									
								</tr>';
		}

		$table_html .= '</tbody>';
		$table_html .= '<tfoot>
	<tr>
		<th class="row-title">' . esc_attr__( 'Option Name', 'cbcurrencyconverter' ) . '</th>
		<th>' . esc_attr__( 'Option ID', 'cbcurrencyconverter' ) . '</th>				
	</tr>
	</tfoot>
</table>';


		return $table_html;

	}//end method setting_reset_html_table

	/**
	 * Direct currency rate
	 *
	 * @param $from
	 * @param $to
	 * @param $amount
	 * @param $decimal_point
	 *
	 * @return string
	 * @since v3.1.0
	 */
	public static function cbcurrencyconverter_rate($from = '', $to = '', $amount = 1, $decimal_point = 2){
		$from          = sanitize_text_field( $from );
		$to            = sanitize_text_field($to );
		$amount        = floatval($amount);
		$decimal_point = intval($decimal_point);

		if ( $amount == 0 || $from == '' || $to == '' ) {
			return 0;
		}


		$conversion_value = CBCurrencyConverterHelper::getCurrencyRate( $amount, $from, $to, $decimal_point );
		$conversion_value = str_replace( ',', '', $conversion_value );

		//$conversion_value_formatted = number_format_i18n( $conversion_value, $decimal_point );
		$conversion_value_formatted = number_format( $conversion_value, $decimal_point, '.', '' );

		return apply_filters( 'cbxconvertcurrency_conversion_value_formatted', $conversion_value_formatted, $conversion_value, $amount, $from, $to, $decimal_point, 'api' );
	}//end method cbcurrencyconverter_rate
}//end of class CBCurrencyConverterHelper