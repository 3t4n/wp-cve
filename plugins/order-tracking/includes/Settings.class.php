<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdotpSettings' ) ) {
/**
 * Class to handle configurable settings for Order Tracking
 * @since 3.0.0
 */
class ewdotpSettings {

	public $order_information_options = array();

	public $sales_rep_options = array();

	public $status_options = array();

	public $currency_options = array();

	public $notification_options = array();

	public $country_phone_array = array(
		// 'AD' => array( 'name' => 'ANDORRA', 'code' => '376' ),
		// 'AE' => array( 'name' => 'UNITED ARAB EMIRATES', 'code' => '971' ),
		// 'AF' => array( 'name' => 'AFGHANISTAN', 'code' => '93' ),
		// 'AG' => array( 'name' => 'ANTIGUA AND BARBUDA', 'code' => '1268' ),
		// 'AI' => array( 'name' => 'ANGUILLA', 'code' => '1264' ),
		// 'AL' => array( 'name' => 'ALBANIA', 'code' => '355' ),
		// 'AM' => array( 'name' => 'ARMENIA', 'code' => '374' ),
		// 'AN' => array( 'name' => 'NETHERLANDS ANTILLES', 'code' => '599' ),
		// 'AO' => array( 'name' => 'ANGOLA', 'code' => '244' ),
		// 'AQ' => array( 'name' => 'ANTARCTICA', 'code' => '672' ),
		'AR' => array( 'name' => 'ARGENTINA', 'code' => '54' ),
		// 'AS' => array( 'name' => 'AMERICAN SAMOA', 'code' => '1684' ),
		'AT' => array( 'name' => 'AUSTRIA', 'code' => '43' ),
		'AU' => array( 'name' => 'AUSTRALIA', 'code' => '61' ),
		// 'AW' => array( 'name' => 'ARUBA', 'code' => '297' ),
		// 'AZ' => array( 'name' => 'AZERBAIJAN', 'code' => '994' ),
		// 'BA' => array( 'name' => 'BOSNIA AND HERZEGOVINA', 'code' => '387' ),
		// 'BB' => array( 'name' => 'BARBADOS', 'code' => '1246' ),
		// 'BD' => array( 'name' => 'BANGLADESH', 'code' => '880' ),
		'BE' => array( 'name' => 'BELGIUM', 'code' => '32' ),
		// 'BF' => array( 'name' => 'BURKINA FASO', 'code' => '226' ),
		'BG' => array( 'name' => 'BULGARIA', 'code' => '359' ),
		// 'BH' => array( 'name' => 'BAHRAIN', 'code' => '973' ),
		// 'BI' => array( 'name' => 'BURUNDI', 'code' => '257' ),
		// 'BJ' => array( 'name' => 'BENIN', 'code' => '229' ),
		// 'BL' => array( 'name' => 'SAINT BARTHELEMY', 'code' => '590' ),
		// 'BM' => array( 'name' => 'BERMUDA', 'code' => '1441' ),
		// 'BN' => array( 'name' => 'BRUNEI DARUSSALAM', 'code' => '673' ),
		// 'BO' => array( 'name' => 'BOLIVIA', 'code' => '591' ),
		'BR' => array( 'name' => 'BRAZIL', 'code' => '55' ),
		// 'BS' => array( 'name' => 'BAHAMAS', 'code' => '1242' ),
		// 'BT' => array( 'name' => 'BHUTAN', 'code' => '975' ),
		// 'BW' => array( 'name' => 'BOTSWANA', 'code' => '267' ),
		// 'BY' => array( 'name' => 'BELARUS', 'code' => '375' ),
		// 'BZ' => array( 'name' => 'BELIZE', 'code' => '501' ),
		'CA' => array( 'name' => 'CANADA', 'code' => '1' ),
		// 'CC' => array( 'name' => 'COCOS (KEELING) ISLANDS', 'code' => '61' ),
		// 'CD' => array( 'name' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'code' => '243' ),
		// 'CF' => array( 'name' => 'CENTRAL AFRICAN REPUBLIC', 'code' => '236' ),
		// 'CG' => array( 'name' => 'CONGO', 'code' => '242' ),
		'CH' => array( 'name' => 'SWITZERLAND', 'code' => '41' ),
		// 'CI' => array( 'name' => 'COTE D IVOIRE', 'code' => '225' ),
		// 'CK' => array( 'name' => 'COOK ISLANDS', 'code' => '682' ),
		// 'CL' => array( 'name' => 'CHILE', 'code' => '56' ),
		// 'CM' => array( 'name' => 'CAMEROON', 'code' => '237' ),
		'CN' => array( 'name' => 'CHINA', 'code' => '86' ),
		// 'CO' => array( 'name' => 'COLOMBIA', 'code' => '57' ),
		// 'CR' => array( 'name' => 'COSTA RICA', 'code' => '506' ),
		// 'CU' => array( 'name' => 'CUBA', 'code' => '53' ),
		// 'CV' => array( 'name' => 'CAPE VERDE', 'code' => '238' ),
		// 'CX' => array( 'name' => 'CHRISTMAS ISLAND', 'code' => '61' ),
		// 'CY' => array( 'name' => 'CYPRUS', 'code' => '357' ),
		'CZ' => array( 'name' => 'CZECH REPUBLIC', 'code' => '420' ),
		'DE' => array( 'name' => 'GERMANY', 'code' => '49' ),
		// 'DJ' => array( 'name' => 'DJIBOUTI', 'code' => '253' ),
		'DK' => array( 'name' => 'DENMARK', 'code' => '45' ),
		// 'DM' => array( 'name' => 'DOMINICA', 'code' => '1767' ),
		// 'DO' => array( 'name' => 'DOMINICAN REPUBLIC', 'code' => '1809' ),
		// 'DZ' => array( 'name' => 'ALGERIA', 'code' => '213' ),
		// 'EC' => array( 'name' => 'ECUADOR', 'code' => '593' ),
		'EE' => array( 'name' => 'ESTONIA', 'code' => '372' ),
		// 'EG' => array( 'name' => 'EGYPT', 'code' => '20' ),
		// 'ER' => array( 'name' => 'ERITREA', 'code' => '291' ),
		'ES' => array( 'name' => 'SPAIN', 'code' => '34' ),
		// 'ET' => array( 'name' => 'ETHIOPIA', 'code' => '251' ),
		'FI' => array( 'name' => 'FINLAND', 'code' => '358' ),
		// 'FJ' => array( 'name' => 'FIJI', 'code' => '679' ),
		// 'FK' => array( 'name' => 'FALKLAND ISLANDS (MALVINAS)', 'code' => '500' ),
		// 'FM' => array( 'name' => 'MICRONESIA, FEDERATED STATES OF', 'code' => '691' ),
		// 'FO' => array( 'name' => 'FAROE ISLANDS', 'code' => '298' ),
		'FR' => array( 'name' => 'FRANCE', 'code' => '33' ),
		// 'GA' => array( 'name' => 'GABON', 'code' => '241' ),
		'GB' => array( 'name' => 'UNITED KINGDOM', 'code' => '44' ),
		// 'GD' => array( 'name' => 'GRENADA', 'code' => '1473' ),
		// 'GE' => array( 'name' => 'GEORGIA', 'code' => '995' ),
		// 'GH' => array( 'name' => 'GHANA', 'code' => '233' ),
		// 'GI' => array( 'name' => 'GIBRALTAR', 'code' => '350' ),
		'GL' => array( 'name' => 'GREENLAND', 'code' => '299' ),
		// 'GM' => array( 'name' => 'GAMBIA', 'code' => '220' ),
		// 'GN' => array( 'name' => 'GUINEA', 'code' => '224' ),
		// 'GQ' => array( 'name' => 'EQUATORIAL GUINEA', 'code' => '240' ),
		'GR' => array( 'name' => 'GREECE', 'code' => '30' ),
		// 'GT' => array( 'name' => 'GUATEMALA', 'code' => '502' ),
		// 'GU' => array( 'name' => 'GUAM', 'code' => '1671' ),
		// 'GW' => array( 'name' => 'GUINEA-BISSAU', 'code' => '245' ),
		// 'GY' => array( 'name' => 'GUYANA', 'code' => '592' ),
		'HK' => array( 'name' => 'HONG KONG', 'code' => '852' ),
		// 'HN' => array( 'name' => 'HONDURAS', 'code' => '504' ),
		'HR' => array( 'name' => 'CROATIA', 'code' => '385' ),
		// 'HT' => array( 'name' => 'HAITI', 'code' => '509' ),
		'HU' => array( 'name' => 'HUNGARY', 'code' => '36' ),
		'ID' => array( 'name' => 'INDONESIA', 'code' => '62' ),
		'IE' => array( 'name' => 'IRELAND', 'code' => '353' ),
		'IL' => array( 'name' => 'ISRAEL', 'code' => '972' ),
		// 'IM' => array( 'name' => 'ISLE OF MAN', 'code' => '44' ),
		'IN' => array( 'name' => 'INDIA', 'code' => '91' ),
		// 'IQ' => array( 'name' => 'IRAQ', 'code' => '964' ),
		// 'IR' => array( 'name' => 'IRAN, ISLAMIC REPUBLIC OF', 'code' => '98' ),
		'IS' => array( 'name' => 'ICELAND', 'code' => '354' ),
		'IT' => array( 'name' => 'ITALY', 'code' => '39' ),
		// 'JM' => array( 'name' => 'JAMAICA', 'code' => '1876' ),
		// 'JO' => array( 'name' => 'JORDAN', 'code' => '962' ),
		'JP' => array( 'name' => 'JAPAN', 'code' => '81' ),
		// 'KE' => array( 'name' => 'KENYA', 'code' => '254' ),
		// 'KG' => array( 'name' => 'KYRGYZSTAN', 'code' => '996' ),
		// 'KH' => array( 'name' => 'CAMBODIA', 'code' => '855' ),
		// 'KI' => array( 'name' => 'KIRIBATI', 'code' => '686' ),
		// 'KM' => array( 'name' => 'COMOROS', 'code' => '269' ),
		// 'KN' => array( 'name' => 'SAINT KITTS AND NEVIS', 'code' => '1869' ),
		// 'KP' => array( 'name' => 'KOREA DEMOCRATIC PEOPLES REPUBLIC OF', 'code' => '850' ),
		'KR' => array( 'name' => 'KOREA REPUBLIC OF', 'code' => '82' ),
		// 'KW' => array( 'name' => 'KUWAIT', 'code' => '965' ),
		// 'KY' => array( 'name' => 'CAYMAN ISLANDS', 'code' => '1345' ),
		// 'KZ' => array( 'name' => 'KAZAKSTAN', 'code' => '7' ),
		// 'LA' => array( 'name' => 'LAO PEOPLES DEMOCRATIC REPUBLIC', 'code' => '856' ),
		// 'LB' => array( 'name' => 'LEBANON', 'code' => '961' ),
		// 'LC' => array( 'name' => 'SAINT LUCIA', 'code' => '1758' ),
		'LI' => array( 'name' => 'LIECHTENSTEIN', 'code' => '423' ),
		// 'LK' => array( 'name' => 'SRI LANKA', 'code' => '94' ),
		// 'LR' => array( 'name' => 'LIBERIA', 'code' => '231' ),
		// 'LS' => array( 'name' => 'LESOTHO', 'code' => '266' ),
		'LT' => array( 'name' => 'LITHUANIA', 'code' => '370' ),
		'LU' => array( 'name' => 'LUXEMBOURG', 'code' => '352' ),
		'LV' => array( 'name' => 'LATVIA', 'code' => '371' ),
		// 'LY' => array( 'name' => 'LIBYAN ARAB JAMAHIRIYA', 'code' => '218' ),
		// 'MA' => array( 'name' => 'MOROCCO', 'code' => '212' ),
		// 'MC' => array( 'name' => 'MONACO', 'code' => '377' ),
		// 'MD' => array( 'name' => 'MOLDOVA, REPUBLIC OF', 'code' => '373' ),
		'ME' => array( 'name' => 'MONTENEGRO', 'code' => '382' ),
		// 'MF' => array( 'name' => 'SAINT MARTIN', 'code' => '1599' ),
		// 'MG' => array( 'name' => 'MADAGASCAR', 'code' => '261' ),
		// 'MH' => array( 'name' => 'MARSHALL ISLANDS', 'code' => '692' ),
		// 'MK' => array( 'name' => 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'code' => '389' ),
		// 'ML' => array( 'name' => 'MALI', 'code' => '223' ),
		// 'MM' => array( 'name' => 'MYANMAR', 'code' => '95' ),
		// 'MN' => array( 'name' => 'MONGOLIA', 'code' => '976' ),
		// 'MO' => array( 'name' => 'MACAU', 'code' => '853' ),
		// 'MP' => array( 'name' => 'NORTHERN MARIANA ISLANDS', 'code' => '1670' ),
		// 'MR' => array( 'name' => 'MAURITANIA', 'code' => '222' ),
		// 'MS' => array( 'name' => 'MONTSERRAT', 'code' => '1664' ),
		// 'MT' => array( 'name' => 'MALTA', 'code' => '356' ),
		// 'MU' => array( 'name' => 'MAURITIUS', 'code' => '230' ),
		// 'MV' => array( 'name' => 'MALDIVES', 'code' => '960' ),
		// 'MW' => array( 'name' => 'MALAWI', 'code' => '265' ),
		'MX' => array( 'name' => 'MEXICO', 'code' => '52' ),
		'MY' => array( 'name' => 'MALAYSIA', 'code' => '60' ),
		// 'MZ' => array( 'name' => 'MOZAMBIQUE', 'code' => '258' ),
		// 'NA' => array( 'name' => 'NAMIBIA', 'code' => '264' ),
		// 'NC' => array( 'name' => 'NEW CALEDONIA', 'code' => '687' ),
		// 'NE' => array( 'name' => 'NIGER', 'code' => '227' ),
		// 'NG' => array( 'name' => 'NIGERIA', 'code' => '234' ),
		// 'NI' => array( 'name' => 'NICARAGUA', 'code' => '505' ),
		'NL' => array( 'name' => 'NETHERLANDS', 'code' => '31' ),
		'NO' => array( 'name' => 'NORWAY', 'code' => '47' ),
		// 'NP' => array( 'name' => 'NEPAL', 'code' => '977' ),
		// 'NR' => array( 'name' => 'NAURU', 'code' => '674' ),
		// 'NU' => array( 'name' => 'NIUE', 'code' => '683' ),
		'NZ' => array( 'name' => 'NEW ZEALAND', 'code' => '64' ),
		// 'OM' => array( 'name' => 'OMAN', 'code' => '968' ),
		// 'PA' => array( 'name' => 'PANAMA', 'code' => '507' ),
		// 'PE' => array( 'name' => 'PERU', 'code' => '51' ),
		// 'PF' => array( 'name' => 'FRENCH POLYNESIA', 'code' => '689' ),
		// 'PG' => array( 'name' => 'PAPUA NEW GUINEA', 'code' => '675' ),
		// 'PH' => array( 'name' => 'PHILIPPINES', 'code' => '63' ),
		// 'PK' => array( 'name' => 'PAKISTAN', 'code' => '92' ),
		'PL' => array( 'name' => 'POLAND', 'code' => '48' ),
		// 'PM' => array( 'name' => 'SAINT PIERRE AND MIQUELON', 'code' => '508' ),
		// 'PN' => array( 'name' => 'PITCAIRN', 'code' => '870' ),
		'PR' => array( 'name' => 'PUERTO RICO', 'code' => '1' ),
		'PT' => array( 'name' => 'PORTUGAL', 'code' => '351' ),
		// 'PW' => array( 'name' => 'PALAU', 'code' => '680' ),
		// 'PY' => array( 'name' => 'PARAGUAY', 'code' => '595' ),
		// 'QA' => array( 'name' => 'QATAR', 'code' => '974' ),
		'RO' => array( 'name' => 'ROMANIA', 'code' => '40' ),
		'RS' => array( 'name' => 'SERBIA', 'code' => '381' ),
		'RU' => array( 'name' => 'RUSSIAN FEDERATION', 'code' => '7' ),
		// 'RW' => array( 'name' => 'RWANDA', 'code' => '250' ),
		// 'SA' => array( 'name' => 'SAUDI ARABIA', 'code' => '966' ),
		// 'SB' => array( 'name' => 'SOLOMON ISLANDS', 'code' => '677' ),
		// 'SC' => array( 'name' => 'SEYCHELLES', 'code' => '248' ),
		// 'SD' => array( 'name' => 'SUDAN', 'code' => '249' ),
		'SE' => array( 'name' => 'SWEDEN', 'code' => '46' ),
		'SG' => array( 'name' => 'SINGAPORE', 'code' => '65' ),
		// 'SH' => array( 'name' => 'SAINT HELENA', 'code' => '290' ),
		'SI' => array( 'name' => 'SLOVENIA', 'code' => '386' ),
		'SK' => array( 'name' => 'SLOVAKIA', 'code' => '421' ),
		// 'SL' => array( 'name' => 'SIERRA LEONE', 'code' => '232' ),
		// 'SM' => array( 'name' => 'SAN MARINO', 'code' => '378' ),
		// 'SN' => array( 'name' => 'SENEGAL', 'code' => '221' ),
		// 'SO' => array( 'name' => 'SOMALIA', 'code' => '252' ),
		// 'SR' => array( 'name' => 'SURINAME', 'code' => '597' ),
		// 'ST' => array( 'name' => 'SAO TOME AND PRINCIPE', 'code' => '239' ),
		// 'SV' => array( 'name' => 'EL SALVADOR', 'code' => '503' ),
		// 'SY' => array( 'name' => 'SYRIAN ARAB REPUBLIC', 'code' => '963' ),
		// 'SZ' => array( 'name' => 'SWAZILAND', 'code' => '268' ),
		// 'TC' => array( 'name' => 'TURKS AND CAICOS ISLANDS', 'code' => '1649' ),
		// 'TD' => array( 'name' => 'CHAD', 'code' => '235' ),
		// 'TG' => array( 'name' => 'TOGO', 'code' => '228' ),
		'TH' => array( 'name' => 'THAILAND', 'code' => '66' ),
		// 'TJ' => array( 'name' => 'TAJIKISTAN', 'code' => '992' ),
		// 'TK' => array( 'name' => 'TOKELAU', 'code' => '690' ),
		// 'TL' => array( 'name' => 'TIMOR-LESTE', 'code' => '670' ),
		// 'TM' => array( 'name' => 'TURKMENISTAN', 'code' => '993' ),
		// 'TN' => array( 'name' => 'TUNISIA', 'code' => '216' ),
		// 'TO' => array( 'name' => 'TONGA', 'code' => '676' ),
		// 'TR' => array( 'name' => 'TURKEY', 'code' => '90' ),
		// 'TT' => array( 'name' => 'TRINIDAD AND TOBAGO', 'code' => '1868' ),
		// 'TV' => array( 'name' => 'TUVALU', 'code' => '688' ),
		'TW' => array( 'name' => 'TAIWAN', 'code' => '886' ),
		// 'TZ' => array( 'name' => 'TANZANIA, UNITED REPUBLIC OF', 'code' => '255' ),
		'UA' => array( 'name' => 'UKRAINE', 'code' => '380' ),
		// 'UG' => array( 'name' => 'UGANDA', 'code' => '256' ),
		'US' => array( 'name' => 'UNITED STATES', 'code' => '1' ),
		'UY' => array( 'name' => 'URUGUAY', 'code' => '598' ),
		// 'UZ' => array( 'name' => 'UZBEKISTAN', 'code' => '998' ),
		// 'VA' => array( 'name' => 'HOLY SEE (VATICAN CITY STATE)', 'code' => '39' ),
		// 'VC' => array( 'name' => 'SAINT VINCENT AND THE GRENADINES', 'code' => '1784' ),
		// 'VE' => array( 'name' => 'VENEZUELA', 'code' => '58' ),
		// 'VG' => array( 'name' => 'VIRGIN ISLANDS, BRITISH', 'code' => '1284' ),
		// 'VI' => array( 'name' => 'VIRGIN ISLANDS, U.S.', 'code' => '1340' ),
		'VN' => array( 'name' => 'VIETNAM', 'code' => '84' ),
		// 'VU' => array( 'name' => 'VANUATU', 'code' => '678' ),
		// 'WF' => array( 'name' => 'WALLIS AND FUTUNA', 'code' => '681' ),
		// 'WS' => array( 'name' => 'SAMOA', 'code' => '685' ),
		// 'XK' => array( 'name' => 'KOSOVO', 'code' => '381' ),
		// 'YE' => array( 'name' => 'YEMEN', 'code' => '967' ),
		// 'YT' => array( 'name' => 'MAYOTTE', 'code' => '262' ),
		'ZA' => array( 'name' => 'SOUTH AFRICA', 'code' => '27' ),
		// 'ZM' => array( 'name' => 'ZAMBIA', 'code' => '260' ),
		// 'ZW' => array( 'name' => 'ZIMBABWE', 'code' => '263' )
	);

	/**
	 * Default values for settings
	 * @since 3.0.0
	 */
	public $defaults = array();

	/**
	 * Stored values for settings
	 * @since 3.0.0
	 */
	public $settings = array();

	public function __construct() {

		add_action( 'init', array( $this, 'set_defaults' ) );

		add_action( 'init', array( $this, 'set_field_options' ), 11 );

		add_action( 'init', array( $this, 'load_settings_panel' ), 12 );
	}

	/**
	 * Load the plugin's default settings
	 * @since 3.0.0
	 */
	public function set_defaults() {

		$order_information_defaults = array(
			'order_number',
			'order_name',
			'order_status',
			'order_updated',
			'order_notes',
		);

		$this->defaults = array(

			'order-information'					=> $order_information_defaults,

			'date-format'						=> _x( 'd-m-Y H:i:s', 'Default date format for display. Available options here https://www.php.net/manual/en/datetime.format.php', 'order-tracking' ),
			'form-instructions'					=> __( 'Enter the order number you would like to track in the form below.', 'order-tracking' ),

			'access-role'						=> 'manage_options',
			'tracking-graphic'					=> 'default',

			'google-maps-api-key'				=> 'AIzaSyBFLmQU4VaX-T67EnKFtos7S7m_laWn6L4',

			'admin-email'						=> get_option( 'admin_email' ),
			'ultimate-purchase-email'			=> get_option( 'admin_email' ),
			'email-messages'					=> array(),

			'label-retrieving-results'			=> __( 'Retrieving Results...', 'order-tracking' ),
			'label-customer-order-thank-you'	=> __( 'Thank you. Your order number is:', 'order-tracking' ),
		);

		$this->defaults = apply_filters( 'ewd_otp_defaults', $this->defaults, $this );
	}

	/**
	 * Put all of the available possible select options into key => value arrays
	 * @since 3.0.0
	 */
	public function set_field_options() {
		global $ewd_otp_controller;

		$this->currency_options = array(
			'AUD' => __( 'Australian Dollar', 'order-tracking'),
			'BRL' => __( 'Brazilian Real', 'order-tracking'),
			'CAD' => __( 'Canadian Dollar', 'order-tracking'),
			'CZK' => __( 'Czech Koruna', 'order-tracking'),
			'DKK' => __( 'Danish Krone', 'order-tracking'),
			'EUR' => __( 'Euro', 'order-tracking'),
			'HKD' => __( 'Hong Kong Dollar', 'order-tracking'),
			'HUF' => __( 'Hungarian Forint', 'order-tracking'),
			'ILS' => __( 'Israeli New Sheqel', 'order-tracking'),
			'JPY' => __( 'Japanese Yen', 'order-tracking'),
			'MYR' => __( 'Malaysian Ringgit', 'order-tracking'),
			'MXN' => __( 'Mexican Peso', 'order-tracking'),
			'NOK' => __( 'Norwegian Krone', 'order-tracking'),
			'NZD' => __( 'New Zealand Dollar', 'order-tracking'),
			'PHP' => __( 'Philippine Peso', 'order-tracking'),
			'PLN' => __( 'Polish Zloty', 'order-tracking'),
			'GBP' => __( 'Pound Sterling', 'order-tracking'),
			'RUB' => __( 'Russian Ruble', 'order-tracking'),
			'SGD' => __( 'Singapore Dollar', 'order-tracking'),
			'SEK' => __( 'Swedish Krona', 'order-tracking'),
			'CHF' => __( 'Swiss Franc', 'order-tracking'),
			'TWD' => __( 'Taiwan New Dollar', 'order-tracking'),
			'THB' => __( 'Thai Baht', 'order-tracking'),
			'TRY' => __( 'Turkish Lira', 'order-tracking'),
			'USD' => __( 'U.S. Dollar', 'order-tracking'),
		);

		$this->currency_options = apply_filters( 'ewd_otp_currency_options', $this->currency_options, $this );

		$this->order_information_options = array(
			'order_number'			=> __( 'Order Number', 'order-tracking' ),
			'order_name'			=> __( 'Name', 'order-tracking' ),
			'order_status'			=> __( 'Status', 'order-tracking' ),
			'order_location'		=> __( 'Location', 'order-tracking' ),
			'order_updated'			=> __( 'Updated Date', 'order-tracking' ),
			'order_notes'			=> __( 'Notes', 'order-tracking' ),
			'customer_notes'		=> __( 'Customer Notes', 'order-tracking' ),
			'order_graphic'			=> __( 'Status Graphic', 'order-tracking' ),
			'order_map'				=> __( 'Tracking Map', 'order-tracking' ),
			'customer_name'			=> __( 'Customer Name', 'order-tracking' ),
			'customer_email'		=> __( 'Customer Email', 'order-tracking' ),
			'sales_rep_first_name'	=> __( 'Sales Rep First Name', 'order-tracking' ),
			'sales_rep_last_name'	=> __( 'Sales Rep Last Name', 'order-tracking' ),
			'sales_rep_email'		=> __( 'Sales Rep Email', 'order-tracking' ),
		);

		$this->order_information_options = apply_filters( 'ewd_otp_order_information_options', $this->order_information_options, $this );

		$statuses = ewd_otp_decode_infinite_table_setting( $this->get_setting( 'statuses' ) );

		foreach ( $statuses as $status ) {

			$this->status_options[ $status->status ] = $status->status;
		}

		$this->status_options = apply_filters( 'ewd_otp_status_options', $this->status_options, $this );

		$emails = ewd_otp_decode_infinite_table_setting( $this->get_setting( 'email-messages' ) );

		foreach ( $emails as $email ) { 

			$this->notification_options['Emails'][ $email->id ] = $email->name;
		}

		if ( post_type_exists( 'uwpm_mail_template' ) ) {

			$this->notification_options[-1] = '';
			
			$args = array(
				'post_type'		=> 'uwpm_mail_template',
				'numberposts'	=> -1
			);

			$uwpm_emails = get_posts( $args );

			foreach ( $uwpm_emails as $uwpm_email ) { 

				$email_id = $uwpm_email->ID * -1;

				$this->notification_options['Ultimate WP Mail'][ $email_id ] = $uwpm_email->post_title;
			}
		}

		$this->notification_options = apply_filters( 'ewd_otp_notification_options', $this->notification_options, $this );

		$args = array(
			'sales_reps_per_page'	=> -1
		);

		$sales_reps = $ewd_otp_controller->sales_rep_manager->get_matching_sales_reps( $args );

		foreach ( $sales_reps as $sales_rep ) {
			
			$this->sales_rep_options[ $sales_rep->id ] = $sales_rep->first_name . ' ' . $sales_rep->last_name; 
		}

		$this->sales_rep_options = apply_filters( 'ewd_otp_sales_rep_options', $this->sales_rep_options, $this );
	}

	/**
	 * Get a setting's value or fallback to a default if one exists
	 * @since 3.0.0
	 */
	public function get_setting( $setting ) { 

		if ( empty( $this->settings ) ) {
			$this->settings = get_option( 'ewd-otp-settings' );
		}
		
		if ( ! empty( $this->settings[ $setting ] ) ) {
			return apply_filters( 'ewd-otp-settings-' . $setting, $this->settings[ $setting ] );
		}

		if ( ! empty( $this->defaults[ $setting ] ) ) { 
			return apply_filters( 'ewd-otp-settings-' . $setting, $this->defaults[ $setting ] );
		}

		return apply_filters( 'ewd-otp-settings-' . $setting, null );
	}

	/**
	 * Set a setting to a particular value
	 * @since 3.0.0
	 */
	public function set_setting( $setting, $value ) {

		$this->settings[ $setting ] = $value;
	}

	/**
	 * Save all settings, to be used with set_setting
	 * @since 3.0.0
	 */
	public function save_settings() {
		
		update_option( 'ewd-otp-settings', $this->settings );
	}

	/**
	 * Load the admin settings page
	 * @since 3.0.0
	 * @sa https://github.com/NateWr/simple-admin-pages
	 */
	public function load_settings_panel() {

		global $ewd_otp_controller;

		require_once( EWD_OTP_PLUGIN_DIR . '/lib/simple-admin-pages/simple-admin-pages.php' );
		$sap = sap_initialize_library(
			$args = array(
				'version'       => '2.6.18',
				'lib_url'       => EWD_OTP_PLUGIN_URL . '/lib/simple-admin-pages/',
				'theme'			=> 'purple',
			)
		);
		
		$sap->add_page(
			'submenu',
			array(
				'id'            => 'ewd-otp-settings',
				'title'         => __( 'Settings', 'order-tracking' ),
				'menu_title'    => __( 'Settings', 'order-tracking' ),
				'parent_menu'	=> 'ewd-otp-orders',
				'description'   => '',
				'capability'    => $this->get_setting( 'access-role' ),
				'default_tab'   => 'ewd-otp-basic-tab',
			)
		);

		$sap->add_section(
			'ewd-otp-settings',
			array(
				'id'				=> 'ewd-otp-basic-tab',
				'title'				=> __( 'Basic', 'order-tracking' ),
				'is_tab'			=> true,
				'rank'				=> 1,
				'tutorial_yt_id'	=> 'v8t0Z06Y_XY',
				)
		);

		$sap->add_section(
			'ewd-otp-settings',
			array(
				'id'            => 'ewd-otp-general',
				'title'         => __( 'General', 'order-tracking' ),
				'tab'	        => 'ewd-otp-basic-tab',
			)
		);

		$sap->add_setting(
			'ewd-otp-settings',
			'ewd-otp-general',
			'textarea',
			array(
				'id'			=> 'custom-css',
				'title'			=> __( 'Custom CSS', 'order-tracking' ),
				'description'	=> __( 'You can add custom CSS styles to your appointment booking page in the box above.', 'order-tracking' ),			
			)
		);

		$sap->add_setting(
			'ewd-otp-settings',
			'ewd-otp-general',
			'checkbox',
			array(
				'id'            => 'order-information',
				'title'         => __( 'Order Information Displayed', 'order-tracking' ),
				'description'   => __( 'What information should be displayed for your orders?', 'order-tracking' ), 
				'options'       => $this->order_information_options
			)
		);

		$sap->add_setting(
			'ewd-otp-settings',
			'ewd-otp-general',
			'toggle',
			array(
				'id'			=> 'hide-blank-fields',
				'title'			=> __( 'Hide Blank Fields', 'order-tracking' ),
				'description'	=> __( 'Should fields which don\'t have a value (ex. customer name, custom fields) be hidden if they\'re empty?', 'order-tracking' )
			)
		);

		$sap->add_setting(
			'ewd-otp-settings',
			'ewd-otp-general',
			'textarea',
			array(
				'id'			=> 'form-instructions',
				'title'			=> __( 'Form Instructions', 'order-tracking' ),
				'description'	=> __( 'The instructions that will display above the order form.', 'order-tracking' ),			
			)
		);

		$sap->add_setting(
			'ewd-otp-settings',
			'ewd-otp-general',
			'text',
			array(
				'id'            => 'date-format',
				'title'         => __( 'Date/Time Format', 'order-tracking' ),
				'description'	=> __( 'The format to use when displaying dates. Possible options can be: <a href="https://www.php.net/manual/en/datetime.format.php">found here</a>', 'order-tracking' )
			)
		);

		$sap->add_setting(
			'ewd-otp-settings',
			'ewd-otp-general',
			'radio',
			array(
				'id'			=> 'email-frequency',
				'title'			=> __( 'Order Notification Frequency', 'order-tracking' ),
				'description'	=> __( 'How often should notifications be sent to customers about the status of their orders?', 'order-tracking' ),
				'options'		=> array(
					'status_change'	=> __( 'On Status Change', 'order-tracking' ),
					'change'		=> __( 'On Save', 'order-tracking' ),
					'creation'		=> __( 'On Creation', 'order-tracking' ),
					'never'			=> __( 'Never', 'order-tracking' ),
				)
			)
		);

		$sap->add_setting(
			'ewd-otp-settings',
			'ewd-otp-general',
			'toggle',
			array(
				'id'			=> 'disable-ajax-loading',
				'title'			=> __( 'Disable AJAX Reloads', 'order-tracking' ),
				'description'	=> __( 'Should the use of AJAX to display search results without reloading the page be disabled?', 'order-tracking' )
			)
		);

		$sap->add_setting(
			'ewd-otp-settings',
			'ewd-otp-general',
			'toggle',
			array(
				'id'			=> 'new-window',
				'title'			=> __( 'New Window', 'order-tracking' ),
				'description'	=> __( 'Should search results open in a new window? (Doesn\'t work with AJAX reloads)', 'order-tracking' )
			)
		);

		$sap->add_setting(
			'ewd-otp-settings',
			'ewd-otp-general',
			'toggle',
			array(
				'id'			=> 'display-print-button',
				'title'			=> __( 'Display "Print" Button', 'order-tracking' ),
				'description'	=> __( 'Should a "Print" button be added to tracking form results, so that visitors can print their order information more easily?', 'order-tracking' )
			)
		);

		$sap->add_setting(
			'ewd-otp-settings',
			'ewd-otp-general',
			'toggle',
			array(
				'id'			=> 'email-verification',
				'title'			=> __( 'Email Verification', 'order-tracking' ),
				'description'	=> __( 'Do visitors need to also enter the email address associated with an order to be able to view order information?', 'order-tracking' )
			)
		);

		$sap->add_setting(
			'ewd-otp-settings',
			'ewd-otp-general',
			'text',
			array(
				'id'          => 'google-maps-api-key',
				'title'       => __( 'Google Maps API Key', 'order-tracking' ),
				'description' => sprintf(
					__( 'If you\'re displaying a map of your order location (using the "Tracking Map" checkbox of the "Order Information" setting above), Google requires an API key to use their maps. %sGet an API key%s.', 'order-tracking' ),
					'<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">',
					'</a>'
				),
				'conditional_on'		=> 'order-information',
				'conditional_on_value'	=> 'order_map'
			)
		);

		$sap->add_setting(
			'ewd-otp-settings',
			'ewd-otp-general',
			'text',
			array(
				'id'            => 'tracking-page-url',
				'title'         => __( 'Status Tracking URL', 'order-tracking' ),
				'description'	=> __( 'The URL of your tracking page, required if you want to include a tracking link in your message body, on the WooCommerce order page, etc.', 'order-tracking' )
			)
		);

		$sap->add_setting(
			'ewd-otp-settings',
			'ewd-otp-general',
			'toggle',
			array(
				'id'          => 'use-wp-timezone',
				'title'       => __( 'Use WP Timezone', 'order-tracking' ),
				'description' => __( 'By default, the timestamp on status updates uses your server\'s timezone. Enabling this will make it display (in the admin and on the front-end tracking page) using the timezone you have set in your WordPress General Settings instead. ', 'order-tracking' )
			)
		);

		$sap->add_section(
			'ewd-otp-settings',
			array(
				'id'					=> 'ewd-otp-statuses-tab',
				'title'					=> __( 'Statuses', 'order-tracking' ),
				'is_tab'				=> true,
				'rank'					=> 3,
				'tutorial_yt_id'		=> 'ih7qJEuOgPY',
				)
		);

		$sap->add_section(
			'ewd-otp-settings',
			array(
				'id'            => 'ewd-otp-statuses',
				'title'         => __( 'Statuses', 'order-tracking' ),
				'tab'	        => 'ewd-otp-statuses-tab',
			)
		);

		$statuses_description = __( 'Statuses let your customers know the current status of their order.', 'order-tracking' ) . '<br />';
		
		$sap->add_setting(
			'ewd-otp-settings',
			'ewd-otp-statuses',
			'infinite_table',
			array(
				'id'			=> 'statuses',
				'title'			=> __( 'Statuses', 'order-tracking' ),
				'add_label'		=> __( '+ ADD', 'order-tracking' ),
				'del_label'		=> __( 'Delete', 'order-tracking' ),
				'description'	=> $statuses_description,
				'fields'		=> array(
					'status' => array(
						'type' 		=> 'text',
						'label' 	=> 'Status',
						'required' 	=> true
					),
					'percentage' => array(
						'type' 		=> 'text',
						'label' 	=> '&#37; Complete',
						'required' 	=> false
					),
					'email' => array(
						'type' 			=> 'select',
						'label' 		=> __( 'Notification', 'order-tracking' ),
						'options' 		=> $this->notification_options
					),
					'internal' => array(
						'type' 		=> 'select',
						'label' 	=> __( 'Internal Status', 'order-tracking' ),
						'options' 	=> array(
							'no'		=> __( 'No', 'order-tracking' ),
							'yes'		=> __( 'Yes', 'order-tracking' ),
						)
					)
				)
			)
		);

		$sap->add_section(
			'ewd-otp-settings',
			array(
				'id'				=> 'ewd-otp-notifications-tab',
				'title'				=> __( 'Notifications', 'order-tracking' ),
				'is_tab'			=> true,
				'rank'				=> 5,
				'tutorial_yt_id'	=> 'IDi__KeytMQ',
				)
		);

		$sap->add_section(
			'ewd-otp-settings',
			array(
				'id'            => 'ewd-otp-notifications',
				'title'         => __( 'Emails', 'order-tracking' ),
				'tab'	        => 'ewd-otp-notifications-tab',
			)
		);

		$emails_description = __( 'What should be in the messages sent to users? You can put [order-name], [order-number], [order-status], [order-notes], [customer-notes] and [order-time] into the message, to include current order name, order number, order status, public order notes or the time the order was updated.', 'order-tracking' ) . '<br />';
		$emails_description .= __( 'You can also use [tracking-link], [customer-name], [customer-number], [customer-id], [sales-rep], [sales-rep-number] or the slug of a custom field enclosed in square brackets to include those fields in the email.', 'order-tracking' );
		
		$sap->add_setting(
			'ewd-otp-settings',
			'ewd-otp-notifications',
			'infinite_table',
			array(
				'id'			=> 'email-messages',
				'title'			=> __( 'Email Messages', 'order-tracking' ),
				'add_label'		=> __( '+ ADD', 'order-tracking' ),
				'del_label'		=> __( 'Delete', 'order-tracking' ),
				'description'	=> $emails_description,
				'fields'		=> array(
					'id' => array(
						'type' 		=> 'hidden',
						'label' 	=> 'ID',
						'required' 	=> true,
						'classes' 	=> array( 'sap-hidden' ),
					),
					'name' => array(
						'type' 		=> 'text',
						'label' 	=> 'Name',
						'required' 	=> true
					),
					'subject' => array(
						'type' 		=> 'text',
						'label' 	=> 'Subject',
						'required' 	=> true
					),
					'message' => array(
						'type' 		=> 'editor',
						'label' 	=> 'Message',
						'required' 	=> true
					)
				)
			)
		);

		$sap->add_setting(
			'ewd-otp-settings',
			'ewd-otp-notifications',
			'text',
			array(
				'id'            => 'admin-email',
				'title'         => __( 'Admin Email', 'order-tracking' ),
				'description'	=> __( 'What email should customer note and customer order notifications be sent to, if they\'ve been set in the "Premium" area of the "Settings" page? Leave blank to use the WordPress admin email address.', 'order-tracking' ),
			)
		);

		/**
	     * Premium options preview only
	     */
	    // "Premium" Tab
	    $sap->add_section(
	      'ewd-otp-settings',
	      array(
	        'id'					=> 'ewd-otp-premium-tab',
	        'title'					=> __( 'Premium', 'order-tracking' ),
	        'is_tab'				=> true,
			'rank'					=> 2,
			'tutorial_yt_id'		=> 'DDQO1Wkahf0',
	        'show_submit_button'	=> $this->show_submit_button( 'premium' )
	      )
	    );
	    $sap->add_section(
	      'ewd-otp-settings',
	      array(
	        'id'       => 'ewd-otp-premium-tab-body',
	        'tab'      => 'ewd-otp-premium-tab',
	        'callback' => $this->premium_info( 'premium' )
	      )
	    );
	
	    // "Locations" Tab
	    $sap->add_section(
	      'ewd-otp-settings',
	      array(
	        'id'					=> 'ewd-otp-locations-tab',
	        'title'					=> __( 'Locations', 'order-tracking' ),
	        'is_tab'				=> true,
			'rank'					=> 4,
			'tutorial_yt_id'		=> 'hptAmlqQ4G0',
	        'show_submit_button'	=> $this->show_submit_button( 'locations' )
	      )
	    );
	    $sap->add_section(
	      'ewd-otp-settings',
	      array(
	        'id'       => 'ewd-otp-locations-tab-body',
	        'tab'      => 'ewd-otp-locations-tab',
	        'callback' => $this->premium_info( 'locations' )
	      )
	    );

	    // "Payments" Tab
	    $sap->add_section(
	      'ewd-otp-settings',
	      array(
	        'id'					=> 'ewd-otp-payments-tab',
	        'title'					=> __( 'Payments', 'order-tracking' ),
	        'is_tab'				=> true,
			'rank'					=> 7,
			'tutorial_yt_id'		=> 'oDt9BGvVdtQ',
	        'show_submit_button'	=> $this->show_submit_button( 'payments' )
	      )
	    );
	    $sap->add_section(
	      'ewd-otp-settings',
	      array(
	        'id'       => 'ewd-otp-payments-tab-body',
	        'tab'      => 'ewd-otp-payments-tab',
	        'callback' => $this->premium_info( 'payments' )
	      )
	    );
	
	    // "WooCommerce" Tab
	    $sap->add_section(
	      'ewd-otp-settings',
	      array(
	        'id'					=> 'ewd-otp-woocommerce-tab',
	        'title'					=> __( 'WooCommerce', 'order-tracking' ),
	        'is_tab'				=> true,
			'rank'					=> 6,
			'tutorial_yt_id'		=> 'zWTGldvnnc8',
	        'show_submit_button'	=> $this->show_submit_button( 'woocommerce' )
	      )
	    );
	    $sap->add_section(
	      'ewd-otp-settings',
	      array(
	        'id'       => 'ewd-otp-woocommerce-tab-body',
	        'tab'      => 'ewd-otp-woocommerce-tab',
	        'callback' => $this->premium_info( 'woocommerce' )
	      )
	    );	    
	
	    // "Zendesk" Tab
	    $sap->add_section(
	      'ewd-otp-settings',
	      array(
	        'id'					=> 'ewd-otp-zendesk-tab',
	        'title'					=> __( 'Zendesk', 'order-tracking' ),
	        'is_tab'				=> true,
			'rank'					=> 10,
			'tutorial_yt_id'		=> 'r00ewZ8l0z8',
	        'show_submit_button'	=> $this->show_submit_button( 'zendesk' )
	      )
	    );
	    $sap->add_section(
	      'ewd-otp-settings',
	      array(
	        'id'       => 'ewd-otp-zendesk-tab-body',
	        'tab'      => 'ewd-otp-zendesk-tab',
	        'callback' => $this->premium_info( 'zendesk' )
	      )
	    );
	
	    // "Labelling" Tab
	    $sap->add_section(
	      'ewd-otp-settings',
	      array(
	        'id'					=> 'ewd-otp-labelling-tab',
	        'title'					=> __( 'Labelling', 'order-tracking' ),
	        'is_tab'				=> true,
			'rank'					=> 9,
			'tutorial_yt_id'		=> 'oGimPjCPTdU',
	        'show_submit_button'	=> $this->show_submit_button( 'labelling' )
	      )
	    );
	    $sap->add_section(
	      'ewd-otp-settings',
	      array(
	        'id'       => 'ewd-otp-labelling-tab-body',
	        'tab'      => 'ewd-otp-labelling-tab',
	        'callback' => $this->premium_info( 'labelling' )
	      )
	    );
	
	    // "Styling" Tab
	    $sap->add_section(
	      'ewd-otp-settings',
	      array(
	        'id'					=> 'ewd-otp-styling-tab',
	        'title'					=> __( 'Styling', 'order-tracking' ),
	        'is_tab'				=> true,
			'rank'					=> 8,
			'tutorial_yt_id'		=> 'c75VcMG11a8',
	        'show_submit_button'	=> $this->show_submit_button( 'styling' )
	      )
	    );
	    $sap->add_section(
	      'ewd-otp-settings',
	      array(
	        'id'       => 'ewd-otp-styling-tab-body',
	        'tab'      => 'ewd-otp-styling-tab',
	        'callback' => $this->premium_info( 'styling' )
	      )
	    );

		$sap = apply_filters( 'ewd_otp_settings_page', $sap, $this );

		$sap->add_admin_menus();

	}

	public function show_submit_button( $permission_type = '' ) {
		global $ewd_otp_controller;

		if ( $ewd_otp_controller->permissions->check_permission( $permission_type ) ) {
			return true;
		}

		return false;
	}

	public function premium_info( $section_and_perm_type ) {
		global $ewd_otp_controller;

		$is_premium_user = $ewd_otp_controller->permissions->check_permission( $section_and_perm_type );
		$is_helper_installed = defined( 'EWDPH_PLUGIN_FNAME' ) && is_plugin_active( EWDPH_PLUGIN_FNAME );

		if ( $is_premium_user || $is_helper_installed ) {
			return false;
		}

		$content = '';

		$premium_features = '
			<p><strong>' . __( 'The premium version also gives you access to the following features:', 'order-tracking' ) . '</strong></p>
			<ul class="ewd-otp-dashboard-new-footer-one-benefits">
				<li>' . __( 'Create & Assign Orders to Sales Reps', 'order-tracking' ) . '</li>
				<li>' . __( 'Create & Tie Orders to Customers', 'order-tracking' ) . '</li>
				<li>' . __( 'Custom Fields', 'order-tracking' ) . '</li>
				<li>' . __( 'WooCommerce Order Integration', 'order-tracking' ) . '</li>
				<li>' . __( 'Advanced Display & Styling Options', 'order-tracking' ) . '</li>
				<li>' . __( 'Front-End Customer Order Form', 'order-tracking' ) . '</li>
				<li>' . __( 'Import/Export Orders', 'order-tracking' ) . '</li>
				<li>' . __( 'Set Up Status Locations', 'order-tracking' ) . '</li>
				<li>' . __( 'Email Support', 'order-tracking' ) . '</li>
			</ul>
			<div class="ewd-otp-dashboard-new-footer-one-buttons">
				<a class="ewd-otp-dashboard-new-upgrade-button" href="https://www.etoilewebdesign.com/license-payment/?Selected=OTP&Quantity=1&utm_source=otp_settings&utm_content=' . $section_and_perm_type . '" target="_blank">' . __( 'UPGRADE NOW', 'order-tracking' ) . '</a>
			</div>
		';

		switch ( $section_and_perm_type ) {

			case 'premium':

				$content = '
					<div class="ewd-otp-settings-preview">
						<h2>' . __( 'Premium', 'order-tracking' ) . '<span>' . __( 'Premium', 'order-tracking' ) . '</span></h2>
						<p>' . __( 'The premium options let you change the tracking graphic, configure notification emails, customize the order form and more.', 'order-tracking' ) . '</p>
						<div class="ewd-otp-settings-preview-images">
							<img src="' . EWD_OTP_PLUGIN_URL . '/assets/img/premium-screenshots/premium1.png" alt="OTP premium screenshot one">
							<img src="' . EWD_OTP_PLUGIN_URL . '/assets/img/premium-screenshots/premium2.png" alt="OTP premium screenshot two">
						</div>
						' . $premium_features . '
					</div>
				';

				break;

			case 'locations':

				$content = '
					<div class="ewd-otp-settings-preview">
						<h2>' . __( 'Locations', 'order-tracking' ) . '<span>' . __( 'Premium', 'order-tracking' ) . '</span></h2>
						<p>' . __( 'You can create locations, which can be assigned to orders, so your customers know exactly where their orders are. You can also specify latitude and longitude coordinates for each location, allowing it to display on a map on the tracking page.', 'order-tracking' ) . '</p>
						<div class="ewd-otp-settings-preview-images">
							<img src="' . EWD_OTP_PLUGIN_URL . '/assets/img/premium-screenshots/locations.png" alt="OTP locations screenshot">
						</div>
						' . $premium_features . '
					</div>
				';

				break;

			case 'payments':

				$content = '
					<div class="ewd-otp-settings-preview">
						<h2>' . __( 'Payments', 'order-tracking' ) . '<span>' . __( 'Premium', 'order-tracking' ) . '</span></h2>
						<p>' . __( 'The payment options let you enable and configure the ability to accept payment for orders via PayPal.', 'order-tracking' ) . '</p>
						<div class="ewd-otp-settings-preview-images">
							<img src="' . EWD_OTP_PLUGIN_URL . '/assets/img/premium-screenshots/payments.png" alt="OTP payments screenshot">
						</div>
						' . $premium_features . '
					</div>
				';

				break;

			case 'woocommerce':

				$content = '
					<div class="ewd-otp-settings-preview">
						<h2>' . __( 'WooCommerce', 'order-tracking' ) . '<span>' . __( 'Premium', 'order-tracking' ) . '</span></h2>
						<p>' . __( 'The WooCommerce options let you enable and configure the ability to have the plugin automatically create a corresponding order every time a new order is placed on your site via WooCommerce.', 'order-tracking' ) . '</p>
						<div class="ewd-otp-settings-preview-images">
							<img src="' . EWD_OTP_PLUGIN_URL . '/assets/img/premium-screenshots/woocommerce1.png" alt="OTP woocommerce screenshot one">
							<img src="' . EWD_OTP_PLUGIN_URL . '/assets/img/premium-screenshots/woocommerce2.png" alt="OTP woocommerce screenshot two">
						</div>
						' . $premium_features . '
					</div>
				';

				break;

			case 'zendesk':

				$content = '
					<div class="ewd-otp-settings-preview">
						<h2>' . __( 'Sendesk', 'order-tracking' ) . '<span>' . __( 'Premium', 'order-tracking' ) . '</span></h2>
						<p>' . __( 'This lets you enable Zendesk integration, so, every time you get a new ticket in Zendesk, it creates an order in the plugin.', 'order-tracking' ) . '</p>
						<div class="ewd-otp-settings-preview-images">
							<img src="' . EWD_OTP_PLUGIN_URL . '/assets/img/premium-screenshots/zendesk.png" alt="OTP zendesk screenshot">
						</div>
						' . $premium_features . '
					</div>
				';

				break;

			case 'labelling':

				$content = '
					<div class="ewd-otp-settings-preview">
						<h2>' . __( 'Labelling', 'order-tracking' ) . '<span>' . __( 'Premium', 'order-tracking' ) . '</span></h2>
						<p>' . __( 'The labelling options let you change the wording of the different labels that appear on the front end of the plugin. You can use this to translate them, customize the wording for your purpose, etc.', 'order-tracking' ) . '</p>
						<div class="ewd-otp-settings-preview-images">
							<img src="' . EWD_OTP_PLUGIN_URL . '/assets/img/premium-screenshots/labelling1.png" alt="OTP labelling screenshot one">
							<img src="' . EWD_OTP_PLUGIN_URL . '/assets/img/premium-screenshots/labelling2.png" alt="OTP labelling screenshot two">
						</div>
						' . $premium_features . '
					</div>
				';

				break;

			case 'styling':

				$content = '
					<div class="ewd-otp-settings-preview">
						<h2>' . __( 'Styling', 'order-tracking' ) . '<span>' . __( 'Premium', 'order-tracking' ) . '</span></h2>
						<p>' . __( 'The styling options let you modify the color, font size, font family, border, margin and padding of the various elements found in your tracking forms and orders.', 'order-tracking' ) . '</p>
						<div class="ewd-otp-settings-preview-images">
							<img src="' . EWD_OTP_PLUGIN_URL . '/assets/img/premium-screenshots/styling.png" alt="OTP styling screenshot">
						</div>
						' . $premium_features . '
					</div>
				';

				break;
		}

		return function() use ( $content ) {

			echo wp_kses_post( $content );
		};
	}

	/**
	 * Create a set of default statuses and emails if none exist
	 * @since 3.0.0
	 */
	public function create_default_statuses_and_emails() {

		$statuses = ewd_otp_decode_infinite_table_setting( $this->get_setting( 'statuses' ) );

		if ( ! empty( $statuses ) ) { return; }

		$emails = array(
			array(
				'id'			=> 1,
				'name'			=> __( 'Default', 'order-tracking' ),
				'subject'		=> __( 'Order Status Update', 'order-tracking' ),
				'message'		=> __( 'Hello [order-name], You have an update for your order [order-number]!', 'order-tracking' )
			)
		);

		$this->set_setting( 'email-messages', json_encode( $emails ) );

		$statuses = array(
			array(
				'status'		=> __( 'Pending Payment', 'order-tracking' ),
				'percentage'	=> '25',
				'email'			=> 1,
				'internal'		=> 'no',
			),
			array(
				'status'		=> __( 'Processing', 'order-tracking' ),
				'percentage'	=> '50',
				'email'			=> 1,
				'internal'		=> 'no',
			),
			array(
				'status'		=> __( 'On Hold', 'order-tracking' ),
				'percentage'	=> '50',
				'email'			=> 1,
				'internal'		=> 'no',
			),
			array(
				'status'		=> __( 'Completed', 'order-tracking' ),
				'percentage'	=> '100',
				'email'			=> 1,
				'internal'		=> 'no',
			),
			array(
				'status'		=> __( 'Cancelled', 'order-tracking' ),
				'percentage'	=> '0',
				'email'			=> 1,
				'internal'		=> 'no',
			),
			array(
				'status'		=> __( 'Refunded', 'order-tracking' ),
				'percentage'	=> '0',
				'email'			=> 1,
				'internal'		=> 'no',
			),
			array(
				'status'		=> __( 'Failed', 'order-tracking' ),
				'percentage'	=> '0',
				'email'			=> 1,
				'internal'		=> 'no',
			),
		);


		$this->set_setting( 'statuses', json_encode( $statuses ) );

		$this->save_settings();
	}

	/**
	 * Load all custom fields 
	 * @since 3.0.0
	 */
	public function get_custom_fields() {
		
		$custom_fields = is_array( get_option( 'ewd-otp-custom-fields' ) ) ? get_option( 'ewd-otp-custom-fields' ) : array();

		return $custom_fields;
	}

	/**
	 * Load the custom fields related to orders
	 * @since 3.0.0
	 */
	public function get_order_custom_fields() {
		
		$custom_fields = is_array( get_option( 'ewd-otp-custom-fields' ) ) ? get_option( 'ewd-otp-custom-fields' ) : array();

		$return_fields = array();

		foreach ( $custom_fields as $custom_field ){

			if ( $custom_field->function == 'orders' ) { $return_fields[] = $custom_field; }
		}

		return $return_fields;
	}

	/**
	 * Load the custom fields related to customers
	 * @since 3.0.0
	 */
	public function get_customer_custom_fields() {
		
		$custom_fields = is_array( get_option( 'ewd-otp-custom-fields' ) ) ? get_option( 'ewd-otp-custom-fields' ) : array();

		$return_fields = array();

		foreach ( $custom_fields as $custom_field ){

			if ( $custom_field->function == 'customers' ) { $return_fields[] = $custom_field; }
		}

		return $return_fields;
	}

	/**
	 * Load the custom fields related to sales reps
	 * @since 3.0.0
	 */
	public function get_sales_rep_custom_fields() {
		
		$custom_fields = is_array( get_option( 'ewd-otp-custom-fields' ) ) ? get_option( 'ewd-otp-custom-fields' ) : array();

		$return_fields = array();

		foreach ( $custom_fields as $custom_field ){

			if ( $custom_field->function == 'sales_reps' ) { $return_fields[] = $custom_field; }
		}

		return $return_fields;
	}
}
} // endif;
