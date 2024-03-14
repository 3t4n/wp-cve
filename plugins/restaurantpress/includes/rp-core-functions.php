<?php
/**
 * RestaurantPress Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @package  RestaurantPress/Functions
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include core functions (available in both admin and frontend).
require RP_ABSPATH . 'includes/rp-conditional-functions.php';
require RP_ABSPATH . 'includes/rp-deprecated-functions.php';
require RP_ABSPATH . 'includes/rp-formatting-functions.php';
require RP_ABSPATH . 'includes/rp-food-functions.php';
require RP_ABSPATH . 'includes/rp-term-functions.php';
require RP_ABSPATH . 'includes/rp-widget-functions.php';

/**
 * Short Description (excerpt).
 */
add_filter( 'restaurantpress_short_description', 'wptexturize' );
add_filter( 'restaurantpress_short_description', 'convert_smilies' );
add_filter( 'restaurantpress_short_description', 'convert_chars' );
add_filter( 'restaurantpress_short_description', 'wpautop' );
add_filter( 'restaurantpress_short_description', 'shortcode_unautop' );
add_filter( 'restaurantpress_short_description', 'prepend_attachment' );
add_filter( 'restaurantpress_short_description', 'do_shortcode', 11 ); // After wpautop().
add_filter( 'restaurantpress_short_description', 'rp_format_food_short_description', 9999999 );
add_filter( 'restaurantpress_short_description', 'rp_do_oembeds' );
add_filter( 'restaurantpress_short_description', array( $GLOBALS['wp_embed'], 'run_shortcode' ), 8 ); // Before wpautop().

/**
 * Define a constant if it is not already defined.
 *
 * @since 1.4.0
 * @param string $name  Constant name.
 * @param string $value Value.
 */
function rp_maybe_define_constant( $name, $value ) {
	if ( ! defined( $name ) ) {
		define( $name, $value );
	}
}

/**
 * Get template part (for templates like the layout-loop).
 *
 * RP_TEMPLATE_DEBUG_MODE will prevent overrides in themes from taking priority.
 *
 * @param mixed  $slug Template slug.
 * @param string $name Template name (default: '').
 */
function rp_get_template_part( $slug, $name = '' ) {
	$template = '';

	// Look in yourtheme/slug-name.php and yourtheme/restaurantpress/slug-name.php.
	if ( $name && ! RP_TEMPLATE_DEBUG_MODE ) {
		$template = locate_template( array( "{$slug}-{$name}.php", RP()->template_path() . "{$slug}-{$name}.php" ) );
	}

	// Get default slug-name.php.
	if ( ! $template && $name && file_exists( RP()->plugin_path() . "/templates/{$slug}-{$name}.php" ) ) {
		$template = RP()->plugin_path() . "/templates/{$slug}-{$name}.php";
	}

	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/restaurantpress/slug.php.
	if ( ! $template && ! RP_TEMPLATE_DEBUG_MODE ) {
		$template = locate_template( array( "{$slug}.php", RP()->template_path() . "{$slug}.php" ) );
	}

	// Allow 3rd party plugins to filter template file from their plugin.
	$template = apply_filters( 'rp_get_template_part', $template, $slug, $name );

	if ( $template ) {
		load_template( $template, false );
	}
}

/**
 * Get other templates (e.g. layout attributes) passing attributes and including the file.
 *
 * @param string $template_name Template name.
 * @param array  $args          Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 */
function rp_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args ); // @codingStandardsIgnoreLine
	}

	$located = rp_locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $located ) ) {
		/* translators: %s template */
		rp_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '1.4.0' );
		return;
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$located = apply_filters( 'rp_get_template', $located, $template_name, $args, $template_path, $default_path );

	do_action( 'restaurantpress_before_template_part', $template_name, $template_path, $located, $args );

	include $located;

	do_action( 'restaurantpress_after_template_part', $template_name, $template_path, $located, $args );
}

/**
 * Like rp_get_template, but returns the HTML instead of outputting.
 *
 * @see   rp_get_template
 * @since 1.4.0
 * @param string $template_name Template name.
 * @param array  $args          Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 *
 * @return string
 */
function rp_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	ob_start();
	rp_get_template( $template_name, $args, $template_path, $default_path );
	return ob_get_clean();
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 * yourtheme/$template_path/$template_name
 * yourtheme/$template_name
 * $default_path/$template_name
 *
 * @param  string $template_name Template name.
 * @param  string $template_path Template path. (default: '').
 * @param  string $default_path  Default path. (default: '').
 * @return string
 */
function rp_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = RP()->template_path();
	}

	if ( ! $default_path ) {
		$default_path = RP()->plugin_path() . '/templates/';
	}

	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		)
	);

	// Get default template/.
	if ( ! $template || RP_TEMPLATE_DEBUG_MODE ) {
		$template = $default_path . $template_name;
	}

	// Return what we found.
	return apply_filters( 'restaurantpress_locate_template', $template, $template_name, $template_path );
}

/**
 * Get Base Currency Code.
 *
 * @return string
 */
function get_restaurantpress_currency() {
	return apply_filters( 'restaurantpress_currency', get_option( 'restaurantpress_currency' ) );
}

/**
 * Get full list of currency codes.
 *
 * @return array
 */
function get_restaurantpress_currencies() {
	static $currencies;

	if ( ! isset( $currencies ) ) {
		$currencies = array_unique(
			apply_filters( 'restaurantpress_currencies',
				array(
					'AED' => __( 'United Arab Emirates dirham', 'restaurantpress' ),
					'AFN' => __( 'Afghan afghani', 'restaurantpress' ),
					'ALL' => __( 'Albanian lek', 'restaurantpress' ),
					'AMD' => __( 'Armenian dram', 'restaurantpress' ),
					'ANG' => __( 'Netherlands Antillean guilder', 'restaurantpress' ),
					'AOA' => __( 'Angolan kwanza', 'restaurantpress' ),
					'ARS' => __( 'Argentine peso', 'restaurantpress' ),
					'AUD' => __( 'Australian dollar', 'restaurantpress' ),
					'AWG' => __( 'Aruban florin', 'restaurantpress' ),
					'AZN' => __( 'Azerbaijani manat', 'restaurantpress' ),
					'BAM' => __( 'Bosnia and Herzegovina convertible mark', 'restaurantpress' ),
					'BBD' => __( 'Barbadian dollar', 'restaurantpress' ),
					'BDT' => __( 'Bangladeshi taka', 'restaurantpress' ),
					'BGN' => __( 'Bulgarian lev', 'restaurantpress' ),
					'BHD' => __( 'Bahraini dinar', 'restaurantpress' ),
					'BIF' => __( 'Burundian franc', 'restaurantpress' ),
					'BMD' => __( 'Bermudian dollar', 'restaurantpress' ),
					'BND' => __( 'Brunei dollar', 'restaurantpress' ),
					'BOB' => __( 'Bolivian boliviano', 'restaurantpress' ),
					'BRL' => __( 'Brazilian real', 'restaurantpress' ),
					'BSD' => __( 'Bahamian dollar', 'restaurantpress' ),
					'BTC' => __( 'Bitcoin', 'restaurantpress' ),
					'BTN' => __( 'Bhutanese ngultrum', 'restaurantpress' ),
					'BWP' => __( 'Botswana pula', 'restaurantpress' ),
					'BYR' => __( 'Belarusian ruble (old)', 'restaurantpress' ),
					'BYN' => __( 'Belarusian ruble', 'restaurantpress' ),
					'BZD' => __( 'Belize dollar', 'restaurantpress' ),
					'CAD' => __( 'Canadian dollar', 'restaurantpress' ),
					'CDF' => __( 'Congolese franc', 'restaurantpress' ),
					'CHF' => __( 'Swiss franc', 'restaurantpress' ),
					'CLP' => __( 'Chilean peso', 'restaurantpress' ),
					'CNY' => __( 'Chinese yuan', 'restaurantpress' ),
					'COP' => __( 'Colombian peso', 'restaurantpress' ),
					'CRC' => __( 'Costa Rican col&oacute;n', 'restaurantpress' ),
					'CUC' => __( 'Cuban convertible peso', 'restaurantpress' ),
					'CUP' => __( 'Cuban peso', 'restaurantpress' ),
					'CVE' => __( 'Cape Verdean escudo', 'restaurantpress' ),
					'CZK' => __( 'Czech koruna', 'restaurantpress' ),
					'DJF' => __( 'Djiboutian franc', 'restaurantpress' ),
					'DKK' => __( 'Danish krone', 'restaurantpress' ),
					'DOP' => __( 'Dominican peso', 'restaurantpress' ),
					'DZD' => __( 'Algerian dinar', 'restaurantpress' ),
					'EGP' => __( 'Egyptian pound', 'restaurantpress' ),
					'ERN' => __( 'Eritrean nakfa', 'restaurantpress' ),
					'ETB' => __( 'Ethiopian birr', 'restaurantpress' ),
					'EUR' => __( 'Euro', 'restaurantpress' ),
					'FJD' => __( 'Fijian dollar', 'restaurantpress' ),
					'FKP' => __( 'Falkland Islands pound', 'restaurantpress' ),
					'GBP' => __( 'Pound sterling', 'restaurantpress' ),
					'GEL' => __( 'Georgian lari', 'restaurantpress' ),
					'GGP' => __( 'Guernsey pound', 'restaurantpress' ),
					'GHS' => __( 'Ghana cedi', 'restaurantpress' ),
					'GIP' => __( 'Gibraltar pound', 'restaurantpress' ),
					'GMD' => __( 'Gambian dalasi', 'restaurantpress' ),
					'GNF' => __( 'Guinean franc', 'restaurantpress' ),
					'GTQ' => __( 'Guatemalan quetzal', 'restaurantpress' ),
					'GYD' => __( 'Guyanese dollar', 'restaurantpress' ),
					'HKD' => __( 'Hong Kong dollar', 'restaurantpress' ),
					'HNL' => __( 'Honduran lempira', 'restaurantpress' ),
					'HRK' => __( 'Croatian kuna', 'restaurantpress' ),
					'HTG' => __( 'Haitian gourde', 'restaurantpress' ),
					'HUF' => __( 'Hungarian forint', 'restaurantpress' ),
					'IDR' => __( 'Indonesian rupiah', 'restaurantpress' ),
					'ILS' => __( 'Israeli new shekel', 'restaurantpress' ),
					'IMP' => __( 'Manx pound', 'restaurantpress' ),
					'INR' => __( 'Indian rupee', 'restaurantpress' ),
					'IQD' => __( 'Iraqi dinar', 'restaurantpress' ),
					'IRR' => __( 'Iranian rial', 'restaurantpress' ),
					'IRT' => __( 'Iranian toman', 'restaurantpress' ),
					'ISK' => __( 'Icelandic kr&oacute;na', 'restaurantpress' ),
					'JEP' => __( 'Jersey pound', 'restaurantpress' ),
					'JMD' => __( 'Jamaican dollar', 'restaurantpress' ),
					'JOD' => __( 'Jordanian dinar', 'restaurantpress' ),
					'JPY' => __( 'Japanese yen', 'restaurantpress' ),
					'KES' => __( 'Kenyan shilling', 'restaurantpress' ),
					'KGS' => __( 'Kyrgyzstani som', 'restaurantpress' ),
					'KHR' => __( 'Cambodian riel', 'restaurantpress' ),
					'KMF' => __( 'Comorian franc', 'restaurantpress' ),
					'KPW' => __( 'North Korean won', 'restaurantpress' ),
					'KRW' => __( 'South Korean won', 'restaurantpress' ),
					'KWD' => __( 'Kuwaiti dinar', 'restaurantpress' ),
					'KYD' => __( 'Cayman Islands dollar', 'restaurantpress' ),
					'KZT' => __( 'Kazakhstani tenge', 'restaurantpress' ),
					'LAK' => __( 'Lao kip', 'restaurantpress' ),
					'LBP' => __( 'Lebanese pound', 'restaurantpress' ),
					'LKR' => __( 'Sri Lankan rupee', 'restaurantpress' ),
					'LRD' => __( 'Liberian dollar', 'restaurantpress' ),
					'LSL' => __( 'Lesotho loti', 'restaurantpress' ),
					'LYD' => __( 'Libyan dinar', 'restaurantpress' ),
					'MAD' => __( 'Moroccan dirham', 'restaurantpress' ),
					'MDL' => __( 'Moldovan leu', 'restaurantpress' ),
					'MGA' => __( 'Malagasy ariary', 'restaurantpress' ),
					'MKD' => __( 'Macedonian denar', 'restaurantpress' ),
					'MMK' => __( 'Burmese kyat', 'restaurantpress' ),
					'MNT' => __( 'Mongolian t&ouml;gr&ouml;g', 'restaurantpress' ),
					'MOP' => __( 'Macanese pataca', 'restaurantpress' ),
					'MRO' => __( 'Mauritanian ouguiya', 'restaurantpress' ),
					'MUR' => __( 'Mauritian rupee', 'restaurantpress' ),
					'MVR' => __( 'Maldivian rufiyaa', 'restaurantpress' ),
					'MWK' => __( 'Malawian kwacha', 'restaurantpress' ),
					'MXN' => __( 'Mexican peso', 'restaurantpress' ),
					'MYR' => __( 'Malaysian ringgit', 'restaurantpress' ),
					'MZN' => __( 'Mozambican metical', 'restaurantpress' ),
					'NAD' => __( 'Namibian dollar', 'restaurantpress' ),
					'NGN' => __( 'Nigerian naira', 'restaurantpress' ),
					'NIO' => __( 'Nicaraguan c&oacute;rdoba', 'restaurantpress' ),
					'NOK' => __( 'Norwegian krone', 'restaurantpress' ),
					'NPR' => __( 'Nepalese rupee', 'restaurantpress' ),
					'NZD' => __( 'New Zealand dollar', 'restaurantpress' ),
					'OMR' => __( 'Omani rial', 'restaurantpress' ),
					'PAB' => __( 'Panamanian balboa', 'restaurantpress' ),
					'PEN' => __( 'Peruvian nuevo sol', 'restaurantpress' ),
					'PGK' => __( 'Papua New Guinean kina', 'restaurantpress' ),
					'PHP' => __( 'Philippine peso', 'restaurantpress' ),
					'PKR' => __( 'Pakistani rupee', 'restaurantpress' ),
					'PLN' => __( 'Polish z&#x142;oty', 'restaurantpress' ),
					'PRB' => __( 'Transnistrian ruble', 'restaurantpress' ),
					'PYG' => __( 'Paraguayan guaran&iacute;', 'restaurantpress' ),
					'QAR' => __( 'Qatari riyal', 'restaurantpress' ),
					'RON' => __( 'Romanian leu', 'restaurantpress' ),
					'RSD' => __( 'Serbian dinar', 'restaurantpress' ),
					'RUB' => __( 'Russian ruble', 'restaurantpress' ),
					'RWF' => __( 'Rwandan franc', 'restaurantpress' ),
					'SAR' => __( 'Saudi riyal', 'restaurantpress' ),
					'SBD' => __( 'Solomon Islands dollar', 'restaurantpress' ),
					'SCR' => __( 'Seychellois rupee', 'restaurantpress' ),
					'SDG' => __( 'Sudanese pound', 'restaurantpress' ),
					'SEK' => __( 'Swedish krona', 'restaurantpress' ),
					'SGD' => __( 'Singapore dollar', 'restaurantpress' ),
					'SHP' => __( 'Saint Helena pound', 'restaurantpress' ),
					'SLL' => __( 'Sierra Leonean leone', 'restaurantpress' ),
					'SOS' => __( 'Somali shilling', 'restaurantpress' ),
					'SRD' => __( 'Surinamese dollar', 'restaurantpress' ),
					'SSP' => __( 'South Sudanese pound', 'restaurantpress' ),
					'STD' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'restaurantpress' ),
					'SYP' => __( 'Syrian pound', 'restaurantpress' ),
					'SZL' => __( 'Swazi lilangeni', 'restaurantpress' ),
					'THB' => __( 'Thai baht', 'restaurantpress' ),
					'TJS' => __( 'Tajikistani somoni', 'restaurantpress' ),
					'TMT' => __( 'Turkmenistan manat', 'restaurantpress' ),
					'TND' => __( 'Tunisian dinar', 'restaurantpress' ),
					'TOP' => __( 'Tongan pa&#x2bb;anga', 'restaurantpress' ),
					'TRY' => __( 'Turkish lira', 'restaurantpress' ),
					'TTD' => __( 'Trinidad and Tobago dollar', 'restaurantpress' ),
					'TWD' => __( 'New Taiwan dollar', 'restaurantpress' ),
					'TZS' => __( 'Tanzanian shilling', 'restaurantpress' ),
					'UAH' => __( 'Ukrainian hryvnia', 'restaurantpress' ),
					'UGX' => __( 'Ugandan shilling', 'restaurantpress' ),
					'USD' => __( 'United States dollar', 'restaurantpress' ),
					'UYU' => __( 'Uruguayan peso', 'restaurantpress' ),
					'UZS' => __( 'Uzbekistani som', 'restaurantpress' ),
					'VEF' => __( 'Venezuelan bol&iacute;var', 'restaurantpress' ),
					'VND' => __( 'Vietnamese &#x111;&#x1ed3;ng', 'restaurantpress' ),
					'VUV' => __( 'Vanuatu vatu', 'restaurantpress' ),
					'WST' => __( 'Samoan t&#x101;l&#x101;', 'restaurantpress' ),
					'XAF' => __( 'Central African CFA franc', 'restaurantpress' ),
					'XCD' => __( 'East Caribbean dollar', 'restaurantpress' ),
					'XOF' => __( 'West African CFA franc', 'restaurantpress' ),
					'XPF' => __( 'CFP franc', 'restaurantpress' ),
					'YER' => __( 'Yemeni rial', 'restaurantpress' ),
					'ZAR' => __( 'South African rand', 'restaurantpress' ),
					'ZMW' => __( 'Zambian kwacha', 'restaurantpress' ),
				)
			)
		);
	}

	return $currencies;
}

/**
 * Get Currency symbol.
 *
 * @param  string $currency Currency. (default: '').
 * @return string
 */
function get_restaurantpress_currency_symbol( $currency = '' ) {
	if ( ! $currency ) {
		$currency = get_restaurantpress_currency();
	}

	$symbols = apply_filters( 'restaurantpress_currency_symbols', array(
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
		'GEL' => '&#x10da;',
		'GGP' => '&pound;',
		'GHS' => '&#x20b5;',
		'GIP' => '&pound;',
		'GMD' => 'D',
		'GNF' => 'Fr',
		'GTQ' => 'Q',
		'GYD' => '&#36;',
		'HKD' => '&#36;',
		'HNL' => 'L',
		'HRK' => 'Kn',
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
		'KZT' => 'KZT',
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
		'MRO' => 'UM',
		'MUR' => '&#x20a8;',
		'MVR' => '.&#x783;',
		'MWK' => 'MK',
		'MXN' => '&#36;',
		'MYR' => '&#82;&#77;',
		'MZN' => 'MT',
		'NAD' => '&#36;',
		'NGN' => '&#8358;',
		'NIO' => 'C&#36;',
		'NOK' => '&#107;&#114;',
		'NPR' => '&#8360;',
		'NZD' => '&#36;',
		'OMR' => '&#x631;.&#x639;.',
		'PAB' => 'B/.',
		'PEN' => 'S/.',
		'PGK' => 'K',
		'PHP' => '&#8369;',
		'PKR' => '&#8360;',
		'PLN' => '&#122;&#322;',
		'PRB' => '&#x440;.',
		'PYG' => '&#8370;',
		'QAR' => '&#x631;.&#x642;',
		'RMB' => '&yen;',
		'RON' => 'lei',
		'RSD' => '&#x434;&#x438;&#x43d;.',
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
		'STD' => 'Db',
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
	) );
	$currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';

	return apply_filters( 'restaurantpress_currency_symbol', $currency_symbol, $currency );
}

/**
 * Return "theme support" values from the current theme, if set.
 *
 * @since  1.7.0
 * @param  string $prop Name of prop (or key::subkey for arrays of props) if you want a specific value. Leave blank to get all props as an array.
 * @param  mixed  $default Optional value to return if the theme does not declare support for a prop.
 * @return mixed  Value of prop(s).
 */
function rp_get_theme_support( $prop = '', $default = null ) {
	$theme_support = get_theme_support( 'restaurantpress' );
	$theme_support = is_array( $theme_support ) ? $theme_support[0] : false;

	if ( ! $theme_support ) {
		return $default;
	}

	if ( $prop ) {
		$prop_stack = explode( '::', $prop );
		$prop_key   = array_shift( $prop_stack );

		if ( isset( $theme_support[ $prop_key ] ) ) {
			$value = $theme_support[ $prop_key ];

			if ( count( $prop_stack ) ) {
				foreach ( $prop_stack as $prop_key ) {
					if ( is_array( $value ) && isset( $value[ $prop_key ] ) ) {
						$value = $value[ $prop_key ];
					} else {
						$value = $default;
						break;
					}
				}
			}
		} else {
			$value = $default;
		}

		return $value;
	}

	return $theme_support;
}

/**
 * Get an image size by name or defined dimensions.
 *
 * The returned variable is filtered by restaurantpress_get_image_size_{image_size} filter to
 * allow 3rd party customisation.
 *
 * Sizes defined by the theme take priority over settings. Settings are hidden when a theme
 * defines sizes.
 *
 * @param array|string $image_size Name of the image size to get, or an array of dimensions.
 * @return array Array of dimensions including width, height, and cropping mode. Cropping mode is 0 for no crop, and 1 for hard crop.
 */
function rp_get_image_size( $image_size ) {
	$size = array(
		'width'  => 600,
		'height' => 600,
		'crop'   => 1,
	);

	if ( is_array( $image_size ) ) {
		$size       = array(
			'width'  => isset( $image_size[0] ) ? absint( $image_size[0] ) : 600,
			'height' => isset( $image_size[1] ) ? absint( $image_size[1] ) : 600,
			'crop'   => isset( $image_size[2] ) ? absint( $image_size[2] ) : 1,
		);
		$image_size = $size['width'] . '_' . $size['height'];
	} elseif ( in_array( $image_size, array( 'single', 'food_single', 'restaurantpress_single' ), true ) ) {
		$size['width']  = absint( rp_get_theme_support( 'single_image_width', get_option( 'restaurantpress_single_image_width', 600 ) ) );
		$size['height'] = '';
		$size['crop']   = 0;
		$image_size     = 'single';
	} elseif ( in_array( $image_size, array( 'thumbnail', 'food_thumbnail', 'food_grid', 'restaurantpress_thumbnail' ), true ) ) {
		$size['width'] = absint( rp_get_theme_support( 'thumbnail_image_width', get_option( 'restaurantpress_thumbnail_image_width', 300 ) ) );
		$cropping      = get_option( 'restaurantpress_thumbnail_cropping', '1:1' );

		if ( 'uncropped' === $cropping ) {
			$size['height'] = '';
			$size['crop']   = 0;
		} elseif ( 'custom' === $cropping ) {
			$width          = max( 1, get_option( 'restaurantpress_thumbnail_cropping_custom_width', '4' ) );
			$height         = max( 1, get_option( 'restaurantpress_thumbnail_cropping_custom_height', '3' ) );
			$size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
			$size['crop']   = 1;
		} else {
			$cropping_split = explode( ':', $cropping );
			$width          = max( 1, current( $cropping_split ) );
			$height         = max( 1, end( $cropping_split ) );
			$size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
			$size['crop']   = 1;
		}
		$image_size = 'thumbnail';
	}

	return apply_filters( 'restaurantpress_get_image_size_' . $image_size, $size );
}

/**
 * Queue some JavaScript code to be output in the footer.
 *
 * @param string $code Code.
 */
function rp_enqueue_js( $code ) {
	global $rp_queued_js;

	if ( empty( $rp_queued_js ) ) {
		$rp_queued_js = '';
	}

	$rp_queued_js .= "\n" . $code . "\n";
}

/**
 * Output any queued javascript code in the footer.
 */
function rp_print_js() {
	global $rp_queued_js;

	if ( ! empty( $rp_queued_js ) ) {
		// Sanitize.
		$rp_queued_js = wp_check_invalid_utf8( $rp_queued_js );
		$rp_queued_js = preg_replace( '/&#(x)?0*(?(1)27|39);?/i', "'", $rp_queued_js );
		$rp_queued_js = str_replace( "\r", '', $rp_queued_js );

		$js = "<!-- RestaurantPress JavaScript -->\n<script type=\"text/javascript\">\njQuery(function($) { $rp_queued_js });\n</script>\n";

		/**
		 * Queued jsfilter.
		 *
		 * @param string $js JavaScript code.
		 */
		echo apply_filters( 'restaurantpress_queued_js', $js ); // WPCS: XSS ok.

		unset( $rp_queued_js );
	}
}

/**
 * Set a cookie - wrapper for setcookie using WP constants.
 *
 * @since 1.5.0
 *
 * @param string  $name   Name of the cookie being set.
 * @param string  $value  Value of the cookie.
 * @param integer $expire Expiry of the cookie.
 * @param bool    $secure Whether the cookie should be served only over https.
 */
function rp_setcookie( $name, $value, $expire = 0, $secure = false ) {
	if ( ! headers_sent() ) {
		setcookie( $name, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure, apply_filters( 'restaurantpress_cookie_httponly', false, $name, $value, $expire, $secure ) );
	} elseif ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		headers_sent( $file, $line );
		trigger_error( "{$name} cookie cannot be set - headers already sent by {$file} on line {$line}", E_USER_NOTICE ); // WPCS: XSS ok.
	}
}

/**
 * Various rewrite rule fixes.
 *
 * @since  1.6.0
 * @param  array $rules Rules.
 * @return array
 */
function rp_fix_rewrite_rules( $rules ) {
	global $wp_rewrite;

	$permalinks = rp_get_permalink_structure();

	// Fix the rewrite rules when the food permalink have %food_menu_cat% flag.
	if ( preg_match( '`/(.+)(/%food_menu_cat%)`', $permalinks['food_rewrite_slug'], $matches ) ) {
		foreach ( $rules as $rule => $rewrite ) {
			if ( preg_match( '`^' . preg_quote( $matches[1], '`' ) . '/\(`', $rule ) && preg_match( '/^(index\.php\?food_menu_cat)(?!(.*food_menu))/', $rewrite ) ) {
				unset( $rules[ $rule ] );
			}
		}
	}

	return $rules;
}
add_filter( 'rewrite_rules_array', 'rp_fix_rewrite_rules' );

/**
 * Display a RestaurantPress help tip.
 *
 * @param  string $tip        Help tip text.
 * @param  bool   $allow_html Allow sanitized HTML if true or escape.
 * @return string
 */
function rp_help_tip( $tip, $allow_html = false ) {
	if ( $allow_html ) {
		$tip = rp_sanitize_tooltip( $tip );
	} else {
		$tip = esc_attr( $tip );
	}

	return '<span class="restaurantpress-help-tip" data-tip="' . $tip . '"></span>';
}

/**
 * Wrapper for set_time_limit to see if it is enabled.
 *
 * @since 1.5.0
 * @param int $limit Time limit.
 */
function rp_set_time_limit( $limit = 0 ) {
	if ( function_exists( 'set_time_limit' ) && false === strpos( ini_get( 'disable_functions' ), 'set_time_limit' ) && ! ini_get( 'safe_mode' ) ) {
		@set_time_limit( $limit ); // @codingStandardsIgnoreLine
	}
}

/**
 * Wrapper for nocache_headers which also disables page caching.
 *
 * @since 1.6.0
 */
function rp_nocache_headers() {
	RP_Cache_Helper::set_nocache_constants();
	nocache_headers();
}

/**
 * Get rounding precision for internal RP calculations.
 * Will increase the precision of rp_get_price_decimals by 2 decimals, unless RP_ROUNDING_PRECISION is set to a higher number.
 *
 * @since  1.6.0
 * @return int
 */
function rp_get_rounding_precision() {
	$precision = rp_get_price_decimals() + 2;
	if ( absint( RP_ROUNDING_PRECISION ) > $precision ) {
		$precision = absint( RP_ROUNDING_PRECISION );
	}
	return $precision;
}

/**
 * Prints human-readable information about a variable.
 *
 * Some server environments blacklist some debugging functions. This function provides a safe way to
 * turn an expression into a printable, readable form without calling blacklisted functions.
 *
 * @since 1.4
 *
 * @param mixed $expression The expression to be printed.
 * @param bool  $return Optional. Default false. Set to true to return the human-readable string.
 * @return string|bool False if expression could not be printed. True if the expression was printed.
 *     If $return is true, a string representation will be returned.
 */
function rp_print_r( $expression, $return = false ) {
	$alternatives = array(
		array(
			'func' => 'print_r',
			'args' => array( $expression, true ),
		),
		array(
			'func' => 'var_export',
			'args' => array( $expression, true ),
		),
		array(
			'func' => 'json_encode',
			'args' => array( $expression ),
		),
		array(
			'func' => 'serialize',
			'args' => array( $expression ),
		),
	);

	$alternatives = apply_filters( 'restaurantpress_print_r_alternatives', $alternatives, $expression );

	foreach ( $alternatives as $alternative ) {
		if ( function_exists( $alternative['func'] ) ) {
			$res = call_user_func_array( $alternative['func'], $alternative['args'] );
			if ( $return ) {
				return $res;
			} else {
				echo $res; // WPCS: XSS ok.
				return true;
			}
		}
	}

	return false;
}

/**
 * Get permalink settings for RestaurantPress independent of the user locale.
 *
 * @since  1.6.0
 * @return array
 */
function rp_get_permalink_structure() {
	if ( did_action( 'admin_init' ) ) {
		rp_switch_to_site_locale();
	}

	$permalinks = wp_parse_args( (array) get_option( 'restaurantpress_permalinks', array() ), array(
		'food_base'              => '',
		'category_base'          => '',
		'tag_base'               => '',
		'use_verbose_page_rules' => false,
	) );

	// Ensure rewrite slugs are set.
	$permalinks['food_rewrite_slug']     = untrailingslashit( empty( $permalinks['food_base'] ) ? _x( 'food', 'slug', 'restaurantpress' ) : $permalinks['food_base'] );
	$permalinks['category_rewrite_slug'] = untrailingslashit( empty( $permalinks['category_base'] ) ? _x( 'food-category', 'slug', 'restaurantpress' ) : $permalinks['category_base'] );
	$permalinks['tag_rewrite_slug']      = untrailingslashit( empty( $permalinks['tag_base'] ) ? _x( 'food-tag', 'slug', 'restaurantpress' ) : $permalinks['tag_base'] );

	if ( did_action( 'admin_init' ) ) {
		rp_restore_locale();
	}
	return $permalinks;
}

/**
 * Switch RestaurantPress to site language.
 *
 * @since 1.6.0
 */
function rp_switch_to_site_locale() {
	if ( function_exists( 'switch_to_locale' ) ) {
		switch_to_locale( get_locale() );

		// Filter on plugin_locale so load_plugin_textdomain loads the correct locale.
		add_filter( 'plugin_locale', 'get_locale' );

		// Init RP locale.
		RP()->load_plugin_textdomain();
	}
}

/**
 * Switch RestaurantPress language to original.
 *
 * @since 1.6.0
 */
function rp_restore_locale() {
	if ( function_exists( 'restore_previous_locale' ) ) {
		restore_previous_locale();

		// Remove filter.
		remove_filter( 'plugin_locale', 'get_locale' );

		// Init RP locale.
		RP()->load_plugin_textdomain();
	}
}

/**
 * Convert plaintext phone number to clickable phone number.
 *
 * Remove formatting and allow "+".
 * Example and specs: https://developer.mozilla.org/en/docs/Web/HTML/Element/a#Creating_a_phone_link
 *
 * @since 1.4.0
 *
 * @param  string $phone Content to convert phone number.
 * @return string Content with converted phone number.
 */
function rp_make_phone_clickable( $phone ) {
	$number = trim( preg_replace( '/[^\d|\+]/', '', $phone ) );

	return '<a href="tel:' . esc_attr( $number ) . '">' . esc_html( $phone ) . '</a>';
}

/**
 * Read in RestaurantPress headers when reading plugin headers.
 *
 * @since  1.4.0
 * @param  array $headers Headers.
 * @return array
 */
function rp_enable_rp_plugin_headers( $headers ) {
	if ( ! class_exists( 'RP_Plugin_Updates' ) ) {
		include_once dirname( __FILE__ ) . '/admin/plugin-updates/class-rp-plugin-updates.php';
	}

	$headers['RPRequires'] = RP_Plugin_Updates::VERSION_REQUIRED_HEADER;
	$headers['RPTested']   = RP_Plugin_Updates::VERSION_TESTED_HEADER;
	return $headers;
}
add_filter( 'extra_plugin_headers', 'rp_enable_rp_plugin_headers' );

/**
 * Delete expired transients.
 *
 * Deletes all expired transients. The multi-table delete syntax is used.
 * to delete the transient record from table a, and the corresponding.
 * transient_timeout record from table b.
 *
 * Based on code inside core's upgrade_network() function.
 *
 * @since  1.4.0
 * @return int Number of transients that were cleared.
 */
function rp_delete_expired_transients() {
	global $wpdb;

	$sql  = "DELETE a, b FROM $wpdb->options a, $wpdb->options b
		WHERE a.option_name LIKE %s
		AND a.option_name NOT LIKE %s
		AND b.option_name = CONCAT( '_transient_timeout_', SUBSTRING( a.option_name, 12 ) )
		AND b.option_value < %d";
	$rows = $wpdb->query( $wpdb->prepare( $sql, $wpdb->esc_like( '_transient_' ) . '%', $wpdb->esc_like( '_transient_timeout_' ) . '%', time() ) ); // WPCS: unprepared SQL ok.

	$sql   = "DELETE a, b FROM $wpdb->options a, $wpdb->options b
		WHERE a.option_name LIKE %s
		AND a.option_name NOT LIKE %s
		AND b.option_name = CONCAT( '_site_transient_timeout_', SUBSTRING( a.option_name, 17 ) )
		AND b.option_value < %d";
	$rows2 = $wpdb->query( $wpdb->prepare( $sql, $wpdb->esc_like( '_site_transient_' ) . '%', $wpdb->esc_like( '_site_transient_timeout_' ) . '%', time() ) ); // WPCS: unprepared SQL ok.

	return absint( $rows + $rows2 );
}
add_action( 'restaurantpress_installed', 'rp_delete_expired_transients' );

/**
 * Make a URL relative, if possible.
 *
 * @since  1.4.0
 * @param  string $url URL to make relative.
 * @return string
 */
function rp_get_relative_url( $url ) {
	return rp_is_external_resource( $url ) ? $url : str_replace( array( 'http://', 'https://' ), '//', $url );
}

/**
 * See if a resource is remote.
 *
 * @since  1.4.0
 * @param  string $url URL to check.
 * @return bool
 */
function rp_is_external_resource( $url ) {
	$wp_base = str_replace( array( 'http://', 'https://' ), '//', get_home_url( null, '/', 'http' ) );
	return strstr( $url, '://' ) && strstr( $wp_base, $url );
}

/**
 * See if theme/s is activate or not.
 *
 * @since  1.6.0
 * @param  string|array $theme Theme name or array of theme names to check.
 * @return boolean
 */
function rp_is_active_theme( $theme ) {
	return is_array( $theme ) ? in_array( get_template(), $theme, true ) : get_template() === $theme;
}

/**
 * Cleans up session data - cron callback.
 *
 * @since 1.6.0
 */
function rp_cleanup_session_data() {
	$session_class = apply_filters( 'restaurantpress_session_handler', 'RP_Session_Handler' );
	$session       = new $session_class();

	if ( is_callable( array( $session, 'cleanup_sessions' ) ) ) {
		$session->cleanup_sessions();
	}
}
add_action( 'restaurantpress_cleanup_sessions', 'rp_cleanup_session_data' );

/**
 * Convert a decimal (e.g. 3.5) to a fraction (e.g. 7/2).
 * From: https://www.designedbyaturtle.co.uk/2015/converting-a-decimal-to-a-fraction-in-php/
 *
 * @param float $decimal the decimal number.
 * @return array|bool a 1/2 would be [1, 2] array (this can be imploded with '/' to form a string).
 */
function rp_decimal_to_fraction( $decimal ) {
	if ( 0 > $decimal || ! is_numeric( $decimal ) ) {
		// Negative digits need to be passed in as positive numbers and prefixed as negative once the response is imploded.
		return false;
	}

	if ( 0 === $decimal ) {
		return array( 0, 1 );
	}

	$tolerance   = 1.e-4;
	$numerator   = 1;
	$h2          = 0;
	$denominator = 0;
	$k2          = 1;
	$b           = 1 / $decimal;

	do {
		$b           = 1 / $b;
		$a           = floor( $b );
		$aux         = $numerator;
		$numerator   = $a * $numerator + $h2;
		$h2          = $aux;
		$aux         = $denominator;
		$denominator = $a * $denominator + $k2;
		$k2          = $aux;
		$b           = $b - $a;
	} while ( abs( $decimal - $numerator / $denominator ) > $decimal * $tolerance );

	return array( $numerator, $denominator );
}
