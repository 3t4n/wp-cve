<?php
function directorypress_has_map() {
	if(directorypress_is_maps_active()){
		return true;
	}else{
		return false;
	}
}
function directorypress_countrycodes() {
	$countrycodes['Afghanistan'] = 'AF';
	$countrycodes['Åland Islands'] = 'AX';
	$countrycodes['Albania'] = 'AL';
	$countrycodes['Algeria'] = 'DZ';
	$countrycodes['American Samoa'] = 'AS';
	$countrycodes['Andorra'] = 'AD';
	$countrycodes['Angola'] = 'AO';
	$countrycodes['Anguilla'] = 'AI';
	$countrycodes['Antarctica'] = 'AQ';
	$countrycodes['Antigua and Barbuda'] = 'AG';
	$countrycodes['Argentina'] = 'AR';
	$countrycodes['Armenia'] = 'AM';
	$countrycodes['Aruba'] = 'AW';
	$countrycodes['Australia'] = 'AU';
	$countrycodes['Austria'] = 'AT';
	$countrycodes['Azerbaijan'] = 'AZ';
	$countrycodes['Bahamas'] = 'BS';
	$countrycodes['Bahrain'] = 'BH';
	$countrycodes['Bangladesh'] = 'BD';
	$countrycodes['Barbados'] = 'BB';
	$countrycodes['Belarus'] = 'BY';
	$countrycodes['Belgium'] = 'BE';
	$countrycodes['Belize'] = 'BZ';
	$countrycodes['Benin'] = 'BJ';
	$countrycodes['Bermuda'] = 'BM';
	$countrycodes['Bhutan'] = 'BT';
	$countrycodes['Bolivia, Plurinational State of'] = 'BO';
	$countrycodes['Bonaire, Sint Eustatius and Saba'] = 'BQ';
	$countrycodes['Bosnia and Herzegovina'] = 'BA';
	$countrycodes['Botswana'] = 'BW';
	$countrycodes['Bouvet Island'] = 'BV';
	$countrycodes['Brazil'] = 'BR';
	$countrycodes['British Indian Ocean Territory'] = 'IO';
	$countrycodes['Brunei Darussalam'] = 'BN';
	$countrycodes['Bulgaria'] = 'BG';
	$countrycodes['Burkina Faso'] = 'BF';
	$countrycodes['Burundi'] = 'BI';
	$countrycodes['Cambodia'] = 'KH';
	$countrycodes['Cameroon'] = 'CM';
	$countrycodes['Canada'] = 'CA';
	$countrycodes['Cape Verde'] = 'CV';
	$countrycodes['Cayman Islands'] = 'KY';
	$countrycodes['Central African Republic'] = 'CF';
	$countrycodes['Chad'] = 'TD';
	$countrycodes['Chile'] = 'CL';
	$countrycodes['China'] = 'CN';
	$countrycodes['Christmas Island'] = 'CX';
	$countrycodes['Cocos (Keeling) Islands'] = 'CC';
	$countrycodes['Colombia'] = 'CO';
	$countrycodes['Comoros'] = 'KM';
	$countrycodes['Congo'] = 'CG';
	$countrycodes['Congo, the Democratic Republic of the'] = 'CD';
	$countrycodes['Cook Islands'] = 'CK';
	$countrycodes['Costa Rica'] = 'CR';
	$countrycodes['Côte d\'Ivoire'] = 'CI';
	$countrycodes['Croatia'] = 'HR';
	$countrycodes['Cuba'] = 'CU';
	$countrycodes['Curaçao'] = 'CW';
	$countrycodes['Cyprus'] = 'CY';
	$countrycodes['Czech Republic'] = 'CZ';
	$countrycodes['Denmark'] = 'DK';
	$countrycodes['Djibouti'] = 'DJ';
	$countrycodes['Dominica'] = 'DM';
	$countrycodes['Dominican Republic'] = 'DO';
	$countrycodes['Ecuador'] = 'EC';
	$countrycodes['Egypt'] = 'EG';
	$countrycodes['El Salvador'] = 'SV';
	$countrycodes['Equatorial Guinea'] = 'GQ';
	$countrycodes['Eritrea'] = 'ER';
	$countrycodes['Estonia'] = 'EE';
	$countrycodes['Ethiopia'] = 'ET';
	$countrycodes['Falkland Islands (Malvinas)'] = 'FK';
	$countrycodes['Faroe Islands'] = 'FO';
	$countrycodes['Fiji'] = 'FJ';
	$countrycodes['Finland'] = 'FI';
	$countrycodes['France'] = 'FR';
	$countrycodes['French Guiana'] = 'GF';
	$countrycodes['French Polynesia'] = 'PF';
	$countrycodes['French Southern Territories'] = 'TF';
	$countrycodes['Gabon'] = 'GA';
	$countrycodes['Gambia'] = 'GM';
	$countrycodes['Georgia'] = 'GE';
	$countrycodes['Germany'] = 'DE';
	$countrycodes['Ghana'] = 'GH';
	$countrycodes['Gibraltar'] = 'GI';
	$countrycodes['Greece'] = 'GR';
	$countrycodes['Greenland'] = 'GL';
	$countrycodes['Grenada'] = 'GD';
	$countrycodes['Guadeloupe'] = 'GP';
	$countrycodes['Guam'] = 'GU';
	$countrycodes['Guatemala'] = 'GT';
	$countrycodes['Guernsey'] = 'GG';
	$countrycodes['Guinea'] = 'GN';
	$countrycodes['Guinea-Bissau'] = 'GW';
	$countrycodes['Guyana'] = 'GY';
	$countrycodes['Haiti'] = 'HT';
	$countrycodes['Heard Island and McDonald Islands'] = 'HM';
	$countrycodes['Holy See (Vatican City State)'] = 'VA';
	$countrycodes['Honduras'] = 'HN';
	$countrycodes['Hong Kong'] = 'HK';
	$countrycodes['Hungary'] = 'HU';
	$countrycodes['Iceland'] = 'IS';
	$countrycodes['India'] = 'IN';
	$countrycodes['Indonesia'] = 'ID';
	$countrycodes['Iran, Islamic Republic of'] = 'IR';
	$countrycodes['Iraq'] = 'IQ';
	$countrycodes['Ireland'] = 'IE';
	$countrycodes['Isle of Man'] = 'IM';
	$countrycodes['Israel'] = 'IL';
	$countrycodes['Italy'] = 'IT';
	$countrycodes['Jamaica'] = 'JM';
	$countrycodes['Japan'] = 'JP';
	$countrycodes['Jersey'] = 'JE';
	$countrycodes['Jordan'] = 'JO';
	$countrycodes['Kazakhstan'] = 'KZ';
	$countrycodes['Kenya'] = 'KE';
	$countrycodes['Kiribati'] = 'KI';
	$countrycodes['Korea, Democratic People\'s Republic of'] = 'KP';
	$countrycodes['Korea, Republic of'] = 'KR';
	$countrycodes['Kuwait'] = 'KW';
	$countrycodes['Kyrgyzstan'] = 'KG';
	$countrycodes['Lao People\'s Democratic Republic'] = 'LA';
	$countrycodes['Latvia'] = 'LV';
	$countrycodes['Lebanon'] = 'LB';
	$countrycodes['Lesotho'] = 'LS';
	$countrycodes['Liberia'] = 'LR';
	$countrycodes['Libya'] = 'LY';
	$countrycodes['Liechtenstein'] = 'LI';
	$countrycodes['Lithuania'] = 'LT';
	$countrycodes['Luxembourg'] = 'LU';
	$countrycodes['Macao'] = 'MO';
	$countrycodes['Macedonia, the Former Yugoslav Republic of'] = 'MK';
	$countrycodes['Madagascar'] = 'MG';
	$countrycodes['Malawi'] = 'MW';
	$countrycodes['Malaysia'] = 'MY';
	$countrycodes['Maldives'] = 'MV';
	$countrycodes['Mali'] = 'ML';
	$countrycodes['Malta'] = 'MT';
	$countrycodes['Marshall Islands'] = 'MH';
	$countrycodes['Martinique'] = 'MQ';
	$countrycodes['Mauritania'] = 'MR';
	$countrycodes['Mauritius'] = 'MU';
	$countrycodes['Mayotte'] = 'YT';
	$countrycodes['Mexico'] = 'MX';
	$countrycodes['Micronesia, Federated States of'] = 'FM';
	$countrycodes['Moldova, Republic of'] = 'MD';
	$countrycodes['Monaco'] = 'MC';
	$countrycodes['Mongolia'] = 'MN';
	$countrycodes['Montenegro'] = 'ME';
	$countrycodes['Montserrat'] = 'MS';
	$countrycodes['Morocco'] = 'MA';
	$countrycodes['Mozambique'] = 'MZ';
	$countrycodes['Myanmar'] = 'MM';
	$countrycodes['Namibia'] = 'NA';
	$countrycodes['Nauru'] = 'NR';
	$countrycodes['Nepal'] = 'NP';
	$countrycodes['Netherlands'] = 'NL';
	$countrycodes['New Caledonia'] = 'NC';
	$countrycodes['New Zealand'] = 'NZ';
	$countrycodes['Nicaragua'] = 'NI';
	$countrycodes['Niger'] = 'NE';
	$countrycodes['Nigeria'] = 'NG';
	$countrycodes['Niue'] = 'NU';
	$countrycodes['Norfolk Island'] = 'NF';
	$countrycodes['Northern Mariana Islands'] = 'MP';
	$countrycodes['Norway'] = 'NO';
	$countrycodes['Oman'] = 'OM';
	$countrycodes['Pakistan'] = 'PK';
	$countrycodes['Palau'] = 'PW';
	$countrycodes['Palestine, State of'] = 'PS';
	$countrycodes['Panama'] = 'PA';
	$countrycodes['Papua New Guinea'] = 'PG';
	$countrycodes['Paraguay'] = 'PY';
	$countrycodes['Peru'] = 'PE';
	$countrycodes['Philippines'] = 'PH';
	$countrycodes['Pitcairn'] = 'PN';
	$countrycodes['Poland'] = 'PL';
	$countrycodes['Portugal'] = 'PT';
	$countrycodes['Puerto Rico'] = 'PR';
	$countrycodes['Qatar'] = 'QA';
	$countrycodes['Réunion'] = 'RE';
	$countrycodes['Romania'] = 'RO';
	$countrycodes['Russian Federation'] = 'RU';
	$countrycodes['Rwanda'] = 'RW';
	$countrycodes['Saint Barthélemy'] = 'BL';
	$countrycodes['Saint Helena, Ascension and Tristan da Cunha'] = 'SH';
	$countrycodes['Saint Kitts and Nevis'] = 'KN';
	$countrycodes['Saint Lucia'] = 'LC';
	$countrycodes['Saint Martin (French part)'] = 'MF';
	$countrycodes['Saint Pierre and Miquelon'] = 'PM';
	$countrycodes['Saint Vincent and the Grenadines'] = 'VC';
	$countrycodes['Samoa'] = 'WS';
	$countrycodes['San Marino'] = 'SM';
	$countrycodes['Sao Tome and Principe'] = 'ST';
	$countrycodes['Saudi Arabia'] = 'SA';
	$countrycodes['Senegal'] = 'SN';
	$countrycodes['Serbia'] = 'RS';
	$countrycodes['Seychelles'] = 'SC';
	$countrycodes['Sierra Leone'] = 'SL';
	$countrycodes['Singapore'] = 'SG';
	$countrycodes['Sint Maarten (Dutch part)'] = 'SX';
	$countrycodes['Slovakia'] = 'SK';
	$countrycodes['Slovenia'] = 'SI';
	$countrycodes['Solomon Islands'] = 'SB';
	$countrycodes['Somalia'] = 'SO';
	$countrycodes['South Africa'] = 'ZA';
	$countrycodes['South Georgia and the South Sandwich Islands'] = 'GS';
	$countrycodes['South Sudan'] = 'SS';
	$countrycodes['Spain'] = 'ES';
	$countrycodes['Sri Lanka'] = 'LK';
	$countrycodes['Sudan'] = 'SD';
	$countrycodes['Suriname'] = 'SR';
	$countrycodes['Svalbard and Jan Mayen'] = 'SJ';
	$countrycodes['Swaziland'] = 'SZ';
	$countrycodes['Sweden'] = 'SE';
	$countrycodes['Switzerland'] = 'CH';
	$countrycodes['Syrian Arab Republic'] = 'SY';
	$countrycodes['Taiwan, Province of China"'] = 'TW';
	$countrycodes['Tajikistan'] = 'TJ';
	$countrycodes['"Tanzania, United Republic of"'] = 'TZ';
	$countrycodes['Thailand'] = 'TH';
	$countrycodes['Timor-Leste'] = 'TL';
	$countrycodes['Togo'] = 'TG';
	$countrycodes['Tokelau'] = 'TK';
	$countrycodes['Tonga'] = 'TO';
	$countrycodes['Trinidad and Tobago'] = 'TT';
	$countrycodes['Tunisia'] = 'TN';
	$countrycodes['Turkey'] = 'TR';
	$countrycodes['Turkmenistan'] = 'TM';
	$countrycodes['Turks and Caicos Islands'] = 'TC';
	$countrycodes['Tuvalu'] = 'TV';
	$countrycodes['Uganda'] = 'UG';
	$countrycodes['Ukraine'] = 'UA';
	$countrycodes['United Arab Emirates'] = 'AE';
	$countrycodes['United Kingdom'] = 'GB';
	$countrycodes['United States'] = 'US';
	$countrycodes['United States Minor Outlying Islands'] = 'UM';
	$countrycodes['Uruguay'] = 'UY';
	$countrycodes['Uzbekistan'] = 'UZ';
	$countrycodes['Vanuatu'] = 'VU';
	$countrycodes['Venezuela,  Bolivarian Republic of'] = 'VE';
	$countrycodes['Viet Nam'] = 'VN';
	$countrycodes['Virgin Islands, British'] = 'VG';
	$countrycodes['Virgin Islands, U.S.'] = 'VI';
	$countrycodes['Wallis and Futuna'] = 'WF';
	$countrycodes['Western Sahara'] = 'EH';
	$countrycodes['Yemen'] = 'YE';
	$countrycodes['Zambia'] = 'ZM';
	$countrycodes['Zimbabwe'] = 'ZW';
	return $countrycodes;
}

function directorypress_map_type() {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_map_type'] == 'mapbox') {
		return 'mapbox';
	}elseif ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_map_type'] == 'google') {
		return 'google';
	}else{
		return 'mapbox';
	}
}

function directorypress_map_styles() {
	if (directorypress_map_type() == 'google') {
		global $directorypress_google_maps_styles;
		
		return $directorypress_google_maps_styles;
	} elseif (directorypress_map_type() == 'mapbox') {
		return directorypress_mapbox_styles();
	}
}

function directorypress_map_name_selected() {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if (directorypress_map_type() == 'google') {
		return $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_map_style'];
	} elseif (directorypress_map_type() == 'mapbox') {
		$style = 'streets-v10';
		return $style;
	}
}

function directorypress_map_style_selected($map_style = false) {
	if (!$map_style) {
		$map_style = directorypress_map_name_selected();
	}
	
	if (directorypress_map_type() == 'google') {
		global $directorypress_google_maps_styles;

		if (!empty($directorypress_google_maps_styles[$map_style])) {
			return $directorypress_google_maps_styles[$map_style];
		} else {
			return '';
		}
	} elseif (directorypress_map_type() == 'mapbox') {
		$mapbox_styles = directorypress_mapbox_styles();
		if (in_array($map_style, $mapbox_styles)) {
			return $map_style;
		} else {
			return array_shift($mapbox_styles);
		}
	}
}

function directorypress_mapbox_styles() {
	$styles = array(
			'Streets' => 'streets-v10',
			'OutDoors' => 'outdoors-v10',
			'Light' => 'light-v9',
			'Dark' => 'dark-v9',
			'Satellite' => 'satellite-v9',
			'Satellite streets' => 'satellite-streets-v10',
			'Navigation preview day' => 'navigation-preview-day-v2',
			'Navigation preview night' => 'navigation-preview-night-v2',
			'Navigation guidance day' => 'navigation-guidance-day-v2',
			'Navigation guidance night' => 'navigation-guidance-night-v2',
	);
	
	$styles = apply_filters('directorypress_mapbox_maps_styles', $styles);
	
	return $styles;
}