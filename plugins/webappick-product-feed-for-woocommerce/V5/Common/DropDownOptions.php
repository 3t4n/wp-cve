<?php

namespace CTXFeed\V5\Common;

use CTXFeed\V5\API\RestController;
use CTXFeed\V5\Merchant\MerchantAttributesFactory;
use CTXFeed\V5\Merchant\TemplateConfig;
use CTXFeed\V5\Product\ProductAttributeFactory;
use CTXFeed\V5\Query\QueryFactory;
use CTXFeed\V5\Query\WCQuery;
use CTXFeed\V5\Utility\Cache;
use CTXFeed\V5\Utility\Config;
use CTXFeed\V5\Utility\DropDown;
use WOOMC\DAO\Factory;

/**
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Common\DropDownOptions
 */
//TODO: Most of the functions didn't applied filter. Need to apply filter.
class DropDownOptions {
	/**
	 * Cache keys to save without html.
	 * @var array
	 */
	private static $cache_keys = [
		'woo_feed_product_attribute_dropdown_array'
	];
	/**
	 * Product Category
	 */
	private static $cats = [];

	/**
	 * The single instance of the class
	 *
	 * @var DropDownOptions
	 *
	 */
	protected static $_instance = null;

	/**
	 * Main DropDownOptions Instance.
	 *
	 * Ensures only one instance of DropDownOptions is loaded or can be loaded.
	 *
	 * @return DropDownOptions Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Country List
	 *
	 * @param bool $dropdown
	 * @param string $selected
	 *
	 * @return array
	 * @since   4.3.16
	 * @author  Nazrul Islam Nayan
	 * @updated 15-07-2022
	 */
	public static function feed_country( $selected = '', $dropdown = true ) {
		$countries = array(
			'AF' => 'Afghanistan',
			'AX' => 'Aland Islands',
			'AL' => 'Albania',
			'DZ' => 'Algeria',
			'AS' => 'American Samoa',
			'AD' => 'Andorra',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctica',
			'AG' => 'Antigua and Barbuda',
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AW' => 'Aruba',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BS' => 'Bahamas the',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BB' => 'Barbados',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BZ' => 'Belize',
			'BJ' => 'Benin',
			'BM' => 'Bermuda',
			'BT' => 'Bhutan',
			'BO' => 'Bolivia',
			'BA' => 'Bosnia and Herzegovina',
			'BW' => 'Botswana',
			'BV' => 'Bouvet Island (Bouvetoya)',
			'BR' => 'Brazil',
			'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
			'VG' => 'British Virgin Islands',
			'BN' => 'Brunei Darussalam',
			'BG' => 'Bulgaria',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodia',
			'CM' => 'Cameroon',
			'CA' => 'Canada',
			'CV' => 'Cape Verde',
			'KY' => 'Cayman Islands',
			'CF' => 'Central African Republic',
			'TD' => 'Chad',
			'CL' => 'Chile',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos (Keeling) Islands',
			'CO' => 'Colombia',
			'KM' => 'Comoros the',
			'CD' => 'Congo',
			'CG' => 'Congo the',
			'CK' => 'Cook Islands',
			'CR' => 'Costa Rica',
			'CI' => 'Cote d\'Ivoire',
			'HR' => 'Croatia',
			'CU' => 'Cuba',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'DK' => 'Denmark',
			'DJ' => 'Djibouti',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'EC' => 'Ecuador',
			'EG' => 'Egypt',
			'SV' => 'El Salvador',
			'GQ' => 'Equatorial Guinea',
			'ER' => 'Eritrea',
			'EE' => 'Estonia',
			'ET' => 'Ethiopia',
			'FO' => 'Faroe Islands',
			'FK' => 'Falkland Islands (Malvinas)',
			'FJ' => 'Fiji the Fiji Islands',
			'FI' => 'Finland',
			'FR' => 'France',
			'GF' => 'French Guiana',
			'PF' => 'French Polynesia',
			'TF' => 'French Southern Territories',
			'GA' => 'Gabon',
			'GM' => 'Gambia the',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GR' => 'Greece',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GG' => 'Guernsey',
			'GN' => 'Guinea',
			'GW' => 'Guinea-Bissau',
			'GY' => 'Guyana',
			'HT' => 'Haiti',
			'HM' => 'Heard Island and McDonald Islands',
			'VA' => 'Holy See (Vatican City State)',
			'HN' => 'Honduras',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Iran',
			'IQ' => 'Iraq',
			'IE' => 'Ireland',
			'IM' => 'Isle of Man',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'JM' => 'Jamaica',
			'JP' => 'Japan',
			'JE' => 'Jersey',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KI' => 'Kiribati',
			'KP' => 'Korea',
			'KR' => 'Korea',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyz Republic',
			'LA' => 'Lao',
			'LV' => 'Latvia',
			'LB' => 'Lebanon',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libyan Arab Jamahiriya',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MO' => 'Macao',
			'MK' => 'Macedonia',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malta',
			'MH' => 'Marshall Islands',
			'MQ' => 'Martinique',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MX' => 'Mexico',
			'FM' => 'Micronesia',
			'MD' => 'Moldova',
			'MC' => 'Monaco',
			'MN' => 'Mongolia',
			'ME' => 'Montenegro',
			'MS' => 'Montserrat',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar',
			'NA' => 'Namibia',
			'NR' => 'Nauru',
			'NP' => 'Nepal',
			'AN' => 'Netherlands Antilles',
			'NL' => 'Netherlands',
			'NC' => 'New Caledonia',
			'NZ' => 'New Zealand',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'MP' => 'Northern Mariana Islands',
			'NO' => 'Norway',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PW' => 'Palau',
			'PS' => 'Palestinian Territory',
			'PA' => 'Panama',
			'PG' => 'Papua New Guinea',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn Islands',
			'PL' => 'Poland',
			'PT' => 'Portugal, Portuguese Republic',
			'PR' => 'Puerto Rico',
			'QA' => 'Qatar',
			'RE' => 'Reunion',
			'RO' => 'Romania',
			'RU' => 'Russian Federation',
			'RW' => 'Rwanda',
			'BL' => 'Saint Barthelemy',
			'SH' => 'Saint Helena',
			'KN' => 'Saint Kitts and Nevis',
			'LC' => 'Saint Lucia',
			'MF' => 'Saint Martin',
			'PM' => 'Saint Pierre and Miquelon',
			'VC' => 'Saint Vincent and the Grenadines',
			'WS' => 'Samoa',
			'SM' => 'San Marino',
			'ST' => 'Sao Tome and Principe',
			'SA' => 'Saudi Arabia',
			'SN' => 'Senegal',
			'RS' => 'Serbia',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapore',
			'SK' => 'Slovakia (Slovak Republic)',
			'SI' => 'Slovenia',
			'SB' => 'Solomon Islands',
			'SO' => 'Somalia, Somali Republic',
			'ZA' => 'South Africa',
			'GS' => 'South Georgia and the South Sandwich Islands',
			'ES' => 'Spain',
			'LK' => 'Sri Lanka',
			'SD' => 'Sudan',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard & Jan Mayen Islands',
			'SZ' => 'Swaziland',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'SY' => 'Syrian Arab Republic',
			'TW' => 'Taiwan',
			'TJ' => 'Tajikistan',
			'TZ' => 'Tanzania',
			'TH' => 'Thailand',
			'TL' => 'Timor-Leste',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinidad and Tobago',
			'TN' => 'Tunisia',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'TC' => 'Turks and Caicos Islands',
			'TV' => 'Tuvalu',
			'UG' => 'Uganda',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'GB' => 'United Kingdom',
			'US' => 'United States',
			'UM' => 'United States Minor Outlying Islands',
			'VI' => 'United States Virgin Islands',
			'UY' => 'Uruguay, Eastern Republic of',
			'UZ' => 'Uzbekistan',
			'VU' => 'Vanuatu',
			'VE' => 'Venezuela',
			'VN' => 'Vietnam',
			'WF' => 'Wallis and Futuna',
			'EH' => 'Western Sahara',
			'YE' => 'Yemen',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe',
		);

		return $countries;
		//return self::Create_DropDown_IF_Needed( $countries, $dropdown, $selected );
	}

	/**
	 * Feed Template List.
	 *
	 * @param $dropdown
	 * @param $selected
	 *
	 * @return array|false|mixed|string|string[]
	 */
	public static function provider( $selected = '', $dropdown = true ) {
		$merchant          = [
			1 => [
				'optionGroup' => 'Custom Templates',
				'options'     => [
					'custom'  => esc_html__( 'Custom Template 1', 'woo-feed' ),
					'custom2' => esc_html__( 'Custom Template 2 (XML)', 'woo-feed' ),
				]
			],
			2 => [
				'optionGroup' => 'Popular Templates',
				'options'     => [
					'google'                 => esc_html__( 'Google Shopping', 'woo-feed' ),
					'google_local'           => esc_html__( 'Google Local Inventory Ads', 'woo-feed' ),
					'google_local_inventory' => esc_html__( 'Google Local Product Inventory', 'woo-feed' ),
					'googlereview'           => esc_html__( 'Google Product Review', 'woo-feed' ),
					'google_shopping_action' => esc_html__( 'Google Shopping Action', 'woo-feed' ),
					'google_promotions'      => esc_html__( 'Google Promotions', 'woo-feed' ),
					'google_dynamic_ads'     => esc_html__( 'Google Dynamic Search Ads', 'woo-feed' ),
					'adwords'                => esc_html__( 'Google Ads', 'woo-feed' ),
					'adwords_local_product'  => esc_html__( 'Google Ads Local Product', 'woo-feed' ),
					'facebook'               => esc_html__( 'Facebook Catalog / Instagram', 'woo-feed' ),
					'pinterest'              => esc_html__( 'Pinterest Catalog', 'woo-feed' ),
					'pinterest_rss'          => esc_html__( 'Pinterest RSS Board', 'woo-feed' ),
					'bing'                   => esc_html__( 'Bing Shopping', 'woo-feed' ),
					'bing_local_inventory'   => esc_html__( 'Bing Local Inventory', 'woo-feed' ),
					'snapchat'               => esc_html__( 'Snapchat', 'woo-feed' ),
					'tiktok'                 => esc_html__( 'TikTok Ads Manager', 'woo-feed' ),
					'idealo'                 => esc_html__( 'Idealo', 'woo-feed' ),
					'pricespy'               => esc_html__( 'PriceSpy', 'woo-feed' ),
					'pricerunner'            => esc_html__( 'Price Runner', 'woo-feed' ),
					'yandex_csv'             => esc_html__( 'Yandex (CSV)', 'woo-feed' ),
					'yandex_xml'             => esc_html__( 'Yandex (XML)', 'woo-feed' ),
				]
			],
			3 => [
				'optionGroup' => 'Other Templates',
				'options'     => [
					'adform'                                         => esc_html__( 'AdForm', 'woo-feed' ),
					'adroll'                                         => esc_html__( 'AdRoll', 'woo-feed' ),
					'avantlink'                                      => esc_html__( 'Avantlink', 'woo-feed' ),
					'become'                                         => esc_html__( 'Become', 'woo-feed' ),
					'beslist.nl'                                     => esc_html__( 'Beslist.nl', 'woo-feed' ),
					'bestprice'                                      => esc_html__( 'Bestprice', 'woo-feed' ),
					'billiger.de'                                    => esc_html__( 'Billiger.de', 'woo-feed' ),
					'bol'                                            => esc_html__( 'Bol.com', 'woo-feed' ),
					'bonanza'                                        => esc_html__( 'Bonanza', 'woo-feed' ),
					'catchdotcom'                                    => esc_html__( 'Catch.com.au', 'woo-feed' ),
					'cdiscount.fr'                                   => esc_html__( 'CDiscount.fr', 'woo-feed' ),
					'comparer.be'                                    => esc_html__( 'Comparer.be', 'woo-feed' ),
					'connexity'                                      => esc_html__( 'Connexity', 'woo-feed' ),
					'criteo'                                         => esc_html__( 'Criteo', 'woo-feed' ),
					'crowdfox'                                       => esc_html__( 'Crowdfox', 'woo-feed' ),
					'daisycon'                                       => esc_html__( 'Daisycon Advertiser (General)', 'woo-feed' ),
					'daisycon_automotive'                            => esc_html__( 'Daisycon Advertiser (Automotive)', 'woo-feed' ),
					'daisycon_books'                                 => esc_html__( 'Daisycon Advertiser (Books)', 'woo-feed' ),
					'daisycon_cosmetics'                             => esc_html__( 'Daisycon Advertiser (Cosmetics)', 'woo-feed' ),
					'daisycon_daily_offers'                          => esc_html__( 'Daisycon Advertiser (Daily Offers)', 'woo-feed' ),
					'daisycon_electronics'                           => esc_html__( 'Daisycon Advertiser (Electronics)', 'woo-feed' ),
					'daisycon_fashion'                               => esc_html__( 'Daisycon Advertiser (Fashion)', 'woo-feed' ),
					'daisycon_food_drinks'                           => esc_html__( 'Daisycon Advertiser (Food & Drinks)', 'woo-feed' ),
					'daisycon_holidays_accommodations_and_transport' => esc_html__( 'Daisycon Advertiser (Holidays: Accommodations and transport)', 'woo-feed' ),
					'daisycon_holidays_accommodations'               => esc_html__( 'Daisycon Advertiser (Holidays: Accommodations)', 'woo-feed' ),
					'daisycon_holidays_trips'                        => esc_html__( 'Daisycon Advertiser (Holidays: Trips)', 'woo-feed' ),
					'daisycon_home_garden'                           => esc_html__( 'Daisycon Advertiser (Home & Garden)', 'woo-feed' ),
					'daisycon_housing'                               => esc_html__( 'Daisycon Advertiser (Housing)', 'woo-feed' ),
					'daisycon_magazines'                             => esc_html__( 'Daisycon Advertiser (Magazines)', 'woo-feed' ),
					'daisycon_studies_trainings'                     => esc_html__( 'Daisycon Advertiser (Studies & Trainings)', 'woo-feed' ),
					'daisycon_telecom_accessories'                   => esc_html__( 'Daisycon Advertiser (Telecom: Accessories)', 'woo-feed' ),
					'daisycon_telecom_all_in_one'                    => esc_html__( 'Daisycon Advertiser (Telecom: All-in-one)', 'woo-feed' ),
					'daisycon_telecom_gsm_subscription'              => esc_html__( 'Daisycon Advertiser (Telecom: GSM + Subscription)', 'woo-feed' ),
					'daisycon_telecom_gsm'                           => esc_html__( 'Daisycon Advertiser (Telecom: GSM only)', 'woo-feed' ),
					'daisycon_telecom_sim'                           => esc_html__( 'Daisycon Advertiser (Telecom: Sim only)', 'woo-feed' ),
					'daisycon_work_jobs'                             => esc_html__( 'Daisycon Advertiser (Work & Jobs)', 'woo-feed' ),
					'dooyoo'                                         => esc_html__( 'Dooyoo', 'woo-feed' ),
					'ecommerceit'                                    => esc_html__( 'Ecommerce.it', 'woo-feed' ),
					'etsy'                                           => esc_html__( 'Etsy', 'woo-feed' ),
					'fruugo'                                         => esc_html__( 'Fruugo', 'woo-feed' ),
					'fashionchick'                                   => esc_html__( 'Fashionchick.nl', 'woo-feed' ),
					'fruugo.au'                                      => esc_html__( 'Fruugoaustralia.com', 'woo-feed' ),
					'fyndiq.se'                                      => esc_html__( 'Fyndiq.se', 'woo-feed' ),
					'goedgeplaatst'                                  => esc_html__( 'GoedGeplaatst.nl', 'woo-feed' ),
					'heureka.sk'                                     => esc_html__( 'Heureka.sk', 'woo-feed' ),
					'hintaseuranta.fi'                               => esc_html__( 'Hintaseuranta.fi', 'woo-feed' ),
					'incurvy'                                        => esc_html__( 'Incurvy', 'woo-feed' ),
					'jet'                                            => esc_html__( 'Jet.com', 'woo-feed' ),
					'kelkoo'                                         => esc_html__( 'Kelkoo', 'woo-feed' ),
					'kieskeurig.nl'                                  => esc_html__( 'Kieskeurig.nl', 'woo-feed' ),
					'kijiji.ca'                                      => esc_html__( 'Kijiji.ca', 'woo-feed' ),
					'leguide'                                        => esc_html__( 'LeGuide', 'woo-feed' ),
					'marktplaats.nl'                                 => esc_html__( 'Marktplaats.nl', 'woo-feed' ),
					'miinto.de'                                      => esc_html__( 'Miinto.de', 'woo-feed' ),
					'miinto.nl'                                      => esc_html__( 'Miinto.nl', 'woo-feed' ),
					'modalova'                                       => esc_html__( 'Modalova', 'woo-feed' ),
					'modina.de'                                      => esc_html__( 'Modina.de', 'woo-feed' ),
					'moebel.de'                                      => esc_html__( 'Moebel.de', 'woo-feed' ),
					'myshopping.com.au'                              => esc_html__( 'Myshopping.com.au', 'woo-feed' ),
					'nextad'                                         => esc_html__( 'TheNextAd', 'woo-feed' ),
					'nextag'                                         => esc_html__( 'Nextag', 'woo-feed' ),
					'polyvore'                                       => esc_html__( 'Polyvore', 'woo-feed' ),
					'pricegrabber'                                   => esc_html__( 'Price Grabber', 'woo-feed' ),
					'prisjakt'                                       => esc_html__( 'Prisjakt', 'woo-feed' ),
					'profit_share'                                   => esc_html__( 'Profit Share', 'woo-feed' ),
					'rakuten.de'                                     => esc_html__( 'Rakuten.de', 'woo-feed' ),
					'real'                                           => esc_html__( 'Real', 'woo-feed' ),
					'shareasale'                                     => esc_html__( 'ShareASale', 'woo-feed' ),
					'shopalike.fr'                                   => esc_html__( 'Shopalike.fr', 'woo-feed' ),
					'shopbot'                                        => esc_html__( 'Shopbot', 'woo-feed' ),
					'shopmania'                                      => esc_html__( 'Shopmania', 'woo-feed' ),
					'shopping'                                       => esc_html__( 'Shopping.com', 'woo-feed' ),
					'shopflix'                                       => esc_html__( 'Shopflix (WellComm)', 'woo-feed' ),
					'shopzilla'                                      => esc_html__( 'Shopzilla', 'woo-feed' ),
					'skinflint.co.uk'                                => esc_html__( 'SkinFlint.co.uk', 'woo-feed' ),
					'skroutz'                                        => esc_html__( 'Skroutz.gr', 'woo-feed' ),
					'smartly.io'                                     => esc_html__( 'Smartly.io', 'woo-feed' ),
					'spartoo.fi'                                     => esc_html__( 'Spartoo.fi', 'woo-feed' ),
					'shopee'                                         => esc_html__( 'Shopee', 'woo-feed' ),
					'stylight.com'                                   => esc_html__( 'Stylight.com', 'woo-feed' ),
					'trovaprezzi'                                    => esc_html__( 'Trovaprezzi.it', 'woo-feed' ),
					'twenga'                                         => esc_html__( 'Twenga', 'woo-feed' ),
					'tweaker_xml'                                    => esc_html__( 'Tweakers (XML)', 'woo-feed' ),
					'tweaker_csv'                                    => esc_html__( 'Tweakers (CSV)', 'woo-feed' ),
					'vertaa.fi'                                      => esc_html__( 'Vertaa.fi', 'woo-feed' ),
					'walmart'                                        => esc_html__( 'Walmart', 'woo-feed' ),
					'webmarchand'                                    => esc_html__( 'Webmarchand', 'woo-feed' ),
					'wine_searcher'                                  => esc_html__( 'Wine Searcher', 'woo-feed' ),
					'wish'                                           => esc_html__( 'Wish.com', 'woo-feed' ),
					'yahoo_nfa'                                      => esc_html__( 'Yahoo NFA', 'woo-feed' ),
					'zap.co.il'                                      => esc_html__( 'Zap.co.il', 'woo-feed' ),
					'zbozi.cz'                                       => esc_html__( 'Zbozi.cz', 'woo-feed' ),
					'zalando'                                        => esc_html__( 'Zalando', 'woo-feed' ),
					'admarkt'                                        => esc_html__( 'Admarkt(marktplaats)', 'woo-feed' ),
					'glami'                                          => esc_html__( 'GLAMI', 'woo-feed' ),
				]
			],
		];

		return $merchant;
		//return self::Create_DropDown_IF_Needed( $merchant, $dropdown, $selected );
	}

	/**
	 * Feed File Type List.
	 *
	 * @param $dropdown
	 * @param $selected
	 *
	 * @return array|false|mixed|string|string[]
	 */
	public static function file_types( $selected = '', $dropdown = true ) {
		$types = array(
			'xml'  => 'XML',
			'csv'  => 'CSV',
			'tsv'  => 'TSV',
			'xls'  => 'XLS',
			'xlsx'  => 'XLSX',
			'txt'  => 'TXT',
			'json' => 'JSON',
		);

		return $types;
		//return self::Create_DropDown_IF_Needed( $types, $dropdown, $selected );
	}

	/**
	 * Variation Options.
	 *
	 * @param $dropdown
	 * @param $selected
	 *
	 * @return array|false|mixed|string|string[]
	 */
	public static function include_variation( $selected = '', $dropdown = true ) {
		$variation_options = array(
			'y'         => esc_html__( 'All Variations', 'woo-feed' ),
			'n'         => esc_html__( 'Variable Products (Parent)', 'woo-feed' ),
			'default'   => esc_html__( 'Default Variation', 'woo-feed' ),
			'cheap'     => esc_html__( 'Cheapest Variation', 'woo-feed' ),
			'expensive' => esc_html__( 'Expensive Variation', 'woo-feed' ),
			'first'     => esc_html__( 'First Variation', 'woo-feed' ),
			'last'      => esc_html__( 'Last Variation', 'woo-feed' ),
			'both'      => esc_html__( 'Variable + Variations', 'woo-feed' ),
		);

		return $variation_options;
		//return self::Create_DropDown_IF_Needed( $variation_options, $dropdown, $selected );
	}

	/**
	 * Variation Price Options.
	 *
	 * @param $dropdown
	 * @param $selected
	 *
	 * @return array|false|mixed|string|string[]
	 */
	public static function variable_product_price( $selected = '', $dropdown = true ) {
		$variation_price = array(
			'first' => esc_html__( 'First Variation Price', 'woo-feed' ),
			'max'   => esc_html__( 'Max Variation Price', 'woo-feed' ),
			'min'   => esc_html__( 'Min Variation Price', 'woo-feed' ),
		);

		return $variation_price;
		//return self::Create_DropDown_IF_Needed( $variation_price, $dropdown, $selected );
	}

	/**
	 * Variation Quantity Options.
	 *
	 * @param $dropdown
	 * @param $selected
	 *
	 * @return array|false|mixed|string|string[]
	 */
	public static function variable_product_quantity( $selected = '', $dropdown = true ) {
		$variation_quantity = array(
			'first' => esc_html__( 'First Variation Quantity', 'woo-feed' ),
			'max'   => esc_html__( 'Max Variation Quantity', 'woo-feed' ),
			'min'   => esc_html__( 'Min Variation Quantity', 'woo-feed' ),
			'sum'   => esc_html__( 'Sum of Variation Quantity', 'woo-feed' ),
		);

		return $variation_quantity;
		//return self::Create_DropDown_IF_Needed( $variation_quantity, $dropdown, $selected );
	}

	/**
	 * CSV Feed Delimiters.
	 *
	 * @param $dropdown
	 * @param $selected
	 *
	 * @return array|false|mixed|string|string[]
	 */
	public static function delimiters( $selected = '', $dropdown = true ) {
		$delimiters = [
			','  => 'Comma',
			':'  => 'Colon',
			' '  => 'Space',
			'|'  => 'Pipe',
			';'  => 'Semi Colon',
			"\t" => 'TAB',
		];

		return $delimiters;
		//return self::Create_DropDown_IF_Needed( $delimiters, $dropdown, $selected );
	}

	/**
	 * CSV Feed Enclosure.
	 *
	 * @param $dropdown
	 * @param $selected
	 *
	 * @return array|false|mixed|string|string[]
	 */
	public static function enclosure( $selected = '', $dropdown = true ) {
		$enclosure = [
			'double' => '"',
			'single' => '\'',
			' '      => 'None',
		];

		return $enclosure;
		//return self::Create_DropDown_IF_Needed( $enclosure, $dropdown, $selected );
	}

	/**
	 * Get Merchant attribute dropdown.
	 *
	 * @param        $attributes
	 * @param string $selected
	 * @param bool $dropdown
	 *
	 * @return string|false
	 */
	public static function merchant_attributes( $attributes, $selected = '', $dropdown = true ) {
		$cache_key = 'ctx_merchant_attribute_dropdown_' . $selected;

		return $attributes;
		//return self::Create_DropDown_IF_Needed( $attributes, $dropdown, $selected, $cache_key, true );
	}

	/**
	 * Get Merchant attribute from API.
	 *
	 * @param $selected
	 * @param $dropdown
	 *
	 * @return array|false|mixed|string|string[]
	 */
	public static function mattributes( $selected = '', $dropdown = true ) {
		$merchantAttributes = MerchantAttributesFactory::get();

		return $merchantAttributes;
	}

	/**
	 * Product Attributes.
	 *
	 * @param $dropdown
	 * @param $selected
	 *
	 * @return array|false|mixed|string|string[]
	 */
	public static function product_attributes( $selected = '', $dropdown = true, $cache_key = 'woo_feed_product_attribute_dropdown' ) {

		$attributes = ProductAttributeFactory::getAttributes();

		return $attributes;
		//return self::Create_DropDown_IF_Needed( $attributes, $dropdown, $selected, $cache_key, true );
	}

	/**
	 * Comparing Condition. Used on Dynamic Attributes and
	 *
	 * @param $dropdown
	 * @param $selected
	 *
	 * @return array|false|mixed|string|string[]
	 */
	public static function conditions( $selected = '', $dropdown = true ) {
		$conditions = array(
//			''          => __( 'Select Condition' ),
			'=='        => __( 'is / equal', 'woo-feed' ),
			'!='        => __( 'is not / not equal', 'woo-feed' ),
			'>='        => __( 'equals or greater than', 'woo-feed' ),
			'>'         => __( 'greater than', 'woo-feed' ),
			'<='        => __( 'equals or less than', 'woo-feed' ),
			'<'         => __( 'less than', 'woo-feed' ),
			'contains'  => __( 'contains', 'woo-feed' ),
			'nContains' => __( 'does not contain', 'woo-feed' ),
			'between'   => __( 'between', 'woo-feed' ),
		);

		return $conditions;
		//return self::Create_DropDown_IF_Needed( $conditions, $dropdown, $selected );
	}

	/**
	 * Variation Query Type at Settings.
	 *
	 * @param $dropdown
	 * @param $selected
	 *
	 * @return array|false|mixed|string|string[]
	 */
	public static function variation_query_type( $selected = '', $dropdown = false ) {
		$variation_query_type = [
			'individual' => __( 'Individual', 'woo-feed' ),
			'variable'   => __( 'Variable Dependable', 'woo-feed' ),
		];

		return $variation_query_type;
		//return self::Create_DropDown_IF_Needed( $variation_query_type, $dropdown, $selected );
	}

	/**
	 * Product Query Type at Settings.
	 *
	 * @param $dropdown
	 * @param $selected
	 *
	 * @return array|false|mixed|string|string[]
	 */
	public static function product_query_type( $selected = '', $dropdown = false ) {
		$product_query_type = [
			'wc'   => __( 'WC_Product_Query', 'woo-feed' ),
			'wp'   => __( 'WP_Query', 'woo-feed' ),
			'both' => __( 'Both', 'woo-feed' ),
		];

		return $product_query_type;
		//return self::Create_DropDown_IF_Needed( $product_query_type, $dropdown, $selected );
	}

	/**
	 * Get all WP Options list.
	 *
	 * @param $dropdown
	 * @param $selected
	 *
	 * @return array|false|mixed|string|string[]
	 */
	public static function get_options( $selected = '', $dropdown = false ) {
		$options    = [];
		$getOptions = wp_load_alloptions();

		if ( ! empty( $getOptions ) ) {
			$options[''] = "Select an Option";
			foreach ( $getOptions as $key => $option ) {
				$options[ $key ] = $key;
			}
		}

		return $options;
		//return self::Create_DropDown_IF_Needed( $options, $dropdown, $selected );
	}

	/**
	 * Get Product Categories for category mapping.
	 *
	 * @param $dropdown
	 * @param $selected
	 *
	 * @return void
	 */
	/**
	 * Get All Product Categories
	 *
	 * @param int $parent Category Parent ID.
	 * @param     $dropdown
	 * @param     $selected
	 *
	 * @return array
	 */
	public static function get_catmap_categories( $parent = 0, $dropdown = true, $selected = [], $slug = '' ) {

		$args = [
			'taxonomy'     => 'product_cat',
			'parent'       => $parent,
			'orderby'      => 'term_id',
			'show_count'   => 1,
			'pad_counts'   => 1,
			'hierarchical' => 1,
			'title_li'     => '',
			'hide_empty'   => 0,
		];

		$categories = get_categories( $args );

		if ( ! empty( $categories ) ) {
			if ( ! empty( $slug ) ) {
				$slug = $slug . ' > ';
			}
			foreach ( $categories as $cat ) {
				if ( ! array_key_exists( $cat->slug, self::$cats ) ) {
					if ( ! empty( get_term_children( $cat->term_id, 'product_cat' ) ) ) {
						$group_id = 'group-parent-' . $cat->term_id;
					} else {
						$group_id = 'group-child-' . $cat->parent;
					}
					$cat->name= htmlspecialchars_decode($cat->name);
					self::$cats[ $cat->slug ] = [
						'name'      => $slug . $cat->name,
						'id'        => $cat->term_id,
						'has_child' => get_term_children( $cat->term_id, 'product_cat' ),
						'group_id'  => $group_id
					];
					self::get_catmap_categories( $cat->term_id, false, '', $slug . $cat->name );
				}
			}
		}


		return self::$cats;
	}

	/**
	 * Get Product Categories
	 *
	 * @param $dropdown
	 * @param $selected
	 *
	 * @return void
	 */
	/**
	 * Get All Product Categories
	 *
	 * @param int $parent Category Parent ID.
	 * @param     $dropdown
	 * @param     $selected
	 *
	 * @return array
	 */
	public static function get_categories( $args ) {

		$current_language = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : '';
		$query_vars = [
			'taxonomy'     => 'product_cat',
			'orderby'      => 'term_group',
			'show_count'   => 1,
			'pad_counts'   => 1,
			'hierarchical' => 1,
			'title_li'     => '',
			'hide_empty'   => 0,
			'language'	   => $current_language
		];
		$query_vars = wp_parse_args( $args, $query_vars);

		$categories = get_categories( $query_vars );
		if ( ! empty( $categories ) ) {
			foreach ( $categories as $cat ) {
				self::$cats[ $cat->slug ] = $cat->name;
			}
		}

		return apply_filters('ctx_feed_filter_categories', self::$cats, $query_vars);
	}


	/**
	 * Get Product Categories
	 *
	 * @param $dropdown
	 * @param $selected
	 *
	 * @return void
	 */

	/**
	 * Read txt file which contains google taxonomy list
	 *
	 * @return array
	 */
	public static function googleTaxonomyArray() {
		// Get All Google Taxonomies
		$fileName           = WOO_FEED_FREE_ADMIN_PATH . '/partials/templates/taxonomies/google_taxonomy.txt';
		$customTaxonomyFile = fopen( $fileName, 'r' );  // phpcs:ignore
		$taxonomy           = [];
		if ( $customTaxonomyFile ) {
			// First line contains metadata, ignore it
			fgets( $customTaxonomyFile );  // phpcs:ignore
			while ( $line = fgets( $customTaxonomyFile ) ) {  // phpcs:ignore
				list( $catId, $cat ) = explode( '-', $line );
				$taxonomy[] = [
					'value' => absint( trim( $catId ) ),
					'label' => trim( $catId ) . " - " . trim( $cat ),
				];
			}
		}

		return array_filter( $taxonomy );
	}


	/**
	 * Read txt file which contains facebook taxonomy list
	 *
	 * @return array
	 */
	public static function facebookTaxonomyArray() {
		// Get All Facebook Taxonomies
		$fileName           = WOO_FEED_FREE_ADMIN_PATH . '/partials/templates/taxonomies/fb_taxonomy.txt';
		$customTaxonomyFile = fopen( $fileName, 'r' );  // phpcs:ignore
		$taxonomy           = array();
		if ( $customTaxonomyFile ) {
			// First line contains metadata, ignore it
			fgets( $customTaxonomyFile );  // phpcs:ignore
			while ( $line = fgets( $customTaxonomyFile ) ) {  // phpcs:ignore
				list( $catId, $cat ) = explode( ',', $line );
				$taxonomy[] = array(
					'value' => absint( trim( $catId ) ),
					'label' => trim( $catId ) . " - " . trim( $cat ),
				);
			}
		}
		$taxonomy = array_filter( $taxonomy );

		return $taxonomy;
	}

	/**
	 * Get Output Types
	 *
	 * @return array
	 */
	public static function output_types() {
		$output_types = array(
			'1'  => 'Default',
			'2'  => 'Strip Tags',
			'3'  => 'UTF-8 Encode',
			'4'  => 'htmlentities',
			'5'  => 'Integer',
			'6'  => 'Price',
			'7'  => 'Rounded Price',
			'8'  => 'Remove Space',
			'9'  => 'CDATA',
			'10' => 'Remove Special Character',
			'11' => 'Remove ShortCodes',
			'12' => 'ucwords',
			'13' => 'ucfirst',
			'14' => 'strtoupper',
			'15' => 'strtolower',
			'16' => 'urlToSecure',
			'17' => 'urlToUnsecure',
			'18' => 'only_parent',
			'19' => 'parent',
			'20' => 'parent_if_empty',
			'21' => 'htmlspecialchars',
			'22' => 'htmlspecialchars_decode',
		);

		//when wpml or polylang plugin is activated
		if (
			 class_exists( 'SitePress', false ) || defined( 'POLYLANG_BASENAME' ) || function_exists( 'PLL' ) // When WPML is active
			|| is_plugin_active( 'translatepress-multilingual/index.php' ) // Translatepress
		) {
			array_push( $output_types, 'parent_lang' );
			array_push( $output_types, 'parent_lang_if_empty' );
		}

		return apply_filters( 'woo_feed_output_types', $output_types );
	}

	/**
	 * Get Product Statuses
	 *
	 * @return array
	 */
	public static function get_post_statuses() {
		return (array) apply_filters( 'woo_feed_product_statuses', get_post_statuses() );
	}

	public static function all_product_ids( $args ) {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return [];
		}

		$query_vars = wp_parse_args( $args, [ 'return' => 'objects' ]);

		$config   = new Config( [] );
		$wc_query = new WCQuery( $config, $query_vars );
		$products = $wc_query->product_ids();

		$product_ids_with_titles = [];
		foreach ( $products as $product ) {
			if ( $product instanceof \WC_Product ) {
				$id                             = $product->get_id();
				$sku                            = ! empty( $product->get_sku() ) ? "::" . $product->get_sku() : '';
				$product_ids_with_titles[ $id ] = $id . $sku . '::' . $product->get_name();
			}
		}

		return apply_filters( 'ctx_feed_all_product_ids_with_title', $product_ids_with_titles, $config, $wc_query, $query_vars );
	}


	/**
	 * Get Active Languages for current site.
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public static function getActiveLanguages( $selected = '' ) {
		$options = false;
		if ( false === $options ) {
			$languages = [];
			if ( class_exists( 'SitePress' ) ) {
				$get_languages = apply_filters( 'wpml_active_languages', null, 'orderby=id&order=desc' );
				if ( ! empty( $get_languages ) ) {
					foreach ( $get_languages as $key => $language ) {
						$languages[ $key ] = $language['translated_name'];
					}
				}
			}

			// when polylang plugin is activated
			if ( defined( 'POLYLANG_BASENAME' ) || function_exists( 'PLL' ) ) {
				// polylang language names
				$poly_languages_names = pll_languages_list( [ 'fields' => 'name' ] );

				// polylang language locales
				$poly_languages_slugs = pll_languages_list( [ 'fields' => 'slug' ] );

				// polylang language lists
				$get_languages = array_combine( $poly_languages_slugs, $poly_languages_names );

				if ( ! empty( $get_languages ) ) {
					$languages = [];
					foreach ( $get_languages as $key => $value ) {
						$languages[ $key ] = $value;
					}
				}
			}


			//when translatepress is activated
			if ( is_plugin_active( 'translatepress-multilingual/index.php' ) ) {
				if ( class_exists( 'TRP_Translate_Press' ) ) {
					$tr_press_languages = trp_get_languages( 'default' );

					if ( ! empty( $tr_press_languages ) ) {
						foreach ( $tr_press_languages as $key => $value ) {
							$languages[ $key ] = $value;
						}
					}
				}
			}

			//language dropdown
			$options = $languages;

		}

		return $options;
	}

	/**
	 * Get Active Currency
	 *
	 * @param string $selected
	 *
	 * @return false|mixed|string
	 * @since 3.3.2
	 */
	public static function getActiveCurrencies( $selected = '' ) {
		$options = false;
		if ( false === $options ) {
			global $woocommerce_wpml;
			if ( class_exists( 'SitePress' ) && class_exists( 'woocommerce_wpml' ) && wcml_is_multi_currency_on() && isset( $woocommerce_wpml->multi_currency->currencies ) ) {
				$get_currencies = $woocommerce_wpml->multi_currency->currencies;
				if ( ! empty( $get_currencies ) ) {
					$currencies = [];
					foreach ( $get_currencies as $key => $currency ) {
						$currencies[ $key ] = $key;
					}
					$options = $currencies;
				}
			} elseif ( class_exists( 'WC_Aelia_CurrencySwitcher' ) ) {
				$base_currency  = get_woocommerce_currency();
				$get_currencies = apply_filters( 'wc_aelia_cs_enabled_currencies', $base_currency );

				// Fixed warning with Alia Currency Plugin's initial settings when activated.
				if ( ! empty( $get_currencies ) ) {

					if ( is_array( $get_currencies ) ) {
						$currencies = [];
						foreach ( $get_currencies as $currency ) {
							$currencies[ $currency ] = $currency;
						}
					} elseif ( gettype( $get_currencies ) === 'string' ) {
						$currencies = [
							$get_currencies => $get_currencies,
						];
					} else {
						$currencies = [];
					}


					$options = $currencies;
				}
			} elseif ( class_exists( 'WOOCS' ) ) {
				global $WOOCS;
				$get_currencies = $WOOCS->get_currencies();
				if ( ! empty( $get_currencies ) ) {
					$currencies = [];
					foreach ( $get_currencies as $key => $currency ) {
						$currencies[ $key ] = $key;
					}
					$options = $currencies;
				}
			} elseif ( is_plugin_active( 'currency-switcher-woocommerce/currency-switcher-woocommerce.php' ) ) {

				if ( function_exists( 'alg_get_enabled_currencies' ) ) {
					$currencies = alg_get_enabled_currencies();
					$currencies = array_combine( $currencies, $currencies );

					$options = $currencies;
				}
			} elseif ( is_plugin_active( 'woocommerce-multicurrency/woocommerce-multicurrency.php' ) ) {

				if ( class_exists( 'WOOMC\DAO\Factory' ) ) {
					$currencies = Factory::getDao()->getEnabledCurrencies();
					$currencies = array_combine( $currencies, $currencies );

					$options = $currencies;
				}
			} elseif ( is_plugin_active( 'woo-multi-currency/woo-multi-currency.php' ) || is_plugin_active( 'woocommerce-multi-currency/woocommerce-multi-currency.php' ) ) {
				$settings = get_option( 'woo_multi_currency_params' );
				if ( isset( $settings['currency'] ) ) {
					$currencies = $settings['currency'];
					$currencies = array_combine( $currencies, $currencies );
					$options    = $currencies;
				}
			}
		}

		return $options;
	}

	/**
	 * Get Product Statuses
	 *
	 * @return array
	 */
	public static function post_statuses() {
		return (array) apply_filters( 'woo_feed_product_statuses', get_post_statuses() );
	}

	/**
	 * Get WooCommerce Vendor List for multi-vendor shop
	 *
	 * @return false|WP_User[]|array
	 */
	public static function get_vendors() {
		$users       = [];
		$vendor_role = Helper::get_multi_vendor_user_role();
		if ( ! empty( $vendor_role ) ) {
			/**
			 * Filter Get Vendor (User) Query Args
			 *
			 * @param array $args
			 */
			$args = apply_filters( 'woo_feed_get_vendors_args', [ 'role' => $vendor_role ] );
			if ( is_array( $args ) && ! empty( $args ) ) {
				$users = get_users( $args );
			}
		}


		return apply_filters( 'woo_feed_product_vendors', $users );
	}

	/**
	 * Make DropDown Option from array if needed else return the array.
	 *
	 * @param        $array
	 * @param        $DropDownStatus
	 * @param        $selected
	 * @param string $cache_key
	 * @param bool $cache
	 *
	 * @return array|false|mixed|string|string[]
	 */
	public static function Create_DropDown_IF_Needed( $array, $DropDownStatus, $selected, $cache_key = '', $cache = false ) {

		if ( $DropDownStatus ) {
			return Dropdown::Create( $array, $selected, $cache_key, $cache );
		}

		return $array;
	}

}
