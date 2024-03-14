<?php

/**
 * @since      1.0.0
 * @package    Simple_Address_Autocomplete
 * @subpackage Simple_Address_Autocomplete/includes
 * @author     Raza Khadim <razakhadim@gmail.com>
 */

class Simple_Address_Autocomplete
{
	protected $loader;
	protected $plugin_name;
	protected $version;


	public function __construct()
	{
		if (defined('SIMPLE_ADDRESS_AUTOCOMPLETE_VERSION')) {
			$this->version = SIMPLE_ADDRESS_AUTOCOMPLETE_VERSION;
		} else {
			$this->version = '1.2.1';
		}
		$this->plugin_name = 'simple-address-autocomplete';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();


		add_action('admin_menu', array($this, 'simple_address_autocomplete_settings_page'));

		// Add Settings and Fields
		add_action('admin_init', array($this, 'simple_address_autocomplete_setup_sections'));
		add_action('admin_init', array($this, 'simple_address_autocomplete_fields'));
	}

	public function simple_address_autocomplete_settings_page()
	{
		// Add the menu item and page
		$page_title = 'Simple Address Autocomplete Settings';
		$menu_title = 'Simple Autocomplete';
		$capability = 'manage_options';
		$slug = 'simple_autocomplete';
		$callback = array($this, 'simple_address_autocomplete_settings_page_content');

		//add under WP Settings
		add_submenu_page('options-general.php', $page_title, $menu_title, $capability, $slug, $callback);
	}

	public function simple_address_autocomplete_settings_page_content()
	{ ?>
		<div class="wrap">
			<h2>Simple Address Autocomplete</h2>
			<form method="POST" action="options.php">
				<?php
				settings_fields('simple_autocomplete');
				do_settings_sections('simple_autocomplete');
				submit_button();
				?>
			</form>
		</div> <?php
			}

			public function simple_address_autocomplete_setup_sections()
			{
				add_settings_section('general_settings', 'General Settings', array($this, 'simple_adddress_auto_complete_section_callback'), 'simple_autocomplete');
				add_settings_section('general_settings_second_section', '', array($this, 'simple_adddress_auto_complete_section_callback'), 'simple_autocomplete');
			}
			public function simple_adddress_auto_complete_section_callback($arguments)
			{
				switch ($arguments['id']) {
					case 'general_settings':
						echo '';
						break;
					case 'general_settings_second_section':
						echo 'Got questions? The <a target="_blank" href="https://saa.khadim.nz/">Knowledge Base</a> explains each of the settings above and answers all the frequently asked questions. <a target="_blank" href="https://wordpress.org/support/plugin/simple-address-autocomplete/"> Get help</a>';
						break;
				}
			}

			public function simple_address_autocomplete_fields()
			{
				$fields = array(

					array(
						'uid' => 'simple_aa_options_google_maps_api_key',
						'label' => 'Google Maps API Key',
						'placeholder' => 'Enter your API key here',
						'section' => 'general_settings',
						'type' => 'password',
						'helper' => '<i><a target="_blank" href="https://saa.khadim.nz/kb/how-to-get-google-maps-api-key"> How to get API key? </a></i>',
						'supplimental' => 'An API Key is required for this plugin to function.',
						'default' => ''
					),
					array(
						'uid' => 'simple_aa_options_field_ids',
						'label' => 'Field IDs',
						'placeholder' => 'Enter field ID, one per line',
						'section' => 'general_settings',
						'type' => 'textarea',
						'helper' => '',
						'supplimental' => 'Enter one field id per line. See <i><a target="_blank" href="https://saa.khadim.nz/kb/how-to-find-the-field-id"> How to find field id? </a></i> if need help locating field id.',
						'default' => ''

					),
					array(
						'uid' => 'simple_aa_options_country',
						'label' => 'Country',
						'section' => 'general_settings',
						'type' => 'multiselect',
						'helper' => '',
						'supplimental' => 'Use CTRL/command + click to select multiple countries. For help see <i><a target="_blank" href="https://saa.khadim.nz/doc/country"> What does it do? </a></i>',
						'options' => array(
							"WW" => "Worldwide",
							"AF" => "Afghanistan",
							"AL" => "Albania",
							"DZ" => "Algeria",
							"AS" => "American Samoa",
							"AD" => "Andorra",
							"AO" => "Angola",
							"AI" => "Anguilla",
							"AQ" => "Antarctica",
							"AG" => "Antigua and Barbuda",
							"AR" => "Argentina",
							"AM" => "Armenia",
							"AW" => "Aruba",
							"AU" => "Australia",
							"AT" => "Austria",
							"AZ" => "Azerbaijan",
							"BS" => "Bahamas",
							"BH" => "Bahrain",
							"BD" => "Bangladesh",
							"BB" => "Barbados",
							"BY" => "Belarus",
							"BE" => "Belgium",
							"BZ" => "Belize",
							"BJ" => "Benin",
							"BM" => "Bermuda",
							"BT" => "Bhutan",
							"BO" => "Bolivia",
							"BA" => "Bosnia and Herzegovina",
							"BW" => "Botswana",
							"BV" => "Bouvet Island",
							"BR" => "Brazil",
							"IO" => "British Indian Ocean Territory",
							"BN" => "Brunei Darussalam",
							"BG" => "Bulgaria",
							"BF" => "Burkina Faso",
							"BI" => "Burundi",
							"KH" => "Cambodia",
							"CM" => "Cameroon",
							"CA" => "Canada",
							"CV" => "Cape Verde",
							"KY" => "Cayman Islands",
							"CF" => "Central African Republic",
							"TD" => "Chad",
							"CL" => "Chile",
							"CN" => "China",
							"CX" => "Christmas Island",
							"CC" => "Cocos (Keeling) Islands",
							"CO" => "Colombia",
							"KM" => "Comoros",
							"CG" => "Congo",
							"CD" => "Congo, the Democratic Republic of the",
							"CK" => "Cook Islands",
							"CR" => "Costa Rica",
							"CI" => "Cote D'Ivoire",
							"HR" => "Croatia",
							"CU" => "Cuba",
							"CY" => "Cyprus",
							"CZ" => "Czech Republic",
							"DK" => "Denmark",
							"DJ" => "Djibouti",
							"DM" => "Dominica",
							"DO" => "Dominican Republic",
							"EC" => "Ecuador",
							"EG" => "Egypt",
							"SV" => "El Salvador",
							"GQ" => "Equatorial Guinea",
							"ER" => "Eritrea",
							"EE" => "Estonia",
							"ET" => "Ethiopia",
							"FK" => "Falkland Islands (Malvinas)",
							"FO" => "Faroe Islands",
							"FJ" => "Fiji",
							"FI" => "Finland",
							"FR" => "France",
							"GF" => "French Guiana",
							"PF" => "French Polynesia",
							"TF" => "French Southern Territories",
							"GA" => "Gabon",
							"GM" => "Gambia",
							"GE" => "Georgia",
							"DE" => "Germany",
							"GH" => "Ghana",
							"GI" => "Gibraltar",
							"GR" => "Greece",
							"GL" => "Greenland",
							"GD" => "Grenada",
							"GP" => "Guadeloupe",
							"GU" => "Guam",
							"GT" => "Guatemala",
							"GN" => "Guinea",
							"GW" => "Guinea-Bissau",
							"GY" => "Guyana",
							"HT" => "Haiti",
							"HM" => "Heard Island and Mcdonald Islands",
							"VA" => "Holy See (Vatican City State)",
							"HN" => "Honduras",
							"HK" => "Hong Kong",
							"HU" => "Hungary",
							"IS" => "Iceland",
							"IN" => "India",
							"ID" => "Indonesia",
							"IR" => "Iran, Islamic Republic of",
							"IQ" => "Iraq",
							"IE" => "Ireland",
							"IL" => "Israel",
							"IT" => "Italy",
							"JM" => "Jamaica",
							"JP" => "Japan",
							"JO" => "Jordan",
							"KZ" => "Kazakhstan",
							"KE" => "Kenya",
							"KI" => "Kiribati",
							"KP" => "Korea, Democratic People's Republic of",
							"KR" => "Korea, Republic of",
							"KW" => "Kuwait",
							"KG" => "Kyrgyzstan",
							"LA" => "Lao People's Democratic Republic",
							"LV" => "Latvia",
							"LB" => "Lebanon",
							"LS" => "Lesotho",
							"LR" => "Liberia",
							"LY" => "Libyan Arab Jamahiriya",
							"LI" => "Liechtenstein",
							"LT" => "Lithuania",
							"LU" => "Luxembourg",
							"MO" => "Macao",
							"MK" => "Macedonia, the Former Yugoslav Republic of",
							"MG" => "Madagascar",
							"MW" => "Malawi",
							"MY" => "Malaysia",
							"MV" => "Maldives",
							"ML" => "Mali",
							"MT" => "Malta",
							"MH" => "Marshall Islands",
							"MQ" => "Martinique",
							"MR" => "Mauritania",
							"MU" => "Mauritius",
							"YT" => "Mayotte",
							"MX" => "Mexico",
							"FM" => "Micronesia, Federated States of",
							"MD" => "Moldova, Republic of",
							"MC" => "Monaco",
							"MN" => "Mongolia",
							"MS" => "Montserrat",
							"MA" => "Morocco",
							"MZ" => "Mozambique",
							"MM" => "Myanmar",
							"NA" => "Namibia",
							"NR" => "Nauru",
							"NP" => "Nepal",
							"NL" => "Netherlands",
							"AN" => "Netherlands Antilles",
							"NC" => "New Caledonia",
							"NZ" => "New Zealand",
							"NI" => "Nicaragua",
							"NE" => "Niger",
							"NG" => "Nigeria",
							"NU" => "Niue",
							"NF" => "Norfolk Island",
							"MP" => "Northern Mariana Islands",
							"NO" => "Norway",
							"OM" => "Oman",
							"PK" => "Pakistan",
							"PW" => "Palau",
							"PS" => "Palestinian Territory, Occupied",
							"PA" => "Panama",
							"PG" => "Papua New Guinea",
							"PY" => "Paraguay",
							"PE" => "Peru",
							"PH" => "Philippines",
							"PN" => "Pitcairn",
							"PL" => "Poland",
							"PT" => "Portugal",
							"PR" => "Puerto Rico",
							"QA" => "Qatar",
							"RE" => "Reunion",
							"RO" => "Romania",
							"RU" => "Russian Federation",
							"RW" => "Rwanda",
							"SH" => "Saint Helena",
							"KN" => "Saint Kitts and Nevis",
							"LC" => "Saint Lucia",
							"PM" => "Saint Pierre and Miquelon",
							"VC" => "Saint Vincent and the Grenadines",
							"WS" => "Samoa",
							"SM" => "San Marino",
							"ST" => "Sao Tome and Principe",
							"SA" => "Saudi Arabia",
							"SN" => "Senegal",
							"CS" => "Serbia and Montenegro",
							"SC" => "Seychelles",
							"SL" => "Sierra Leone",
							"SG" => "Singapore",
							"SK" => "Slovakia",
							"SI" => "Slovenia",
							"SB" => "Solomon Islands",
							"SO" => "Somalia",
							"ZA" => "South Africa",
							"GS" => "South Georgia and the South Sandwich Islands",
							"ES" => "Spain",
							"LK" => "Sri Lanka",
							"SD" => "Sudan",
							"SR" => "Suriname",
							"SJ" => "Svalbard and Jan Mayen",
							"SZ" => "Swaziland",
							"SE" => "Sweden",
							"CH" => "Switzerland",
							"SY" => "Syrian Arab Republic",
							"TW" => "Taiwan, Province of China",
							"TJ" => "Tajikistan",
							"TZ" => "Tanzania, United Republic of",
							"TH" => "Thailand",
							"TL" => "Timor-Leste",
							"TG" => "Togo",
							"TK" => "Tokelau",
							"TO" => "Tonga",
							"TT" => "Trinidad and Tobago",
							"TN" => "Tunisia",
							"TR" => "Turkey",
							"TM" => "Turkmenistan",
							"TC" => "Turks and Caicos Islands",
							"TV" => "Tuvalu",
							"UG" => "Uganda",
							"UA" => "Ukraine",
							"AE" => "United Arab Emirates",
							"GB" => "United Kingdom",
							"US" => "United States",
							"UM" => "United States Minor Outlying Islands",
							"UY" => "Uruguay",
							"UZ" => "Uzbekistan",
							"VU" => "Vanuatu",
							"VE" => "Venezuela",
							"VN" => "Viet Nam",
							"VG" => "Virgin Islands, British",
							"VI" => "Virgin Islands, U.s.",
							"WF" => "Wallis and Futuna",
							"EH" => "Western Sahara",
							"YE" => "Yemen",
							"ZM" => "Zambia",
							"ZW" => "Zimbabwe",
						),
						'default' => array(),
					),
					array(
						'uid' => 'simple_aa_options_bias_coordinates',
						'label' => 'Biased Search Coordinates',
						'placeholder' => '-37.7878809, 175.281788',
						'section' => 'general_settings',
						'type' => 'text',
						'helper' => ' ',
						'supplimental' => 'Enter the area coordinates here. See <i><a target="_blank" href="https://saa.khadim.nz/kb/how-to-get-coordinates"> How to get area coordinates? </a></i> if you need help finding coordinates.',
						'default' => '',
					),

					array(
						'uid' => 'simple_aa_options_restriction_type',
						'label' => 'Restriction Type',
						'placeholder' => ' ',
						'section' => 'general_settings',
						'type' => 'select',
						'helper' => ' ',
						'supplimental' => 'Restricted will limit the search to selected coordinates and will override country selction. Read more about <i><a target="_blank" href="https://saa.khadim.nz/kb/how-to-get-coordinates"> Restriction Type </a></i> here.',
						'options' => array(
							'biased' => 'Biased',
							'restricted' => 'Restricted',
						),
						'default' => 'biased',
					),
				);
				foreach ($fields as $field) {

					add_settings_field($field['uid'], $field['label'], array($this, 'field_callback'), 'simple_autocomplete', $field['section'], $field);
					register_setting('simple_autocomplete', $field['uid']);
				}
			}

			public function field_callback($arguments)
			{

				$value = get_option($arguments['uid']);

				if (!$value) {
					$value = $arguments['default'];
				}

				switch ($arguments['type']) {
					case 'text':
					case 'password':
					case 'number':
						printf('<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value);
						break;
					case 'textarea':
						printf('<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value);
						break;
					case 'select':
					case 'multiselect':
						if (!empty($arguments['options']) && is_array($arguments['options'])) {
							$attributes = '';
							$options_markup = '';
							foreach ($arguments['options'] as $key => $label) {
								$options_markup .= sprintf('<option value="%s" %s>%s</option>', $key, selected(!empty($value) && is_array($value) && in_array($key, $value), true, false), $label);
							}
							if ($arguments['type'] === 'multiselect') {
								$attributes = ' multiple="multiple" ';
							}
							printf('<select name="%1$s[]" id="%1$s" %2$s>%3$s</select>', $arguments['uid'], $attributes, $options_markup);
						}
						break;
				}

				if ($helper = $arguments['helper']) {
					printf('<span class="helper"> %s</span>', $helper);
				}

				if ($supplimental = $arguments['supplimental']) {
					printf('<p class="description">%s</p>', $supplimental);
				}
			}
			/**
			 * Load the required dependencies for this plugin.
			 * @since    1.0.0
			 * @access   private
			 */
			private function load_dependencies()
			{


				require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-simple-address-autocomplete-loader.php';
				require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-simple-address-autocomplete-i18n.php';
				require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-simple-address-autocomplete-admin.php';
				require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-simple-address-autocomplete-public.php';

				$this->loader = new Simple_Address_Autocomplete_Loader();
			}

			/**
			 * Uses the Simple_Address_Autocomplete_i18n class in order to set the domain and to register the hook
			 * with WordPress.
			 *
			 * @since    1.0.0
			 * @access   private
			 */
			private function set_locale()
			{

				$plugin_i18n = new Simple_Address_Autocomplete_i18n();
				$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
			}

			/**
			 * Register all of the hooks related to the admin area functionality
			 * of the plugin.
			 *
			 * @since    1.0.0
			 * @access   private
			 */
			private function define_admin_hooks()
			{

				$plugin_admin = new Simple_Address_Autocomplete_Admin($this->get_plugin_name(), $this->get_version());

				$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
				$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
			}

			/**
			 * Register all of the hooks related to the public-facing functionality
			 * of the plugin.
			 *
			 * @since    1.0.0
			 * @access   private
			 */
			private function define_public_hooks()
			{

				$plugin_public = new Simple_Address_Autocomplete_Public($this->get_plugin_name(), $this->get_version());

				$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
				$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
			}

			/**
			 * Run the loader to execute all of the hooks with WordPress.
			 *
			 * @since    1.0.0
			 */
			public function run()
			{
				$this->loader->run();
			}

			/**
			 * The name of the plugin used to uniquely identify it within the context of
			 * WordPress and to define internationalization functionality.
			 *
			 * @since     1.0.0
			 * @return    string    The name of the plugin.
			 */
			public function get_plugin_name()
			{
				return $this->plugin_name;
			}

			/**
			 * The reference to the class that orchestrates the hooks with the plugin.
			 *
			 * @since     1.0.0
			 * @return    Simple_Address_Autocomplete_Loader    Orchestrates the hooks of the plugin.
			 */
			public function get_loader()
			{
				return $this->loader;
			}

			/**
			 * Retrieve the version number of the plugin.
			 *
			 * @since     1.0.0
			 * @return    string    The version number of the plugin.
			 */
			public function get_version()
			{
				return $this->version;
			}
		}
