<?php
/*
Plugin Name: azurecurve Flags
Plugin URI: http://development.azurecurve.co.uk/plugins/flags

Description: Allows a 16x16 flag to be displayed in a post of page using a shortcode.
Version: 2.2.0

Author: azurecurve
Author URI: http://development.azurecurve.co.uk

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.

The full copy of the GNU General Public License is available here: http://www.gnu.org/licenses/gpl.txt

*/

//include menu
require_once( dirname(  __FILE__ ) . '/includes/menu.php');

function azc_f_flag($atts, $content = null) {
	if (empty($atts)){
		$flag = 'none';
	}else{
		$attribs = implode('',$atts);
		$flag = trim ( trim ( trim ( trim ( trim ( $attribs , '=' ) , '"' ) , "'" ) , '&#8217;' ) , "&#8221;" );
	}
	$flag = esc_html($flag);
	$country_name = azc_f_get_country_name($flag);
	return "<img class='azc_flags' src='".plugin_dir_url(__FILE__)."images/".esc_html($flag).".png' alt= '".esc_html($country_name)."' />";
}
add_shortcode( 'flag', 'azc_f_flag' );
add_shortcode( 'flags', 'azc_f_flag' );
add_shortcode( 'FLAG', 'azc_f_flag' );
add_shortcode( 'FLAGS', 'azc_f_flag' );

function azc_f_load_css(){
	wp_enqueue_style( 'azurecurve-flags', plugins_url( 'style.css', __FILE__ ) );
}
add_action('wp_enqueue_scripts', 'azc_f_load_css');

// Add Action Link
function azc_f_plugin_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=azc-f">'.__('Settings' ,'azc-i').'</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}
add_filter('plugin_action_links', 'azc_f_plugin_action_links', 10, 2);

function azc_create_f_plugin_menu() {
	global $admin_page_hooks;
    
	add_submenu_page( "azc-plugin-menus"
						,"Flags"
						,"Flags"
						,'manage_options'
						,"azc-f"
						,"azc_f_settings" );
}
add_action("admin_menu", "azc_create_f_plugin_menu");

function azc_f_settings() {
	if (!current_user_can('manage_options')) {
		$error = new WP_Error('not_found', __('You do not have sufficient permissions to access this page.' , 'azc_md'), array('response' => '200'));
		if(is_wp_error($error)){
			wp_die($error, '', $error->get_error_data());
		}
    }
	?>
	<div id="azc-t-general" class="wrap">
			<h2>azurecurve Flags</h2>

			<label for="explanation">
				<p>azurecurve Flags <?php _e('allows a 16x16 flag to be displayed in a post of page using a [flag] shortcode.', 'azc_md'); ?></p>
				<p><?php _e('Format of shortcode is [flag=gb] to display the flag of the United Kingdom of Great Britain and Northern Ireland; 247 flags are included.', 'azc_md'); ?></p>
				<p><?php _e('Defintion of flags can be found at Wikipedia page ISO 3166-1 alpha-2: ', 'azc_md'); ?><a href='https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2'>https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2</a></p>
			</label>
			<p>
			Available flags are:
			
			<?php $dir = plugin_dir_path(__FILE__).'/images';
			$flags = array();
			if (is_dir($dir)){
				if ($directory = opendir($dir)){
					while (($file = readdir($directory)) !== false){
						if ($file != '.' and $file != '..' and $file != 'Thumbs.db' and $file != 'index.php' and $file != 'Favicon-16x16.png'){
							$filewithoutext = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
							$flags[] = $filewithoutext;
						}
					}
					closedir($directory);
				}
				asort($flags);
				
				if ($directory = opendir($dir)){
					foreach ($flags as $flag){	
						$country_name = azc_f_get_country_name($flag);
						echo "<div style='width: 200px; display: inline-block;'><img src='";
						echo plugin_dir_url(__FILE__)."images/".esc_html($flag).".png' alt='".esc_html($country_name)."' />&nbsp;<em>".esc_html($country_name)." (".esc_html($flag).")</em></div>";
					}
				}
			}
			?>
				
			</p>
			<label for="additional-plugins">
				azurecurve <?php _e('has the following plugins which allow shortcodes to be used in comments and widgets:', 'azc_md'); ?>
			</label>
			<ul class='azc_plugin_index'>
				<li>
					<?php
					if ( is_plugin_active( 'azurecurve-shortcodes-in-comments/azurecurve-shortcodes-in-comments.php' ) ) {
						echo "<a href='admin.php?page=azc-sic' class='azc_plugin_index'>Shortcodes in Comments</a>";
					}else{
						echo "<a href='https://wordpress.org/plugins/azurecurve-shortcodes-in-comments/' class='azc_plugin_index'>Shortcodes in Comments</a>";
					}
					?>
				</li>
				<li>
					<?php
					if ( is_plugin_active( 'azurecurve-shortcodes-in-widgets/azurecurve-shortcodes-in-widgets.php' ) ) {
						echo "<a href='admin.php?page=azc-siw' class='azc_plugin_index'>Shortcodes in Widgets</a>";
					}else{
						echo "<a href='https://wordpress.org/plugins/azurecurve-shortcodes-in-widgets/' class='azc_plugin_index'>Shortcodes in Widgets</a>";
					}
					?>
				</li>
			</ul>
	</div>
<?php }


/**
 * Get country name.
 *
 * @since 2.2.0
 *
 */
function azc_f_get_country_name($country_code){

$countries = array(
				'AW' => 'Aruba',
				'AG' => 'Antigua and Barbuda',
				'AE' => 'United Arab Emirates',
				'AF' => 'Afghanistan',
				'DZ' => 'Algeria',
				'AZ' => 'Azerbaijan',
				'AL' => 'Albania',
				'AM' => 'Armenia',
				'AD' => 'Andorra',
				'AO' => 'Angola',
				'AS' => 'American Samoa',
				'AR' => 'Argentina',
				'AU' => 'Australia',
				'AT' => 'Austria',
				'AI' => 'Anguilla',
				'AX' => 'Aland Islands',
				'AQ' => 'Antarctica',
				'BH' => 'Bahrain',
				'BB' => 'Barbados',
				'BW' => 'Botswana',
				'BM' => 'Bermuda',
				'BE' => 'Belgium',
				'BS' => 'Bahamas, The',
				'BD' => 'Bangladesh',
				'BZ' => 'Belize',
				'BA' => 'Bosnia and Herzegovina',
				'BO' => 'Bolivia Plurinational State of',
				'BL' => 'Saint Barthelemy',
				'MM' => 'Myanmar',
				'BJ' => 'Benin',
				'BY' => 'Belarus',
				'SB' => 'Solomon Islands',
				'BR' => 'Brazil',
				'BT' => 'Bhutan',
				'BG' => 'Bulgaria',
				'BV' => 'Bouvet Island',
				'BN' => 'Brunei',
				'BI' => 'Burundi',
				'CA' => 'Canada',
				'KH' => 'Cambodia',
				'TD' => 'Chad',
				'LK' => 'Sri Lanka',
				'CG' => 'Congo, Republic of the',
				'CD' => 'Congo Democratic Republic of the',
				'CN' => 'China',
				'CL' => 'Chile',
				'KY' => 'Cayman Islands',
				'CC' => 'Cocos Keeling Islands',
				'CM' => 'Cameroon',
				'KM' => 'Comoros',
				'CO' => 'Colombia',
				'MP' => 'Northern Mariana Islands',
				'CR' => 'Costa Rica',
				'CF' => 'Central African Republic',
				'CU' => 'Cuba',
				'CV' => 'Cabo Verde',
				'CK' => 'Cook Islands',
				'CY' => 'Cyprus',
				'DK' => 'Denmark',
				'DJ' => 'Djibouti',
				'DM' => 'Dominica',
				'DO' => 'Dominican Republic',
				'EC' => 'Ecuador',
				'EG' => 'Egypt',
				'IE' => 'Ireland',
				'GQ' => 'Equatorial Guinea',
				'EE' => 'Estonia',
				'ER' => 'Eritrea',
				'SV' => 'El Salvador',
				'ET' => 'Ethiopia',
				'CZ' => 'Czechia',
				'GF' => 'French Guiana',
				'FI' => 'Finland',
				'FJ' => 'Fiji',
				'FK' => 'Falkland Islands Islas Malvinas',
				'FM' => 'Micronesia Federated States of',
				'FO' => 'Faroe Islands',
				'PF' => 'French Polynesia',
				'FR' => 'France',
				'TF' => 'French Southern and Antarctic Lands',
				'GM' => 'Gambia, The',
				'GA' => 'Gabon',
				'GE' => 'Georgia',
				'GH' => 'Ghana',
				'GI' => 'Gibraltar',
				'GD' => 'Grenada',
				'GG' => 'Guernsey',
				'GL' => 'Greenland',
				'DE' => 'Germany',
				'GP' => 'Guadeloupe',
				'GU' => 'Guam',
				'GR' => 'Greece',
				'GT' => 'Guatemala',
				'GN' => 'Guinea',
				'GY' => 'Guyana',
				'HT' => 'Haiti',
				'HK' => 'Hong Kong',
				'HM' => 'Heard Island and McDonald Islands',
				'HN' => 'Honduras',
				'HR' => 'Croatia',
				'HU' => 'Hungary',
				'IS' => 'Iceland',
				'ID' => 'Indonesia',
				'IM' => 'Isle of Man',
				'IN' => 'India',
				'IO' => 'British Indian Ocean Territory',
				'IR' => 'Iran Islamic Republic of',
				'IL' => 'Israel',
				'IT' => 'Italy',
				'CI' => 'Cote d\'Ivoire',
				'IQ' => 'Iraq',
				'JP' => 'Japan',
				'JE' => 'Jersey',
				'JM' => 'Jamaica',
				'SJ' => 'Jan Mayen',
				'JO' => 'Jordan',
				'KE' => 'Kenya',
				'KG' => 'Kyrgyzstan',
				'KP' => 'Korea Democratic People\'s Republic of',
				'KI' => 'Kiribati',
				'KR' => 'Korea Republic of',
				'CX' => 'Christmas Island',
				'KW' => 'Kuwait',
				'XK' => 'Kosovo',
				'KZ' => 'Kazakhstan',
				'LA' => 'Laos',
				'LB' => 'Lebanon',
				'LV' => 'Latvia',
				'LT' => 'Lithuania',
				'LR' => 'Liberia',
				'SK' => 'Slovakia',
				'UM' => 'United States Minor Outlying Islands',
				'LI' => 'Liechtenstein',
				'LS' => 'Lesotho',
				'LU' => 'Luxembourg',
				'LY' => 'Libya',
				'MG' => 'Madagascar',
				'MQ' => 'Martinique',
				'MO' => 'Macau',
				'MD' => 'Moldova Republic of',
				'YT' => 'Mayotte',
				'MN' => 'Mongolia',
				'MS' => 'Montserrat',
				'MW' => 'Malawi',
				'ME' => 'Montenegro',
				'MK' => 'North Macedonia',
				'ML' => 'Mali',
				'MC' => 'Monaco',
				'MA' => 'Morocco',
				'MU' => 'Mauritius',
				'MR' => 'Mauritania',
				'MT' => 'Malta',
				'OM' => 'Oman',
				'MV' => 'Maldives',
				'MX' => 'Mexico',
				'MY' => 'Malaysia',
				'MZ' => 'Mozambique',
				'NC' => 'New Caledonia',
				'NU' => 'Niue',
				'NF' => 'Norfolk Island',
				'NE' => 'Niger',
				'VU' => 'Vanuatu',
				'NG' => 'Nigeria',
				'NL' => 'Netherlands',
				'NO' => 'Norway',
				'NP' => 'Nepal',
				'NR' => 'Nauru',
				'SR' => 'Suriname',
				'BQ' => 'Bonaire, Sint Eustatius and Saba',
				'NI' => 'Nicaragua',
				'NZ' => 'New Zealand',
				'PY' => 'Paraguay',
				'PN' => 'Pitcairn Islands',
				'PE' => 'Peru',
				'PK' => 'Pakistan',
				'PL' => 'Poland',
				'PA' => 'Panama',
				'PT' => 'Portugal',
				'PG' => 'Papua New Guinea',
				'PW' => 'Palau',
				'GW' => 'Guinea-Bissau',
				'QA' => 'Qatar',
				'RE' => 'Reunion',
				'RS' => 'Serbia',
				'MH' => 'Marshall Islands',
				'MF' => 'Saint Martin',
				'RO' => 'Romania',
				'PH' => 'Philippines',
				'PR' => 'Puerto Rico',
				'RU' => 'Russia',
				'RW' => 'Rwanda',
				'SA' => 'Saudi Arabia',
				'PM' => 'Saint Pierre and Miquelon',
				'KN' => 'Saint Kitts and Nevis',
				'SC' => 'Seychelles',
				'ZA' => 'South Africa',
				'SN' => 'Senegal',
				'SH' => 'Saint Helena',
				'SI' => 'Slovenia',
				'SL' => 'Sierra Leone',
				'SM' => 'San Marino',
				'SG' => 'Singapore',
				'SO' => 'Somalia',
				'ES' => 'Spain',
				'SS' => 'South Sudan',
				'LC' => 'Saint Lucia',
				'SD' => 'Sudan',
				'SJ' => 'Svalbard',
				'SE' => 'Sweden',
				'GS' => 'South Georgia and the South Sandwich Islands',
				'SX' => 'Sint Maarten',
				'SY' => 'Syrian Arab Republic',
				'CH' => 'Switzerland',
				'TT' => 'Trinidad and Tobago',
				'TH' => 'Thailand',
				'TJ' => 'Tajikistan',
				'TC' => 'Turks and Caicos Islands',
				'TK' => 'Tokelau',
				'TO' => 'Tonga',
				'TG' => 'Togo',
				'ST' => 'Sao Tome and Principe',
				'TN' => 'Tunisia',
				'TL' => 'Timor-Leste',
				'TR' => 'Turkey',
				'TV' => 'Tuvalu',
				'TW' => 'Taiwan',
				'TM' => 'Turkmenistan',
				'TZ' => 'Tanzania, United Republic of',
				'CW' => 'Curacao',
				'UG' => 'Uganda',
				'GB' => 'United Kingdom of Great Britain and Northern Ireland',
				'UA' => 'Ukraine',
				'US' => 'United States of America',
				'BF' => 'Burkina Faso',
				'UY' => 'Uruguay',
				'UZ' => 'Uzbekistan',
				'VC' => 'Saint Vincent and the Grenadines',
				'VE' => 'Venezuela Bolivarian Republic of',
				'VG' => 'Virgin Islands British',
				'VN' => 'Vietnam',
				'VI' => 'Virgin Islands U.S.',
				'VA' => 'Holy See',
				'NA' => 'Namibia',
				'PS' => 'Palestine, State of',
				'WF' => 'Wallis and Futuna',
				'EH' => 'Western Sahara',
				'WS' => 'Samoa',
				'SZ' => 'Eswatini',
				'CS' => 'Serbia and Montenegro',
				'YE' => 'Yemen',
				'ZM' => 'Zambia',
				'ZW' => 'Zimbabwe',
				'ENGLAND' => 'England',
				'WALES' => 'Wales',
				'SCOTLAND' => 'Scotland',
				'NORTHERNIRELAND' => 'Northern Ireland',
				'NORTHUMBERLAND' => 'Northumberland',
				'CURACAO' => 'Curaçao',
				'ULSTER' => 'Ulster Banner',
				'EUROPEANUNION' => 'European Union',
			);
			
	$country_name = $countries[strtoupper($country_code)];
	
	if (strlen($country_name) == 0){
		$country_name = $country_code;
	}
	
	return $country_name;
}