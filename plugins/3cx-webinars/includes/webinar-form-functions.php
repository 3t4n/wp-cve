<?php

function wp3cxw_sanitize_host($host) {
	$host = strtolower( $host );
	$host = preg_replace( '/[^a-z0-9_\\-.:\/]/', '', $host );
	return $host;
}

function wp3cxw_sanitize_token($token) {
	$token = preg_replace( '/[^a-zA-Z0-9]/', '', $token );
	return $token;
}

function wp3cxw_sanitize_extension($ext) {
	$ext = preg_replace( '/[^0-9]/', '', $ext );
	return $ext;
}

function wp3cxw_sanitize_country($country) {
	$a=wp3cxw_get_countries();
	if (!isset($a[$country])) {
		return '';
	}
	return $country;
}

function wp3cxw_webinar_form( $id ) {
	return WP3CXW_WebinarForm::get_instance( $id );
}

function wp3cxw_get_webinar_form_by_title( $title ) {
	$page = get_page_by_title( $title, OBJECT, WP3CXW_WebinarForm::post_type );

	if ( $page ) {
		return wp3cxw_webinar_form( $page->ID );
	}

	return null;
}

function wp3cxw_get_current_webinar_form() {
	if ( $current = WP3CXW_WebinarForm::get_current() ) {
		return $current;
	}
}

function wp3cxw_webinar_form_tag_func( $atts, $content = null, $code = '' ) {
	if ( is_feed() ) {
		return '[3cx-webinar]';
	}

	if ( '3cx-webinar' == $code ) {
		$atts = shortcode_atts(
			array(
				'id' => 0,
				'title' => '',
				'html_id' => '',
				'html_name' => '',
				'html_class' => '',
				'output' => 'form',
			),
			$atts, 'wp3cxw'
		);

		$id = (int) $atts['id'];
		$title = trim( $atts['title'] );

		if ( ! $webinar_form = wp3cxw_webinar_form( $id ) ) {
			$webinar_form = wp3cxw_get_webinar_form_by_title( $title );
		}
	}

	if ( ! $webinar_form ) {
		return '[3cx-webinar 404 "Not Found"]';
	}

	return $webinar_form->form_html( $atts );
}

function wp3cxw_save_webinar_form( $args = '', $context = 'save' ) {
	$args = wp_parse_args( $args, array(
		'id' => -1,
		'title' => null,
		'locale' => null,
		'config' => null
	) );

	$args['id'] = (int) $args['id'];

	if ( -1 == $args['id'] ) {
		$webinar_form = WP3CXW_WebinarForm::get_template();
	} else {
		$webinar_form = wp3cxw_webinar_form( $args['id'] );
	}

	if ( empty( $webinar_form ) ) {
		return false;
	}

	if ( null !== $args['title'] ) {
		$webinar_form->set_title( $args['title'] );
	}

	if ( null !== $args['locale'] ) {
		$webinar_form->set_locale( $args['locale'] );
	}

	$properties = $webinar_form->get_properties();
	
	$properties['config'] = wp3cxw_sanitize_config($args['config'], $properties['config'] );

	$webinar_form->set_properties( $properties );

	do_action( 'wp3cxw_save_webinar_form', $webinar_form, $args, $context );

	if ( 'save' == $context ) {
		$webinar_form->save();
	}

	return $webinar_form;
}

function wp3cxw_sanitize_config( $input, $defaults = array() ) {
	$defaults = wp_parse_args( $defaults, array(
		'active' => false,
		'apitoken' => '',
		'cache_expiry' => 5,
		'portalfqdn' => '',
		'extension' => '',
		'country' => '',
		'maxparticipants' => 0,
    'subject' => '',
    'days' => 0
	) );

	$input = wp_parse_args( $input, $defaults );

	$output = array();
	$output['active'] = (bool)$input['active'];
	$output['apitoken'] = wp3cxw_sanitize_token($input['apitoken']);
	$output['cache_expiry'] = max(min(intval($input['cache_expiry']),60),1);
	$output['portalfqdn'] = wp3cxw_sanitize_host($input['portalfqdn'] );
	$output['extension'] = wp3cxw_sanitize_extension( $input['extension'] );
	$output['country'] = wp3cxw_sanitize_country( $input['country'] );
	$output['maxparticipants'] = max(min(intval($input['maxparticipants']),9999),0);
	$output['subject'] = trim($input['subject']);
	$output['days'] = max(min(intval($input['days']),365),0);
	return $output;
}

function wp3cxw_get_countries() {
	return array (
  'af' => 'Afghanistan',
  'ax' => 'Åland Islands',
  'al' => 'Albania',
  'dz' => 'Algeria',
  'as' => 'American Samoa',
  'ad' => 'Andorra',
  'ao' => 'Angola',
  'ai' => 'Anguilla',
  'aq' => 'Antarctica',
  'ag' => 'Antigua and Barbuda',
  'ar' => 'Argentina',
  'am' => 'Armenia',
  'aw' => 'Aruba',
  'au' => 'Australia',
  'at' => 'Austria',
  'az' => 'Azerbaijan',
  'bs' => 'Bahamas',
  'bh' => 'Bahrain',
  'bd' => 'Bangladesh',
  'bb' => 'Barbados',
  'by' => 'Belarus',
  'be' => 'Belgium',
  'bz' => 'Belize',
  'bj' => 'Benin',
  'bm' => 'Bermuda',
  'bt' => 'Bhutan',
  'bo' => 'Bolivia (Plurinational State of)',
  'bq' => 'Bonaire, Sint Eustatius and Saba',
  'ba' => 'Bosnia and Herzegovina',
  'bw' => 'Botswana',
  'bv' => 'Bouvet Island',
  'br' => 'Brazil',
  'io' => 'British Indian Ocean Territory',
  'bn' => 'Brunei Darussalam',
  'bg' => 'Bulgaria',
  'bf' => 'Burkina Faso',
  'bi' => 'Burundi',
  'cv' => 'Cabo Verde',
  'kh' => 'Cambodia',
  'cm' => 'Cameroon',
  'ca' => 'Canada',
  'ky' => 'Cayman Islands',
  'cf' => 'Central African Republic',
  'td' => 'Chad',
  'cl' => 'Chile',
  'cn' => 'China',
  'cx' => 'Christmas Island',
  'cc' => 'Cocos (Keeling) Islands',
  'co' => 'Colombia',
  'km' => 'Comoros',
  'cg' => 'Congo',
  'cd' => 'Congo, Democratic Republic of the',
  'ck' => 'Cook Islands',
  'cr' => 'Costa Rica',
  'ci' => 'Côte d\'Ivoire',
  'hr' => 'Croatia',
  'cu' => 'Cuba',
  'cw' => 'Curaçao',
  'cy' => 'Cyprus',
  'cz' => 'Czechia',
  'dk' => 'Denmark',
  'dj' => 'Djibouti',
  'dm' => 'Dominica',
  'do' => 'Dominican Republic',
  'ec' => 'Ecuador',
  'eg' => 'Egypt',
  'sv' => 'El Salvador',
  'gq' => 'Equatorial Guinea',
  'er' => 'Eritrea',
  'ee' => 'Estonia',
  'sz' => 'Eswatini',
  'et' => 'Ethiopia',
  'fk' => 'Falkland Islands (Malvinas)',
  'fo' => 'Faroe Islands',
  'fj' => 'Fiji',
  'fi' => 'Finland',
  'fr' => 'France',
  'gf' => 'French Guiana',
  'pf' => 'French Polynesia',
  'tf' => 'French Southern Territories',
  'ga' => 'Gabon',
  'gm' => 'Gambia',
  'ge' => 'Georgia',
  'de' => 'Germany',
  'gh' => 'Ghana',
  'gi' => 'Gibraltar',
  'gr' => 'Greece',
  'gl' => 'Greenland',
  'gd' => 'Grenada',
  'gp' => 'Guadeloupe',
  'gu' => 'Guam',
  'gt' => 'Guatemala',
  'gg' => 'Guernsey',
  'gn' => 'Guinea',
  'gw' => 'Guinea-Bissau',
  'gy' => 'Guyana',
  'ht' => 'Haiti',
  'hm' => 'Heard Island and McDonald Islands',
  'va' => 'Holy See',
  'hn' => 'Honduras',
  'hk' => 'Hong Kong',
  'hu' => 'Hungary',
  'is' => 'Iceland',
  'in' => 'India',
  'id' => 'Indonesia',
  'ir' => 'Iran (Islamic Republic of)',
  'iq' => 'Iraq',
  'ie' => 'Ireland',
  'im' => 'Isle of Man',
  'il' => 'Israel',
  'it' => 'Italy',
  'jm' => 'Jamaica',
  'jp' => 'Japan',
  'je' => 'Jersey',
  'jo' => 'Jordan',
  'kz' => 'Kazakhstan',
  'ke' => 'Kenya',
  'ki' => 'Kiribati',
  'kp' => 'Korea (Democratic People\'s Republic of)',
  'kr' => 'Korea, Republic of',
  'kw' => 'Kuwait',
  'kg' => 'Kyrgyzstan',
  'la' => 'Lao People\'s Democratic Republic',
  'lv' => 'Latvia',
  'lb' => 'Lebanon',
  'ls' => 'Lesotho',
  'lr' => 'Liberia',
  'ly' => 'Libya',
  'li' => 'Liechtenstein',
  'lt' => 'Lithuania',
  'lu' => 'Luxembourg',
  'mo' => 'Macao',
  'mk' => 'Macedonia, the former Yugoslav Republic of',
  'mg' => 'Madagascar',
  'mw' => 'Malawi',
  'my' => 'Malaysia',
  'mv' => 'Maldives',
  'ml' => 'Mali',
  'mt' => 'Malta',
  'mh' => 'Marshall Islands',
  'mq' => 'Martinique',
  'mr' => 'Mauritania',
  'mu' => 'Mauritius',
  'yt' => 'Mayotte',
  'mx' => 'Mexico',
  'fm' => 'Micronesia (Federated States of)',
  'md' => 'Moldova, Republic of',
  'mc' => 'Monaco',
  'mn' => 'Mongolia',
  'me' => 'Montenegro',
  'ms' => 'Montserrat',
  'ma' => 'Morocco',
  'mz' => 'Mozambique',
  'mm' => 'Myanmar',
  'na' => 'Namibia',
  'nr' => 'Nauru',
  'np' => 'Nepal',
  'nl' => 'Netherlands',
  'nc' => 'New Caledonia',
  'nz' => 'New Zealand',
  'ni' => 'Nicaragua',
  'ne' => 'Niger',
  'ng' => 'Nigeria',
  'nu' => 'Niue',
  'nf' => 'Norfolk Island',
  'mp' => 'Northern Mariana Islands',
  'no' => 'Norway',
  'om' => 'Oman',
  'pk' => 'Pakistan',
  'pw' => 'Palau',
  'ps' => 'Palestine, State of',
  'pa' => 'Panama',
  'pg' => 'Papua New Guinea',
  'py' => 'Paraguay',
  'pe' => 'Peru',
  'ph' => 'Philippines',
  'pn' => 'Pitcairn',
  'pl' => 'Poland',
  'pt' => 'Portugal',
  'pr' => 'Puerto Rico',
  'qa' => 'Qatar',
  're' => 'Réunion',
  'ro' => 'Romania',
  'ru' => 'Russian Federation',
  'rw' => 'Rwanda',
  'bl' => 'Saint Barthélemy',
  'sh' => 'Saint Helena, Ascension and Tristan da Cunha',
  'kn' => 'Saint Kitts and Nevis',
  'lc' => 'Saint Lucia',
  'mf' => 'Saint Martin (French part)',
  'pm' => 'Saint Pierre and Miquelon',
  'vc' => 'Saint Vincent and the Grenadines',
  'ws' => 'Samoa',
  'sm' => 'San Marino',
  'st' => 'Sao Tome and Principe',
  'sa' => 'Saudi Arabia',
  'sn' => 'Senegal',
  'rs' => 'Serbia',
  'sc' => 'Seychelles',
  'sl' => 'Sierra Leone',
  'sg' => 'Singapore',
  'sx' => 'Sint Maarten (Dutch part)',
  'sk' => 'Slovakia',
  'si' => 'Slovenia',
  'sb' => 'Solomon Islands',
  'so' => 'Somalia',
  'za' => 'South Africa',
  'gs' => 'South Georgia and the South Sandwich Islands',
  'ss' => 'South Sudan',
  'es' => 'Spain',
  'lk' => 'Sri Lanka',
  'sd' => 'Sudan',
  'sr' => 'Suriname',
  'sj' => 'Svalbard and Jan Mayen',
  'se' => 'Sweden',
  'ch' => 'Switzerland',
  'sy' => 'Syrian Arab Republic',
  'tw' => '&#91;a&#93;',
  'tj' => 'Tajikistan',
  'tz' => 'Tanzania, United Republic of',
  'th' => 'Thailand',
  'tl' => 'Timor-Leste',
  'tg' => 'Togo',
  'tk' => 'Tokelau',
  'to' => 'Tonga',
  'tt' => 'Trinidad and Tobago',
  'tn' => 'Tunisia',
  'tr' => 'Turkey',
  'tm' => 'Turkmenistan',
  'tc' => 'Turks and Caicos Islands',
  'tv' => 'Tuvalu',
  'ug' => 'Uganda',
  'ua' => 'Ukraine',
  'ae' => 'United Arab Emirates',
  'gb' => 'United Kingdom of Great Britain and Northern Ireland',
  'us' => 'United States of America',
  'um' => 'United States Minor Outlying Islands',
  'uy' => 'Uruguay',
  'uz' => 'Uzbekistan',
  'vu' => 'Vanuatu',
  've' => 'Venezuela (Bolivarian Republic of)',
  'vn' => 'Viet Nam',
  'vg' => 'Virgin Islands (British)',
  'vi' => 'Virgin Islands (U.S.)',
  'wf' => 'Wallis and Futuna',
  'eh' => 'Western Sahara',
  'ye' => 'Yemen',
  'zm' => 'Zambia',
  'zw' => 'Zimbabwe'
	);
}

