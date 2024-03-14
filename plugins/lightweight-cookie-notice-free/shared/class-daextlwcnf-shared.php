<?php

/*
 * this class should be used to stores properties and methods shared by the
 * admin and public side of wordpress
 */

class Daextlwcnf_Shared {

	//regex
	public $hex_rgb_regex = '/^#(?:[0-9a-fA-F]{3}){1,2}$/';
	public $font_family_regex = '/^([A-Za-z0-9-\'", ]*)$/';

	protected static $instance = null;

	private $data = array();

	private function __construct() {

		add_action( 'daextlwcnf_cron_hook', array( $this, 'daextlwcnf_cron_exec' ) );

		//Set plugin textdomain
		load_plugin_textdomain( 'daextlwcnf', false, 'lightweight-cookie-notice-free/lang/' );

		$this->data['slug'] = 'daextlwcnf';
		$this->data['ver']  = '1.09';
		$this->data['dir']  = substr( plugin_dir_path( __FILE__ ), 0, - 7 );
		$this->data['url']  = substr( plugin_dir_url( __FILE__ ), 0, - 7 );

		//Here are stored the plugin option with the related default values
		$this->data['options'] = [

			//Database Version -----------------------------------------------------------------------------------------
			$this->get( 'slug' ) . "_database_version"                                => "0",

			//General --------------------------------------------------------------------------------------------------
			$this->get( 'slug' ) . '_headings_font_family'                            => "'Open Sans', sans-serif",
			$this->get( 'slug' ) . '_headings_font_weight'                            => "600",
			$this->get( 'slug' ) . '_paragraphs_font_family'                          => "'Open Sans', sans-serif",
			$this->get( 'slug' ) . '_paragraphs_font_weight'                          => "400",
			$this->get( 'slug' ) . '_strong_tags_font_weight'                         => "600",
			$this->get( 'slug' ) . '_buttons_font_family'                             => "'Open Sans', sans-serif",
			$this->get( 'slug' ) . '_buttons_font_weight'                             => "400",
			$this->get( 'slug' ) . '_buttons_border_radius'                           => "4",
			$this->get( 'slug' ) . '_containers_border_radius'                        => "4",

            //Cookie Notice --------------------------------------------------------------------------------------------
			$this->get( 'slug' ) . '_cookie_notice_main_message_text'                 => '<p>' . esc_html__('This site uses cookies to improve your online experience, allow you to share content on social media, measure traffic to this website and display customised ads based on your browsing activity.', 'daextlwcnf') . '</p>',
			$this->get( 'slug' ) . '_cookie_notice_main_message_font_color'           => "#666666",
			$this->get( 'slug' ) . '_cookie_notice_main_message_link_font_color'      => "#1e58b1",
			$this->get( 'slug' ) . '_cookie_notice_button_1_text'                     => esc_html__('Settings', 'daextlwcnf'),
			$this->get( 'slug' ) . '_cookie_notice_button_1_action'                   => "0",
			$this->get( 'slug' ) . '_cookie_notice_button_1_url'                      => "",
			$this->get( 'slug' ) . '_cookie_notice_button_1_background_color'         => "#ffffff",
			$this->get( 'slug' ) . '_cookie_notice_button_1_background_color_hover'   => "#ffffff",
			$this->get( 'slug' ) . '_cookie_notice_button_1_border_color'             => "#1e58b1",
			$this->get( 'slug' ) . '_cookie_notice_button_1_border_color_hover'       => "#1e58b1",
			$this->get( 'slug' ) . '_cookie_notice_button_1_font_color'               => "#1e58b1",
			$this->get( 'slug' ) . '_cookie_notice_button_1_font_color_hover'         => "#1e58b1",
			$this->get( 'slug' ) . '_cookie_notice_button_2_text'                     => esc_html__('Accept', 'daextlwcnf'),
			$this->get( 'slug' ) . '_cookie_notice_button_2_action'                   => "2",
			$this->get( 'slug' ) . '_cookie_notice_button_2_url'                      => "",
			$this->get( 'slug' ) . '_cookie_notice_button_2_background_color'         => "#1e58b1",
			$this->get( 'slug' ) . '_cookie_notice_button_2_background_color_hover'   => "#1e58b1",
			$this->get( 'slug' ) . '_cookie_notice_button_2_border_color'             => "#1e58b1",
			$this->get( 'slug' ) . '_cookie_notice_button_2_border_color_hover'       => "#1e58b1",
			$this->get( 'slug' ) . '_cookie_notice_button_2_font_color'               => "#ffffff",
			$this->get( 'slug' ) . '_cookie_notice_button_2_font_color_hover'         => "#ffffff",
			$this->get( 'slug' ) . '_cookie_notice_button_dismiss_action'             => "0",
			$this->get( 'slug' ) . '_cookie_notice_button_dismiss_url'                => "",
			$this->get( 'slug' ) . '_cookie_notice_button_dismiss_color'              => "#646464",
			$this->get( 'slug' ) . '_cookie_notice_container_position'                => "2",
			$this->get( 'slug' ) . '_cookie_notice_container_width'                   => "1140",
			$this->get( 'slug' ) . '_cookie_notice_container_opacity'                 => "1",
			$this->get( 'slug' ) . '_cookie_notice_container_border_width'            => "0",
			$this->get( 'slug' ) . '_cookie_notice_container_background_color'        => "#ffffff",
			$this->get( 'slug' ) . '_cookie_notice_container_border_color'            => "#e1e1e1",
			$this->get( 'slug' ) . '_cookie_notice_container_border_opacity'          => "1",
			$this->get( 'slug' ) . '_cookie_notice_container_drop_shadow'             => "1",
			$this->get( 'slug' ) . '_cookie_notice_container_drop_shadow_color'       => "#242f42",
			$this->get( 'slug' ) . '_cookie_notice_mask'                              => "1",
			$this->get( 'slug' ) . '_cookie_notice_mask_color'                        => "#242f42",
			$this->get( 'slug' ) . '_cookie_notice_mask_opacity'                      => "0.54",
			$this->get( 'slug' ) . '_cookie_notice_shake_effect'                      => "1",

			//Cookie Settings ------------------------------------------------------------------------------------------
			$this->get( 'slug' ) . '_cookie_settings_logo_url'                        => "",
			$this->get( 'slug' ) . '_cookie_settings_title'                           => esc_html__('Cookie Settings', 'daextlwcnf'),
			$this->get( 'slug' ) . '_cookie_settings_description'                     => '<p>' . esc_html__('We want to be transparent about the data we and our partners collect and how we use it, so you can best exercise control over your personal data. For more information, please see our Privacy Policy.', 'daextlwcnf') . '</p><p><strong>' . esc_html__('Information we collect', 'daextlwcnf') . '</strong></p><p>' . esc_html__('We use this information to improve the performance and experience of our site visitors. This includes improving search results, showing more relevant content and promotional materials, better communication, and improved site performance.', 'daextlwcnf') . '</p>' . '<p><strong>' . esc_html__('Information about cookies', 'daextlwcnf') . '</strong></p><p>' . esc_html__('We use the following essential and non-essential cookies to better improve your overall web browsing experience. Our partners use cookies and other mechanisms to connect you with your social networks and tailor advertising to better match your interests.', 'daextlwcnf') . '</p><p>' . esc_html__('You can make your choices by allowing categories of cookies by using the respective activation switches. Essential cookies cannot be rejected as without them certain core website functionalities would not work.', 'daextlwcnf') . '</p>',
			$this->get( 'slug' ) . '_cookie_settings_button_1_text'                   => esc_html__('Close', 'daextlwcnf'),
			$this->get( 'slug' ) . '_cookie_settings_button_1_action'                 => "2",
			$this->get( 'slug' ) . '_cookie_settings_button_1_url'                    => "",
			$this->get( 'slug' ) . '_cookie_settings_button_1_background_color'       => "#ffffff",
			$this->get( 'slug' ) . '_cookie_settings_button_1_background_color_hover' => "#ffffff",
			$this->get( 'slug' ) . '_cookie_settings_button_1_border_color'           => "#1e58b1",
			$this->get( 'slug' ) . '_cookie_settings_button_1_border_color_hover'     => "#1e58b1",
			$this->get( 'slug' ) . '_cookie_settings_button_1_font_color'             => "#1e58b1",
			$this->get( 'slug' ) . '_cookie_settings_button_1_font_color_hover'       => "#1e58b1",
			$this->get( 'slug' ) . '_cookie_settings_button_2_text'                   => esc_html__('Accept', 'daextlwcnf'),
			$this->get( 'slug' ) . '_cookie_settings_button_2_action'                 => "1",
			$this->get( 'slug' ) . '_cookie_settings_button_2_url'                    => "",
			$this->get( 'slug' ) . '_cookie_settings_button_2_background_color'       => "#1e58b1",
			$this->get( 'slug' ) . '_cookie_settings_button_2_background_color_hover' => "#1e58b1",
			$this->get( 'slug' ) . '_cookie_settings_button_2_border_color'           => "#1e58b1",
			$this->get( 'slug' ) . '_cookie_settings_button_2_border_color_hover'     => "#1e58b1",
			$this->get( 'slug' ) . '_cookie_settings_button_2_font_color'             => "#ffffff",
			$this->get( 'slug' ) . '_cookie_settings_button_2_font_color_hover'       => "#ffffff",
			$this->get( 'slug' ) . '_cookie_settings_headings_font_color'             => "#222222",
			$this->get( 'slug' ) . '_cookie_settings_paragraphs_font_color'           => "#666666",
			$this->get( 'slug' ) . '_cookie_settings_links_font_color'                => "#1e58b1",
			$this->get( 'slug' ) . '_cookie_settings_container_background_color'      => "#ffffff",
			$this->get( 'slug' ) . '_cookie_settings_container_opacity'               => "1.0",
			$this->get( 'slug' ) . '_cookie_settings_container_border_width'          => "0",
			$this->get( 'slug' ) . '_cookie_settings_container_border_color'          => "#e1e1e1",
			$this->get( 'slug' ) . '_cookie_settings_container_border_opacity'        => "1.0",
			$this->get( 'slug' ) . '_cookie_settings_container_drop_shadow'           => "1",
			$this->get( 'slug' ) . '_cookie_settings_container_drop_shadow_color'     => "#242f42",
			$this->get( 'slug' ) . '_cookie_settings_container_highlight_color'       => "#f8f8f8",
			$this->get( 'slug' ) . '_cookie_settings_separator_color'                 => "#e1e1e1",
			$this->get( 'slug' ) . '_cookie_settings_mask'                            => "1",
			$this->get( 'slug' ) . '_cookie_settings_mask_color'                      => "#242f42",
			$this->get( 'slug' ) . '_cookie_settings_mask_opacity'                    => "0.54",

			//Revisit Consent Button
			$this->get( 'slug' ) . '_revisit_consent_button_enable'                   => '0',
			$this->get( 'slug' ) . '_revisit_consent_button_tooltip_text'             => 'Cookie Settings',
			$this->get( 'slug' ) . '_revisit_consent_button_position'                 => 'left',
            $this->get( 'slug' ) . '_revisit_consent_button_background_color'         => '#1e58b1',
			$this->get( 'slug' ) . '_revisit_consent_button_icon_color'               => '#ffffff',

			//Geolocation ----------------------------------------------------------------------------------------------
			$this->get( 'slug' ) . '_enable_geolocation'                              => "0",
			$this->get( 'slug' ) . '_geolocation_service'                             => "0",
			$this->get( 'slug' ) . '_geolocation_locale'                              => [
				'at',
				'be',
				'bg',
				'cy',
				'cz',
				'dk',
				'ee',
				'fi',
				'fr',
				'hu',
				'ie',
				'it',
				'lv',
				'lt',
				'lu',
				'mt',
				'nl',
				'pl',
				'pt',
				'sk',
				'si',
				'es',
				'se',
				'gb'
			],
			$this->get( 'slug' ) . '_maxmind_license_key'                             => "",
			$this->get( 'slug' ) . '_maxmind_database_file_path'                      => $this->get_plugin_upload_path() . $this->generate_random_string_of_characters( 100 ) . '.mmdb',

			//Advanced -------------------------------------------------------------------------------------------------
			$this->get( 'slug' ) . '_assets_mode'                                     => "1",
			$this->get( 'slug' ) . '_test_mode'                                       => "0",
			$this->get( 'slug' ) . '_cookie_expiration'                               => "0",
			$this->get( 'slug' ) . '_reload_page'                                     => "0",
			$this->get( 'slug' ) . '_google_font_url'                                 => "https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap",
			$this->get( 'slug' ) . '_responsive_breakpoint'                           => "700",
			$this->get( 'slug' ) . '_force_css_specificity'                           => "1",
			$this->get( 'slug' ) . '_compress_output'                                 => "1",

		];

		//country list (ISO 3166-1 alpha-2)
		$geolocation_locale                                        = array(
			'Andorra'                                      => 'ad',
			'United Arab Emirates'                         => 'ae',
			'Afghanistan'                                  => 'af',
			'Antigua and Barbuda'                          => 'ag',
			'Anguilla'                                     => 'ai',
			'Albania'                                      => 'al',
			'Armenia'                                      => 'am',
			'Angola'                                       => 'ao',
			'Antartica'                                    => 'aq',
			'Argentina'                                    => 'ar',
			'American Samoa'                               => 'as',
			'Austria'                                      => 'at',
			'Australia'                                    => 'au',
			'Aruba'                                        => 'aw',
			'Åland Islands'                                => 'ax',
			'Azerbaijan'                                   => 'az',
			'Bosnia and Herzegovina'                       => 'ba',
			'Barbados'                                     => 'bb',
			'Bangladesh'                                   => 'bd',
			'Belgium'                                      => 'be',
			'Burkina Faso'                                 => 'bf',
			'Bulgaria'                                     => 'bg',
			'Bahrain'                                      => 'bh',
			'Burundi'                                      => 'bi',
			'Benin'                                        => 'bj',
			'Saint Barthélemy'                             => 'bl',
			'Bermuda'                                      => 'bm',
			'Brunei Darussalam'                            => 'bn',
			'Bolivia'                                      => 'bo',
			'Bonaire, Sint Eustatius and Saba'             => 'bq',
			'Brazil'                                       => 'br',
			'Bahamas'                                      => 'bs',
			'Bhutan'                                       => 'bt',
			'Bouvet Island'                                => 'bv',
			'Botswana'                                     => 'bw',
			'Belarus'                                      => 'by',
			'Belize'                                       => 'bz',
			'Canada'                                       => 'ca',
			'Cocos (Keeling) Islands'                      => 'cc',
			'Congo Democratic Republic'                    => 'cd',
			'Central African Republic'                     => 'cf',
			'Congo'                                        => 'cg',
			'Switzerland'                                  => 'ch',
			'Côte d\'Ivoire'                               => 'ci',
			'Cook Islands'                                 => 'ck',
			'Chile'                                        => 'cl',
			'Cameroon'                                     => 'cm',
			'China'                                        => 'cn',
			'Colombia'                                     => 'co',
			'Costa Rica'                                   => 'cr',
			'Cuba'                                         => 'cu',
			'Cape Verde'                                   => 'cv',
			'Curaçao'                                      => 'cw',
			'Christmas Island'                             => 'cx',
			'Cyprus'                                       => 'cy',
			'Czech Republic'                               => 'cz',
			'Germany'                                      => 'de',
			'Djibouti'                                     => 'dj',
			'Denmark'                                      => 'dk',
			'Dominica'                                     => 'dm',
			'Dominican Republic'                           => 'do',
			'Algeria'                                      => 'dz',
			'Ecuador'                                      => 'ec',
			'Estonia'                                      => 'ee',
			'Egypt'                                        => 'eg',
			'Western Sahara'                               => 'eh',
			'Eritrea'                                      => 'er',
			'Spain'                                        => 'es',
			'Ethiopia'                                     => 'et',
			'Finland'                                      => 'fi',
			'Fiji'                                         => 'fj',
			'Falkland Islands (Malvinas)'                  => 'fk',
			'Micronesia Federated States of'               => 'fm',
			'Faroe Islands'                                => 'fo',
			'France'                                       => 'fr',
			'Gabon'                                        => 'ga',
			'United Kingdom'                               => 'gb',
			'Grenada'                                      => 'gd',
			'Georgia'                                      => 'ge',
			'French Guiana'                                => 'gf',
			'Guernsey'                                     => 'gg',
			'Ghana'                                        => 'gh',
			'Gibraltar'                                    => 'gi',
			'Greenland'                                    => 'gl',
			'Gambia'                                       => 'gm',
			'Guinea'                                       => 'gn',
			'Guadeloupe'                                   => 'gp',
			'Equatorial Guinea'                            => 'gq',
			'Greece'                                       => 'gr',
			'South Georgia and the South Sandwich Islands' => 'gs',
			'Guatemala'                                    => 'gt',
			'Guam'                                         => 'gu',
			'Guinea-Bissau'                                => 'gw',
			'Guyana'                                       => 'gy',
			'Hong Kong'                                    => 'hk',
			'Heard Island and McDonald Islands'            => 'hm',
			'Honduras'                                     => 'hn',
			'Croatia'                                      => 'hr',
			'Haiti'                                        => 'ht',
			'Hungary'                                      => 'hu',
			'Indonesia'                                    => 'id',
			'Ireland'                                      => 'ie',
			'Israel'                                       => 'il',
			'Isle of Man'                                  => 'im',
			'India'                                        => 'in',
			'British Indian Ocean Territory'               => 'io',
			'Iraq'                                         => 'iq',
			'Iran, Islamic Republic of'                    => 'ir',
			'Iceland'                                      => 'is',
			'Italy'                                        => 'it',
			'Jersey'                                       => 'je',
			'Jamaica'                                      => 'jm',
			'Jordan'                                       => 'jo',
			'Japan'                                        => 'jp',
			'Kenya'                                        => 'ke',
			'Kyrgyzstan'                                   => 'kg',
			'Cambodia'                                     => 'kh',
			'Kiribati'                                     => 'ki',
			'Comoros'                                      => 'km',
			'Saint Kitts and Nevis'                        => 'kn',
			'Korea, Democratic People\'s Republic of'      => 'kp',
			'Korea, Republic of'                           => 'kr',
			'Kuwait'                                       => 'kw',
			'Cayman Islands'                               => 'ky',
			'Kazakhstan'                                   => 'kz',
			'Lao People\'s Democratic Republic'            => 'la',
			'Lebanon'                                      => 'la',
			'Saint Lucia'                                  => 'lc',
			'Liechtenstein'                                => 'li',
			'Sri Lanka'                                    => 'lk',
			'Liberia'                                      => 'lr',
			'Lesotho'                                      => 'ls',
			'Lithuania'                                    => 'lt',
			'Luxembourg'                                   => 'lu',
			'Latvia'                                       => 'lv',
			'Libya'                                        => 'ly',
			'Morocco'                                      => 'ma',
			'Monaco'                                       => 'mc',
			'Moldova, Republic of'                         => 'md',
			'Montenegro'                                   => 'me',
			'Saint Martin (French part)'                   => 'mf',
			'Madagascar'                                   => 'mg',
			'Marshall Islands'                             => 'mh',
			'Macedonia, the former Yugoslav Republic of'   => 'mk',
			'Mali'                                         => 'ml',
			'Myanmar'                                      => 'mm',
			'Mongolia'                                     => 'mn',
			'Macao'                                        => 'mo',
			'Northern Mariana Islands'                     => 'mp',
			'Martinique'                                   => 'mq',
			'Mauritania'                                   => 'mr',
			'Montserrat'                                   => 'ms',
			'Malta'                                        => 'mt',
			'Mauritius'                                    => 'mu',
			'Maldives'                                     => 'mv',
			'Malawi'                                       => 'mw',
			'Mexico'                                       => 'mx',
			'Malaysia'                                     => 'my',
			'Mozambique'                                   => 'mz',
			'Namibia'                                      => 'na',
			'New Caledonia'                                => 'nc',
			'Niger'                                        => 'ne',
			'Norfolk Island'                               => 'nf',
			'Nigeria'                                      => 'ng',
			'Nicaragua'                                    => 'ni',
			'Netherlands'                                  => 'nl',
			'Norway'                                       => 'no',
			'Nepal'                                        => 'np',
			'Nauru'                                        => 'nr',
			'Niue'                                         => 'nu',
			'New Zealand'                                  => 'nz',
			'Oman'                                         => 'om',
			'Panama'                                       => 'pa',
			'Peru'                                         => 'pe',
			'French Polynesia'                             => 'pf',
			'Papua New Guinea'                             => 'pg',
			'Philippines'                                  => 'ph',
			'Pakistan'                                     => 'pk',
			'Poland'                                       => 'pl',
			'Saint Pierre and Miquelon'                    => 'pm',
			'Pitcairn'                                     => 'pn',
			'Puerto Rico'                                  => 'pr',
			'Palestine, State of'                          => 'ps',
			'Portugal'                                     => 'pt',
			'Palau'                                        => 'pw',
			'Paraguay'                                     => 'py',
			'Qatar'                                        => 'qa',
			'Réunion'                                      => 're',
			'Romania'                                      => 'ro',
			'Serbia'                                       => 'rs',
			'Russian Federation'                           => 'ru',
			'Rwanda'                                       => 'rw',
			'Saudi Arabia'                                 => 'sa',
			'Solomon Islands'                              => 'sb',
			'Seychelles'                                   => 'sc',
			'Sudan'                                        => 'sd',
			'Sweden'                                       => 'se',
			'Singapore'                                    => 'sg',
			'Saint Helena, Ascension and Tristan da Cunha' => 'sh',
			'Slovenia'                                     => 'si',
			'Svalbard and Jan Mayen'                       => 'sj',
			'Slovakia'                                     => 'sk',
			'Sierra Leone'                                 => 'sl',
			'San Marino'                                   => 'sm',
			'Senegal'                                      => 'sn',
			'Somalia'                                      => 'so',
			'Suriname'                                     => 'sr',
			'South Sudan'                                  => 'ss',
			'Sao Tome and Principe'                        => 'st',
			'El Salvador'                                  => 'sv',
			'Sint Maarten (Dutch part)'                    => 'sx',
			'Syrian Arab Republic'                         => 'sy',
			'Swaziland'                                    => 'sz',
			'Turks and Caicos Islands'                     => 'tc',
			'Chad'                                         => 'td',
			'French Southern Territories'                  => 'tf',
			'Togo'                                         => 'tg',
			'Thailand'                                     => 'th',
			'Tajikistan'                                   => 'tj',
			'Tokelau'                                      => 'tk',
			'Timor-Leste'                                  => 'tl',
			'Turkmenistan'                                 => 'tm',
			'Tunisia'                                      => 'tn',
			'Tonga'                                        => 'to',
			'Turkey'                                       => 'tr',
			'Trinidad and Tobago'                          => 'tt',
			'Tuvalu'                                       => 'tv',
			'Taiwan, Province of China'                    => 'tw',
			'Tanzania, United Republic of'                 => 'tz',
			'Ukraine'                                      => 'ua',
			'Uganda'                                       => 'ug',
			'United States Minor Outlying Islands'         => 'um',
			'United States'                                => 'us',
			'Uruguay'                                      => 'uy',
			'Uzbekistan'                                   => 'uz',
			'Holy See (Vatican City State)'                => 'va',
			'Saint Vincent and the Grenadines'             => 'vc',
			'Venezuela, Bolivarian Republic of'            => 've',
			'Virgin Islands, British'                      => 'vg',
			'Virgin Islands, U.S.'                         => 'vi',
			'Viet Nam'                                     => 'vn',
			'Vanuatu'                                      => 'vu',
			'Wallis and Futuna'                            => 'wf',
			'Samoa'                                        => 'ws',
			'Yemen'                                        => 'ye',
			'Mayotte'                                      => 'yt',
			'South Africa'                                 => 'za',
			'Zambia'                                       => 'zm',
			'Zimbabwe'                                     => 'zw'
		);
		$this->data['options'][ $this->get( 'slug' ) . '_locale' ] = $geolocation_locale;

	}

	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	//retrieve data
	public function get( $index ) {
		return $this->data[ $index ];
	}

	/*
	 * If $needle is present in the $haystack array echos 'selected="selected"'.
	 *
	 * @param $haystack Array
	 * @param $needle String
	 */
	public function selected_array( $array, $needle ) {

		if ( is_array( $array ) and in_array( $needle, $array ) ) {
			return 'selected="selected"';
		}

	}

	/**
	 * Verifies if the provided IP address belongs to a country included in the "Geolocation Locale" option.
	 *
	 * @param $ip_address
	 *
	 * @return bool Returns True if the country associated with the IP address is included in the "Geolocation Locale"
	 * option, otherwise returns false.
	 * @throws \MaxMind\Db\Reader\InvalidDatabaseException
	 */
	public function is_valid_locale_maxmind_geolite2( $ip_address ) {

		$result = false;

		//Create the Reader object
		$file_path = get_option( $this->get( 'slug' ) . '_maxmind_database_file_path' );
		$reader    = new GeoIp2\Database\Reader( $file_path );

		//Get the country
		try {
			$record = $reader->country( $ip_address );
		} catch ( Exception $e ) {
			return null;
		}

		//Get the list of the countries from the "Geolocation Locale" option.
		$country_iso_code     = $record->country->isoCode;
		$geolocation_locale   = get_option( $this->get( 'slug' ) . '_geolocation_locale' );
		$geolocation_locale_a = maybe_unserialize( $geolocation_locale );

		//Verify if the detected locale is present in the list of the countries.
		if ( is_array( $geolocation_locale_a ) ) {
			foreach ( $geolocation_locale_a as $key => $single_geolocation_locale ) {
				if ( mb_strtolower( $single_geolocation_locale ) === mb_strtolower( $country_iso_code ) ) {
					$result = true;
				}
			}
		}

		return $result;

	}

	/**
	 * Get the IP address of the user. If the retrieved IP address is not valid an empty string is returned.
	 *
	 * @return string
	 */
	public function get_ip_address() {

		$ip_address = $_SERVER['REMOTE_ADDR'];

		if ( rest_is_ip_address( $ip_address ) ) {
			return $ip_address;
		} else {
			return '';
		}

	}

	/**
	 * Get the plugin upload path.
	 *
	 * @return string The plugin updload path
	 */
	public function get_plugin_upload_path() {

		$upload_path = WP_CONTENT_DIR . '/uploads/daextlwcnf_uploads/';

		return $upload_path;

	}

	/**
	 * Generates a string of random characters.
	 *
	 * @param int $length The lenght of the returned string.
	 *
	 * @return string A string of random characters.
	 */
	public function generate_random_string_of_characters( $length = 100 ) {
		$characters        = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characters_length = strlen( $characters );
		$random_string     = '';
		for ( $i = 0; $i < $length; $i ++ ) {
			$random_string .= $characters[ rand( 0, $characters_length - 1 ) ];
		}

		return $random_string;
	}

	/**
	 * Update the MaxMind GeoLite2 database.
	 */
	public function daextlwcnf_cron_exec() {

		require_once( $this->get( 'dir' ) . '/admin/inc/class-daextlwcnf-maxmind-integration.php' );
		$this->maxmind_integration = new Daextlwcnf_MaxMind_Integration( $this, true );
		$this->maxmind_integration->update_maxmind_geolite2();

	}

	/**
	 * Filters the provided string with wp_kses() and custom parameters.
	 *
	 * @param $string The string that should be filtered
	 *
	 * @return The filtered string.
	 */
	public function apply_custom_kses( $string ) {

		$allowed_html = array(
			'p'      => array(),
			'a'      => array(
				'href'   => array(),
				'title'  => array(),
				'target' => array(),
				'rel'    => array()
			),
			'strong' => array(),
			'br'     => array(),
			'ol'     => array(),
			'ul'     => array(),
			'li'     => array(),
		);

		return wp_kses( $string, $allowed_html );

	}

	/**
	 * Get the number of seconds associated with the provided identifier of a time period defined with the "Cookie
	 * Expiration" option.
	 *
	 * @param $period
	 *
	 * @return float|int
	 */
	public function get_cookie_expiration_seconds( $period ) {

		switch ( intval( $period, 10 ) ) {

			//Unlimited
			case 0:
				$expiration = 3153600000;
				break;

			//One Hour
			case 1:
				$expiration = 3600;
				break;

			//One Day
			case 2:
				$expiration = 3600 * 24;
				break;

			//One Week
			case 3:
				$expiration = 3600 * 24 * 7;
				break;

			//One Month
			case 4:
				$expiration = 3600 * 24 * 30;
				break;

			//Three Months
			case 5:
				$expiration = 3600 * 2490;
				break;

			//Six Months
			case 6:
				$expiration = 3600 * 24 * 180;
				break;

			//One Year
			case 7:
				$expiration = 3600 * 24 * 365;
				break;

		}

		return $expiration;

	}

	/**
	 * Generates the JavaScript code used to initialize the cookie notice based on the plugin options.
	 */
	public function generate_initialization_script() {

		//Do not proceed if the test mode is enabled and the user is not the administrator
		if ( intval( get_option( $this->get( 'slug' ) . '_test_mode' ), 10 ) === 1 and
		     ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$data = [

			//General ----------------------------------------------------------------------------------------------
			'headings_font_family'                            => get_option( $this->get( 'slug' ) . '_headings_font_family' ),
			'headings_font_weight'                            => get_option( $this->get( 'slug' ) . '_headings_font_weight' ),
			'paragraphs_font_family'                          => get_option( $this->get( 'slug' ) . '_paragraphs_font_family' ),
			'paragraphs_font_weight'                          => get_option( $this->get( 'slug' ) . '_paragraphs_font_weight' ),
			'strong_tags_font_weight'                         => get_option( $this->get( 'slug' ) . '_strong_tags_font_weight' ),
			'buttons_font_family'                             => get_option( $this->get( 'slug' ) . '_buttons_font_family' ),
			'buttons_font_weight'                             => get_option( $this->get( 'slug' ) . '_buttons_font_weight' ),
			'buttons_border_radius'                           => get_option( $this->get( 'slug' ) . '_buttons_border_radius' ),
			'containers_border_radius'                        => get_option( $this->get( 'slug' ) . '_containers_border_radius' ),

			//Cookie Notice ----------------------------------------------------------------------------------------
			'cookie_notice_main_message_text'                 => get_option( $this->get( 'slug' ) . '_cookie_notice_main_message_text' ),
			'cookie_notice_main_message_font_color'           => get_option( $this->get( 'slug' ) . '_cookie_notice_main_message_font_color' ),
			'cookie_notice_main_message_link_font_color'      => get_option( $this->get( 'slug' ) . '_cookie_notice_main_message_link_font_color' ),
			'cookie_notice_button_1_text'                     => get_option( $this->get( 'slug' ) . '_cookie_notice_button_1_text' ),
			'cookie_notice_button_1_action'                   => get_option( $this->get( 'slug' ) . '_cookie_notice_button_1_action' ),
			'cookie_notice_button_1_url'                      => get_option( $this->get( 'slug' ) . '_cookie_notice_button_1_url' ),
			'cookie_notice_button_1_background_color'         => get_option( $this->get( 'slug' ) . '_cookie_notice_button_1_background_color' ),
			'cookie_notice_button_1_background_color_hover'   => get_option( $this->get( 'slug' ) . '_cookie_notice_button_1_background_color_hover' ),
			'cookie_notice_button_1_border_color'             => get_option( $this->get( 'slug' ) . '_cookie_notice_button_1_border_color' ),
			'cookie_notice_button_1_border_color_hover'       => get_option( $this->get( 'slug' ) . '_cookie_notice_button_1_border_color_hover' ),
			'cookie_notice_button_1_font_color'               => get_option( $this->get( 'slug' ) . '_cookie_notice_button_1_font_color' ),
			'cookie_notice_button_1_font_color_hover'         => get_option( $this->get( 'slug' ) . '_cookie_notice_button_1_font_color_hover' ),
			'cookie_notice_button_2_text'                     => get_option( $this->get( 'slug' ) . '_cookie_notice_button_2_text' ),
			'cookie_notice_button_2_action'                   => get_option( $this->get( 'slug' ) . '_cookie_notice_button_2_action' ),
			'cookie_notice_button_2_url'                      => get_option( $this->get( 'slug' ) . '_cookie_notice_button_2_url' ),
			'cookie_notice_button_2_background_color'         => get_option( $this->get( 'slug' ) . '_cookie_notice_button_2_background_color' ),
			'cookie_notice_button_2_background_color_hover'   => get_option( $this->get( 'slug' ) . '_cookie_notice_button_2_background_color_hover' ),
			'cookie_notice_button_2_border_color'             => get_option( $this->get( 'slug' ) . '_cookie_notice_button_2_border_color' ),
			'cookie_notice_button_2_border_color_hover'       => get_option( $this->get( 'slug' ) . '_cookie_notice_button_2_border_color_hover' ),
			'cookie_notice_button_2_font_color'               => get_option( $this->get( 'slug' ) . '_cookie_notice_button_2_font_color' ),
			'cookie_notice_button_2_font_color_hover'         => get_option( $this->get( 'slug' ) . '_cookie_notice_button_2_font_color_hover' ),
			'cookie_notice_button_dismiss_action'             => get_option( $this->get( 'slug' ) . '_cookie_notice_button_dismiss_action' ),
			'cookie_notice_button_dismiss_url'                => get_option( $this->get( 'slug' ) . '_cookie_notice_button_dismiss_url' ),
			'cookie_notice_button_dismiss_color'              => get_option( $this->get( 'slug' ) . '_cookie_notice_button_dismiss_color' ),
			'cookie_notice_container_position'                => get_option( $this->get( 'slug' ) . '_cookie_notice_container_position' ),
			'cookie_notice_container_width'                   => get_option( $this->get( 'slug' ) . '_cookie_notice_container_width' ),
			'cookie_notice_container_opacity'                 => get_option( $this->get( 'slug' ) . '_cookie_notice_container_opacity' ),
			'cookie_notice_container_border_width'            => get_option( $this->get( 'slug' ) . '_cookie_notice_container_border_width' ),
			'cookie_notice_container_background_color'        => get_option( $this->get( 'slug' ) . '_cookie_notice_container_background_color' ),
			'cookie_notice_container_border_color'            => get_option( $this->get( 'slug' ) . '_cookie_notice_container_border_color' ),
			'cookie_notice_container_border_opacity'          => get_option( $this->get( 'slug' ) . '_cookie_notice_container_border_opacity' ),
			'cookie_notice_container_drop_shadow'             => get_option( $this->get( 'slug' ) . '_cookie_notice_container_drop_shadow' ),
			'cookie_notice_container_drop_shadow_color'       => get_option( $this->get( 'slug' ) . '_cookie_notice_container_drop_shadow_color' ),
			'cookie_notice_mask'                              => get_option( $this->get( 'slug' ) . '_cookie_notice_mask' ),
			'cookie_notice_mask_color'                        => get_option( $this->get( 'slug' ) . '_cookie_notice_mask_color' ),
			'cookie_notice_mask_opacity'                      => get_option( $this->get( 'slug' ) . '_cookie_notice_mask_opacity' ),
			'cookie_notice_shake_effect'                      => get_option( $this->get( 'slug' ) . '_cookie_notice_shake_effect' ),

			//Cookie Settings --------------------------------------------------------------------------------------
			'cookie_settings_logo_url'                        => get_option( $this->get( 'slug' ) . '_cookie_settings_logo_url' ),
			'cookie_settings_title'                           => get_option( $this->get( 'slug' ) . '_cookie_settings_title' ),
			'cookie_settings_description'              => get_option( $this->get( 'slug' ) . '_cookie_settings_description' ),
			'cookie_settings_button_1_text'                   => get_option( $this->get( 'slug' ) . '_cookie_settings_button_1_text' ),
			'cookie_settings_button_1_action'                 => get_option( $this->get( 'slug' ) . '_cookie_settings_button_1_action' ),
			'cookie_settings_button_1_url'                    => get_option( $this->get( 'slug' ) . '_cookie_settings_button_1_url' ),
			'cookie_settings_button_1_background_color'       => get_option( $this->get( 'slug' ) . '_cookie_settings_button_1_background_color' ),
			'cookie_settings_button_1_background_color_hover' => get_option( $this->get( 'slug' ) . '_cookie_settings_button_1_background_color_hover' ),
			'cookie_settings_button_1_border_color'           => get_option( $this->get( 'slug' ) . '_cookie_settings_button_1_border_color' ),
			'cookie_settings_button_1_border_color_hover'     => get_option( $this->get( 'slug' ) . '_cookie_settings_button_1_border_color_hover' ),
			'cookie_settings_button_1_font_color'             => get_option( $this->get( 'slug' ) . '_cookie_settings_button_1_font_color' ),
			'cookie_settings_button_1_font_color_hover'       => get_option( $this->get( 'slug' ) . '_cookie_settings_button_1_font_color_hover' ),
			'cookie_settings_button_2_text'                   => get_option( $this->get( 'slug' ) . '_cookie_settings_button_2_text' ),
			'cookie_settings_button_2_action'                 => get_option( $this->get( 'slug' ) . '_cookie_settings_button_2_action' ),
			'cookie_settings_button_2_url'                    => get_option( $this->get( 'slug' ) . '_cookie_settings_button_2_url' ),
			'cookie_settings_button_2_background_color'       => get_option( $this->get( 'slug' ) . '_cookie_settings_button_2_background_color' ),
			'cookie_settings_button_2_background_color_hover' => get_option( $this->get( 'slug' ) . '_cookie_settings_button_2_background_color_hover' ),
			'cookie_settings_button_2_border_color'           => get_option( $this->get( 'slug' ) . '_cookie_settings_button_2_border_color' ),
			'cookie_settings_button_2_border_color_hover'     => get_option( $this->get( 'slug' ) . '_cookie_settings_button_2_border_color_hover' ),
			'cookie_settings_button_2_font_color'             => get_option( $this->get( 'slug' ) . '_cookie_settings_button_2_font_color' ),
			'cookie_settings_button_2_font_color_hover'       => get_option( $this->get( 'slug' ) . '_cookie_settings_button_2_font_color_hover' ),
			'cookie_settings_headings_font_color'             => get_option( $this->get( 'slug' ) . '_cookie_settings_headings_font_color' ),
			'cookie_settings_paragraphs_font_color'           => get_option( $this->get( 'slug' ) . '_cookie_settings_paragraphs_font_color' ),
			'cookie_settings_links_font_color'                => get_option( $this->get( 'slug' ) . '_cookie_settings_links_font_color' ),
			'cookie_settings_container_background_color'      => get_option( $this->get( 'slug' ) . '_cookie_settings_container_background_color' ),
			'cookie_settings_container_opacity'               => get_option( $this->get( 'slug' ) . '_cookie_settings_container_opacity' ),
			'cookie_settings_container_border_width'          => get_option( $this->get( 'slug' ) . '_cookie_settings_container_border_width' ),
			'cookie_settings_container_border_color'          => get_option( $this->get( 'slug' ) . '_cookie_settings_container_border_color' ),
			'cookie_settings_container_border_opacity'        => get_option( $this->get( 'slug' ) . '_cookie_settings_container_border_opacity' ),
			'cookie_settings_container_drop_shadow'           => get_option( $this->get( 'slug' ) . '_cookie_settings_container_drop_shadow' ),
			'cookie_settings_container_drop_shadow_color'     => get_option( $this->get( 'slug' ) . '_cookie_settings_container_drop_shadow_color' ),
			'cookie_settings_container_highlight_color'       => get_option( $this->get( 'slug' ) . '_cookie_settings_container_highlight_color' ),
			'cookie_settings_separator_color'                 => get_option( $this->get( 'slug' ) . '_cookie_settings_separator_color' ),
			'cookie_settings_mask'                            => get_option( $this->get( 'slug' ) . '_cookie_settings_mask' ),
			'cookie_settings_mask_color'                      => get_option( $this->get( 'slug' ) . '_cookie_settings_mask_color' ),
			'cookie_settings_mask_opacity'                    => get_option( $this->get( 'slug' ) . '_cookie_settings_mask_opacity' ),

            //Revisit Consent ------------------------------------------------------------------------------------------
			'revisit_consent_button_enable'                   => get_option( $this->get( 'slug' ) . '_revisit_consent_button_enable' ),
			'revisit_consent_button_tooltip_text'             => get_option( $this->get( 'slug' ) . '_revisit_consent_button_tooltip_text' ),
			'revisit_consent_button_position'                 => get_option( $this->get( 'slug' ) . '_revisit_consent_button_position' ),
			'revisit_consent_button_background_color'         => get_option( $this->get( 'slug' ) . '_revisit_consent_button_background_color' ),
			'revisit_consent_button_icon_color'               => get_option( $this->get( 'slug' ) . '_revisit_consent_button_icon_color' ),

			//Geolocation ------------------------------------------------------------------------------------------
			'enable_geolocation'                              => get_option( $this->get( 'slug' ) . '_enable_geolocation' ),
			'geolocation_service'                             => get_option( $this->get( 'slug' ) . '_geolocation_service' ),
			'geolocation_locale'                              => get_option( $this->get( 'slug' ) . '_geolocation_locale' ),

			//Advanced ---------------------------------------------------------------------------------------------
			'responsive_breakpoint'                           => get_option( $this->get( 'slug' ) . '_responsive_breakpoint' ),
			'cookie_expiration'                               => $this->get_cookie_expiration_seconds( get_option( $this->get( 'slug' ) . '_cookie_expiration' ) ),
			'reload_page'                                     => get_option( $this->get( 'slug' ) . '_reload_page' ),
			'force_css_specificity'                           => get_option( "daextlwcnf_force_css_specificity" ),

		];

		//turn on output buffer
		ob_start();

		?>

	      let daextlwcnfReadyStateCheckInterval = setInterval(function() {

	        if (document.readyState === "complete") {

	          clearInterval(daextlwcnfReadyStateCheckInterval);

	          window.daextlwcnfCookieNotice.initialize({

	            //General ----------------------------------------------------------------------------------------------
	            headingsFontFamily: <?php echo wp_json_encode( $data['headings_font_family'] ); ?>,
	            headingsFontWeight: <?php echo wp_json_encode( $data['headings_font_weight'] ); ?>,
	            paragraphsFontFamily: <?php echo wp_json_encode( $data['paragraphs_font_family'] ); ?>,
	            paragraphsFontWeight: <?php echo wp_json_encode( $data['paragraphs_font_weight'] ); ?>,
	            strongTagsFontWeight: <?php echo wp_json_encode( $data['strong_tags_font_weight'] ); ?>,
	            buttonsFontFamily: <?php echo wp_json_encode( $data['buttons_font_family'] ); ?>,
	            buttonsFontWeight: <?php echo wp_json_encode( $data['buttons_font_weight'] ); ?>,
	            buttonsBorderRadius: <?php echo wp_json_encode( $data['buttons_border_radius'] ); ?>,
	            containersBorderRadius: <?php echo wp_json_encode( $data['containers_border_radius'] ); ?>,

	            //Cookie Notice
	            cookieNoticeMainMessageText: <?php echo wp_json_encode( $data['cookie_notice_main_message_text'] ); ?>,
	            cookieNoticeMainMessageFontColor: <?php echo wp_json_encode( $data['cookie_notice_main_message_font_color'] ); ?>,
	            cookieNoticeMainMessageLinkFontColor: <?php echo wp_json_encode( $data['cookie_notice_main_message_link_font_color'] ); ?>,
	            cookieNoticeButton1Text: <?php echo wp_json_encode( $data['cookie_notice_button_1_text'] ); ?>,
	            cookieNoticeButton1Action: <?php echo wp_json_encode( $data['cookie_notice_button_1_action'] ); ?>,
	            cookieNoticeButton1Url: <?php echo wp_json_encode( $data['cookie_notice_button_1_url'] ); ?>,
	            cookieNoticeButton1BackgroundColor: <?php echo wp_json_encode( $data['cookie_notice_button_1_background_color'] ); ?>,
	            cookieNoticeButton1BackgroundColorHover: <?php echo wp_json_encode( $data['cookie_notice_button_1_background_color_hover'] ); ?>,
	            cookieNoticeButton1BorderColor: <?php echo wp_json_encode( $data['cookie_notice_button_1_border_color'] ); ?>,
	            cookieNoticeButton1BorderColorHover: <?php echo wp_json_encode( $data['cookie_notice_button_1_border_color_hover'] ); ?>,
	            cookieNoticeButton1FontColor: <?php echo wp_json_encode( $data['cookie_notice_button_1_font_color'] ); ?>,
	            cookieNoticeButton1FontColorHover: <?php echo wp_json_encode( $data['cookie_notice_button_1_font_color_hover'] ); ?>,
	            cookieNoticeButton2Text: <?php echo wp_json_encode( $data['cookie_notice_button_2_text'] ); ?>,
	            cookieNoticeButton2Action: <?php echo wp_json_encode( $data['cookie_notice_button_2_action'] ); ?>,
	            cookieNoticeButton2Url: <?php echo wp_json_encode( $data['cookie_notice_button_2_url'] ); ?>,
	            cookieNoticeButton2BackgroundColor: <?php echo wp_json_encode( $data['cookie_notice_button_2_background_color'] ); ?>,
	            cookieNoticeButton2BackgroundColorHover: <?php echo wp_json_encode( $data['cookie_notice_button_2_background_color_hover'] ); ?>,
	            cookieNoticeButton2BorderColor: <?php echo wp_json_encode( $data['cookie_notice_button_2_border_color'] ); ?>,
	            cookieNoticeButton2BorderColorHover: <?php echo wp_json_encode( $data['cookie_notice_button_2_border_color_hover'] ); ?>,
	            cookieNoticeButton2FontColor: <?php echo wp_json_encode( $data['cookie_notice_button_2_font_color'] ); ?>,
	            cookieNoticeButton2FontColorHover: <?php echo wp_json_encode( $data['cookie_notice_button_2_font_color_hover'] ); ?>,
	            cookieNoticeButtonDismissAction: <?php echo wp_json_encode( $data['cookie_notice_button_dismiss_action'] ); ?>,
	            cookieNoticeButtonDismissUrl: <?php echo wp_json_encode( $data['cookie_notice_button_dismiss_url'] ); ?>,
	            cookieNoticeButtonDismissColor: <?php echo wp_json_encode( $data['cookie_notice_button_dismiss_color'] ); ?>,
	            cookieNoticeContainerPosition: <?php echo wp_json_encode( $data['cookie_notice_container_position'] ); ?>,
	            cookieNoticeContainerWidth: <?php echo wp_json_encode( $data['cookie_notice_container_width'] ); ?>,
	            cookieNoticeContainerOpacity: <?php echo wp_json_encode( $data['cookie_notice_container_opacity'] ); ?>,
	            cookieNoticeContainerBorderWidth: <?php echo wp_json_encode( $data['cookie_notice_container_border_width'] ); ?>,
	            cookieNoticeContainerBackgroundColor: <?php echo wp_json_encode( $data['cookie_notice_container_background_color'] ); ?>,
	            cookieNoticeContainerBorderColor: <?php echo wp_json_encode( $data['cookie_notice_container_border_color'] ); ?>,
	            cookieNoticeContainerBorderOpacity: <?php echo wp_json_encode( $data['cookie_notice_container_border_opacity'] ); ?>,
	            cookieNoticeContainerDropShadow: <?php echo wp_json_encode( $data['cookie_notice_container_drop_shadow'] ); ?>,
	            cookieNoticeContainerDropShadowColor: <?php echo wp_json_encode( $data['cookie_notice_container_drop_shadow_color'] ); ?>,
	            cookieNoticeMask: <?php echo wp_json_encode( $data['cookie_notice_mask'] ); ?>,
	            cookieNoticeMaskColor: <?php echo wp_json_encode( $data['cookie_notice_mask_color'] ); ?>,
	            cookieNoticeMaskOpacity: <?php echo wp_json_encode( $data['cookie_notice_mask_opacity'] ); ?>,
	            cookieNoticeShakeEffect: <?php echo wp_json_encode( $data['cookie_notice_shake_effect'] ); ?>,

	            //Cookie Settings --------------------------------------------------------------------------------------
	            cookieSettingsLogoUrl: <?php echo wp_json_encode( $data['cookie_settings_logo_url'] ); ?>,
	            cookieSettingsTitle: <?php echo wp_json_encode( $data['cookie_settings_title'] ); ?>,
	            cookieSettingsDescription: <?php echo wp_json_encode( $data['cookie_settings_description'] ); ?>,
	            cookieSettingsButton1Text: <?php echo wp_json_encode( $data['cookie_settings_button_1_text'] ); ?>,
	            cookieSettingsButton1Action: <?php echo wp_json_encode( $data['cookie_settings_button_1_action'] ); ?>,
	            cookieSettingsButton1Url: <?php echo wp_json_encode( $data['cookie_settings_button_1_url'] ); ?>,
	            cookieSettingsButton1BackgroundColor: <?php echo wp_json_encode( $data['cookie_settings_button_1_background_color'] ); ?>,
	            cookieSettingsButton1BackgroundColorHover: <?php echo wp_json_encode( $data['cookie_settings_button_1_background_color_hover'] ); ?>,
	            cookieSettingsButton1BorderColor: <?php echo wp_json_encode( $data['cookie_settings_button_1_border_color'] ); ?>,
	            cookieSettingsButton1BorderColorHover: <?php echo wp_json_encode( $data['cookie_settings_button_1_border_color_hover'] ); ?>,
	            cookieSettingsButton1FontColor: <?php echo wp_json_encode( $data['cookie_settings_button_1_font_color'] ); ?>,
	            cookieSettingsButton1FontColorHover: <?php echo wp_json_encode( $data['cookie_settings_button_1_font_color_hover'] ); ?>,
	            cookieSettingsButton2Text: <?php echo wp_json_encode( $data['cookie_settings_button_2_text'] ); ?>,
	            cookieSettingsButton2Action: <?php echo wp_json_encode( $data['cookie_settings_button_2_action'] ); ?>,
	            cookieSettingsButton2Url: <?php echo wp_json_encode( $data['cookie_settings_button_2_url'] ); ?>,
	            cookieSettingsButton2BackgroundColor: <?php echo wp_json_encode( $data['cookie_settings_button_2_background_color'] ); ?>,
	            cookieSettingsButton2BackgroundColorHover: <?php echo wp_json_encode( $data['cookie_settings_button_2_background_color_hover'] ); ?>,
	            cookieSettingsButton2BorderColor: <?php echo wp_json_encode( $data['cookie_settings_button_2_border_color'] ); ?>,
	            cookieSettingsButton2BorderColorHover: <?php echo wp_json_encode( $data['cookie_settings_button_2_border_color_hover'] ); ?>,
	            cookieSettingsButton2FontColor: <?php echo wp_json_encode( $data['cookie_settings_button_2_font_color'] ); ?>,
	            cookieSettingsButton2FontColorHover: <?php echo wp_json_encode( $data['cookie_settings_button_2_font_color_hover'] ); ?>,
	            cookieSettingsHeadingsFontColor: <?php echo wp_json_encode( $data['cookie_settings_headings_font_color'] ); ?>,
	            cookieSettingsParagraphsFontColor: <?php echo wp_json_encode( $data['cookie_settings_paragraphs_font_color'] ); ?>,
	            cookieSettingsLinksFontColor: <?php echo wp_json_encode( $data['cookie_settings_links_font_color'] ); ?>,
	            cookieSettingsContainerBackgroundColor: <?php echo wp_json_encode( $data['cookie_settings_container_background_color'] ); ?>,
	            cookieSettingsContainerOpacity: <?php echo wp_json_encode( $data['cookie_settings_container_opacity'] ); ?>,
	            cookieSettingsContainerBorderWidth: <?php echo wp_json_encode( $data['cookie_settings_container_border_width'] ); ?>,
	            cookieSettingsContainerBorderColor: <?php echo wp_json_encode( $data['cookie_settings_container_border_color'] ); ?>,
	            cookieSettingsContainerBorderOpacity: <?php echo wp_json_encode( $data['cookie_settings_container_border_opacity'] ); ?>,
	            cookieSettingsContainerDropShadow: <?php echo wp_json_encode( $data['cookie_settings_container_drop_shadow'] ); ?>,
	            cookieSettingsContainerDropShadowColor: <?php echo wp_json_encode( $data['cookie_settings_container_drop_shadow_color'] ); ?>,
	            cookieSettingsContainerHighlightColor: <?php echo wp_json_encode( $data['cookie_settings_container_highlight_color'] ); ?>,
	            cookieSettingsMask: <?php echo wp_json_encode( $data['cookie_settings_mask'] ); ?>,
	            cookieSettingsMaskColor: <?php echo wp_json_encode( $data['cookie_settings_mask_color'] ); ?>,
	            cookieSettingsMaskOpacity: <?php echo wp_json_encode( $data['cookie_settings_mask_opacity'] ); ?>,
	            cookieSettingsSeparatorColor: <?php echo wp_json_encode( $data['cookie_settings_separator_color'] ); ?>,

                //Revisit Consent ------------------------------------------------------------------------------------------
                revisitConsentButtonEnable: <?php echo wp_json_encode( get_option( $this->get( 'slug' ) . '_revisit_consent_button_enable' ) ); ?>,
                revisitConsentButtonTooltipText: <?php echo wp_json_encode( get_option( $this->get( 'slug' ) . '_revisit_consent_button_tooltip_text' ) ); ?>,
                revisitConsentButtonPosition: <?php echo wp_json_encode( get_option( $this->get( 'slug' ) . '_revisit_consent_button_position' ) ); ?>,
                revisitConsentButtonBackgroundColor: <?php echo wp_json_encode( get_option( $this->get( 'slug' ) . '_revisit_consent_button_background_color' ) ); ?>,
                revisitConsentButtonIconColor: <?php echo wp_json_encode( get_option( $this->get( 'slug' ) . '_revisit_consent_button_icon_color' ) ); ?>,

                //Geolocation ------------------------------------------------------------------------------------------
	            enableGeolocation: <?php echo wp_json_encode( get_option( $this->get( 'slug' ) . '_enable_geolocation' ) ); ?>,
	            geolocationService: <?php echo wp_json_encode( get_option( $this->get( 'slug' ) . '_geolocation_service' ) ); ?>,
	            geolocationLocale: <?php echo wp_json_encode( get_option( $this->get( 'slug' ) . '_geolocation_locale' ) ); ?>,

	            //Advanced ---------------------------------------------------------------------------------------------
	            responsiveBreakpoint: <?php echo wp_json_encode( get_option( $this->get( 'slug' ) . '_responsive_breakpoint' ) ); ?>,
	            cookieExpiration: <?php echo wp_json_encode( $data['cookie_expiration'] ); ?>,
	            reloadPage: <?php echo wp_json_encode( $data['reload_page'] ); ?>,
	            forceCssSpecificity: <?php echo wp_json_encode( $data['force_css_specificity'] ); ?>,

	          });

	        }
	      }, 10);

		<?php

		$out = ob_get_clean();

		//compress javascript if the specific option is enabled
		if ( intval( get_option( $this->get( 'slug' ) . "_compress_output" ), 10 ) === 1 ) {
			$out = \JShrink\Minifier::minify( $out );
		}

		return $out;

	}

}