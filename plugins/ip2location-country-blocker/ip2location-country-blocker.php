<?php

/**
 * Plugin Name: IP2Location Country Blocker
 * Plugin URI: https://ip2location.com/resources/wordpress-ip2location-country-blocker
 * Description: Block visitors from accessing your website or admin area by their country.
 * Version: 2.34.5
 * Author: IP2Location
 * Author URI: https://www.ip2location.com
 * Text Domain: ip2location-country-blocker.
 */
defined('FS_METHOD') || define('FS_METHOD', 'direct');
defined('IP2LOCATION_DIR') || define('IP2LOCATION_DIR', str_replace(['/', '\\'], \DIRECTORY_SEPARATOR, wp_upload_dir()['basedir']) . \DIRECTORY_SEPARATOR . 'ip2location' . \DIRECTORY_SEPARATOR);
define('IPLCB_ROOT', __DIR__ . \DIRECTORY_SEPARATOR);

// For development usage.
if (isset($_SERVER['DEV_MODE'])) {
	$_SERVER['REMOTE_ADDR'] = '8.8.8.8';
}

require_once IPLCB_ROOT . 'vendor' . \DIRECTORY_SEPARATOR . 'autoload.php';

// Initial IP2LocationCountryBlocker class.
$ip2location_country_blocker = new IP2LocationCountryBlocker();

register_activation_hook(__FILE__, [$ip2location_country_blocker, 'set_defaults']);

add_action('init', [$ip2location_country_blocker, 'check_block'], 1);
add_action('admin_enqueue_scripts', [$ip2location_country_blocker, 'plugin_enqueues']);
add_action('admin_notices', [$ip2location_country_blocker, 'show_notice']);
add_action('wp_ajax_ip2location_country_blocker_update_ip2location_database', [$ip2location_country_blocker, 'update_ip2location_database']);
add_action('wp_ajax_ip2location_country_blocker_update_ip2proxy_database', [$ip2location_country_blocker, 'update_ip2proxy_database']);
add_action('wp_ajax_ip2location_country_blocker_validate_token', [$ip2location_country_blocker, 'validate_token']);
add_action('wp_ajax_ip2location_country_blocker_validate_api_key', [$ip2location_country_blocker, 'validate_api_key']);
add_action('wp_footer', [$ip2location_country_blocker, 'footer']);
add_action('wp_ajax_ip2location_country_blocker_submit_feedback', [$ip2location_country_blocker, 'submit_feedback']);
add_action('admin_footer_text', [$ip2location_country_blocker, 'admin_footer_text']);
add_action('ip2location_country_blocker_hourly_event', [$ip2location_country_blocker, 'hourly_event']);

class IP2LocationCountryBlocker
{
	private $session = [
		'country'     => '??',
		'is_proxy'    => '??',
		'proxy_type'  => '??',
		'lookup_mode' => '??',
		'cache'       => false,
	];

	private $countries = ['AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia (Plurinational State of)', 'BQ' => 'Bonaire, Sint Eustatius and Saba', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'BN' => 'Brunei Darussalam', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'CV' => 'Cabo Verde', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos (Keeling) Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CG' => 'Congo', 'CD' => 'Congo (Democratic Republic of the)', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'CI' => 'Cote D\'ivoire', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CW' => 'Curacao', 'CY' => 'Cyprus', 'CZ' => 'Czechia', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands (Malvinas)', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island and Mcdonald Islands', 'VA' => 'Holy See', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran (Islamic Republic of)', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'KP' => 'Korea (Democratic People\'s Republic of)', 'KR' => 'Korea (Republic of)', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Lao People\'s Democratic Republic', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'North Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia (Federated States of)', 'MD' => 'Moldova (Republic of)', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestine, State of', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russian Federation', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena, Ascension and Tristan da Cunha', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin (French Part)', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent and The Grenadines', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SX' => 'Sint Maarten (Dutch Part)', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia and The South Sandwich Islands', 'SS' => 'South Sudan', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen', 'SZ' => 'Eswatini', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syrian Arab Republic', 'TW' => 'Taiwan (Province of China)', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania, United Republic of', 'TH' => 'Thailand', 'TL' => 'Timor-Leste', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom of Great Britain and Northern Ireland', 'US' => 'United States', 'UM' => 'United States Minor Outlying Islands', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VE' => 'Venezuela (Bolivarian Republic of)', 'VN' => 'Viet Nam', 'VG' => 'Virgin Islands (British)', 'VI' => 'Virgin Islands (U.S.)', 'WF' => 'Wallis and Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe'];

	private $country_groups = [
		'APAC'  => ['AS', 'AU', 'BD', 'BN', 'BT', 'CC', 'CK', 'CN', 'CX', 'FJ', 'FM', 'GN', 'GU', 'HK', 'ID', 'IN', 'JP', 'KH', 'KI', 'KP', 'KR', 'LA', 'LK', 'MH', 'MM', 'MN', 'MO', 'MP', 'MV', 'MY', 'NC', 'NF', 'NP', 'NR', 'NU', 'NZ', 'PF', 'PH', 'PK', 'PN', 'PW', 'RU', 'SB', 'SG', 'TH', 'TK', 'TL', 'TO', 'TV', 'TW', 'VN', 'VU', 'WF', 'WS'],
		'ASEAN' => ['BN', 'CN', 'ID', 'JP', 'KH', 'KR', 'LA', 'MM', 'MY', 'PH', 'SG', 'TH', 'VN'],
		'BRIC'  => ['BR', 'CN', 'IN', 'RU'],
		'BRICS' => ['BR', 'CN', 'IN', 'RU', 'ZA'],
		'EAC'   => ['BI', 'KE', 'RW', 'SD', 'TZ', 'UG'],
		'EFTA'  => ['CH', 'IS', 'LI', 'NO'],
		'EMEA'  => ['AD', 'AE', 'AL', 'AM', 'AO', 'AT', 'AX', 'AZ', 'BA', 'BE', 'BG', 'BH', 'BI', 'BJ', 'BW', 'BY', 'CF', 'CG', 'CH', 'CI', 'CM', 'CV', 'CY', 'CZ', 'DE', 'DJ', 'DK', 'DZ', 'EE', 'EG', 'EH', 'ER', 'ES', 'ET', 'FI', 'FO', 'FR', 'GA', 'GB', 'GE', 'GG', 'GH', 'GI', 'GM', 'GN', 'GR', 'HR', 'HU', 'IE', 'IL', 'IM', 'IQ', 'IR', 'IS', 'IT', 'JE', 'JO', 'KE', 'KM', 'KW', 'KZ', 'LB', 'LI', 'LR', 'LS', 'LT', 'LU', 'LV', 'LY', 'MA', 'MC', 'MD', 'ME', 'MG', 'MK', 'ML', 'MR', 'MT', 'MU', 'MW', 'MZ', 'NA', 'NE', 'NL', 'NO', 'OM', 'PL', 'PT', 'QA', 'RE', 'RS', 'RU', 'RW', 'SA', 'SC', 'SD', 'SE', 'SH', 'SI', 'SK', 'SL', 'SM', 'SN', 'ST', 'SY', 'SZ', 'TD', 'TG', 'TN', 'TR', 'TZ', 'UA', 'UG', 'VA', 'YE', 'YT', 'ZA', 'ZM', 'ZW'],
		'EU'    => ['AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'OM', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK'],
	];

	private $robots = [
		'baidu'      => 'Baidu',
		'bingbot'    => 'Bing',
		'feedburner' => 'FeedBurner',
		'google'     => 'Google',
		'msnbot'     => 'MSN',
		'slurp'      => 'Yahoo',
		'yandex'     => 'Yandex',
	];

	private $proxy_types = [
		'VPN', 'TOR', 'DCH', 'PUB', 'WEB', 'SES',
	];

	private $debug_log = '';

	public function __construct()
	{
		// Set priority
		$this->set_priority();

		// Check for IP2Location BIN directory.
		if (!file_exists(IP2LOCATION_DIR)) {
			wp_mkdir_p(IP2LOCATION_DIR);
		}

		// Check for cache directory.
		if (!file_exists(IP2LOCATION_DIR . 'caches')) {
			wp_mkdir_p(IP2LOCATION_DIR . 'caches');
		}

		$this->debug_log = 'debug_' . hash('sha256', get_option('ip2location_country_blocker_private_key') . get_site_url() . get_option('admin_email')) . '.log';

		add_action('admin_menu', [$this, 'add_admin_menu']);
	}

	public function frontend_page()
	{
		$cache_warning = '';
		if (($name = $this->cache_plugin_detected()) !== false) {
			$cache_warning = '
			<div class="error">
				<p>
					This IP2Location Country Blocker plugin does not work well with the <strong>' . $name . '</strong> cache plugin. To avoid unexpected results, we will strongly recommend you to deactivate the cache plugin.
				</p>
			</div>';
		}

		if (!$this->is_setup_completed()) {
			return $this->settings_page();
		}

		$frontend_status = '';

		$enable_frontend = (isset($_POST['submit']) && isset($_POST['enable_frontend'])) ? 1 : ((isset($_POST['submit']) && !isset($_POST['enable_frontend'])) ? 0 : get_option('ip2location_country_blocker_frontend_enabled'));
		$frontend_block_mode = (isset($_POST['frontend_block_mode'])) ? sanitize_text_field($_POST['frontend_block_mode']) : get_option('ip2location_country_blocker_frontend_block_mode');
		$frontend_ban_list = (isset($_POST['frontend_ban_list'])) ? $this->sanitize_array($_POST['frontend_ban_list']) : (!isset($_POST['submit']) ? get_option('ip2location_country_blocker_frontend_banlist') : '');
		$frontend_ban_list = (!is_array($frontend_ban_list)) ? [$frontend_ban_list] : $frontend_ban_list;
		$frontend_option = (isset($_POST['frontend_option'])) ? sanitize_text_field($_POST['frontend_option']) : get_option('ip2location_country_blocker_frontend_option');
		$frontend_error_page = (isset($_POST['frontend_error_page'])) ? sanitize_text_field($_POST['frontend_error_page']) : get_option('ip2location_country_blocker_frontend_error_page');
		$frontend_auto_block_threshold = (isset($_POST['frontend_auto_block_threshold'])) ? sanitize_text_field($_POST['frontend_auto_block_threshold']) : get_option('ip2location_country_blocker_frontend_auto_block_threshold');
		$frontend_redirect_url = (isset($_POST['frontend_redirect_url'])) ? sanitize_text_field($_POST['frontend_redirect_url']) : get_option('ip2location_country_blocker_frontend_redirect_url');
		$frontend_ip_blacklist = (isset($_POST['frontend_ip_blacklist'])) ? sanitize_text_field($_POST['frontend_ip_blacklist']) : get_option('ip2location_country_blocker_frontend_ip_blacklist');
		$frontend_ip_whitelist = (isset($_POST['frontend_ip_whitelist'])) ? sanitize_text_field($_POST['frontend_ip_whitelist']) : get_option('ip2location_country_blocker_frontend_ip_whitelist');
		$enable_frontend_logged_user_whitelist = (isset($_POST['submit']) && isset($_POST['enable_frontend_logged_user_whitelist'])) ? 1 : ((isset($_POST['submit']) && !isset($_POST['enable_frontend_logged_user_whitelist'])) ? 0 : ((get_option('ip2location_country_blocker_frontend_whitelist_logged_user') !== false) ? get_option('ip2location_country_blocker_frontend_whitelist_logged_user') : 1));
		$frontend_skip_bots = (isset($_POST['submit']) && isset($_POST['frontend_skip_bots'])) ? 1 : ((isset($_POST['submit']) && !isset($_POST['frontend_skip_bots'])) ? 0 : get_option('ip2location_country_blocker_frontend_skip_bots'));
		$frontend_bots_list = (isset($_POST['frontend_bots_list'])) ? $this->sanitize_array($_POST['frontend_bots_list']) : (!isset($_POST['submit']) ? get_option('ip2location_country_blocker_frontend_bots_list') : '');
		$frontend_bots_list = (!is_array($frontend_bots_list)) ? [$frontend_bots_list] : $frontend_bots_list;
		$frontend_block_proxy = (isset($_POST['submit']) && isset($_POST['frontend_block_proxy'])) ? 1 : ((isset($_POST['submit']) && !isset($_POST['frontend_block_proxy'])) ? 0 : get_option('ip2location_country_blocker_frontend_block_proxy'));
		$frontend_block_proxy_type = (isset($_POST['frontend_block_proxy_type'])) ? $this->sanitize_array($_POST['frontend_block_proxy_type']) : get_option('ip2location_country_blocker_frontend_block_proxy_type');

		// Sanitize inputs
		if (!empty($frontend_ip_whitelist)) {
			if (strpos($frontend_ip_whitelist, ';')) {
				$list = [];

				$parts = explode(';', $frontend_ip_whitelist);

				sort($parts);

				foreach ($parts as $part) {
					if (strpos($part, '/') !== false) {
						list($ip, $range) = explode('/', $part);

						if (filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4)) {
							if (!preg_match('/^\d{1,2}$/', $range)) {
								continue;
							} elseif ($range < 1 || $range > 32) {
								continue;
							}
						} elseif (filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6)) {
							if (!preg_match('/^\d{1,3}$/', $range)) {
								continue;
							} elseif ($range < 1 || $range > 128) {
								continue;
							}
						} else {
							continue;
						}
					} elseif (!filter_var(str_replace('*', '0', $part), \FILTER_VALIDATE_IP)) {
						continue;
					}

					$list[] = $part;
				}

				$frontend_ip_whitelist = implode(';', $list);
			} else {
				if (!filter_var(str_replace('*', '0', $frontend_ip_whitelist), \FILTER_VALIDATE_IP)) {
					$frontend_ip_whitelist = '';
				}
			}
		}

		if (!empty($frontend_ip_blacklist)) {
			if (strpos($frontend_ip_blacklist, ';')) {
				$list = [];

				$parts = explode(';', $frontend_ip_blacklist);

				sort($parts);

				foreach ($parts as $part) {
					if (strpos($part, '/') !== false) {
						list($ip, $range) = explode('/', $part);

						if (filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4)) {
							if (!preg_match('/^\d{1,2}$/', $range)) {
								continue;
							} elseif ($range < 1 || $range > 32) {
								continue;
							}
						} elseif (filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6)) {
							if (!preg_match('/^\d{1,3}$/', $range)) {
								continue;
							} elseif ($range < 1 || $range > 128) {
								continue;
							}
						} else {
							continue;
						}
					} elseif (!filter_var(str_replace('*', '0', $part), \FILTER_VALIDATE_IP)) {
						continue;
					}

					$list[] = $part;
				}

				$frontend_ip_blacklist = implode(';', $list);
			} else {
				if (!filter_var(str_replace('*', '0', $frontend_ip_blacklist), \FILTER_VALIDATE_IP, \FILTER_FLAG_NO_PRIV_RANGE | \FILTER_FLAG_NO_RES_RANGE)) {
					$frontend_ip_blacklist = '';
				}
			}
		}

		if (isset($_POST['reset'])) {
			$GLOBALS['wpdb']->query('TRUNCATE TABLE ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_frontend_rate_limit_log');

			$frontend_status = '
				<div class="updated">
					<p>' . __('Frontend auto blacklist log has been reset.', 'ip2location-country-blocker') . '</p>
				</div>';
		}

		if (isset($_POST['submit'])) {
			check_admin_referer('save-frontend');

			if (!isset($_POST['frontend_block_proxy_type'])) {
				$frontend_block_proxy_type = '';
			}

			if (!empty($frontend_auto_block_threshold) && !preg_match('/^[0-9]+$/', $frontend_auto_block_threshold)) {
				$frontend_status = '
				<div class="error">
					<p>' . sprintf(__('%1$sERROR:%2$s Auto block threshold has to be a number.', 'ip2location-country-blocker'), '<strong>', '</strong>') . '</p>
				</div>';
			} elseif ($frontend_option == 2 && !filter_var($frontend_error_page, \FILTER_VALIDATE_URL)) {
				$frontend_status = '
				<div class="error">
					<p>' . sprintf(__('%1$sERROR:%2$s Please choose a custom error page.', 'ip2location-country-blocker'), '<strong>', '</strong>') . '</p>
				</div>';
			} elseif ($frontend_option == 3 && !filter_var($frontend_redirect_url, \FILTER_VALIDATE_URL)) {
				$frontend_status = '
				<div class="error">
					<p>' . sprintf(__('%1$sERROR:%2$s Please provide a valid URL for redirection.', 'ip2location-country-blocker'), '<strong>', '</strong>') . '</p>
				</div>';
			} else {
				// Remove country that existed in group to prevent duplicated lookup.
				$removed_list = [];
				if (($groups = $this->get_group_from_list($frontend_ban_list)) !== false) {
					foreach ($groups as $group) {
						foreach ($frontend_ban_list as $country_code) {
							if ($this->is_in_array($country_code, $this->country_groups[$group])) {
								if (($key = array_search($country_code, $frontend_ban_list)) !== false) {
									$removed_list[] = $this->get_country_name($country_code);
									unset($frontend_ban_list[$key]);
								}
							}
						}
					}
				}

				update_option('ip2location_country_blocker_frontend_enabled', $enable_frontend);
				update_option('ip2location_country_blocker_frontend_block_mode', $frontend_block_mode);
				update_option('ip2location_country_blocker_frontend_banlist', $frontend_ban_list);
				update_option('ip2location_country_blocker_frontend_option', $frontend_option);
				update_option('ip2location_country_blocker_frontend_redirect_url', $frontend_redirect_url);
				update_option('ip2location_country_blocker_frontend_error_page', $frontend_error_page);
				update_option('ip2location_country_blocker_frontend_ip_blacklist', $frontend_ip_blacklist);
				update_option('ip2location_country_blocker_frontend_auto_block_threshold', $frontend_auto_block_threshold);
				update_option('ip2location_country_blocker_frontend_ip_whitelist', $frontend_ip_whitelist);
				update_option('ip2location_country_blocker_frontend_whitelist_logged_user', $enable_frontend_logged_user_whitelist);
				update_option('ip2location_country_blocker_frontend_skip_bots', $frontend_skip_bots);
				update_option('ip2location_country_blocker_frontend_bots_list', $frontend_bots_list);
				update_option('ip2location_country_blocker_frontend_block_proxy', $frontend_block_proxy);
				update_option('ip2location_country_blocker_frontend_block_proxy_type', $frontend_block_proxy_type);

				$frontend_status = '
				<div class="updated">
					<p>' . __('Changes saved.', 'ip2location-country-blocker') . '</p>
					' . ((!empty($removed_list)) ? ('<p>' . implode(', ', $removed_list) . ' has been removed from your list as part of country group.</p>') : '') . '
				</div>';
			}
		}

		if (get_option('ip2location_country_blocker_lookup_mode') == 'bin' && !is_file(IP2LOCATION_DIR . get_option('ip2location_country_blocker_database'))) {
			$frontend_status .= '
			<div class="error">
				<p>' . sprintf(__('%1$sERROR:%2$s Unable to find the IP2Location BIN database! Please %3$sdownload the BIN database%4$s in Settings page.', 'ip2location-country-blocker'), '<strong>', '</strong>', '<a href="#bin_download">', '</a>') . '</p>
			</div>';
		}

		echo '
		<div class="wrap">
			<h1>' . __('Frontend Settings', 'ip2location-country-blocker') . '</h1>
			' . $cache_warning . '
			' . $frontend_status . '

			<form method="post" novalidate="novalidate">
				' . wp_nonce_field('save-frontend') . '
				<div style="margin-top:20px">
					<label for="enable_frontend">
						<input type="checkbox" name="enable_frontend" id="enable_frontend"' . (($enable_frontend) ? ' checked' : '') . '>
						' . __('Enable Frontend Blocking', 'ip2location-country-blocker') . '
					</label>
				</div>

				<div class="postbox" style="margin-top:20px;padding-left:15px;padding-right:15px;padding-bottom:20px;">
				<table class="form-table" style="margin-left:20px;">
					<h2 class="title" style="padding-bottom:5px">' . __('Block By Country', 'ip2location-country-blocker') . '</h2>
					<tr>
						<th scope="row">
							<label>' . __('Block by country', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>' . __('Blocking Mode', 'ip2location-country-blocker') . '</span></legend>
								<label><input type="radio" name="frontend_block_mode" value="1"' . (($frontend_block_mode == 1) ? ' checked' : '') . ' class="input-field" /> ' . __('Block countries listed below.', 'ip2location-country-blocker') . '</label><br />
								<label><input type="radio" name="frontend_block_mode" value="2"' . (($frontend_block_mode == 2) ? ' checked' : '') . ' class="input-field" /> ' . sprintf(__('Block all countries %1$sexcept%2$s countries listed below.', 'ip2location-country-blocker'), '<strong>', '</strong>') . '</label>
							</fieldset>
							<select name="frontend_ban_list[]" id="frontend_ban_list" data-placeholder="' . __('Choose Country...', 'ip2location-country-blocker') . '" multiple="true" class="chosen input-field">';

		foreach ($this->country_groups as $group_name => $countries) {
			echo '
									<option value="' . esc_attr($group_name) . '"' . (($this->is_in_array($group_name, $frontend_ban_list)) ? ' selected' : '') . '> ' . esc_html($group_name) . ' Countries</option>';
		}

		foreach ($this->countries as $country_code => $country_name) {
			echo '
									<option value="' . esc_attr($country_code) . '"' . (($this->is_in_array($country_code, $frontend_ban_list)) ? ' selected' : '') . '> ' . esc_html($country_name) . '</option>';
		}

		echo '
							</select>

							<p>' . sprintf(__('%1$sNote:%2$s For EU, APAC and other country groupings, please visit %3$sGeoDataSource Country Grouping Terminology%4$s for details.', 'ip2location-country-blocker'), '<strong>', '</strong>', '<a href="https://github.com/geodatasource/country-grouping-terminology" target="_blank">', '</a>') . '</p>
						</td>
					</tr>
					</table>
				</div>

				<div class="postbox" style="margin-top:20px;padding-left:15px;padding-right:15px;padding-bottom:20px;">
					<table class="form-table" style="margin-left:20px;">
					<h2 class="title" style="padding-bottom:5px">' . __('Block By Proxy', 'ip2location-country-blocker') . '</h2>
					<tr>
						<th scope="row">
							<label>' . __('Block by proxy IP', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<label for="frontend_block_proxy">
								<input type="checkbox" name="frontend_block_proxy" id="frontend_block_proxy"' . (($frontend_block_proxy) ? ' checked' : '') . ' class="input-field">
								' . __('Block proxy IP.', 'ip2location-country-blocker') . '
								<p class="description">
									' . __('IP2Proxy Lookup Mode is required for this option. You can enable/disable the IP2Proxy Lookup Mode at the Settings tab.', 'ip2location-country-blocker') . '
								</p>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label>' . __('Block by proxy type', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<label for="frontend_block_proxy_type">
								' . __('Block following proxy type.', 'ip2location-country-blocker') . '
							</label>
							<div style="margin-top:10px">
								<select name="frontend_block_proxy_type[]" id="frontend_block_proxy_type" data-placeholder="' . __('Choose Proxy Type...', 'ip2location-country-blocker') . '" multiple="true" class="chosen input-field">';

		foreach ($this->proxy_types as $proxy_type) {
			echo '
										<option value="' . esc_attr($proxy_type) . '"' . (($this->is_in_array($proxy_type, $frontend_block_proxy_type)) ? ' selected' : '') . '> ' . esc_html($proxy_type) . '</option>';
		}

		echo '
								</select>

								<p class="description">
									' . sprintf(__('This feature only works with %1$sIP2Proxy Commercial%2$s database.', 'ip2location-country-blocker'), '<a href="https://www.ip2location.com/database/ip2proxy#wordpress-wzdicb" target="_blank">', '</a>') . '
								</p>
							</div>
						</td>
					</tr>

					</table>
				</div>

				<div class="postbox" style="margin-top:20px;padding-left:15px;padding-right:15px;padding-bottom:20px;">
					<table class="form-table" style="margin-left:20px;">
					<h2 class="title" style="padding-bottom:5px">' . __('Other Settings', 'ip2location-country-blocker') . '</h2>
					<tr>
						<th scope="row">
							<label>' . __('Block by bot', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<label for="frontend_skip_bots">
								<input type="checkbox" name="frontend_skip_bots" id="frontend_skip_bots"' . (($frontend_skip_bots) ? ' checked' : '') . ' class="input-field">
								' . __('Do not block the below bots and crawlers.', 'ip2location-country-blocker') . '
							</label>

							<div style="margin-top:10px;">
								<select name="frontend_bots_list[]" id="frontend_bots_list" data-placeholder="' . __('Choose Robot...', 'ip2location-country-blocker') . '" multiple="true" class="chosen input-field">';

		foreach ($this->robots as $robot_code => $robot_name) {
			echo '
										<option value="' . esc_attr($robot_code) . '"' . (($this->is_in_array($robot_code, $frontend_bots_list)) ? ' selected' : '') . '> ' . esc_html($robot_name) . '</option>';
		}

		echo '
								</select>
							</div>
						</td>
					</tr>


					<tr>
						<th scope="row">
							<label>' . __('Display page when visitor is blocked', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<div style="margin-bottom:10px;">
								<strong>' . __('Show the following page when visitor is blocked.', 'ip2location-country-blocker') . '</strong>
							</div>

							<fieldset>
								<legend class="screen-reader-text"><span>' . __('Error Option', 'ip2location-country-blocker') . '</span></legend>

								<label>
									<input type="radio" name="frontend_option" id="frontend_option_1" value="1"' . (($frontend_option == 1) ? ' checked' : '') . ' class="input-field">
									' . __('Default Error 403 Page', 'ip2location-country-blocker') . '
								</label>
								<br />
								<label>
									<input type="radio" name="frontend_option" id="frontend_option_2" value="2"' . (($frontend_option == 2) ? ' checked' : '') . ' class="input-field">
									' . __('Custom Error Page: ', 'ip2location-country-blocker') . '
									<select name="frontend_error_page" id="frontend_error_page" class="input-field">';

		$pages = get_pages(['post_status' => 'publish,private']);

		foreach ($pages as $page) {
			echo '
										<option value="' . esc_attr($page->guid) . '"' . (($frontend_error_page == $page->guid) ? ' selected' : '') . '>' . esc_html($page->post_title) . '</option>';
		}

		echo '
									</select>
								</label>
								<br />
								<label>
									<input type="radio" name="frontend_option" id="frontend_option_3" value="3"' . (($frontend_option == 3) ? ' checked' : '') . ' class="input-field" />
									' . __('URL: ', 'ip2location-country-blocker') . '
									<input type="text" name="frontend_redirect_url" id="frontend_redirect_url" value="' . esc_attr($frontend_redirect_url) . '" class="regular-text code input-field" />
								</label>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label>' . __('Blacklist IP addresses', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>' . __('Blacklist', 'ip2location-country-blocker') . '</span></legend>
								<input type="text" name="frontend_ip_blacklist" id="frontend_ip_blacklist" value="' . esc_attr($frontend_ip_blacklist) . '" class="regular-text ip-address-list" />
								<p class="description">' . __('Use asterisk (*) for wildcard matching. E.g.: 8.8.8.* will match IP from 8.8.8.0 to 8.8.8.255. CIDR format also supported.', 'ip2location-country-blocker') . '</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label>' . __('Automatic Blacklist', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<input type="text" name="frontend_auto_block_threshold" id="frontend_auto_block_threshold" maxlength="20" placeholder="100" value="' . esc_attr($frontend_auto_block_threshold) . '" class="regular-text code input-field" />
							<a href="javascript:;" id="link-reset">Reset</a>
							<p class="description">' . __('Automatically add client IP into blacklist if client keep hitting front pages more than this threshold within 24 hours.', 'ip2location-country-blocker') . '</p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label>' . __('Whitelist IP addresses', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>Blacklist</span></legend>
								<input type="text" name="frontend_ip_whitelist" id="frontend_ip_whitelist" value="' . esc_attr($frontend_ip_whitelist) . '" class="regular-text ip-address-list" />
								<p class="description">' . __('Use asterisk (*) for wildcard matching. E.g.: 8.8.8.* will match IP from 8.8.8.0 to 8.8.8.255. CIDR format also supported.', 'ip2location-country-blocker') . '</p>
							</fieldset>
						</td>
					</tr>
				</table>
				<label for="enable_frontend_logged_user_whitelist">
					<input type="checkbox" name="enable_frontend_logged_user_whitelist" id="enable_frontend_logged_user_whitelist"' . (($enable_frontend_logged_user_whitelist) ? ' checked' : '') . ' class="input-field">
						' . __('Bypass blocking for logged in user.', 'ip2location-country-blocker') . '
				</label>
				</div>

				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="' . __('Save Changes', 'ip2location-country-blocker') . '" />
				</p>
			</form>

			<div class="clear"></div>
			<input type="hidden" id="support_proxy" value="' . ((get_option('ip2location_country_blocker_px_lookup_mode')) ? 1 : 0) . '">
		</div>';
	}

	public function backend_page()
	{
		$cache_warning = '';
		if (($name = $this->cache_plugin_detected()) !== false) {
			$cache_warning = '
			<div class="error">
				<p>
					' . sprintf(__('This IP2Location Country Blocker plugin does not work well with the %1$s%2$s%3$s cache plugin. To avoid unexpected results, we will strongly recommend you to deactivate the cache plugin.', 'ip2location-country-blocker'), '<strong>', $name, '</strong>') . '
				</p>
			</div>';
		}

		if (!$this->is_setup_completed()) {
			return $this->settings_page();
		}

		$backend_status = '';

		$enable_backend = (isset($_POST['submit']) && isset($_POST['enable_backend'])) ? 1 : ((isset($_POST['submit']) && !isset($_POST['enable_backend'])) ? 0 : get_option('ip2location_country_blocker_backend_enabled'));
		$backend_block_mode = (isset($_POST['backend_block_mode'])) ? sanitize_text_field($_POST['backend_block_mode']) : get_option('ip2location_country_blocker_backend_block_mode');
		$backend_ban_list = (isset($_POST['backend_ban_list'])) ? $this->sanitize_array($_POST['backend_ban_list']) : (!isset($_POST['submit']) ? get_option('ip2location_country_blocker_backend_banlist') : '');
		$backend_ban_list = (!is_array($backend_ban_list)) ? [$backend_ban_list] : $backend_ban_list;
		$backend_option = (isset($_POST['backend_option'])) ? sanitize_text_field($_POST['backend_option']) : get_option('ip2location_country_blocker_backend_option');
		$backend_error_page = (isset($_POST['backend_error_page'])) ? sanitize_text_field($_POST['backend_error_page']) : get_option('ip2location_country_blocker_backend_error_page');
		$backend_redirect_url = (isset($_POST['backend_redirect_url'])) ? sanitize_text_field($_POST['backend_redirect_url']) : get_option('ip2location_country_blocker_backend_redirect_url');
		$bypass_code = (isset($_POST['bypass_code'])) ? sanitize_text_field($_POST['bypass_code']) : get_option('ip2location_country_blocker_bypass_code');
		$backend_ip_blacklist = (isset($_POST['backend_ip_blacklist'])) ? sanitize_text_field($_POST['backend_ip_blacklist']) : get_option('ip2location_country_blocker_backend_ip_blacklist');
		$backend_auto_block_threshold = (isset($_POST['backend_auto_block_threshold'])) ? sanitize_text_field($_POST['backend_auto_block_threshold']) : get_option('ip2location_country_blocker_backend_auto_block_threshold');
		$backend_ip_whitelist = (isset($_POST['backend_ip_whitelist'])) ? sanitize_text_field($_POST['backend_ip_whitelist']) : get_option('ip2location_country_blocker_backend_ip_whitelist');
		$backend_skip_bots = (isset($_POST['submit']) && isset($_POST['backend_skip_bots'])) ? 1 : ((isset($_POST['submit']) && !isset($_POST['backend_skip_bots'])) ? 0 : get_option('ip2location_country_blocker_backend_skip_bots'));
		$backend_bots_list = (isset($_POST['backend_bots_list'])) ? $this->sanitize_array($_POST['backend_bots_list']) : (!isset($_POST['submit']) ? get_option('ip2location_country_blocker_backend_bots_list') : '');
		$backend_bots_list = (!is_array($backend_bots_list)) ? [$backend_bots_list] : $backend_bots_list;
		$backend_block_proxy = (isset($_POST['submit']) && isset($_POST['backend_block_proxy'])) ? 1 : ((isset($_POST['submit']) && !isset($_POST['backend_block_proxy'])) ? 0 : get_option('ip2location_country_blocker_backend_block_proxy'));
		$backend_block_proxy_type = (isset($_POST['backend_block_proxy_type'])) ? $this->sanitize_array($_POST['backend_block_proxy_type']) : get_option('ip2location_country_blocker_backend_block_proxy_type');
		$email_notification = (isset($_POST['email_notification'])) ? sanitize_text_field($_POST['email_notification']) : get_option('ip2location_country_blocker_email_notification');
		$access_email_notification = (isset($_POST['access_email_notification'])) ? sanitize_text_field($_POST['access_email_notification']) : get_option('ip2location_country_blocker_access_email_notification');

		// Sanitize inputs
		if (!empty($backend_ip_whitelist)) {
			if (strpos($backend_ip_whitelist, ';')) {
				$list = [];
				$parts = explode(';', $backend_ip_whitelist);

				sort($parts);

				foreach ($parts as $part) {
					if (strpos($part, '/') !== false) {
						list($ip, $range) = explode('/', $part);

						if (filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4)) {
							if (!preg_match('/^\d{1,2}$/', $range)) {
								continue;
							} elseif ($range < 1 || $range > 32) {
								continue;
							}
						} elseif (filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6)) {
							if (!preg_match('/^\d{1,3}$/', $range)) {
								continue;
							} elseif ($range < 1 || $range > 128) {
								continue;
							}
						} else {
							continue;
						}
					} elseif (!filter_var(str_replace('*', '0', $part), \FILTER_VALIDATE_IP)) {
						continue;
					}

					$list[] = $part;
				}

				$backend_ip_whitelist = implode(';', $list);
			} else {
				if (!filter_var(str_replace('*', '0', $backend_ip_whitelist), \FILTER_VALIDATE_IP, \FILTER_FLAG_NO_PRIV_RANGE | \FILTER_FLAG_NO_RES_RANGE)) {
					$backend_ip_whitelist = '';
				}
			}
		}

		if (!empty($backend_ip_blacklist)) {
			if (strpos($backend_ip_blacklist, ';')) {
				$list = [];
				$parts = explode(';', $backend_ip_blacklist);

				sort($parts);

				foreach ($parts as $part) {
					if (strpos($part, '/') !== false) {
						list($ip, $range) = explode('/', $part);

						if (filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4)) {
							if (!preg_match('/^\d{1,2}$/', $range)) {
								continue;
							} elseif ($range < 1 || $range > 32) {
								continue;
							}
						} elseif (filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6)) {
							if (!preg_match('/^\d{1,3}$/', $range)) {
								continue;
							} elseif ($range < 1 || $range > 128) {
								continue;
							}
						} else {
							continue;
						}
					} elseif (!filter_var(str_replace('*', '0', $part), \FILTER_VALIDATE_IP)) {
						continue;
					}

					$list[] = $part;
				}

				$backend_ip_blacklist = implode(';', $list);
			} else {
				if (!filter_var(str_replace('*', '0', $backend_ip_blacklist), \FILTER_VALIDATE_IP, \FILTER_FLAG_NO_PRIV_RANGE | \FILTER_FLAG_NO_RES_RANGE)) {
					$backend_ip_blacklist = '';
				}
			}
		}

		$result = $this->get_location($this->get_ip());
		$my_country_code = $result['country_code'];
		$my_country_name = $result['country_name'];

		if (isset($_POST['reset'])) {
			$GLOBALS['wpdb']->query('TRUNCATE TABLE ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_backend_rate_limit_log');

			$frontend_status = '
				<div class="updated">
					<p>' . __('Backend auto blacklist log has been reset.', 'ip2location-country-blocker') . '</p>
				</div>';
		}

		if (isset($_POST['submit'])) {
			check_admin_referer('save-backend');

			if (!isset($_POST['backend_block_proxy_type'])) {
				$backend_block_proxy_type = '';
			}

			if (!empty($backend_auto_block_threshold) && !preg_match('/^[0-9]+$/', $backend_auto_block_threshold)) {
				$backend_status = '
				<div class="error">
					<p>' . sprintf(__('%1$sERROR:%2$s Auto block threshold has to be a number.', 'ip2location-country-blocker'), '<strong>', '</strong>') . '</p>
				</div>';
			} elseif ($backend_option == 2 && !filter_var($backend_error_page, \FILTER_VALIDATE_URL)) {
				$backend_status = '
				<div class="error">
					<p>' . sprintf(__('%1$sERROR:%2$s Please choose a custom error page.', 'ip2location-country-blocker'), '<strong>', '</strong>') . '</p>
				</div>';
			} elseif ($backend_option == 3 && !filter_var($backend_redirect_url, \FILTER_VALIDATE_URL)) {
				$backend_status = '
				<div class="error">
					<p>' . sprintf(__('%1$sERROR:%2$s Please provide a valid URL for redirection.', 'ip2location-country-blocker'), '<strong>', '</strong>') . '</p>
				</div>';
			} else {
				// Remove country that existed in group to prevent duplicated lookup.
				$removed_list = [];
				if (($groups = $this->get_group_from_list($backend_ban_list)) !== false) {
					foreach ($groups as $group) {
						foreach ($backend_ban_list as $country_code) {
							if ($this->is_in_array($country_code, $this->country_groups[$group])) {
								if (($key = array_search($country_code, $backend_ban_list)) !== false) {
									$removed_list[] = $this->get_country_name($country_code);
									unset($backend_ban_list[$key]);
								}
							}
						}
					}
				}

				update_option('ip2location_country_blocker_backend_enabled', $enable_backend);
				update_option('ip2location_country_blocker_backend_block_mode', $backend_block_mode);
				update_option('ip2location_country_blocker_backend_banlist', $backend_ban_list);
				update_option('ip2location_country_blocker_backend_option', $backend_option);
				update_option('ip2location_country_blocker_backend_redirect_url', $backend_redirect_url);
				update_option('ip2location_country_blocker_backend_error_page', $backend_error_page);
				update_option('ip2location_country_blocker_bypass_code', $bypass_code);
				update_option('ip2location_country_blocker_backend_ip_blacklist', $backend_ip_blacklist);
				update_option('ip2location_country_blocker_backend_auto_block_threshold', $backend_auto_block_threshold);
				update_option('ip2location_country_blocker_backend_ip_whitelist', $backend_ip_whitelist);
				update_option('ip2location_country_blocker_backend_skip_bots', $backend_skip_bots);
				update_option('ip2location_country_blocker_backend_bots_list', $backend_bots_list);
				update_option('ip2location_country_blocker_backend_block_proxy', $backend_block_proxy);
				update_option('ip2location_country_blocker_backend_block_proxy_type', $backend_block_proxy_type);
				update_option('ip2location_country_blocker_access_email_notification', $access_email_notification);
				update_option('ip2location_country_blocker_email_notification', $email_notification);

				if ($backend_auto_block_threshold) {
					$this->create_table();
				}

				$backend_status = '
				<div class="updated">
					<p>' . __('Changes saved.', 'ip2location-country-blocker') . '</p>
					' . ((!empty($removed_list)) ? ('<p>' . implode(', ', $removed_list) . ' has been removed from your list as part of country group.</p>') : '') . '
				</div>';
			}
		}

		if (get_option('ip2location_country_blocker_lookup_mode') == 'bin' && !is_file(IP2LOCATION_DIR . get_option('ip2location_country_blocker_database'))) {
			$backend_status .= '
			<div class="error">
				<p>' . sprintf(__('%1$sERROR:%2$s Unable to find the IP2Location BIN database! Please download the database at at %3$sIP2Location commercial database%4$s | %5$sIP2Location LITE database (free edition)%6$s.', 'ip2location-country-blocker'), '<strong>', '</strong>', '<a href="https://www.ip2location.com/?r=wordpress" target="_blank">', '</a>', '<a href="https://lite.ip2location.com/?r=wordpress" target="_blank">', '</a>') . '</p>
			</div>';
		}

		echo '
		<div class="wrap">
			<h1>' . __('Backend Settings', 'ip2location-country-blocker') . '</h1>
			' . $cache_warning . '
			' . $backend_status . '

			<form id="form_backend_settings" method="post" novalidate="novalidate">
				' . wp_nonce_field('save-backend') . '
				<input type="hidden" name="my_country_code" id="my_country_code" value="' . esc_attr($my_country_code) . '" />
				<input type="hidden" name="my_country_name" id="my_country_name" value="' . esc_attr($my_country_name) . '" />
				<div style="margin-top:20px;">
					<label for="enable_backend">
						<input type="checkbox" name="enable_backend" id="enable_backend"' . (($enable_backend) ? ' checked' : '') . '>
						' . __('Enable Backend Blocking', 'ip2location-country-blocker') . '
					</label>
				</div>

				<div class="postbox" style="margin-top:20px;padding-left:15px;padding-right:15px;padding-bottom:20px;">
				<table class="form-table" style="margin-left:20px;">
					<h2 class="title" style="padding-bottom:5px">' . __('Block By Country', 'ip2location-country-blocker') . '</h2>
					<tr>
						<th scope="row">
							<label>' . __('Block by country', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>Blocking Mode</span></legend>
								<label><input type="radio" name="backend_block_mode" value="1"' . (($backend_block_mode == 1) ? ' checked' : '') . ' class="input-field" /> ' . __('Block countries listed below.', 'ip2location-country-blocker') . '</label><br />
								<label><input type="radio" name="backend_block_mode" value="2"' . (($backend_block_mode == 2) ? ' checked' : '') . ' class="input-field" /> ' . sprintf(__('Block all countries %1$sexcept%2$s countries listed below.', 'ip2location-country-blocker'), '<strong>', '</strong>') . '</label>
							</fieldset>

							<select name="backend_ban_list[]" id="backend_ban_list" data-placeholder="Choose Country..." multiple="true" class="chosen input-field">';

		foreach ($this->country_groups as $group_name => $countries) {
			echo '
									<option value="' . esc_attr($group_name) . '"' . (($this->is_in_array($group_name, $backend_ban_list)) ? ' selected' : '') . '> ' . esc_html($group_name) . ' Countries</option>';
		}

		foreach ($this->countries as $country_code => $country_name) {
			echo '
									<option value="' . esc_attr($country_code) . '"' . (($this->is_in_array($country_code, $backend_ban_list)) ? ' selected' : '') . '> ' . esc_html($country_name) . '</option>';
		}

		echo '
							</select>

							<p>' . sprintf(__('%1$sNote:%2$s For EU, APAC and other country groupings, please visit %3$sGeoDataSource Country Grouping Terminology%4$s for details.', 'ip2location-country-blocker'), '<strong>', '</strong>', '<a href="https://github.com/geodatasource/country-grouping-terminology" target="_blank">', '</a>') . '</p>
						</td>
					</tr>
				</table>
				</div>

				<div class="postbox" style="margin-top:20px;padding-left:15px;padding-right:15px;padding-bottom:20px;">
				<table class="form-table" style="margin-left:20px;">
					<h2 class="title" style="padding-bottom:5px">' . __('Block By Proxy', 'ip2location-country-blocker') . '</h2>
					<tr>
						<th scope="row">
							<label>' . __('Block by proxy IP', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<label for="backend_block_proxy">
								<input type="checkbox" name="backend_block_proxy" id="backend_block_proxy"' . (($backend_block_proxy) ? ' checked' : '') . ' class="input-field">
								' . __('Block proxy IP.', 'ip2location-country-blocker') . '
							</label>
							<p class="description">
								' . __('IP2Proxy Lookup Mode is required for this option. You can enable/disable the IP2Proxy Lookup Mode at the Settings tab.', 'ip2location-country-blocker') . '
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label>' . __('Block by proxy type', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<label for="backend_block_proxy_type">
								' . __('Block following proxy type.', 'ip2location-country-blocker') . '
							</label>
							<div style="margin-top:10px">
								<select name="backend_block_proxy_type[]" id="backend_block_proxy_type" data-placeholder="Choose Proxy Type..." multiple="true" class="chosen input-field">';

		foreach ($this->proxy_types as $proxy_type) {
			echo '
										<option value="' . esc_attr($proxy_type) . '"' . (($this->is_in_array($proxy_type, $backend_block_proxy_type)) ? ' selected' : '') . '> ' . esc_html($proxy_type) . '</option>';
		}

		echo '
								</select>

								<p class="description">
									' . sprintf(__('This feature only works with %1$sIP2Proxy Commercial%2$s database.', 'ip2location-country-blocker'), '<a href="https://www.ip2location.com/database/ip2proxy#wordpress-wzdicb" target="_blank">', '</a>') . '
								</p>
							</div>
						</td>
					</tr>
				</table>
				</div>

				<div class="postbox" style="margin-top:20px;padding-left:15px;padding-right:15px;padding-bottom:20px;">
				<table class="form-table" style="margin-left:20px;">
					<h2 class="title" style="padding-bottom:5px">' . __('Other Settings', 'ip2location-country-blocker') . '</h2>
					<tr>
						<th scope="row">
							<label>' . __('Block by bot', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<label for="backend_skip_bots">
								<input type="checkbox" name="backend_skip_bots" id="backend_skip_bots"' . (($backend_skip_bots) ? ' checked' : '') . ' class="input-field">
								' . __('Do not block the below bots and crawlers.', 'ip2location-country-blocker') . '
							</label>
							<div style="margin-top:10px">
								<select name="backend_bots_list[]" id="backend_bots_list" data-placeholder="Choose Robot..." multiple="true" class="chosen input-field">';

		foreach ($this->robots as $robot_code => $robot_name) {
			echo '
										<option value="' . esc_attr($robot_code) . '"' . (($this->is_in_array($robot_code, $backend_bots_list)) ? ' selected' : '') . '> ' . esc_html($robot_name) . '</option>';
		}

		echo '
								</select>
							</div>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label>' . __('Display page when visitor is blocked', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<p>
								<strong>' . __('Show the following page when a visitor is blocked.', 'ip2location-country-blocker') . '</strong>
							</p>

							<fieldset>
								<legend class="screen-reader-text"><span>' . __('Error Option', 'ip2location-country-blocker') . '</span></legend>

								<label>
									<input type="radio" name="backend_option" id="backend_option_1" value="1"' . (($backend_option == 1) ? ' checked' : '') . ' class="input-field">
									' . __('Default Error 403 Page', 'ip2location-country-blocker') . '
								</label>
								<br />
								<label>
									<input type="radio" name="backend_option" id="backend_option_2" value="2"' . (($backend_option == 2) ? ' checked' : '') . ' class="input-field">
									' . __('Custom Error Page: ', 'ip2location-country-blocker') . '
									<select name="backend_error_page" id="backend_error_page" class="input-field">';

		$pages = get_pages(['post_status' => 'publish,private']);

		foreach ($pages as $page) {
			echo '
										<option value="' . esc_attr($page->guid) . '"' . (($backend_error_page == $page->guid) ? ' selected' : '') . '>' . esc_html($page->post_title) . '</option>';
		}

		echo '
									</select>
								</label>
								<br />
								<label>
									<input type="radio" name="backend_option" id="backend_option_3" value="3"' . (($backend_option == 3) ? ' checked' : '') . ' class="input-field">
									' . __('URL: ', 'ip2location-country-blocker') . '
									<input type="text" name="backend_redirect_url" id="backend_redirect_url" value="' . esc_attr($backend_redirect_url) . '" class="regular-text code input-field" />
								</label>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label>' . __('Secret code to bypass blocking (Max 20 characters)', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<input type="text" name="bypass_code" id="bypass_code" maxlength="20" value="' . esc_attr($bypass_code) . '" class="regular-text code input-field" />
							<p class="description">
								' . sprintf(__('This is the secret code used to bypass all blockings to backend pages. It take precedence over all block settings configured. To bypass, you just need to append the %1$ssecret_code%2$s parameter with above value to the wp-login.php page. For example, https://www.example.com/wp-login.php%3$s?secret_code=1234567%4$s', 'ip2location-country-blocker'), '<strong>', '</strong>', '<code>', '</code>') . '
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label>' . __('Blacklist IP addresses', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>' . __('Blacklist', 'ip2location-country-blocker') . '</span></legend>
								<input type="text" name="backend_ip_blacklist" id="backend_ip_blacklist" value="' . esc_attr($backend_ip_blacklist) . '" class="regular-text ip-address-list" />
								<p class="description">' . __('Use asterisk (*) for wildcard matching. E.g.: 8.8.8.* will match IP from 8.8.8.0 to 8.8.8.255. CIDR format also supported.', 'ip2location-country-blocker') . '</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label>' . __('Automatic Blacklist', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<input type="text" name="backend_auto_block_threshold" id="backend_auto_block_threshold" maxlength="20" placeholder="100" value="' . esc_attr($backend_auto_block_threshold) . '" class="regular-text code input-field" />
							<a href="javascript:;" id="link-reset">Reset</a>
							<p class="description">' . __('Automatically add client IP into blacklist if client keep hitting admin area more than this threshold within 24 hours.', 'ip2location-country-blocker') . '</p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label>' . __('Whitelist IP addresses', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>' . __('Blacklist', 'ip2location-country-blocker') . '</span></legend>
								<input type="text" name="backend_ip_whitelist" id="backend_ip_whitelist" value="' . esc_attr($backend_ip_whitelist) . '" class="regular-text ip-address-list" />
								<p class="description">' . __('Use asterisk (*) for wildcard matching. E.g.: 8.8.8.* will match IP from 8.8.8.0 to 8.8.8.255. CIDR format also supported.', 'ip2location-country-blocker') . '</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="email_notification">' . __('Email Notification', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<select name="email_notification">
								<option value="none"> ' . __('None', 'ip2location-country-blocker') . '</option>';

		$users = get_users(['role' => 'administrator']);

		foreach ($users as $user) {
			echo '
									<option value="' . esc_attr($user->user_email) . '"' . (($user->user_email == $email_notification) ? ' selected' : '') . '>' . esc_html($user->display_name) . '</option>';
		}

		echo '
							</select>

							<p class="description">
								' . __('Send email notification to selected recipient when a visitor is blocked.', 'ip2location-country-blocker') . '
							</p>
						</td>
					</tr>
				</table>
				</div>

				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="' . __('Save Changes', 'ip2location-country-blocker') . '" />
				</p>
			</form>
			<div class="clear"></div>
			<input type="hidden" id="support_proxy" value="' . ((get_option('ip2location_country_blocker_px_lookup_mode')) ? 1 : 0) . '">
		</div>';
	}

	public function statistics_page()
	{
		if (!$this->is_setup_completed()) {
			return $this->settings_page();
		}

		if (isset($_POST['purge'])) {
			check_admin_referer('purge-logs');

			$GLOBALS['wpdb']->query('TRUNCATE TABLE ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_log');
		}

		// Remove logs older than 30 days.
		$GLOBALS['wpdb']->query('DELETE FROM ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_log WHERE date_created <="' . date('Y-m-d H:i:s', strtotime('-30 days')) . '"');

		// Prepare logs for last 30 days.
		$results = $GLOBALS['wpdb']->get_results('SELECT DATE_FORMAT(date_created, "%Y-%m-%d") AS date, side, COUNT(*) AS total FROM ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_log GROUP BY date, side ORDER BY date', OBJECT);

		$lines = [];
		for ($d = 30; $d > 0; --$d) {
			$lines[date('Y-m-d', strtotime('-' . $d . ' days'))][1] = 0;
			$lines[date('Y-m-d', strtotime('-' . $d . ' days'))][2] = 0;
		}

		foreach ($results as $result) {
			$lines[$result->date][$result->side] = $result->total;
		}

		ksort($lines);

		$labels = [];
		$frontend_access = [];
		$backend_access = [];

		foreach ($lines as $date => $value) {
			$labels[] = $date;
			$frontend_access[] = (isset($value[1])) ? $value[1] : 0;
			$backend_access[] = (isset($value[2])) ? $value[2] : 0;
		}

		$frontends = ['countries' => [], 'colors' => [], 'totals' => []];
		$backends = ['countries' => [], 'colors' => [], 'totals' => []];

		// Prepare blocked countries.
		$results = $GLOBALS['wpdb']->get_results('SELECT side,country_code, COUNT(*) AS total FROM ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_log GROUP BY country_code, side ORDER BY total DESC;', OBJECT);

		foreach ($results as $result) {
			if ($result->side == 1) {
				$frontends['countries'][] = addslashes($this->get_country_name($result->country_code));
				$frontends['colors'][] = 'get_color()';
				$frontends['totals'][] = $result->total;
			} else {
				$backends['countries'][] = addslashes($this->get_country_name($result->country_code));
				$backends['colors'][] = 'get_color()';
				$backends['totals'][] = $result->total;
			}
		}

		// Add index to table id not exist.
		$results = $GLOBALS['wpdb']->get_results('SELECT COUNT(*) AS total FROM information_schema.statistics WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_log" AND INDEX_NAME = "idx_ip_address"', OBJECT);

		if ($results[0]->total == 0) {
			$GLOBALS['wpdb']->query('ALTER TABLE `' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_log` ADD INDEX `idx_ip_address` (`ip_address`);');
		}

		echo '
		<div class="wrap">
			<h1>' . __('Statistics (Past 30 Days)', 'ip2location-country-blocker') . '</h1>

			' . ((get_option('ip2location_country_blocker_log_enabled')) ? '' : '<div class="update-message notice inline notice-warning notice-alt">' . sprintf(__('Visitor log is disabled. Please enable it in %1$sSettings%2$s page to collect statistics data.', 'ip2location-country-blocker'), '<a href="admin.php?page=ip2location-country-blocker-settings">', '</a>') . '</div>') . '

			<p>
				<canvas id="line_chart" style="width:100%;height:400px"></canvas>
			</p>

			<p>
				<div style="float:left;width:400px;margin-right:30px">
					<h3>' . __('Frontend', 'ip2location-country-blocker') . '</h3>';

		if (empty($frontends['countries'])) {
			echo '
						<div style="border:1px solid #E1E1E1;padding:10px;background-color:#fff">' . __('No data available.', 'ip2location-country-blocker') . '</div>';
		} else {
			echo '
						<canvas id="pie_chart_frontend" style="width:100%;height:300px"></canvas>

						<h4>' . __('Top 10 IP Address Blocked', 'ip2location-country-blocker') . '</h4>

						<table class="wp-list-table widefat striped">
							<thead>
								<tr>
									<th>' . __('IP Address', 'ip2location-country-blocker') . '</th>
									<th><div align="center">' . __('Country Code', 'ip2location-country-blocker') . '</div></th>
									<th><div align="right">' . __('Total', 'ip2location-country-blocker') . '</div></th>
								</tr>
							</thead>
							<tbody>';

			$results = $GLOBALS['wpdb']->get_results('SELECT ip_address, country_code, COUNT(*) AS total FROM ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_log WHERE side = "1" GROUP BY ip_address ORDER BY total DESC LIMIT 10;', OBJECT);

			foreach ($results as $result) {
				echo '
									<tr>
										<td>' . esc_html($result->ip_address) . '</td>
										<td align="center">' . esc_html($result->country_code) . '</td>
										<td align="right">' . esc_html($result->total) . '</td>
									</tr>';
			}

			echo '
							</tbody>
						</table>';
		}

		echo '
				</div>

				<div style="float:left;width:400px">
					<h3>' . __('Backend', 'ip2location-country-blocker') . '</h3>';

		if (empty($backends['countries'])) {
			echo '
						<div style="border:1px solid #E1E1E1;padding:10px;background-color:#fff">' . __('No data available.', 'ip2location-country-blocker') . '</div>';
		} else {
			echo '
						<canvas id="pie_chart_backend" style="width:100%;height:300px"></canvas>

						<h4>Top 10 IP Address Blocked</h4>

							<table class="wp-list-table widefat striped">
								<thead>
									<tr>
										<th>' . __('IP Address', 'ip2location-country-blocker') . '</th>
										<th><div align="center">' . __('Country Code', 'ip2location-country-blocker') . '</div></th>
										<th><div align="right">' . __('Total', 'ip2location-country-blocker') . '</div></th>
									</tr>
								</thead>
								<tbody>';

			$results = $GLOBALS['wpdb']->get_results('SELECT ip_address, country_code, COUNT(*) AS total FROM ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_log WHERE side = "2" GROUP BY ip_address ORDER BY total DESC LIMIT 10;', OBJECT);

			foreach ($results as $result) {
				echo '
							<tr>
								<td>' . esc_html($result->ip_address) . '</td>
								<td align="center">' . esc_html($result->country_code) . '</td>
								<td align="right">' . esc_html($result->total) . '</td>
							</tr>';
			}

			echo '
								</tbody>
							</table>';
		}

		echo '
				</div>
			</p>

			<div class="clear"></div>

			<p>
				<form id="form-purge" method="post">
					' . wp_nonce_field('purge-logs') . '
					<input type="hidden" name="purge" value="true">
					<input type="submit" name="submit" id="btn-purge" class="button button-primary" value="' . __('Purge All Logs', 'ip2location-country-blocker') . '" />
				</form>
			</p>
		</div>
		<script>
		jQuery(document).ready(function($){
			function get_color(){
				var r = Math.floor(Math.random() * 200);
				var g = Math.floor(Math.random() * 200);
				var b = Math.floor(Math.random() * 200);

				return \'rgb(\' + r + \', \' + g + \', \' + b + \', 0.4)\';
			}

			var ctx = document.getElementById(\'line_chart\').getContext(\'2d\');
			var line = new Chart(ctx, {
				type: \'line\',
				data: {
					labels: [\'' . implode('\', \'', $labels) . '\'],
					datasets: [{
						label: \'Frontend\',
						data: [' . implode(', ', $frontend_access) . '],
						backgroundColor: get_color()
					}, {
						label: \'Backend\',
						data: [' . implode(', ', $backend_access) . '],
						backgroundColor: get_color()
					}]
				},
				options: {
					title: {
						display: true,
						text: \'Access Blocked\'
					},
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero:true
							}
						}]
					}
				}
			});';

		if (!empty($frontends['countries'])) {
			echo '
				var ctx = document.getElementById(\'pie_chart_frontend\').getContext(\'2d\');
				var pie = new Chart(ctx, {
					type: \'pie\',
					data: {
						labels: [\'' . implode('\', \'', $frontends['countries']) . '\'],
						datasets: [{
							backgroundColor: [' . implode(', ', $frontends['colors']) . '],
							data: [' . implode(', ', $frontends['totals']) . ']
						}]
					},
					options: {
						title: {
							display: true,
							text: \'Access Blocked By Country\'
						}
					}
				});';
		}

		if (!empty($backends['countries'])) {
			echo '
				var ctx = document.getElementById(\'pie_chart_backend\').getContext(\'2d\');
				var pie = new Chart(ctx, {
					type: \'pie\',
					data: {
						labels: [\'' . implode('\', \'', $backends['countries']) . '\'],
						datasets: [{
							backgroundColor: [' . implode(', ', $backends['colors']) . '],
							data: [' . implode(', ', $backends['totals']) . ']
						}]
					},
					options: {
						title: {
							display: true,
							text: \'Access Blocked By Country\'
						}
					}
				});';
		}

		echo '
		});
		</script>';
	}

	public function ip_lookup_page()
	{
		if (!$this->is_setup_completed()) {
			return $this->settings_page();
		}

		$ip_lookup_status = '';

		$ip_address = (isset($_POST['ip_address'])) ? sanitize_text_field($_POST['ip_address']) : $this->get_ip();

		if (isset($_POST['submit'])) {
			check_admin_referer('ip-lookup');

			$this->cache_flush();

			if (!filter_var($ip_address, \FILTER_VALIDATE_IP, \FILTER_FLAG_NO_PRIV_RANGE | \FILTER_FLAG_NO_RES_RANGE)) {
				$ip_lookup_status = '
				<div class="error">
					<p>' . sprintf(__('%1$sERROR:%2$s Please enter an IP address.', 'ip2location-country-blocker'), '<strong>', '</strong>') . '</p>
				</div>';
			} else {
				$result = $this->get_location($ip_address);

				if (empty($result['country_code'])) {
					$ip_lookup_status = '
					<div class="error">
						<p>' . sprintf(__('%1$sERROR:%2$s Unable to lookup IP address %3$s%4$s%5$s.', 'ip2location-country-blocker'), '<strong>', '</strong>', '<strong>', htmlspecialchars($ip_address), '</strong>') . '</p>
					</div>';
				} else {
					$ip_lookup_status = '
					<div class="updated">
						<p>' . sprintf(__('IP address %1$s%2$s%3$s belongs to %4$s%5$s (%6$s)%7$s.', 'ip2location-country-blocker'), '<code>', htmlspecialchars($ip_address), '</code>', '<strong>', $result['country_name'], $result['country_code'], '<strong>') . '</p>
					</div>';

					if (!empty($result['is_proxy'])) {
						$ip_lookup_status .= '
						<div class="updated">
							<p>Proxy: ' . ucwords(strtolower($result['is_proxy'])) . '</p>
						</div>';
					}
				}
			}
		}

		echo '
		<div class="wrap">
			<h1>' . __('IP Lookup', 'ip2location-country-blocker') . '</h1>

			' . $ip_lookup_status . '

			<form method="post" novalidate="novalidate">
			' . wp_nonce_field('ip-lookup') . '
				<table class="form-table">
					<tr>
						<th scope="row"><label for="ip_address">' . __('IP Address', 'ip2location-country-blocker') . '</label></th>
						<td>
							<input name="ip_address" type="text" id="ip_address" value="' . esc_attr($ip_address) . '" class="regular-text" />
							<p class="description">' . __('Enter a valid IP address to lookup for country information.', 'ip2location-country-blocker') . '</p>
						</td>
					</tr>
				</table>

				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="' . __('Lookup', 'ip2location-country-blocker') . '" />
				</p>
			</form>

			<div class="clear"></div>
		</div>';
	}

	public function settings_page()
	{
		$disabled = (!$this->is_setup_completed());

		$settings_status = '';

		$lookup_mode = (isset($_POST['lookup_mode'])) ? sanitize_text_field($_POST['lookup_mode']) : get_option('ip2location_country_blocker_lookup_mode');
		$px_lookup_mode = (isset($_POST['px_lookup_mode'])) ? sanitize_text_field($_POST['px_lookup_mode']) : get_option('ip2location_country_blocker_px_lookup_mode');
		$api_key = (isset($_POST['api_key'])) ? sanitize_text_field($_POST['api_key']) : get_option('ip2location_country_blocker_api_key');
		$px_api_key = (isset($_POST['px_api_key'])) ? sanitize_text_field($_POST['px_api_key']) : get_option('ip2location_country_blocker_px_api_key');
		$download_token = (isset($_POST['download_token'])) ? sanitize_text_field($_POST['download_token']) : get_option('ip2location_country_blocker_token');
		$download_ipv4_only = (isset($_POST['lookup_mode']) && isset($_POST['download_ipv4_only'])) ? 1 : ((isset($_POST['lookup_mode']) && !isset($_POST['download_ipv4_only'])) ? 0 : get_option('ip2location_country_blocker_download_ipv4_only'));
		$detect_forwarder_ip = (isset($_POST['submit']) && isset($_POST['detect_forwarder_ip'])) ? 1 : ((isset($_POST['submit']) && !isset($_POST['detect_forwarder_ip'])) ? 0 : get_option('ip2location_country_blocker_detect_forwarder_ip'));
		$enable_log = (isset($_POST['submit']) && isset($_POST['enable_log'])) ? 1 : ((isset($_POST['submit']) && !isset($_POST['enable_log'])) ? 0 : get_option('ip2location_country_blocker_log_enabled'));
		$enable_debug_log = (isset($_POST['submit']) && isset($_POST['enable_debug_log'])) ? 1 : ((isset($_POST['submit']) && !isset($_POST['enable_debug_log'])) ? 0 : get_option('ip2location_country_blocker_debug_log_enabled'));

		if (isset($_POST['lookup_mode'])) {
			check_admin_referer('settings');

			if ($lookup_mode == 'ws') {
				if (empty($_POST['api_key'])) {
					$settings_status = '
					<div class="error">
						<p>' . sprintf(__('%1$sERROR:%2$s Invalid IP2Location API key.', 'ip2location-country-blocker'), '<strong>', '</strong>') . '</p>
					</div>';
				} else {
					$response = wp_remote_get('https://api.ip2location.io/?' . http_build_query([
						'key' => $api_key,
						'src' => 'wordpress-wzdicb',
					]), ['timeout' => 3]);

					$json = json_decode($response['body']);

					if (isset($json->error)) {
						$response = wp_remote_get('https://api.ip2location.com/v2/?' . http_build_query([
							'key'   => $api_key,
							'check' => 1,
						]), ['timeout' => 3]);

						$json = json_decode($response['body']);

						if (empty($json)) {
							$settings_status = '
							<div class="error">
								<p>' . sprintf(__('%1$sERROR:%2$s Error when accessing IP2Location web service gateway.', 'ip2location-country-blocker'), '<strong>', '</strong>') . '</p>
							</div>';
						} else {
							if (!preg_match('/^[0-9]+$/', $json->response)) {
								$settings_status = '
								<div class="error">
									<p>' . sprintf(__('%1$sERROR:%2$s Invalid IP2Location API key.', 'ip2location-country-blocker'), '<strong>', '</strong>') . '</p>
								</div>';
							} else {
								update_option('ip2location_country_blocker_api_key', $api_key);
							}
						}
					} else {
						if (!preg_match('/^[0-9]+$/', $json->response)) {
							$settings_status = '
							<div class="error">
								<p>' . sprintf(__('%1$sERROR:%2$s Invalid IP2Location API key.', 'ip2location-country-blocker'), '<strong>', '</strong>') . '</p>
							</div>';
						} else {
							update_option('ip2location_country_blocker_api_key', $api_key);
						}
					}
				}
			}

			if ($px_lookup_mode == 'px_ws') {
				if (empty($_POST['px_api_key'])) {
					$settings_status .= '
					<div class="error">
						' . sprintf(__('%1$sERROR:%2$s Invalid IP2Proxy API key.', 'ip2location-country-blocker'), '<strong>', '</strong>') . '</p>
					</div>';
				} else {
					$response = wp_remote_get('https://api.ip2location.io/?' . http_build_query([
						'key' => $px_api_key,
						'src' => 'wordpress-wzdicb',
					]), ['timeout' => 3]);

					$json = json_decode($response['body']);

					if (isset($json->error)) {
						$response = wp_remote_get('https://api.ip2proxy.com/?' . http_build_query([
							'key'   => $px_api_key,
							'check' => 1,
						]), ['timeout' => 3]);

						$json = json_decode($response['body']);

						if (empty($json)) {
							$settings_status = '
							<div class="error">
								<p>' . sprintf(__('%1$sERROR:%2$s Error when accessing IP2Proxy web service gateway.', 'ip2location-country-blocker'), '<strong>', '</strong>') . '</p>
							</div>';
						} else {
							if (!preg_match('/^[0-9]+$/', $json->response)) {
								$settings_status .= '
								<div class="error">
									<p>' . sprintf(__('%1$sERROR:%2$s Invalid IP2Proxy API key.', 'ip2location-country-blocker'), '<strong>', '</strong>') . '</p>
								</div>';
							} else {
								update_option('ip2location_country_blocker_px_api_key', $px_api_key);
							}
						}
					} else {
						if (!preg_match('/^[0-9]+$/', $json->response)) {
							$settings_status .= '
							<div class="error">
								<p>' . sprintf(__('%1$sERROR:%2$s Invalid IP2Proxy API key.', 'ip2location-country-blocker'), '<strong>', '</strong>') . '</p>
							</div>';
						} else {
							update_option('ip2location_country_blocker_px_api_key', $px_api_key);
						}
					}
				}
			}

			if (empty($settings_status)) {
				if ($enable_log) {
					$this->create_table();
				}

				if (!$enable_debug_log) {
					if (file_exists(IPLCB_ROOT . $this->debug_log)) {
						wp_delete_file(IPLCB_ROOT . $this->debug_log);
					}
				} else {
					if (!get_option('ip2location_country_blocker_private_key')) {
						add_option('ip2location_country_blocker_private_key', hash('sha256', microtime(true) . get_site_url() . get_option('admin_email')));
					}
				}

				update_option('ip2location_country_blocker_lookup_mode', $lookup_mode);
				update_option('ip2location_country_blocker_px_lookup_mode', $px_lookup_mode);
				update_option('ip2location_country_blocker_token', $download_token);
				update_option('ip2location_country_blocker_detect_forwarder_ip', $detect_forwarder_ip);
				update_option('ip2location_country_blocker_log_enabled', $enable_log);
				update_option('ip2location_country_blocker_debug_log_enabled', $enable_debug_log);

				$settings_status = '
				<div class="updated">
					<p>' . __('Changes saved.', 'ip2location-country-blocker') . '</p>
				</div>';
			}
		}

		$date = $this->get_database_date();
		$px_date = $this->get_px_database_date();

		echo '
		<div class="wrap">
			<h1>' . __('Settings', 'ip2location-country-blocker') . '</h1>

			' . $settings_status . '

			<form action="' . get_admin_url() . 'admin.php?page=ip2location-country-blocker-settings" method="post" novalidate="novalidate">
				' . wp_nonce_field('settings') . '
				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="lookup_mode">' . __('IP2Location Lookup Mode', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<select name="lookup_mode" id="lookup_mode"' . (($disabled) ? ' disabled' : '') . '>
								<option value="bin"' . (($lookup_mode == 'bin') ? ' selected' : '') . '> ' . __('Local BIN Database', 'ip2location-country-blocker') . '</option>
								<option value="ws"' . (($lookup_mode == 'ws') ? ' selected' : '') . '> ' . __('API Web Service', 'ip2location-country-blocker') . '</option>
							<select>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<div id="bin_database"' . (($lookup_mode == 'ws') ? ' style="display:none"' : '') . '>
								<div class="iplcb-panel">
									<table class="form-table">
										<tr>
											<th scope="row">
												<label for="download_token">' . __('Download Token', 'ip2location-country-blocker') . '</label>
											</th>
											<td>
												<input type="text" name="download_token" id="download_token" value="' . esc_attr($download_token) . '" class="regular-text code input-field"' . (($disabled) ? ' disabled' : '') . ' />
												<p class="description">
													' . __('Enter your IP2Location download token.', 'ip2location-country-blocker') . '
												</p>
											</td>
										</tr>
										<tr>
											<td></td>
											<td>
												<label for="download_ipv4_only">
													<input type="checkbox" name="download_ipv4_only" id="download_ipv4_only" value="true"' . (($download_ipv4_only) ? ' checked' : '') . (($disabled) ? ' disabled' : '') . '> ' . __('Download IPv4 database only', 'ip2location-country-blocker') . '
												</label>

												<p class="description">
													' . __('Download a smaller database which is faster in lookup speed. Perfect for website with only IPv4 supported.', 'ip2location-country-blocker') . '
												</p>
											</td>
										</tr>
										<tr>
											<th scope="row">
												<label>' . __('Database File', 'ip2location-country-blocker') . '</label>
											</th>
											<td>
												<div>' . ((!is_file(IP2LOCATION_DIR . get_option('ip2location_country_blocker_database'))) ? '<span class="dashicons dashicons-warning" title="Database file not found."></span>' : '') . get_option('ip2location_country_blocker_database') . '
												' . ((preg_match('/LITE/', get_option('ip2location_country_blocker_database'))) ? '<p class="description">' . sprintf(__('If you are looking for high accuracy result, you should consider using the commercial version of %1$sIP2Location BIN database%2$s.', 'ip2location-country-blocker'), '<a href="https://www.ip2location.com/database/db1-ip-country#wordpress-wzdicb" target="_blank">', '</a>') . '</p>' : '') . '
											</td>
										</tr>
										<tr>
											<th scope="row">
												<label>' . __('Database Path', 'ip2location-country-blocker') . '</label>
											</th>
											<td>
												<div>' . IP2LOCATION_DIR . '</div>
											</td>
										</tr>
										<tr>
											<th scope="row">
												<label>' . __('Database Date', 'ip2location-country-blocker') . '</label>
											</th>
											<td>
												' . (($date) ?: '-') . '
											</td>
										</tr>
										<tr>
											<td></td>
											<td id="update_status"><td>
										</tr>
										<tr>
											<td></td>
											<td><button id="update_ip2location_database" type="button" class="button button-secondary"' . (($disabled) ? ' disabled' : '') . '>' . __('Update Database', 'ip2location-country-blocker') . '</button></td>
										</tr>
									</table>
								</div>
							</div>
							<div id="api_web_service"' . (($lookup_mode == 'bin') ? ' style="display:none"' : '') . '>
								<div class="iplcb-panel">
									<table class="form-table">';

		$legacyApiIpl = false;

		if (!empty($api_key) && preg_match('/^[0-9A-Z]{10}$/', $api_key)) {
			$response = wp_remote_get('https://api.ip2location.com/v2/?' . http_build_query([
				'key'   => $api_key,
				'check' => 1,
			]), ['timeout' => 3]);

			$json = json_decode($response['body']);

			if (!empty($json)) {
				$legacyApiIpl = true;
			}
		}

		echo '
									<tr>
										<th scope="row">
											<label for="api_key">API Key</label>
										</th>
										<td>
											<input name="api_key" type="text" id="api_key" value="' . esc_attr($api_key) . '" class="regular-text" />';

		if ($legacyApiIpl) {
			echo '
											<strong><em>(Legacy API)</em></strong>';
		}

		echo '
											<p class="description">Your IP2Location <a href="https://www.ip2location.io/pricing" target="_blank">Geolocation</a> API key.</p>
										</td>
									</tr>';

		if ($legacyApiIpl) {
			if (!empty($json)) {
				if (preg_match('/^[0-9]+$/', $json->response)) {
					echo '
									<tr>
										<th scope="row">
											<label for="available_credit">Available Credit</label>
										</th>
										<td>
											' . number_format($json->response, 0, '', ',') . '
										</td>
									</tr>';
				}
			}
		}

		echo '
									</table>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="px_lookup_mode">' . __('IP2Proxy Lookup Mode', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<select name="px_lookup_mode" id="px_lookup_mode"' . (($disabled) ? ' disabled' : '') . '>
								<option value=""' . (($px_lookup_mode == '') ? ' selected' : '') . '> ' . __('Disabled', 'ip2location-country-blocker') . '</option>
								<option value="px_bin"' . (($px_lookup_mode == 'px_bin') ? ' selected' : '') . '> ' . __('Local BIN Database', 'ip2location-country-blocker') . '</option>
								<option value="px_ws"' . (($px_lookup_mode == 'px_ws') ? ' selected' : '') . '> ' . __('API Web Service', 'ip2location-country-blocker') . '</option>
							<select>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<div id="px_bin_database"' . (($px_lookup_mode == 'px_ws' || $px_lookup_mode == '') ? ' style="display:none"' : '') . '>
								<div class="iplcb-panel">
									<table class="form-table">
										<tr>
											<th scope="row">
												<label for="download_token">' . __('Download Token', 'ip2location-country-blocker') . '</label>
											</th>
											<td>
												<input type="text" name="px_download_token" id="px_download_token" value="' . esc_attr($download_token) . '" class="regular-text code input-field"' . (($disabled) ? ' disabled' : '') . ' />
												<p class="description">
													' . __('Enter your IP2Location download token.', 'ip2location-country-blocker') . '
												</p>
											</td>
										</tr>
										<tr>
											<th scope="row">
												<label>Database File</label>
											</th>
											<td>
												<div>' . ((!is_file(IP2LOCATION_DIR . get_option('ip2location_country_blocker_px_database'))) ? '<span class="dashicons dashicons-warning" title="Database file not found."></span>' : '') . get_option('ip2location_country_blocker_px_database') . '
												' . ((preg_match('/LITE/', get_option('ip2location_country_blocker_px_database'))) ? '<p class="description">If you are looking for high accuracy result, you should consider using the commercial version of <a href="https://www.ip2location.com/database/px1-ip-country#wordpress-wzdicb" target="_blank">IP2Proxy BIN database</a></p>' : '') . '
											</td>
										</tr>
										<tr>
											<th scope="row">
												<label>Database Path</label>
											</th>
											<td>
												<div>' . IP2LOCATION_DIR . '</div>
											</td>
										</tr>
										<tr>
											<th scope="row">
												<label>Database Date</label>
											</th>
											<td>
												' . (($px_date) ?: '-') . '
											</td>
										</tr>
										<tr>
											<td></td>
											<td id="px_update_status"><td>
										</tr>
										<tr>
											<td></td>
											<td><button id="update_ip2proxy_database" type="button" class="button button-secondary"' . (($disabled) ? ' disabled' : '') . '>Update Database</button></td>
										</tr>
									</table>
								</div>
							</div>
							<div id="px_api_web_service"' . (($px_lookup_mode == 'px_bin' || $px_lookup_mode == '') ? ' style="display:none"' : '') . '>
								<div class="iplcb-panel">
									<table class="form-table">';

		$legacyApiIpx = false;
		if (!empty($px_api_key)) {
			$response = wp_remote_get('https://api.ip2proxy.com/?' . http_build_query([
				'key'   => $px_api_key,
				'check' => 1,
			]), ['timeout' => 3]);

			$json = json_decode($response['body']);

			if (!empty($json)) {
				$legacyApiIpx = true;
			}
		}

		echo '
									<tr>
										<th scope="row">
											<label for="api_key">API Key</label>
										</th>
										<td>
											<input name="px_api_key" type="text" id="px_api_key" value="' . esc_attr($px_api_key) . '" class="regular-text" />';

		if ($legacyApiIpx) {
			echo ' <strong><i>(legacy API)</i></strong>';
		}

		echo '
											<p class="description">Your IP2Proxy <a href="https://www.ip2location.io/pricing" target="_blank">Web service</a> API key.</p>
										</td>
									</tr>';

		if (!empty($px_api_key)) {
			if (!empty($json)) {
				if (preg_match('/^[0-9]+$/', $json->response)) {
					echo '
									<tr>
										<th scope="row">
											<label for="available_credit">Available Credit</label>
										</th>
										<td>
											' . number_format($json->response, 0, '', ',') . '
										</td>
									</tr>';
				}
			}
		}

		echo '
									</table>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="detect_forwarder_ip">' . __('Detect Forwarder IP', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<label for="detect_forwarder_ip">
								<input type="checkbox" name="detect_forwarder_ip" id="detect_forwarder_ip" value="1"' . (($detect_forwarder_ip == 1) ? ' checked' : '') . ' /> ' . __('Enable', 'ip2location-country-blocker') . '
								<p class="description">
									' . __('Enable this option to try detecting the IP address behind the Forwarder (such as CDN provider).', 'ip2location-country-blocker') . '
								</p>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="enable_log">' . __('Visitor Logs', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<label for="enable_log">
								<input type="checkbox" name="enable_log" id="enable_log" value="1"' . (($enable_log == 1) ? ' checked' : '') . ' /> ' . __('Enable Logging', 'ip2location-country-blocker') . '
								<p class="description">
									' . __('No statistics will be available if this option is disabled.', 'ip2location-country-blocker') . '
								</p>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="enable_debug_log">' . __('Debugging Logs', 'ip2location-country-blocker') . '</label>
						</th>
						<td>
							<label for="enable_debug_log">
								<input type="checkbox" name="enable_debug_log" id="enable_debug_log" value="1"' . (($enable_debug_log == 1) ? ' checked' : '') . ' /> ' . __('Enable Debugging Log', 'ip2location-country-blocker') . '
								<p class="description">
									' . sprintf(__('Debug log will store under %1s.', 'ip2location-country-blocker'), IPLCB_ROOT . $this->debug_log) . '
									<br>
									<strong>For security concerns, please disable this option after completed debugging process.</strong>
								</p>
							</label>
						</td>
					</tr>
				</table>

				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="' . __('Save Changes', 'ip2location-country-blocker') . '" />
				</p>
			</form>

			<div class="clear"></div>
		</div>

		<div id="download-database-modal" class="ip2location-modal">
			<div class="ip2location-modal-content">
				<span class="ip2location-close">&times;</span>

				<h3>Database Download</h3>

				<div id="download_status"></div>
			</div>
		</div>';

		if (!$this->is_setup_completed()) {
			echo '
			<div id="modal-get-started" class="ip2location-modal" style="display:block">
				<div class="ip2location-modal-content">
					<div align="center" style="margin:10px auto;">
						<img src="' . plugins_url('/assets/img/logo.png', __FILE__) . '" width="200" height="24" align="center" alt="IP2Location"><br>
						<img src="' . plugins_url('/assets/img/get-started.png', __FILE__) . '" width="160" height="125" align="center" style="margin-top:5px;" alt="IP2Location Country Blocker">
					</div>
					<p style="margin-top:0;">
						' . sprintf(__('%1$sIP2Location Country Blocker%2$s is a plugin designed to restrict visitors or traffic based on their geolocation determined by their IP address', 'ip2location-country-blocker'), '<strong>', '</strong>') . '
					</p>
					<p>
						' . __('Please follow these steps to complete the setup.', 'ip2location-country-blocker') . '
					</p>';

			if (!extension_loaded('bcmath')) {
				echo '
					<span class="dashicons dashicons-warning"></span> ' . sprintf(__('IP2Location requires %1$s PHP extension enabled. Please enable this extension in your %2$s.', 'ip2location-country-blocker'), '<strong>bcmath</strong>', '<strong>php.ini</strong>') . '
					<p style="text-align:center;margin-top:25px">
						<button class="button button-primary" style="padding:3px 18px;" disabled>' . __('Get Started', 'ip2location-country-blocker') . '</button>
					</p>';
			} else {
				echo '
					<p style="text-align:center;margin-top:25px">
						<button class="button button-primary" id="btn-get-started" style="padding:3px 18px;">' . __('Get Started', 'ip2location-country-blocker') . '</button>
					</p>';
			}

			echo '
				</div>
			</div>

			<div id="modal-step-1" class="ip2location-modal">
				<div class="ip2location-modal-content">
					<div class="ip2location-sel-form">
						<div class="ip2location-sel-con">
							<h1 style="line-height:1.2;font-size:23px;text-align:center;margin-bottom:25px;">' . __('Choose Query Method', 'ip2location-country-blocker') . '</h1>
							<div class="ip2location-sel-img-div">
								<input width="100" type="radio" name="ipl-sel" id="db" value="db" checked>
								<label for="db">
									<span class="ip2location-sel-img">
										<img src="' . plugins_url('/assets/img/db.png', __FILE__) . '" width="90" height="90" align="center" alt="IP2Location BIN Database">
									</span>
								</label>
								<h4 style="margin-bottom:0;">' . __('IP2Location BIN Database (Local Query)', 'ip2location-country-blocker') . '</h4>
								<p style="margin-top:8px;">' . __('Free geolocation database download', 'ip2location-country-blocker') . '</p>
							</div>
							<div class="ip2location-sel-img-div">
								<input type="radio" name="ipl-sel" id="api" value="api" >
								<label for="api">
									<span class="ip2location-sel-img">
										<img src="' . plugins_url('/assets/img/api.png', __FILE__) . '" width="90" height="90" align="center" alt="IP2Location.io IP Geolocation API">
									</span>
								</label>
								<h4 style="margin-bottom:0;">' . __('IP2Location.io IP Geolocation API (Remote Query)', 'ip2location-country-blocker') . '</h4>
								<p style="margin-top:8px;">' . __('Free 30K IP geolocation queries per month', 'ip2location-country-blocker') . '</p>
							</div>
						</div>
					</div>
					<p style="text-align:right;margin-top:15px">
						<button id="btn-to-step-1" class="button button-primary" style="padding:3px 18px;">' . __('Next', 'ip2location-country-blocker') . ' &raquo;</button>
					</p>
				</div>
			</div>

			<!-- db -->
			<div id="modal-db-step-1" class="ip2location-modal">
				<div class="ip2location-modal-content">
					<div align="center">
						<h1 style="line-height:1.2;font-size:23px;margin-bottom:25px;">' . __('Set Up IP2Location LITE BIN Database', 'ip2location-country-blocker') . '</h1>
						<table class="setup ip2location-steps" width="200">
							<tr>
								<td align="center">
									<img src="' . plugins_url('/assets/img/step-1-selected.png', __FILE__) . '" width="36" height="36" align="center" alt="Wizard Step 1"><br>
									' . __('Step 1', 'ip2location-country-blocker') . '
								</td>
								<td align="center">
									<img src="' . plugins_url('/assets/img/step-2.png', __FILE__) . '" width="36" height="36" align="center" alt="Wizard Step 2"><br>
									' . __('Step 2', 'ip2location-country-blocker') . '
								</td>
								<td align="center">
									<img src="' . plugins_url('/assets/img/step-3.png', __FILE__) . '" width="36" height="36" align="center" alt="Wizard Step 3"><br>
									' . __('Step 3', 'ip2location-country-blocker') . '
								</td>
							</tr>
						</table>
						<div class="ip2location-line"></div>
					</div>
					<form>
						<p>
							<label>' . __('Enter IP2Location LITE download token', 'ip2location-country-blocker') . '</label>
							<input type="text" id="setup_token" class="regular-text code" maxlength="64" style="width:100%; margin-top: 10px; margin-bottom: 4px;">
						</p>
						<p class="description">
							' . sprintf(__('Don\'t have an account yet? Sign up a %1$s free IP geolocation account%2$s to obtain your download token.', 'ip2location-country-blocker'), '<a href="https://lite.ip2location.com/sign-up#wordpress-wzdicb" target="_blank">', '</a>') . '
						</p>
						<p id="token_status" style="margin-top:20px;margin-bottom:20px;">&nbsp;</p>
					</form>
					<p style="text-align:right;margin-top:15px">
						<button id="btn-to-db-step-0" class="button button-secondary" style="padding:3px 18px;margin-right:8px;" >&laquo; ' . __('Previous', 'ip2location-country-blocker') . '</button>
						<button id="btn-to-db-step-2" class="button button-primary" style="padding:3px 18px;" disabled>' . __('Next', 'ip2location-country-blocker') . ' &raquo;</button>
					</p>
				</div>
			</div>

			<div id="modal-db-step-2" class="ip2location-modal">
				<div class="ip2location-modal-content">
					<div align="center">
						<h1 style="line-height:1.2;font-size:23px;margin-bottom:25px;">' . __('Download IP2Location BIN Database', 'ip2location-country-blocker') . '</h1>
						<table class="setup ip2location-steps" width="200">
							<tr>
								<td align="center">
									<img src="' . plugins_url('/assets/img/step-1-selected.png', __FILE__) . '" width="36" height="36" align="center" alt="Wizard Step 1"><br>
									' . __('Step 1', 'ip2location-country-blocker') . '
								</td>
								<td align="center">
									<img src="' . plugins_url('/assets/img/step-2-selected.png', __FILE__) . '" width="36" height="36" align="center" alt="Wizard Step 2"><br>
									' . __('Step 2', 'ip2location-country-blocker') . '
								</td>
								<td align="center">
									<img src="' . plugins_url('/assets/img/step-3.png', __FILE__) . '" width="36" height="36" align="center" alt="Wizard Step 3"><br>
									' . __('Step 3', 'ip2location-country-blocker') . '
								</td>
							</tr>
						</table>
						<div class="ip2location-line"></div>
					</div>

					<form style="height:140px">
						<p id="ip2location_download_status"></p>
					</form>
					<p style="text-align:right;margin-top:30px">
						<button id="btn-to-db-step-1" class="button button-secondary" style="padding:3px 18px;margin-right:8px;" disabled>&laquo; ' . __('Previous', 'ip2location-country-blocker') . '</button>
						<button id="btn-to-db-step-3" class="button button-primary" style="padding:3px 18px;" disabled>' . __('Next', 'ip2location-country-blocker') . ' &raquo;</button>
					</p>
				</div>
			</div>

			<div id="modal-db-step-3" class="ip2location-modal">
				<div class="ip2location-modal-content">
					<div align="center">
						<h1 style="line-height:1.2;font-size:23px;margin-bottom:25px;">' . __('Configure The Rules', 'ip2location-country-blocker') . '</h1>
						<table class="setup ip2location-steps" width="200">
							<tr>
								<td align="center">
									<img src="' . plugins_url('/assets/img/step-1-selected.png', __FILE__) . '" width="36" height="36" align="center" alt="Wizard Step 1"><br>
									' . __('Step 1', 'ip2location-country-blocker') . '
								</td>
								<td align="center">
									<img src="' . plugins_url('/assets/img/step-2-selected.png', __FILE__) . '" width="36" height="36" align="center" alt="Wizard Step 2"><br>
									' . __('Step 2', 'ip2location-country-blocker') . '
								</td>
								<td align="center">
									<img src="' . plugins_url('/assets/img/step-3-selected.png', __FILE__) . '" width="36" height="36" align="center" alt="Wizard Step 3"><br>
									' . __('Step 3', 'ip2location-country-blocker') . '
								</td>
							</tr>
						</table>
						<div class="ip2location-line"></div>
					</div>

					<form style="height:75px;">
						<p>
							' . __('Please click the “Finish” button to start configuring your rules.', 'ip2location-country-blocker') . '
						</p>
					</form>
					<p style="text-align:right;margin-top:30px">
						<button class="button button-primary" style="padding:3px 18px;" onclick="window.location.href=\'' . admin_url('admin.php?page=ip2location-country-blocker') . '\';">' . __('Finish', 'ip2location-country-blocker') . '</button>
					</p>
				</div>
			</div>

			<!-- api -->
			<div id="modal-api-step-1" class="ip2location-modal">
				<div class="ip2location-modal-content">
					<div align="center">
						<h1 style="line-height:1.2;font-size:23px;margin-bottom:25px;">' . __('Set Up IP2Location.io IP Geolocation Service', 'ip2location-country-blocker') . '</h1>
						<table class="setup ip2location-steps" width="200">
							<tr>
								<td align="center">
									<img src="' . plugins_url('/assets/img/step-1-selected.png', __FILE__) . '" width="36" height="36" align="center" alt="Wizard Step 1"><br>
									' . __('Step 1', 'ip2location-country-blocker') . '
								</td>
								<td align="center">
									<img src="' . plugins_url('/assets/img/step-3.png', __FILE__) . '" width="36" height="36" align="center" alt="Wizard Step 2"><br>
									' . __('Step 2', 'ip2location-country-blocker') . '
								</td>
							</tr>
						</table>
						<div class="ip2location-api-line"></div>
					</div>
					<form>
						<p>
							<label>' . __('Enter IP2Location.io IP Geolocation API key', 'ip2location-country-blocker') . '</label>
							<input type="text" id="setup_api_key" class="regular-text code" maxlength="32" style="width:100%;margin-top: 10px; margin-bottom: 4px;">
						</p>
						<p class="description">
							' . sprintf(__('Don\'t have an account yet? Sign up a %1$s free IP geolocation plan%2$s to obtain your API key.', 'ip2location-country-blocker'), '<a href="https://www.ip2location.io/sign-up#wordpress-wzdicb" target="_blank">', '</a>') . '
						</p>
						<p id="api_status">&nbsp;</p>
					</form>
					<p style="text-align:right;margin-top:30px">
						<button id="btn-to-api-step-0" class="button button-secondary" style="padding:3px 18px;margin-right:8px;">&laquo; ' . __('Previous', 'ip2location-country-blocker') . '</button>
						<button id="btn-to-api-step-2" class="button button-primary" style="padding:3px 18px;" >' . __('Next', 'ip2location-country-blocker') . ' &raquo;</button>
					</p>
				</div>
			</div>

			<div id="modal-api-step-2" class="ip2location-modal">
				<div class="ip2location-modal-content">
					<div align="center">
						<h1 style="line-height:1.2;font-size:23px;margin-bottom:25px;">' . __('Configure The Rules', 'ip2location-country-blocker') . '</h1>
						<table class="setup ip2location-steps" width="200">
							<tr>
								<td align="center">
									<img src="' . plugins_url('/assets/img/step-1-selected.png', __FILE__) . '" width="36" height="36" align="center" alt="Wizard Step 1"><br>
									' . __('Step 1', 'ip2location-country-blocker') . '
								</td>
								<td align="center">
									<img src="' . plugins_url('/assets/img/step-3-selected.png', __FILE__) . '" width="36" height="36" align="center" alt="Wizard Step 2"><br>
									' . __('Step 2', 'ip2location-country-blocker') . '
								</td>
							</tr>
						</table>
						<div class="ip2location-api-line"></div>
					</div>

					<form style="height:75px;">
						<p>
							' . __('Please click the “Finish” button to start configuring your rules.', 'ip2location-country-blocker') . '
						</p>
					</form>
					<p style="text-align:right;margin-top:30px">
						<button class="button button-primary" style="padding:3px 18px;" onclick="window.location.href=\'' . admin_url('admin.php?page=ip2location-country-blocker') . '\';">' . __('Finish', 'ip2location-country-blocker') . '</button>
					</p>
				</div>
			</div>
			';
		}

		echo '<input type="hidden" id="validate_token_nonce" value="' . wp_create_nonce('validate-token') . '">';
		echo '<input type="hidden" id="validate_api_key_nonce" value="' . wp_create_nonce('validate-api-key') . '">';
		echo '<input type="hidden" id="update_nonce" value="' . wp_create_nonce('update-database') . '">';
	}

	public function admin_page()
	{
		if (!is_admin()) {
			return;
		}

		// Clear cache older than 3 days
		$this->cache_clear(3);

		// add_action('wp_enqueue_script', 'load_jquery');
		// wp_enqueue_style('iplcb-custom-css', plugins_url('/assets/css/customs.css', __FILE__), [], null);
	}

	public function check_block()
	{
		if (preg_replace('/https?:\/\//', '', $this->get_current_url()) == preg_replace('/https?:\/\//', '', (string) get_option('ip2location_country_blocker_frontend_error_page'))) {
			return;
		}

		if (preg_replace('/https?:\/\//', '', $this->get_current_url()) == preg_replace('/https?:\/\//', '', (string) get_option('ip2location_country_blocker_backend_error_page'))) {
			return;
		}

		// Disable redirection on administrator session
		if (current_user_can('administrator')) {
			return;
		}

		if (is_admin()) {
			return;
		}

		// Ignore internal XHR & cron
		if (isset($_SERVER['SCRIPT_NAME'])) {
			if (in_array(basename($_SERVER['SCRIPT_NAME']), ['admin-ajax.php', 'ajax.php', 'cron.php', 'wp-cron.php'])) {
				return;
			}
		}

		// Ignore content fetcher
		if (preg_match('/facebookexternalhit/', $this->get_user_agent())) {
			return;
		}

		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');
		header('Pragma: no-cache');

		// Backend
		if ($this->is_backend_page()) {
			if (!get_option('ip2location_country_blocker_backend_enabled')) {
				$this->write_debug_log('Backend blocking is disabled.');

				return;
			}

			if (preg_match('/(page|post)_id=([0-9]+)/', (string) get_option('ip2location_country_blocker_backend_error_page'), $matches)) {
				if ($this->get_current_url() == get_permalink($matches[2])) {
					return;
				}
			}

			$result = $this->get_location($this->get_ip());

			if (empty($result['country_code'])) {
				$this->write_debug_log('Cannot identify visitor country.');

				return;
			}

			if ($this->is_in_list($this->get_ip(), 'backend_ip_whitelist')) {
				$this->write_debug_log('IP is in whitelist.');

				return;
			}

			if (get_option('ip2location_country_blocker_backend_skip_bots') && $this->is_bot('backend')) {
				$this->write_debug_log('Web crawler detected.');

				return;
			}

			$secret_code = (isset($_GET['secret_code'])) ? sanitize_text_field($_GET['secret_code']) : (($this->cache_get($this->get_ip() . '_secret_code')) ?: md5(microtime()));

			$this->cache_add($this->get_ip() . '_secret_code', $secret_code);

			$bypass_code = (get_option('ip2location_country_blocker_bypass_code')) ?: md5(microtime());

			// Stop validation if bypass code is provided.
			if ($bypass_code == $secret_code) {
				$this->write_debug_log('Bypassed with secret code.');

				return;
			}

			if ($this->is_in_list($this->get_ip(), 'backend_ip_blacklist')) {
				$this->write_debug_log('IP is in blacklist.', 'BLOCKED');

				$this->block_backend($result['country_code'], false);

				return;
			}

			$ban_list = get_option('ip2location_country_blocker_backend_banlist');

			if (is_array($ban_list)) {
				$ban_list = $this->expand_ban_list($ban_list);

				if ($this->check_list($result['country_code'], $ban_list, get_option('ip2location_country_blocker_backend_block_mode'))) {
					$this->write_debug_log('Country ' . ((get_option('ip2location_country_blocker_backend_block_mode') == 1) ? 'is' : 'not') . ' in the list.');

					$this->block_backend($result['country_code']);

					return;
				}

				$this->write_debug_log('Access is allowed.');
			}

			if (get_option('ip2location_country_blocker_backend_block_proxy') && $result['is_proxy'] == 'YES') {
				$this->write_debug_log('IP is an anonymous proxy.', 'BLOCKED');
				$this->block_backend($result['country_code']);

				return;
			}

			$proxy_type_list = get_option('ip2location_country_blocker_backend_block_proxy_type');

			if (is_array($proxy_type_list)) {
				if (in_array($result['proxy_type'], $proxy_type_list)) {
					$this->write_debug_log('IP is a ' . $result['proxy_type'] . ' proxy.', 'BLOCKED');

					$this->block_backend($result['country_code']);

					return;
				}
			}

			if (get_option('ip2location_country_blocker_backend_auto_block_threshold')) {
				$GLOBALS['wpdb']->query('INSERT INTO ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_backend_rate_limit_log(ip_address, date_created) VALUES ("' . $this->get_ip() . '", "' . date('Y-m-d H:i:s') . '")');

				$total = $GLOBALS['wpdb']->get_var('SELECT COUNT(*) FROM ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_backend_rate_limit_log WHERE ip_address = "' . $this->get_ip() . '" AND date_created >= "' . date('Y-m-d H:i:s', strtotime('-24 hour')) . '";');

				if ($total >= get_option('ip2location_country_blocker_backend_auto_block_threshold')) {
					// Add client IP into blacklist
					update_option('ip2location_country_blocker_backend_ip_blacklist', trim(get_option('ip2location_country_blocker_backend_ip_blacklist') . ';' . $this->get_ip(), ';'));
				}

				$GLOBALS['wpdb']->query('DELETE FROM ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_backend_rate_limit_log WHERE ip_address = "' . $this->get_ip() . '" AND date_created < "' . date('Y-m-d H:i:s', strtotime('-24 hour')) . '";');
			}
		}

		// Frontend
		else {
			if (!get_option('ip2location_country_blocker_frontend_enabled')) {
				$this->write_debug_log('Frontend blocking is disabled.');

				return;
			}

			if (preg_match('/(page|post)_id=([0-9]+)/', get_option('ip2location_country_blocker_frontend_error_page'), $matches)) {
				if ($this->get_current_url() == get_permalink($matches[2])) {
					return;
				}
			}

			if ($this->is_in_list($this->get_ip(), 'frontend_ip_whitelist')) {
				$this->write_debug_log('IP is in whitelist.');

				return;
			}

			if (is_user_logged_in()) {
				if (get_option('ip2location_country_blocker_frontend_whitelist_logged_user') == false || get_option('ip2location_country_blocker_frontend_whitelist_logged_user') == 1) {
					$this->write_debug_log('User is logged in.');

					return;
				}
			}

			if (get_option('ip2location_country_blocker_frontend_skip_bots') && $this->is_bot('frontend')) {
				$this->write_debug_log('Web crawler detected.');

				return;
			}

			$result = $this->get_location($this->get_ip());

			if (empty($result['country_code'])) {
				$this->write_debug_log('Unable to identify visitor country.');

				return;
			}

			if ($this->is_in_list($this->get_ip(), 'frontend_ip_blacklist')) {
				$this->write_debug_log('IP is in blacklist', 'BLOCKED');
				$this->block_frontend($result['country_code'], false);

				return;
			}

			$ban_list = get_option('ip2location_country_blocker_frontend_banlist');

			if (is_array($ban_list)) {
				$ban_list = $this->expand_ban_list($ban_list);

				if ($this->check_list($result['country_code'], $ban_list, get_option('ip2location_country_blocker_frontend_block_mode'))) {
					$this->write_debug_log('Country ' . ((get_option('ip2location_country_blocker_frontend_block_mode') == 1) ? 'is' : 'not') . ' in the list.', 'BLOCKED');
					$this->block_frontend($result['country_code']);

					return;
				}
				$this->write_debug_log('Access is allowed.');
			}

			if (get_option('ip2location_country_blocker_frontend_block_proxy') && $result['is_proxy'] == 'YES') {
				$this->write_debug_log('IP is an anonymous proxy.', 'BLOCKED');
				$this->block_frontend($result['country_code']);

				return;
			}

			$proxy_type_list = get_option('ip2location_country_blocker_frontend_block_proxy_type');

			if (is_array($proxy_type_list)) {
				if (in_array($result['proxy_type'], $proxy_type_list)) {
					$this->write_debug_log('IP is a ' . $result['proxy_type'] . ' proxy.', 'BLOCKED');

					$this->block_frontend($result['country_code']);

					return;
				}
			}

			if (get_option('ip2location_country_blocker_frontend_auto_block_threshold')) {
				$GLOBALS['wpdb']->query('INSERT INTO ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_frontend_rate_limit_log(ip_address, date_created) VALUES ("' . $this->get_ip() . '", "' . date('Y-m-d H:i:s') . '")');

				$total = $GLOBALS['wpdb']->get_var('SELECT COUNT(*) FROM ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_frontend_rate_limit_log WHERE ip_address = "' . $this->get_ip() . '" AND date_created >= "' . date('Y-m-d H:i:s', strtotime('-24 hour')) . '";');

				if ($total >= get_option('ip2location_country_blocker_frontend_auto_block_threshold')) {
					// Add client IP into blacklist
					update_option('ip2location_country_blocker_frontend_ip_blacklist', trim(get_option('ip2location_country_blocker_frontend_ip_blacklist') . ';' . $this->get_ip(), ';'));
				}

				$GLOBALS['wpdb']->query('DELETE FROM ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_frontend_rate_limit_log WHERE ip_address = "' . $this->get_ip() . '" AND date_created < "' . date('Y-m-d H:i:s', strtotime('-24 hour')) . '";');
			}
		}
	}

	public function add_admin_menu()
	{
		add_menu_page(__('Country Blocker', 'ip2location-country-blocker'), __('Country Blocker', 'ip2location-country-blocker'), 'manage_options', 'ip2location-country-blocker', [$this, 'frontend_page'], 'dashicons-admin-ip2location', 30);
		add_submenu_page('ip2location-country-blocker', __('Frontend', 'ip2location-country-blocker'), __('Frontend', 'ip2location-country-blocker'), 'manage_options', 'ip2location-country-blocker', [$this, 'frontend_page']);
		add_submenu_page('ip2location-country-blocker', __('Backend', 'ip2location-country-blocker'), __('Backend', 'ip2location-country-blocker'), 'manage_options', 'ip2location-country-blocker-backend', [$this, 'backend_page']);
		add_submenu_page('ip2location-country-blocker', __('Statistics', 'ip2location-country-blocker'), __('Statistics', 'ip2location-country-blocker'), 'manage_options', 'ip2location-country-blocker-statistics', [$this, 'statistics_page']);
		add_submenu_page('ip2location-country-blocker', __('IP Lookup', 'ip2location-country-blocker'), __('IP Lookup', 'ip2location-country-blocker'), 'manage_options', 'ip2location-country-blocker-ip-lookup', [$this, 'ip_lookup_page']);
		add_submenu_page('ip2location-country-blocker', __('Settings', 'ip2location-country-blocker'), __('Settings', 'ip2location-country-blocker'), 'manage_options', 'ip2location-country-blocker-settings', [$this, 'settings_page']);
	}

	public function set_defaults()
	{
		add_option('ip2location_country_blocker_access_email_notification', 'none');
		add_option('ip2location_country_blocker_api_key', '');
		add_option('ip2location_country_blocker_backend_banlist', '');
		add_option('ip2location_country_blocker_backend_block_mode', '1');
		add_option('ip2location_country_blocker_backend_block_proxy', '0');
		add_option('ip2location_country_blocker_backend_bots_list', '');
		add_option('ip2location_country_blocker_backend_enabled', '0');
		add_option('ip2location_country_blocker_backend_error_page', '');
		add_option('ip2location_country_blocker_backend_auto_block_threshold', '');
		add_option('ip2location_country_blocker_backend_ip_blacklist', '');
		add_option('ip2location_country_blocker_backend_ip_whitelist', '');
		add_option('ip2location_country_blocker_backend_option', '1');
		add_option('ip2location_country_blocker_backend_redirect_url', '');
		add_option('ip2location_country_blocker_backend_skip_bots', '1');
		add_option('ip2location_country_blocker_bypass_code', '');
		add_option('ip2location_country_blocker_database', '');
		add_option('ip2location_country_blocker_debug_log_enabled', '0');
		add_option('ip2location_country_blocker_detect_forwarder_ip', '1');
		add_option('ip2location_country_blocker_email_notification', 'none');
		add_option('ip2location_country_blocker_frontend_banlist', '');
		add_option('ip2location_country_blocker_frontend_block_mode', '1');
		add_option('ip2location_country_blocker_frontend_block_proxy', '0');
		add_option('ip2location_country_blocker_frontend_bots_list', '');
		add_option('ip2location_country_blocker_frontend_enabled', '0');
		add_option('ip2location_country_blocker_frontend_error_page', '');
		add_option('ip2location_country_blocker_frontend_auto_block_threshold', '');
		add_option('ip2location_country_blocker_frontend_ip_blacklist', '');
		add_option('ip2location_country_blocker_frontend_ip_whitelist', '');
		add_option('ip2location_country_blocker_frontend_option', '1');
		add_option('ip2location_country_blocker_frontend_redirect_url', '');
		add_option('ip2location_country_blocker_frontend_skip_bots', '1');
		add_option('ip2location_country_blocker_frontend_whitelist_logged_user', '1');
		add_option('ip2location_country_blocker_log_enabled', '0');
		add_option('ip2location_country_blocker_lookup_mode', 'bin');
		add_option('ip2location_country_blocker_px_api_key', '');
		add_option('ip2location_country_blocker_px_database', '');
		add_option('ip2location_country_blocker_px_lookup_mode', '');
		add_option('ip2location_country_blocker_token', '');
		add_option('ip2location_country_blocker_download_ipv4_only', '0');

		$this->create_table();

		// Create scheduled task
		if (!wp_next_scheduled('ip2location_country_blocker_hourly_event')) {
			wp_schedule_event(time(), 'hourly', 'ip2location_country_blocker_hourly_event');
		}
	}

	public function update_ip2location_database()
	{
		@set_time_limit(300);

		check_ajax_referer('update-database', '__nonce');

		header('Content-Type: application/json');

		if (!current_user_can('administrator')) {
			exit(json_encode([
				'status'  => 'ERROR',
				'message' => __('Permission denied.', 'ip2location-country-blocker'),
			]));
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		WP_Filesystem();
		global $wp_filesystem;

		try {
			$token = (isset($_POST['token'])) ? sanitize_text_field($_POST['token']) : '';
			$ipv4_only = (isset($_POST['ipv4_only']) && $_POST['ipv4_only'] == 'true') ? true : false;
			$ipv6 = ($ipv4_only) ? '' : 'IPV6';
			$code = 'DB1BIN' . $ipv6;

			$working_dir = IP2LOCATION_DIR . 'working' . \DIRECTORY_SEPARATOR;
			$zip_file = $working_dir . 'database.zip';

			// Remove existing working directory
			$wp_filesystem->delete($working_dir, true);

			// Create working directory
			$wp_filesystem->mkdir($working_dir);

			if (!class_exists('WP_Http')) {
				include_once ABSPATH . WPINC . '/class-http.php';
			}

			$request = new WP_Http();

			// Check download permission
			$response = $request->request('https://www.ip2location.com/download-info?' . http_build_query([
				'package' => $code,
				'token'   => $token,
				'source'  => 'wp_country_blocker',
			]));

			$parts = explode(';', $response['body']);

			if ($parts[0] != 'OK') {
				// Download LITE version
				$code = 'DB1LITEBIN' . $ipv6;

				$response = $request->request('https://www.ip2location.com/download-info?' . http_build_query([
					'package' => $code,
					'token'   => $token,
					'source'  => 'wp_country_blocker',
				]));

				$parts = explode(';', $response['body']);

				if ($parts[0] != 'OK') {
					exit(json_encode([
						'status'  => 'ERROR',
						'message' => __('You do not have permission to download this database.', 'ip2location-country-blocker'),
					]));
				}
			}

			// Start downloading BIN database from IP2Location website
			$response = $request->request('https://www.ip2location.com/download?' . http_build_query([
				'file'   => $code,
				'token'  => $token,
				'source' => 'wp_country_blocker',
			]), [
				'timeout'          => 300,
				'follow_redirects' => true,
			]);

			if ((isset($response->errors)) || (!in_array('200', $response['response']))) {
				$wp_filesystem->delete($working_dir, true);

				exit(json_encode([
					'status'  => 'ERROR',
					'message' => __('Connection timed out while downloading database.', 'ip2location-country-blocker'),
				]));
			}

			// Save downloaded package.
			$fp = fopen($zip_file, 'w');

			if (!$fp) {
				exit(json_encode([
					'status'  => 'ERROR',
					'message' => __('No permission to write into file system.', 'ip2location-country-blocker'),
				]));
			}

			fwrite($fp, $response['body']);
			fclose($fp);

			if (filesize($zip_file) < 51200) {
				$message = file_get_contents($zip_file);
				$wp_filesystem->delete($working_dir, true);

				exit(json_encode([
					'status'  => 'ERROR',
					'message' => __('Downloaded database is corrupted. Please try again later.', 'ip2location-country-blocker'),
				]));
			}

			// Unzip the package to working directory
			$result = unzip_file($zip_file, $working_dir);

			// Once extracted, delete the package.
			unlink($zip_file);

			if (is_wp_error($result)) {
				$wp_filesystem->delete($working_dir, true);

				exit(json_encode([
					'status'  => 'ERROR',
					'message' => __('There is problem when decompress the database.', 'ip2location-country-blocker'),
				]));
			}

			// File the BIN database
			$bin_database = '';
			$files = scandir($working_dir);

			foreach ($files as $file) {
				if (strtoupper(substr($file, -4)) == '.BIN') {
					$bin_database = $file;
					break;
				}
			}

			// Move file to IP2Location directory
			$wp_filesystem->move($working_dir . $bin_database, IP2LOCATION_DIR . $bin_database, true);

			update_option('ip2location_country_blocker_lookup_mode', 'bin');
			update_option('ip2location_country_blocker_database', $bin_database);
			update_option('ip2location_country_blocker_token', $token);
			update_option('ip2location_country_blocker_download_ipv4_only', ($ipv4_only) ? 1 : 0);

			// Remove working directory
			$wp_filesystem->delete($working_dir, true);

			// Flush caches
			$this->cache_flush();

			exit(json_encode([
				'status'  => 'OK',
				'message' => '',
			]));
		} catch (Exception $e) {
			exit(json_encode([
				'status'  => 'ERROR',
				'message' => $e->getMessage(),
			]));
		}
	}

	public function update_ip2proxy_database()
	{
		@set_time_limit(300);

		check_ajax_referer('update-database', '__nonce');

		header('Content-Type: application/json');

		if (!current_user_can('administrator')) {
			exit(json_encode([
				'status'  => 'ERROR',
				'message' => __('Permission denied.', 'ip2location-country-blocker'),
			]));
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		WP_Filesystem();
		global $wp_filesystem;

		try {
			$token = (isset($_POST['token'])) ? sanitize_text_field($_POST['token']) : '';
			$code = 'PX2BIN';

			$working_dir = IP2LOCATION_DIR . 'working' . \DIRECTORY_SEPARATOR;
			$zip_file = $working_dir . 'database.zip';

			// Remove existing working directory
			$wp_filesystem->delete($working_dir, true);

			// Create working directory
			$wp_filesystem->mkdir($working_dir);

			// Check download permission
			$response = wp_remote_get('https://www.ip2location.com/download-info?' . http_build_query([
				'package' => $code,
				'token'   => $token,
				'source'  => 'wp_country_blocker',
			]));

			$parts = explode(';', $response['body']);

			if ($parts[0] != 'OK') {
				// Download LITE version
				$code = 'PX2LITEBIN';

				$response = wp_remote_get('https://www.ip2location.com/download-info?' . http_build_query([
					'package' => $code,
					'token'   => $token,
					'source'  => 'wp_country_blocker',
				]));

				$parts = explode(';', $response['body']);

				if ($parts[0] != 'OK') {
					exit(json_encode([
						'status'  => 'ERROR',
						'message' => __('You do not have permission to download this database.', 'ip2location-country-blocker'),
					]));
				}
			}

			// Start downloading BIN database from IP2Location website
			$tmp_file = download_url('https://www.ip2location.com/download?' . http_build_query([
				'file'   => $code,
				'token'  => $token,
				'source' => 'wp_country_blocker',
			]));

			if ((isset($response->errors)) || (!in_array('200', $response['response']))) {
				$wp_filesystem->delete($working_dir, true);

				exit(json_encode([
					'status'  => 'ERROR',
					'message' => __('Connection timed out while downloading database.', 'ip2location-country-blocker'),
				]));
			}

			// Save downloaded package.
			copy($tmp_file, $zip_file);
			wp_delete_file($tmp_file);

			if (filesize($zip_file) < 51200) {
				$wp_filesystem->delete($working_dir, true);

				exit(json_encode([
					'status'  => 'ERROR',
					'message' => __(file_get_contents($zip_file), 'ip2location-country-blocker'),
				]));
			}

			// Unzip the package to working directory
			$result = unzip_file($zip_file, $working_dir);

			// Once extracted, delete the package.
			wp_delete_file($zip_file);

			if (is_wp_error($result)) {
				$wp_filesystem->delete($working_dir, true);

				exit(json_encode([
					'status'  => 'ERROR',
					'message' => __('There is problem when decompress the database.', 'ip2location-country-blocker'),
				]));
			}

			// File the BIN database
			$bin_database = '';
			$files = scandir($working_dir);

			foreach ($files as $file) {
				if (strtoupper(substr($file, -4)) == '.BIN') {
					$bin_database = $file;
					break;
				}
			}

			// Move file to IP2Location directory
			$wp_filesystem->move($working_dir . $bin_database, IP2LOCATION_DIR . $bin_database, true);

			update_option('ip2location_country_blocker_px_lookup_mode', 'px_bin');
			update_option('ip2location_country_blocker_px_database', $bin_database);
			update_option('ip2location_country_blocker_token', $token);
			update_option('ip2location_country_blocker_download_ipv4_only', ($ipv4_only) ? 1 : 0);

			// Remove working directory
			$wp_filesystem->delete($working_dir, true);

			// Flush caches
			$this->cache_flush();

			exit(json_encode([
				'status'  => 'OK',
				'message' => '',
			]));
		} catch (Exception $e) {
			exit(json_encode([
				'status'  => 'ERROR',
				'message' => $e->getMessage(),
			]));
		}
	}

	public function validate_token()
	{
		header('Content-Type: application/json');

		if (!current_user_can('administrator')) {
			exit(json_encode([
				'status'  => 'ERROR',
				'message' => __('Permission denied.', 'ip2location-country-blocker'),
			]));
		}

		check_ajax_referer('validate-token', '__nonce');

		try {
			$token = (isset($_POST['token'])) ? sanitize_text_field($_POST['token']) : '';

			// Check download permission
			$response = wp_remote_get('https://www.ip2location.com/download-info?' . http_build_query([
				'package' => 'DB1BIN',
				'token'   => $token,
				'source'  => 'wp_country_blocker',
			]));

			if (isset($response['errors'])) {
				exit(json_encode([
					'status'  => 'ERROR',
					'message' => 'Unable to reach remote URL. Please try again later.',
				]));
			}

			$parts = explode(';', $response['body']);

			if ($parts[0] != 'OK') {
				$response = wp_remote_get('https://www.ip2location.com/download-info?' . http_build_query([
					'package' => 'DB1LITEBIN',
					'token'   => $token,
					'source'  => 'wp_country_blocker',
				]));

				$parts = explode(';', $response['body']);

				if ($parts[0] != 'OK') {
					exit(json_encode([
						'status'  => 'ERROR',
						'message' => __('Invalid download token.', 'ip2location-country-blocker'),
					]));
				}
			}

			update_option('ip2location_country_blocker_token', $token);

			exit(json_encode([
				'status'  => 'OK',
				'message' => '',
			]));
		} catch (Exception $e) {
			exit(json_encode([
				'status'  => 'ERROR',
				'message' => $e->getMessage(),
			]));
		}
	}

	public function validate_api_key()
	{
		header('Content-Type: application/json');

		if (!current_user_can('administrator')) {
			exit(json_encode([
				'status'  => 'ERROR',
				'message' => __('Permission denied.', 'ip2location-country-blocker'),
			]));
		}

		check_admin_referer('validate-api-key', '__nonce');

		try {
			$apiKey = (isset($_POST['key'])) ? sanitize_text_field($_POST['key']) : '';

			if (empty($apiKey)) {
				exit(json_encode([
					'status'  => 'ERROR',
					'message' => __('Invalid API key.', 'ip2location-country-blocker'),
				]));
			}

			// Check download permission
			$response = wp_remote_get('https://api.ip2location.io/?' . http_build_query([
				'key'    => $apiKey,
				'source' => 'wp_country_blocker',
			]));

			if (!isset($response['response']['code'])) {
				exit(json_encode([
					'status'  => 'ERROR',
					'message' => __('Remote server is not responding. Please try again later.', 'ip2location-country-blocker'),
				]));
			}

			if ($response['response']['code'] != 200) {
				exit(json_encode([
					'status'  => 'ERROR',
					'message' => __('Invalid API key.', 'ip2location-country-blocker'),
				]));
			}

			update_option('ip2location_country_blocker_lookup_mode', 'ws');
			update_option('ip2location_country_blocker_api_key', $apiKey);

			exit(json_encode([
				'status'  => 'OK',
				'message' => '',
			]));
		} catch (Exception $e) {
			exit(json_encode([
				'status'  => 'ERROR',
				'message' => $e->getMessage(),
			]));
		}
	}

	// Add notice in plugin page.
	public function show_notice()
	{
		if ($this->is_setup_completed()) {
			return;
		}

		echo '
		<div class="error">
			<p>
				' . sprintf(__('IP2Location Country Blocker requires the IP2Location BIN database to work. %1$sSetup your database%2$s now.', 'ip2location-country-blocker'), '<a href="' . get_admin_url() . 'admin.php?page=ip2location-country-blocker-settings">', '</a>') . '
			</p>
		</div>';
	}

	// Enqueue the script.
	public function plugin_enqueues($hook)
	{
		wp_enqueue_style('iplcb-styles-css', untrailingslashit(plugins_url('/', __FILE__)) . '/assets/css/styles.css', []);

		// die($hook);

		wp_enqueue_script('iplcb-settings-js', plugins_url('/assets/js/settings.js', __FILE__), ['jquery'], null, true);

		switch ($hook) {
			case 'plugins.php':
				wp_enqueue_script('jquery-ui-dialog');
				wp_enqueue_style('wp-jquery-ui-dialog');

				wp_enqueue_script('iplcb-feedback-js', plugins_url('/assets/js/feedback.js', __FILE__), ['jquery'], null, true);

				break;

			case 'toplevel_page_ip2location-country-blocker':
				add_action('wp_enqueue_script', 'load_jquery');

				wp_enqueue_script('iplcb-frontend-js', plugins_url('/assets/js/frontend.js', __FILE__), ['jquery'], null, true);
				wp_enqueue_script('iplcb-tagsinput-js', plugins_url('/assets/js/jquery.tagsinput.min.js', __FILE__), [], null, true);
				wp_enqueue_script('iplcb-chosen-js', plugins_url('/assets/js/chosen.jquery.min.js', __FILE__), [], null, true);

				wp_enqueue_style('iplcb-customs-css', plugins_url('/', __FILE__) . '/assets/css/customs.css', []);
				wp_enqueue_style('iplcb-tagsinput-css', plugins_url('/', __FILE__) . '/assets/css/jquery.tagsinput.min.css', []);
				wp_enqueue_style('iplcb-chosen-css', plugins_url('/', __FILE__) . '/assets/css/chosen.min.css', []);

				break;

			case 'country-blocker_page_ip2location-country-blocker-backend':
				add_action('wp_enqueue_script', 'load_jquery');

				wp_enqueue_script('iplcb-frontend-js', plugins_url('/assets/js/backend.js', __FILE__), ['jquery'], null, true);
				wp_enqueue_script('iplcb-tagsinput-js', plugins_url('/assets/js/jquery.tagsinput.min.js', __FILE__), [], null, true);
				wp_enqueue_script('iplcb-chosen-js', plugins_url('/assets/js/chosen.jquery.min.js', __FILE__), [], null, true);

				wp_enqueue_style('iplcb-tagsinput-css', plugins_url('/', __FILE__) . '/assets/css/jquery.tagsinput.min.css', []);
				wp_enqueue_style('iplcb-chosen-css', plugins_url('/', __FILE__) . '/assets/css/chosen.min.css', []);

				break;

			case 'country-blocker_page_ip2location-country-blocker-statistics':
				wp_enqueue_script('iplcb-chart-js', plugins_url('/assets/js/chart-js.min.js', __FILE__), ['jquery'], null, true);
				wp_enqueue_script('iplcb-statistics-js', plugins_url('/assets/js/statistics.js', __FILE__), [], null, true);
				break;

			case 'country-blocker_page_ip2location-country-blocker-settings':
				wp_enqueue_script('iplcb-settings-js', plugins_url('/assets/js/settings.js', __FILE__), ['jquery'], null, true);
				break;
		}
	}

	public function footer()
	{
		echo "<!--\n";
		echo "The IP2Location Country Blocker is using IP2Location LITE geolocation database. Please visit https://lite.ip2location.com for more information.\n";
		echo "-->\n";
	}

	public function write_debug_log($message, $action = 'ABORTED')
	{
		if (!get_option('ip2location_country_blocker_debug_log_enabled')) {
			return;
		}

		error_log(json_encode([
			'time'       => gmdate('Y-m-d H:i:s'),
			'client_ip'  => $this->get_ip(),
			'country'    => $this->session['country'],
			'is_proxy'   => $this->session['is_proxy'],
			'proxy_type' => $this->session['proxy_type'],
			'lookup_by'  => $this->session['lookup_mode'],
			'cache'      => $this->session['cache'],
			'uri'        => $this->get_current_url(),
			'message'    => $message,
			'action'     => $action,
		]) . "\n", 3, IPLCB_ROOT . $this->debug_log);
	}

	public function admin_footer_text($footer_text)
	{
		$plugin_name = substr(basename(__FILE__), 0, strpos(basename(__FILE__), '.'));
		$current_screen = get_current_screen();

		if ($current_screen && strpos($current_screen->id, $plugin_name) !== false) {
			$footer_text .= sprintf(
				__('Enjoyed %1$s? Please leave us a %2$s rating. A huge thanks in advance!', $plugin_name),
				'<strong>' . __('IP2Location Country Blocker', $plugin_name) . '</strong>',
				'<a href="https://wordpress.org/support/plugin/' . $plugin_name . '/reviews/?filter=5/#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
			);
		}

		if ($current_screen->id == 'plugins') {
			return $footer_text . '
			<div id="ip2location-country-blocker-feedback-modal" class="ip2location-modal">
				<div class="ip2location-modal-content">
					<span class="ip2location-close">&times;</span>

					<p>
						<h3>Would you mind sharing with us the reason to deactivate the plugin?</h3>
					</p>
					<span id="ip2location-country-blocker-feedback-response"></span>
					<p>
						<label>
							<input type="radio" name="ip2location-country-blocker-feedback" value="1"> I no longer need the plugin
						</label>
					</p>
					<p>
						<label>
							<input type="radio" name="ip2location-country-blocker-feedback" value="2"> I couldn\'t get the plugin to work
						</label>
					</p>
					<p>
						<label>
							<input type="radio" name="ip2location-country-blocker-feedback" value="3"> The plugin doesn\'t meet my requirements
						</label>
					</p>
					<p>
						<label>
							<input type="radio" name="ip2location-country-blocker-feedback" value="5"> The plugin doesn\'t work with Cache plugin
						</label>
					</p>
					<p>
						<label>
							<input type="radio" name="ip2location-country-blocker-feedback" value="4"> Other concerns
							<br><br>
							<textarea id="ip2location-country-blocker-feedback-other" style="display:none;width:100%"></textarea>
						</label>
					</p>
					<p>
						<div style="float:left">
							<input type="button" id="ip2location-country-blocker-submit-feedback-button" class="button button-danger" value="Submit & Deactivate" />
						</div>
						<div style="float:right">
							<a href="#">Skip & Deactivate</a>
						</div>
						<div style="clear:both"></div>
					</p>
				</div>
				<input type="hidden" id="ip2location_country_blocker_feedback_nonce" value="' . wp_create_nonce('submit-feedback') . '">
			</div>';
		}

		return $footer_text;
	}

	public function submit_feedback()
	{
		check_ajax_referer('submit-feedback', '__nonce');

		$feedback = (isset($_POST['feedback'])) ? sanitize_text_field($_POST['feedback']) : '';
		$others = (isset($_POST['others'])) ? sanitize_text_field($_POST['others']) : '';

		$options = [
			1 => 'I no longer need the plugin',
			2 => 'I couldn\'t get the plugin to work',
			3 => 'The plugin doesn\'t meet my requirements',
			4 => 'Other concerns' . (($others) ? (' - ' . $others) : ''),
			5 => 'The plugin doesn\'t work with Cache plugin',
		];

		if (isset($options[$feedback])) {
			wp_remote_get('https://www.ip2location.com/wp-plugin-feedback?' . http_build_query([
				'name'    => 'ip2location-country-blocker',
				'message' => $options[$feedback],
			]), ['timeout' => 5]);
		}
	}

	public function hourly_event()
	{
		$this->cache_clear();
		$this->set_priority();
	}

	private function cache_plugin_detected()
	{
		$plugins = [
			'Breeze'           => 'breeze/breeze.php',
			'Cache Enabler'    => 'cache-enabler/cache-enabler.php',
			'W3 Total Cache'   => 'w3-total-cache/w3-total-cache.php',
			'WP Fastest Cache' => 'wp-fastest-cache/wpFastestCache.php',
			'WP Optimizer'     => 'wp-optimize/wp-optimize.php',
			'WP Rocket'        => 'wp-rocket/wp-rocket.php',
			'WP Super Cache'   => 'wp-super-cache/wp-cache.php',
		];

		foreach ($plugins as $name => $path) {
			if (is_plugin_active($path)) {
				return $name;
			}
		}

		return false;
	}

	private function set_priority()
	{
		global $pagenow;

		// Do not do this in plugins page to prevent deactivation issues.
		if ($pagenow != 'plugins.php') {
			// Make sure this plugin loaded as first priority.
			$this_plugin = plugin_basename(trim(preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR . '/$2', __FILE__)));
			$active_plugins = get_option('active_plugins');
			$this_plugin_key = array_search($this_plugin, $active_plugins);

			if ($this_plugin_key) {
				array_splice($active_plugins, $this_plugin_key, 1);
				array_unshift($active_plugins, $this_plugin);
				update_option('active_plugins', $active_plugins);
			}
		}
	}

	private function is_backend_page()
	{
		if (preg_match('/wp-admin|(wp-)?login/', $_SERVER['SCRIPT_NAME'])) {
			return true;
		}

		return $GLOBALS['pagenow'] == trim(strtolower(parse_url(wp_login_url('', true), \PHP_URL_PATH)), '/');
	}

	private function block_backend($country_code, $email = true)
	{
		if ($email) {
			$this->send_email();
		}

		$this->write_statistics_log(2, $country_code);

		if (get_option('ip2location_country_blocker_backend_option') == 1) {
			$this->deny(null, 'BLOCKED');
		} elseif (get_option('ip2location_country_blocker_backend_option') == 2) {
			$this->deny(get_option('ip2location_country_blocker_backend_error_page'));
		} else {
			$this->redirect(get_option('ip2location_country_blocker_backend_redirect_url'));
		}
	}

	private function block_frontend($country_code, $email = true)
	{
		// if ($email) {
		// 	$this->send_email();
		// }

		$this->write_statistics_log(1, $country_code);

		if (get_option('ip2location_country_blocker_frontend_option') == 1) {
			$this->deny(null, 'BLOCKED');
		} elseif (get_option('ip2location_country_blocker_frontend_option') == 2) {
			$this->deny(get_option('ip2location_country_blocker_frontend_error_page'));
		} else {
			$this->redirect(get_option('ip2location_country_blocker_frontend_redirect_url'));
		}
	}

	private function write_statistics_log($side, $country_code)
	{
		$table_name = $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_log';

		if (get_option('ip2location_country_blocker_log_enabled') && $GLOBALS['wpdb']->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
			$GLOBALS['wpdb']->query('INSERT INTO ' . $table_name . ' (ip_address, country_code, side, page, date_created) VALUES ("' . $this->get_ip() . '", "' . $country_code . '", ' . $side . ', "' . basename(home_url(add_query_arg(null, null))) . '", "' . date('Y-m-d H:i:s') . '")');
		}
	}

	private function get_ip()
	{
		// Possible using CloudFlare service
		if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)) {
			// Make sure originated IP is coming from CloudFlare network
			$networks = [
				'173.245.48.0/20',
				'103.21.244.0/22',
				'103.22.200.0/22',
				'103.31.4.0/22',
				'141.101.64.0/18',
				'108.162.192.0/18',
				'190.93.240.0/20',
				'188.114.96.0/20',
				'197.234.240.0/22',
				'198.41.128.0/17',
				'162.158.0.0/15',
				'104.16.0.0/13',
				'104.24.0.0/14',
				'172.64.0.0/13',
				'131.0.72.0/22',
				'2400:cb00::/32',
				'2606:4700::/32',
				'2803:f800::/32',
				'2405:b500::/32',
				'2405:8100::/32',
				'2a06:98c0::/29',
				'2c0f:f248::/32',
			];

			foreach ($networks as $network) {
				if ($this->cidr_match($_SERVER['REMOTE_ADDR'], $network)) {
					return $_SERVER['HTTP_CF_CONNECTING_IP'];
				}
			}
		}

		// Possible Securi Firewall
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
			// Make sure originated IP is coming from Securi network
			$networks = [
				'192.88.134.0/23',
				'185.93.228.0/22',
				'66.248.200.0/22',
				'208.109.0.0/22',
				'2a02:fe80::/29',
			];

			foreach ($networks as $network) {
				if ($this->cidr_match($_SERVER['REMOTE_ADDR'], $network)) {
					return $_SERVER['HTTP_X_FORWARDED_FOR'];
				}
			}
		}

		// Possible local reverse proxy server
		if (!filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
			if (isset($_SERVER['HTTP_X_REAL_IP']) && filter_var($_SERVER['HTTP_X_REAL_IP'], \FILTER_VALIDATE_IP, \FILTER_FLAG_NO_PRIV_RANGE | \FILTER_FLAG_NO_RES_RANGE)) {
				return $_SERVER['HTTP_X_REAL_IP'];
			}

			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				// Get server IP address
				$server_ip = (isset($_SERVER['SERVER_ADDR'])) ? $_SERVER['SERVER_ADDR'] : '';

				$ip = trim(current(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])));

				if (filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_NO_PRIV_RANGE | \FILTER_FLAG_NO_RES_RANGE) && $ip != $server_ip) {
					return $ip;
				}
			}
		}

		return $_SERVER['REMOTE_ADDR'];
	}

	private function is_bot($interface = 'frontend')
	{
		$is_bot = preg_match('/baidu|bingbot|googlebot|-google|ia_archiver|msnbot|naverbot|pingdom|seznambot|slurp|teoma|twitter|yandex|yeti|feedburner/i', $this->get_user_agent());

		$list = get_option('ip2location_country_blocker_' . (($interface == 'frontend') ? 'frontend' : 'backend') . '_bots_list');

		if (is_array($list)) {
			foreach ($list as $bot) {
				if (empty($bot)) {
					continue;
				}

				if (preg_match('/' . $bot . '/i', $this->get_user_agent())) {
					return true;
				}
			}
		}

		return $is_bot;
	}

	private function send_email()
	{
		if (filter_var(get_option('ip2location_country_blocker_email_notification'), \FILTER_VALIDATE_EMAIL)) {
			$message = [];

			$message[] = 'Hi,';

			$occurrence = $GLOBALS['wpdb']->get_var('SELECT COUNT(*) FROM ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_log WHERE ip_address = "' . $this->get_ip() . '" AND date_created >= "' . date('Y-m-d H:i:s', strtotime('-1 hour')) . '";');

			$message[] = 'IP2Location Country Blocker has detected and blocked the visitor from accessing your admin page:';
			$message[] = '';
			$message[] = 'IP Address: ' . $this->get_ip();
			$message[] = 'Total Occurrence in past 1 hour: ' . $occurrence;
			$message[] = 'URL: ' . $this->get_current_url();
			$message[] = '';
			$message[] = str_repeat('-', 100);
			$message[] = 'Get a free IP2Location LITE database at https://lite.ip2location.com.';
			$message[] = 'Get an accurate IP2Location commercial database at https://www.ip2location.com.';
			$message[] = str_repeat('-', 100);
			$message[] = '';
			$message[] = '';
			$message[] = 'Regards,';
			$message[] = 'IP2Location Country Blocker';
			$message[] = 'www.ip2location.com';

			$this->write_debug_log('Send notification email.');

			wp_mail(get_option('ip2location_country_blocker_email_notification'), 'IP2Location Country Blocker Alert', implode("\n", $message));
		}
	}

	private function get_page_id($url = '')
	{
		if ($url) {
			$parts = parse_url($url);

			$queries = [];

			if (isset($parts['query'])) {
				parse_str($parts['query'], $queries);
			}

			if (isset($queries['page_id'])) {
				return $queries['page_id'];
			}

			if (isset($parts['path'])) {
				$post_name = $parts['path'];
			} else {
				$post_name = '';
			}
		} else {
			$post_name = preg_replace('/\/?\?.+$/', '', trim($_SERVER['REQUEST_URI'], '/'));
		}

		if (strrpos($post_name, '/') !== false) {
			$post_name = substr($post_name, strrpos($post_name, '/') + 1);
		}

		$results = $GLOBALS['wpdb']->get_results("SELECT * FROM {$GLOBALS['wpdb']->prefix}posts WHERE post_name = '$post_name'", OBJECT);

		return ($results) ? $results[0]->ID : null;
	}

	private function get_user_agent()
	{
		return (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';
	}

	private function redirect($url)
	{
		$current_url = preg_replace('/^https?:\/\//', '', home_url(add_query_arg(null, null)));
		$new_url = preg_replace('/^https?:\/\//', '', $url);

		// Prevent infinite redirection.
		if ($new_url == $current_url) {
			return;
		}

		if ($this->get_page_id() !== null && $this->get_page_id() == $this->get_page_id($new_url)) {
			return;
		}

		$this->write_debug_log('Redirected to: "' . $url . '".', 'REDIRECTED');

		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . $url);
		exit;
	}

	private function build_url($scheme, $host, $path, $queries)
	{
		return $scheme . '://' . $host . (($path) ?: '/') . (($queries) ? ('?' . http_build_query($queries)) : '');
	}

	private function get_current_url($add_query = true)
	{
		if (!isset($_SERVER)) {
			return '';
		}

		if (!isset($_SERVER['HTTP_HOST'])) {
			return '';
		}

		$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		$parts = parse_url($current_url);

		$queries = [];

		if (isset($parts['query'])) {
			parse_str($parts['query'], $queries);
		}

		return $this->build_url($parts['scheme'], $parts['host'], (isset($parts['path'])) ? $parts['path'] : '', ($add_query) ? $queries : []);
	}

	private function deny($url = '')
	{
		if (empty($url)) {
			header('HTTP/1.1 403 Forbidden');

			echo '
			<html>
				<head>
					<meta http-equiv="content-type" content="text/html;charset=utf-8">
					<title>Error 403: Access Denied</title>
					<style>
						body{font-family:arial,sans-serif}
					</style>
				</head>
				<body>
					<div style="margin:30px; background-color:#fcc">
						<div style="padding:30px">
							<h2>' . __('Connection denied by IP2Location Country Blocker', 'ip2location-country-blocker') . '</h2>
							<div>' . __('Please contact web administrator for assistance.', 'ip2location-country-blocker') . '</div>
						</div>
						<div style="width:100%; padding:10px 0;margin:60px 0 0 0;text-align:center;background-color:#f00;">' . sprintf(__('%1$sIP Geolocation Powered by IP2Location.com%2$s', 'ip2location-country-blocker'), '<a href="https://www.ip2location.com" target="_blank" style="text-decoration:none;color:#fff">', '</a>') . '</div>
					</div>
				</body>
			</html>';

			$this->write_debug_log('Access denied.');

			exit;
		}

		$this->redirect($url);
	}

	private function check_list($country_code, $ban_list, $mode = 1)
	{
		return ($mode == 1) ? $this->is_in_array($country_code, $ban_list) : !$this->is_in_array($country_code, $ban_list);
	}

	private function expand_ban_list($ban_list)
	{
		if (!is_array($ban_list)) {
			return $ban_list;
		}

		$groups = [];

		foreach ($ban_list as $item) {
			if ($this->is_in_array($item, array_keys($this->country_groups))) {
				$groups = array_merge($groups, $this->country_groups[$item]);

				if (($key = array_search($item, $ban_list)) !== false) {
					unset($ban_list[$key]);
				}
			}
		}

		return array_merge($ban_list, $groups);
	}

	private function get_group_from_list($ban_list)
	{
		$groups = [];

		foreach ($ban_list as $item) {
			if ($this->is_in_array($item, array_keys($this->country_groups))) {
				$groups[] = $item;
			}
		}

		return (empty($groups)) ? false : $groups;
	}

	private function is_in_array($needle, $array)
	{
		if (!is_array($array)) {
			return false;
		}

		foreach (array_values($array) as $key) {
			$return[$key] = 1;
		}

		return isset($return[$needle]);
	}

	private function get_location($ip)
	{
		// Read result from cache to prevent duplicate lookup.
		if ($data = $this->cache_get($ip)) {
			$this->session['country'] = $data->country_code;
			$this->session['country_name'] = $data->country_name;
			$this->session['is_proxy'] = $data->is_proxy;
			$this->session['proxy_type'] = $data->proxy_type;
			$this->session['cache'] = true;

			return [
				'country_code' => $data->country_code,
				'country_name' => $data->country_name,
				'is_proxy'     => $data->is_proxy,
				'proxy_type'   => $data->proxy_type,
			];
		}

		$caches = [
			'country_code' => '',
			'country_name' => '',
			'is_proxy'     => '',
			'proxy_type'   => '',
		];

		switch (get_option('ip2location_country_blocker_lookup_mode')) {
			// IP2Location Web Service
			case 'ws':
				$this->session['lookup_mode'] = 'WS';

				if (preg_match('/^[0-9A-Z]{32}$/', get_option('ip2location_country_blocker_api_key'))) {
					$response = wp_remote_get('https://api.ip2location.io/?' . http_build_query([
						'key'    => get_option('ip2location_country_blocker_api_key'),
						'ip'     => $ip,
						'source' => 'wp-country-blocker',
					]), ['timeout' => 5]);

					$json = json_decode($response['body']);

					if ($json === null) {
						$this->write_debug_log('Web service timed out.', 'ERROR');

						return $caches;
					} elseif (isset($json->error)) {
						$this->write_debug_log($json->error->error_message, 'ERROR');

						return $caches;
					} else {
						$caches['country_code'] = $json->country_code;
						$caches['country_name'] = $json->country_name;
					}
				} else {
					$response = wp_remote_get('https://api.ip2location.com/v2/?' . http_build_query([
						'key'     => get_option('ip2location_country_blocker_api_key'),
						'ip'      => $ip,
						'package' => 'WS1',
					]), ['timeout' => 5]);

					$json = json_decode($response['body']);

					if ($json === null) {
						$this->write_debug_log('Web service timed out.', 'ERROR');

						return $caches;
					} elseif ($json->response != 'OK') {
						$this->write_debug_log($json->response, 'ERROR');

						return $caches;
					} else {
						$caches['country_code'] = $json->country_code;
						$caches['country_name'] = $this->get_country_name($json->country_code);
					}
				}

				break;

				// Local BIN database
			default:
				$this->session['lookup_mode'] = 'BIN';

				// Make sure IP2Location database is exist.
				if (!is_file(IP2LOCATION_DIR . get_option('ip2location_country_blocker_database'))) {
					$this->write_debug_log('Database not found.', 'ERROR');

					return $caches;
				}

				// Create IP2Location object.
				$db = new \IP2Location\Database(IP2LOCATION_DIR . get_option('ip2location_country_blocker_database'), \IP2Location\Database::FILE_IO);

				// Get geolocation by IP address.
				$response = $db->lookup($ip, \IP2Location\Database::ALL);

				if (isset($response['countryCode'])) {
					// Store result into cache for later use.
					$caches['country_code'] = $response['countryCode'];
					$caches['country_name'] = $response['countryName'];
				}
				break;
		}

		switch (get_option('ip2location_country_blocker_px_lookup_mode')) {
			// IP2Location Web Service
			case 'px_ws':
				$this->session['lookup_mode'] = 'WS';

				if (preg_match('/^[0-9A-Z]{32}$/', get_option('ip2location_country_blocker_api_key'))) {
					$response = wp_remote_get('https://api.ip2location.io/?' . http_build_query([
						'key'    => get_option('ip2location_country_blocker_px_api_key'),
						'ip'     => $ip,
						'source' => 'wp-country-blocker',
					]), ['timeout' => 3]);

					$json = json_decode($response['body']);

					if (empty($json)) {
						$this->write_debug_log('Web service timed out.', 'ERROR');

						return $caches;
					} elseif (isset($json->error)) {
						$this->write_debug_log($json->error->error_message, 'ERROR');

						return $caches;
					} else {
						// Store result into cache for later use.
						$caches = [
							'country_code' => $json->country_code,
							'country_name' => $json->country_name,
							'is_proxy'     => ($json->is_proxy) ? 'YES' : 'NO',
							'proxy_type'   => (isset($json->proxy_type)) ? $json->proxy_type : null,
						];
					}
				} else {
					$response = wp_remote_get('https://api.ip2proxy.com/?' . http_build_query([
						'key'     => get_option('ip2location_country_blocker_px_api_key'),
						'ip'      => $ip,
						'package' => 'PX2',
					]), ['timeout' => 3]);

					$json = json_decode($response['body']);

					if (empty($json)) {
						$this->write_debug_log('Web service timed out.', 'ERROR');

						return $caches;
					} elseif ($json->response != 'OK') {
						$this->write_debug_log($json->response, 'ERROR');

						return $caches;
					} else {
						// Store result into cache for later use.
						$caches = [
							'country_code' => $json->countryCode,
							'country_name' => $json->countryName,
							'is_proxy'     => ($json->isProxy) ? 'YES' : 'NO',
							'proxy_type'   => $json->proxyType,
						];
					}
				}
				break;

				// Local BIN database
			case 'px_bin':
				if (!get_option('ip2location_country_blocker_px_database')) {
					break;
				}

				$this->session['lookup_mode'] = 'BIN';

				// Make sure IP2Location database is exist.
				if (!is_file(IP2LOCATION_DIR . get_option('ip2location_country_blocker_px_database'))) {
					$this->write_debug_log('Database not found.', 'ERROR');

					return $caches;
				}

				// Create IP2Location object.
				$db = new \IP2Proxy\Database(IP2LOCATION_DIR . get_option('ip2location_country_blocker_px_database'), \IP2Location\Database::FILE_IO);

				// Get geolocation by IP address.
				$response = $db->lookup($ip, \IP2Proxy\Database::ALL);

				// Store result into cache for later use.
				$caches['is_proxy'] = ($response['countryCode'] == '-') ? 'NO' : (($response['countryCode'] != '-' && !in_array($response['proxyType'], ['SES', 'DCH', 'CDN'])) ? 'YES' : 'NO');
				$caches['proxy_type'] = $response['proxyType'];

				break;
		}

		$this->cache_add($ip, $caches);
		$this->session['country'] = $caches['country_code'];
		$this->session['is_proxy'] = $caches['is_proxy'];
		$this->session['proxy_type'] = $caches['proxy_type'];

		return $caches;
	}

	private function get_country_name($code)
	{
		return (isset($this->countries[$code])) ? $this->countries[$code] : '';
	}

	private function is_in_list($ip, $list_name)
	{
		// Expand IPv6
		if (filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6)) {
			$ip = implode(':', str_split(unpack('H*0', inet_pton($ip))[0], 4));
		}

		$rows = explode(';', get_option('ip2location_country_blocker_' . $list_name));

		if (count($rows) > 0) {
			foreach ($rows as $row) {
				// Expand IPv6
				if (filter_var($row, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6)) {
					$row = implode(':', str_split(unpack('H*0', inet_pton($row))[0], 4));
				}

				if ($row == $ip) {
					return true;
				}

				if (strpos($row, '/') !== false) {
					if ($this->cidr_match($ip, $row)) {
						return true;
					}
				} elseif (preg_match('/^' . str_replace(['.', '*'], ['\\.', '.+'], $row) . '$/', $ip)) {
					return true;
				}
			}
		}

		return false;
	}

	private function get_database_date()
	{
		if (!is_file(IP2LOCATION_DIR . get_option('ip2location_country_blocker_database'))) {
			return;
		}

		$obj = new \IP2Location\Database(IP2LOCATION_DIR . get_option('ip2location_country_blocker_database'), \IP2Location\Database::FILE_IO);

		return date('Y-m-d', strtotime(str_replace('.', '-', $obj->getDatabaseVersion())));
	}

	private function get_px_database_date()
	{
		if (!is_file(IP2LOCATION_DIR . get_option('ip2location_country_blocker_px_database'))) {
			return;
		}

		$db = new \IP2Proxy\Database(IP2LOCATION_DIR . get_option('ip2location_country_blocker_px_database'), \IP2PROXY\Database::FILE_IO);

		return date('Y-m-d', strtotime(str_replace('.', '-', $db->getDatabaseVersion())));
	}

	private function cache_add($key, $value)
	{
		file_put_contents(IP2LOCATION_DIR . 'caches' . \DIRECTORY_SEPARATOR . md5($key . '_ip2location_country_blocker') . '.json', json_encode([
			$key => $value,
		]));
	}

	private function cache_get($key)
	{
		if (file_exists(IP2LOCATION_DIR . 'caches' . \DIRECTORY_SEPARATOR . md5($key . '_ip2location_country_blocker') . '.json')) {
			$json = json_decode(file_get_contents(IP2LOCATION_DIR . 'caches' . \DIRECTORY_SEPARATOR . md5($key . '_ip2location_country_blocker') . '.json'));

			return $json->{$key};
		}

		return null;
	}

	private function cache_clear($day = 1)
	{
		require_once ABSPATH . 'wp-admin/includes/file.php';
		WP_Filesystem();
		global $wp_filesystem;

		$now = time();
		$files = scandir(IP2LOCATION_DIR . 'caches');

		foreach ($files as $file) {
			if (substr($file, -5) == '.json') {
				if ($now - filemtime(IP2LOCATION_DIR . 'caches' . \DIRECTORY_SEPARATOR . $file) >= 60 * 60 * 24 * $day) {
					$wp_filesystem->delete(IP2LOCATION_DIR . 'caches' . \DIRECTORY_SEPARATOR . $file);
				}
			}
		}
	}

	private function cache_flush()
	{
		require_once ABSPATH . 'wp-admin/includes/file.php';
		WP_Filesystem();
		global $wp_filesystem;

		$files = scandir(IP2LOCATION_DIR . 'caches');

		foreach ($files as $file) {
			if (substr($file, -5) == '.json') {
				$wp_filesystem->delete(IP2LOCATION_DIR . 'caches' . \DIRECTORY_SEPARATOR . $file);
			}
		}
	}

	private function get_memory_limit()
	{
		$memory_limit = ini_get('memory_limit');

		if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) {
			if ($matches[2] == 'G') {
				$memory_limit = $matches[1] * 1024 * 1024 * 1024;
			} elseif ($matches[2] == 'M') {
				$memory_limit = $matches[1] * 1024 * 1024;
			} elseif ($matches[2] == 'K') {
				$memory_limit = $matches[1] * 1024;
			}
		}

		return $memory_limit;
	}

	private function is_setup_completed()
	{
		if (get_option('ip2location_country_blocker_lookup_mode') == 'ws' && get_option('ip2location_country_blocker_api_key')) {
			return true;
		}

		if (get_option('ip2location_country_blocker_lookup_mode') == 'bin' && is_file(IP2LOCATION_DIR . get_option('ip2location_country_blocker_database'))) {
			return true;
		}

		return false;
	}

	private function sanitize_array($array)
	{
		if (is_string($array)) {
			return esc_attr($array);
		}
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$array[$key] = $this->sanitize_array($value);
			} else {
				$array[$key] = esc_attr($value);
			}
		}

		return $array;
	}

	private function create_table()
	{
		$GLOBALS['wpdb']->query('
		CREATE TABLE IF NOT EXISTS ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_log (
			`log_id` INT(11) NOT NULL AUTO_INCREMENT,
			`ip_address` VARCHAR(39) NOT NULL COLLATE \'utf8_bin\',
			`country_code` CHAR(2) NOT NULL COLLATE \'utf8_bin\',
			`side` CHAR(1) NOT NULL COLLATE \'utf8_bin\',
			`page` VARCHAR(100) NOT NULL COLLATE \'utf8_bin\',
			`date_created` DATETIME NOT NULL,
			PRIMARY KEY (`log_id`),
			INDEX `idx_country_code` (`country_code`),
			INDEX `idx_side` (`side`),
			INDEX `idx_date_created` (`date_created`),
			INDEX `idx_ip_address` (`ip_address`)
		) COLLATE=\'utf8_bin\'');

		$GLOBALS['wpdb']->query('
		CREATE TABLE IF NOT EXISTS ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_frontend_rate_limit_log (
			`log_id` INT(11) NOT NULL AUTO_INCREMENT,
			`ip_address` VARCHAR(39) NOT NULL COLLATE \'utf8_bin\',
			`date_created` DATETIME NOT NULL,
			PRIMARY KEY (`log_id`),
			INDEX `idx_date_created` (`date_created`),
			INDEX `idx_ip_address` (`ip_address`)
		) COLLATE=\'utf8_bin\'');

		$GLOBALS['wpdb']->query('
		CREATE TABLE IF NOT EXISTS ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_backend_rate_limit_log (
			`log_id` INT(11) NOT NULL AUTO_INCREMENT,
			`ip_address` VARCHAR(39) NOT NULL COLLATE \'utf8_bin\',
			`date_created` DATETIME NOT NULL,
			PRIMARY KEY (`log_id`),
			INDEX `idx_date_created` (`date_created`),
			INDEX `idx_ip_address` (`ip_address`)
		) COLLATE=\'utf8_bin\'');

		$GLOBALS['wpdb']->query('DROP TABLE IF EXISTS ' . $GLOBALS['wpdb']->prefix . 'ip2location_country_blocker_rate_limit_log');
	}

	private function cidr_match($ip, $range)
	{
		list($subnet, $bits) = explode('/', $range);

		if (filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4) && filter_var($subnet, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4)) {
			$ip = ip2long($ip);
			$subnet = ip2long($subnet);
			$mask = -1 << (32 - $bits);
			$subnet &= $mask;

			return ($ip & $mask) == $subnet;
		} elseif (filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6) && filter_var($subnet, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6)) {
			$ip = inet_pton($ip);
			$binary = $this->inet_to_bits($ip);

			if (!filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6)) {
				return false;
			}

			$ipBits = substr($binary, 0, $bits);
			$netBits = substr($binary, 0, $bits);

			return $ipBits === $netBits;
		}
	}

	private function inet_to_bits($inet)
	{
		$splitted = str_split($inet);
		$binary = '';
		foreach ($splitted as $char) {
			$binary .= str_pad(decbin(ord($char)), 8, '0', \STR_PAD_LEFT);
		}

		return $binary;
	}
}
