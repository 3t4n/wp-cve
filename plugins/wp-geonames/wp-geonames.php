<?php
/*
Plugin Name: WP GeoNames
Author: Jacques Malgrange
Text Domain: wpGeonames
Domain Path: /lang
Description: Allows you to insert all or part of the global GeoNames database in your WordPress base.
Version: 1.8
Author URI: https://www.boiteasite.fr
*/
$a = __('Allows you to insert all or part of the global GeoNames database in your WordPress base.','wpGeonames'); // Description
$geoVersion = "1.8";
//
register_activation_hook( __FILE__, 'wpGeonames_creation_table');
//
add_shortcode('wp-geonames', 'wpGeonames_shortcode');
add_action('wp_ajax_nopriv_geoDataRegion', 'wpGeonames_ajax_geoDataRegion');
add_action('wp_ajax_geoDataRegion', 'wpGeonames_ajax_geoDataRegion');
add_action('wp_ajax_nopriv_geoDataCity', 'wpGeonames_ajax_geoDataCity');
add_action('wp_ajax_geoDataCity', 'wpGeonames_ajax_geoDataCity');
add_action('wp_ajax_wpgeonameGetCity', 'wpGeonames_ajax_get_city_by_country_region');
//
if(is_admin()) require(dirname(__FILE__).'/inc/wp-geonames_admin.php');
//
function wpGeonames_enqueue_leaflet() {
	wp_register_style('leaflet', plugins_url('wp-geonames/leaflet/leaflet.css'));
	wp_enqueue_style('leaflet');
	wp_register_script('leaflet', plugins_url('wp-geonames/leaflet/leaflet.js'), array(), false, false);
	wp_enqueue_script('leaflet');
}
function wpGeonames_get_country($postal=0) {
	global $wpdb;
	// OUTPUT Object : country_code, name
	// country list : http://www.nationsonline.org/oneworld/country_code_list.htm
	// $liste = array("AF"=>"Afghanistan", "AX"=>"Aland Islands", "AL"=>"Albania", "DZ"=>"Algeria", "AS"=>"American Samoa", "AD"=>"Andorra", "AO"=>"Angola", "AI"=>"Anguilla", "AQ"=>"Antarctica ", "AG"=>"Antigua and Barbuda", "AR"=>"Argentina", "AM"=>"Armenia", "AW"=>"Aruba", "AU"=>"Australia", "AT"=>"Austria", "AZ"=>"Azerbaijan", "BS"=>"Bahamas", "BH"=>"Bahrain", "BD"=>"Bangladesh", "BB"=>"Barbados", "BY"=>"Belarus", "BE"=>"Belgium", "BZ"=>"Belize", "BJ"=>"Benin", "BM"=>"Bermuda", "BT"=>"Bhutan", "BO"=>"Bolivia", "BA"=>"Bosnia and Herzegovina", "BW"=>"Botswana", "BV"=>"Bouvet Island", "BR"=>"Brazil", "VG"=>"British Virgin Islands", "IO"=>"British Indian Ocean Territory", "BN"=>"Brunei Darussalam", "BG"=>"Bulgaria", "BF"=>"Burkina Faso", "BI"=>"Burundi", "KH"=>"Cambodia", "CM"=>"Cameroon", "CA"=>"Canada ", "CV"=>"Cape Verde", "KY"=>"Cayman Islands ", "CF"=>"Central African Republic", "TD"=>"Chad", "CL"=>"Chile", "CN"=>"China", "HK"=>"Hong Kong, SAR China", "MO"=>"Macao, SAR China", "CX"=>"Christmas Island", "CC"=>"Cocos (Keeling) Islands", "CO"=>"Colombia", "KM"=>"Comoros", "CG"=>"Congo (Brazzaville) ", "CD"=>"Congo, (Kinshasa)", "CK"=>"Cook Islands ", "CR"=>"Costa Rica", "CI"=>"Côte d'Ivoire", "HR"=>"Croatia", "CU"=>"Cuba", "CY"=>"Cyprus", "CZ"=>"Czech Republic", "DK"=>"Denmark", "DJ"=>"Djibouti", "DM"=>"Dominica", "DO"=>"Dominican Republic", "EC"=>"Ecuador", "EG"=>"Egypt", "SV"=>"El Salvador", "GQ"=>"Equatorial Guinea", "ER"=>"Eritrea", "EE"=>"Estonia", "ET"=>"Ethiopia", "FK"=>"Falkland Islands (Malvinas) ", "FO"=>"Faroe Islands", "FJ"=>"Fiji", "FI"=>"Finland", "FR"=>"France", "GF"=>"French Guiana", "PF"=>"French Polynesia", "TF"=>"French Southern Territories ", "GA"=>"Gabon", "GM"=>"Gambia", "GE"=>"Georgia", "DE"=>"Germany ", "GH"=>"Ghana", "GI"=>"Gibraltar ", "GR"=>"Greece", "GL"=>"Greenland", "GD"=>"Grenada", "GP"=>"Guadeloupe", "GU"=>"Guam", "GT"=>"Guatemala", "GG"=>"Guernsey", "GN"=>"Guinea", "GW"=>"Guinea-Bissau", "GY"=>"Guyana", "HT"=>"Haiti", "HM"=>"Heard and Mcdonald Islands", "VA"=>"Holy See (Vatican City State)", "HN"=>"Honduras", "HU"=>"Hungary", "IS"=>"Iceland", "IN"=>"India", "ID"=>"Indonesia", "IR"=>"Iran, Islamic Republic of", "IQ"=>"Iraq", "IE"=>"Ireland", "IM"=>"Isle of Man ", "IL"=>"Israel ", "IT"=>"Italy ", "JM"=>"Jamaica", "JP"=>"Japan", "JE"=>"Jersey", "JO"=>"Jordan", "KZ"=>"Kazakhstan", "KE"=>"Kenya", "KI"=>"Kiribati", "KP"=>"Korea (North)", "KR"=>"Korea (South)", "KW"=>"Kuwait", "KG"=>"Kyrgyzstan", "LA"=>"Lao PDR", "LV"=>"Latvia", "LB"=>"Lebanon", "LS"=>"Lesotho", "LR"=>"Liberia", "LY"=>"Libya", "LI"=>"Liechtenstein", "LT"=>"Lithuania", "LU"=>"Luxembourg", "MK"=>"Macedonia, Republic of", "MG"=>"Madagascar", "MW"=>"Malawi", "MY"=>"Malaysia", "MV"=>"Maldives", "ML"=>"Mali", "MT"=>"Malta", "MH"=>"Marshall Islands", "MQ"=>"Martinique", "MR"=>"Mauritania", "MU"=>"Mauritius", "YT"=>"Mayotte", "MX"=>"Mexico", "FM"=>"Micronesia, Federated States of", "MD"=>"Moldova", "MC"=>"Monaco", "MN"=>"Mongolia", "ME"=>"Montenegro", "MS"=>"Montserrat", "MA"=>"Morocco", "MZ"=>"Mozambique", "MM"=>"Myanmar", "NA"=>"Namibia", "NR"=>"Nauru", "NP"=>"Nepal", "NL"=>"Netherlands", "AN"=>"Netherlands Antilles", "NC"=>"New Caledonia", "NZ"=>"New Zealand", "NI"=>"Nicaragua", "NE"=>"Niger", "NG"=>"Nigeria", "NU"=>"Niue ", "NF"=>"Norfolk Island", "MP"=>"Northern Mariana Islands", "NO"=>"Norway", "OM"=>"Oman", "PK"=>"Pakistan", "PW"=>"Palau", "PS"=>"Palestinian Territory", "PA"=>"Panama", "PG"=>"Papua New Guinea", "PY"=>"Paraguay", "PE"=>"Peru", "PH"=>"Philippines", "PN"=>"Pitcairn ", "PL"=>"Poland", "PT"=>"Portugal", "PR"=>"Puerto Rico", "QA"=>"Qatar", "RE"=>"Réunion", "RO"=>"Romania", "RU"=>"Russian Federation ", "RW"=>"Rwanda", "BL"=>"Saint-Barthélemy", "SH"=>"Saint Helena ", "KN"=>"Saint Kitts and Nevis", "LC"=>"Saint Lucia", "MF"=>"Saint-Martin (French part)", "PM"=>"Saint Pierre and Miquelon ", "VC"=>"Saint Vincent and Grenadines", "WS"=>"Samoa", "SM"=>"San Marino", "ST"=>"Sao Tome and Principe", "SA"=>"Saudi Arabia", "SN"=>"Senegal", "RS"=>"Serbia", "SC"=>"Seychelles", "SL"=>"Sierra Leone", "SG"=>"Singapore", "SK"=>"Slovakia", "SI"=>"Slovenia", "SB"=>"Solomon Islands", "SO"=>"Somalia", "ZA"=>"South Africa", "GS"=>"South Georgia and the South Sandwich Islands", "SS"=>"South Sudan", "ES"=>"Spain", "LK"=>"Sri Lanka", "SD"=>"Sudan", "SR"=>"Suriname", "SJ"=>"Svalbard and Jan Mayen Islands ", "SZ"=>"Swaziland", "SE"=>"Sweden", "CH"=>"Switzerland", "SY"=>"Syrian Arab Republic (Syria)", "TW"=>"Taiwan, Republic of China ", "TJ"=>"Tajikistan", "TZ"=>"Tanzania, United Republic of ", "TH"=>"Thailand", "TL"=>"Timor-Leste", "TG"=>"Togo", "TK"=>"Tokelau ", "TO"=>"Tonga", "TT"=>"Trinidad and Tobago", "TN"=>"Tunisia", "TR"=>"Turkey", "TM"=>"Turkmenistan", "TC"=>"Turks and Caicos Islands ", "TV"=>"Tuvalu", "UG"=>"Uganda", "UA"=>"Ukraine", "AE"=>"United Arab Emirates", "GB"=>"United Kingdom", "US"=>"United States of America", "UM"=>"US Minor Outlying Islands ", "UY"=>"Uruguay", "UZ"=>"Uzbekistan", "VU"=>"Vanuatu", "VE"=>"Venezuela (Bolivarian Republic)", "VN"=>"Viet Nam", "VI"=>"Virgin Islands, US", "WF"=>"Wallis and Futuna Islands ", "EH"=>"Western Sahara ", "YE"=>"Yemen", "ZM"=>"Zambia", "ZW"=>"Zimbabwe");
	// country list : https://en.wikipedia.org/wiki/ISO_3166-1 - Only Indenpendent*
	$liste = array("AD"=>"Andorra", "AE"=>"United Arab Emirates", "AF"=>"Afghanistan", "AG"=>"Antigua and Barbuda", "AL"=>"Albania", "AM"=>"Armenia", "AO"=>"Angola", "AR"=>"Argentina", "AT"=>"Austria", "AU"=>"Australia", "AZ"=>"Azerbaijan", "BA"=>"Bosnia and Herzegovina", "BB"=>"Barbados", "BD"=>"Bangladesh", "BE"=>"Belgium", "BF"=>"Burkina Faso", "BG"=>"Bulgaria", "BH"=>"Bahrain", "BI"=>"Burundi", "BJ"=>"Benin", "BN"=>"Brunei Darussalam", "BO"=>"Bolivia (Plurinational State of)", "BR"=>"Brazil", "BS"=>"Bahamas", "BT"=>"Bhutan", "BW"=>"Botswana", "BY"=>"Belarus", "BZ"=>"Belize", "CA"=>"Canada", "CD"=>"Congo (Democratic Republic of the)", "CF"=>"Central African Republic", "CG"=>"Congo", "CH"=>"Switzerland", "CI"=>"Côte d'Ivoire", "CL"=>"Chile", "CM"=>"Cameroon", "CN"=>"China", "CO"=>"Colombia", "CR"=>"Costa Rica", "CU"=>"Cuba", "CV"=>"Cabo Verde", "CY"=>"Cyprus", "CZ"=>"Czechia", "DE"=>"Germany", "DJ"=>"Djibouti", "DK"=>"Denmark", "DM"=>"Dominica", "DO"=>"Dominican Republic", "DZ"=>"Algeria", "EC"=>"Ecuador", "EE"=>"Estonia", "EG"=>"Egypt", "ER"=>"Eritrea", "ES"=>"Spain", "ET"=>"Ethiopia", "FI"=>"Finland", "FJ"=>"Fiji", "FM"=>"Micronesia (Federated States of)", "FR"=>"France", "GA"=>"Gabon", "GB"=>"United Kingdom of Great Britain and Northern Ireland", "GD"=>"Grenada", "GE"=>"Georgia", "GH"=>"Ghana", "GM"=>"Gambia", "GN"=>"Guinea", "GQ"=>"Equatorial Guinea", "GR"=>"Greece", "GT"=>"Guatemala", "GW"=>"Guinea-Bissau", "GY"=>"Guyana", "HN"=>"Honduras", "HR"=>"Croatia", "HT"=>"Haiti", "HU"=>"Hungary", "ID"=>"Indonesia", "IE"=>"Ireland", "IL"=>"Israel", "IN"=>"India", "IQ"=>"Iraq", "IR"=>"Iran (Islamic Republic of)", "IS"=>"Iceland", "IT"=>"Italy", "JM"=>"Jamaica", "JO"=>"Jordan", "JP"=>"Japan", "KE"=>"Kenya", "KG"=>"Kyrgyzstan", "KH"=>"Cambodia", "KI"=>"Kiribati", "KM"=>"Comoros", "KN"=>"Saint Kitts and Nevis", "KP"=>"Korea (Democratic People's Republic of)", "KR"=>"Korea (Republic of)", "KW"=>"Kuwait", "KZ"=>"Kazakhstan", "LA"=>"Lao People's Democratic Republic", "LB"=>"Lebanon", "LC"=>"Saint Lucia", "LI"=>"Liechtenstein", "LK"=>"Sri Lanka", "LR"=>"Liberia", "LS"=>"Lesotho", "LT"=>"Lithuania", "LU"=>"Luxembourg", "LV"=>"Latvia", "LY"=>"Libya", "MA"=>"Morocco", "MC"=>"Monaco", "MD"=>"Moldova (Republic of)", "ME"=>"Montenegro", "MG"=>"Madagascar", "MH"=>"Marshall Islands", "MK"=>"Macedonia (the former Yugoslav Republic of)", "ML"=>"Mali", "MM"=>"Myanmar", "MN"=>"Mongolia", "MR"=>"Mauritania", "MT"=>"Malta", "MU"=>"Mauritius", "MV"=>"Maldives", "MW"=>"Malawi", "MX"=>"Mexico", "MY"=>"Malaysia", "MZ"=>"Mozambique", "NA"=>"Namibia", "NC"=>"New Caledonia", "NE"=>"Niger", "NG"=>"Nigeria", "NI"=>"Nicaragua", "NL"=>"Netherlands", "NO"=>"Norway", "NP"=>"Nepal", "NR"=>"Nauru", "NZ"=>"New Zealand", "OM"=>"Oman", "PA"=>"Panama", "PE"=>"Peru", "PF"=>"French Polynesia", "PG"=>"Papua New Guinea", "PH"=>"Philippines", "PK"=>"Pakistan", "PL"=>"Poland", "PT"=>"Portugal", "PW"=>"Palau", "PY"=>"Paraguay", "QA"=>"Qatar", "RO"=>"Romania", "RS"=>"Serbia", "RU"=>"Russian Federation", "RW"=>"Rwanda", "SA"=>"Saudi Arabia", "SB"=>"Solomon Islands", "SC"=>"Seychelles", "SD"=>"Sudan", "SE"=>"Sweden", "SG"=>"Singapore", "SI"=>"Slovenia", "SK"=>"Slovakia", "SL"=>"Sierra Leone", "SM"=>"San Marino", "SN"=>"Senegal", "SO"=>"Somalia", "SR"=>"Suriname", "SS"=>"South Sudan", "ST"=>"Sao Tome and Principe", "SV"=>"El Salvador", "SY"=>"Syrian Arab Republic", "SZ"=>"Swaziland", "TD"=>"Chad", "TG"=>"Togo", "TH"=>"Thailand", "TJ"=>"Tajikistan", "TL"=>"Timor-Leste", "TM"=>"Turkmenistan", "TN"=>"Tunisia", "TO"=>"Tonga", "TR"=>"Turkey", "TT"=>"Trinidad and Tobago", "TV"=>"Tuvalu", "TZ"=>"Tanzania, United Republic of", "UA"=>"Ukraine", "UG"=>"Uganda", "US"=>"United States of America", "UY"=>"Uruguay", "UZ"=>"Uzbekistan", "VA"=>"Holy See", "VC"=>"Saint Vincent and the Grenadines", "VE"=>"Venezuela (Bolivarian Republic of)", "VN"=>"Viet Nam", "VU"=>"Vanuatu", "WS"=>"Samoa", "YE"=>"Yemen", "ZA"=>"South Africa", "ZM"=>"Zambia", "ZW"=>"Zimbabwe");
	if(!$postal) $q = $wpdb->get_results("SELECT DISTINCT country_code FROM ".$wpdb->base_prefix."geonames ORDER BY country_code");
	else $q = $wpdb->get_results("SELECT DISTINCT country_code FROM ".$wpdb->base_prefix."geonamesPostal ORDER BY country_code");
	$result = array();
	foreach($q as $r) {
		if(isset($liste[$r->country_code])) {
			$a = new StdClass();
			$a->country_code = $r->country_code;
			$a->name = $liste[$r->country_code];
			$result[] = $a;
		}
	}
	usort($result, "wpGeonames_sortCountry");
	return $result;
}
function wpGeonames_sortCountry($a,$b) { return strcmp($a->name,$b->name); }
function wpGeonames_regionCode2($iso='ZZZ') {
	$a = ',BE,';
	return((strpos($a,$iso)!==false)?true:false);
}
function wpGeonames_get_region_by_country($iso='') {
	//
	global $wpdb;
	$result = array();
	if($iso) {
		$a = "admin1_code"; $b = "ADM1";
		if(wpGeonames_regionCode2($iso)) {
			$a = "admin2_code";
			$b = "ADM2";
		}
		$q = $wpdb->get_results("SELECT
				geonameid,
				name,
				country_code,
				".$a."
			FROM
				".$wpdb->base_prefix."geonames
			WHERE
				feature_class='A' and
				((feature_code='".$b."' and (country_code='".$iso."' or cc2='".$iso."'))
					or
				(feature_code='PCLD' and cc2='".$iso."'))
			ORDER BY name
			");
		$c = array();
		foreach($q as $r) {
			if($r->$a=='00') $r->$a = $r->country_code;
			if(!isset($c[$r->name])) {
				$result[] = $r;
				$c[$r->name] = 1;
			}
		}
	}
	return $result;
}
function wpGeonames_shortcode($a) {
	$shortcode = shortcode_atts(array(
		'id1' => 'geoCountry',
		'id2' => 'geoRegion',
		'id3' => 'geoCity',
		'out' => 'geoRow',
		'map' => '0',
		'zoom' => '9',
		'nbcity' =>'10',
		'data' => ''
		),$a);
	if($shortcode['map']) wpGeonames_enqueue_leaflet();
	$out = '';
	$geoData = array();
	$geoData['selectCountry'] = '';
	$country = wpGeonames_get_country();
	foreach($country as $r) $geoData['selectCountry'] .= '<option value="'.$r->country_code.'">'.$r->name.'</option>';
	$geoData['onChangeCountry'] = 'onchange="geoDataRegion();"';
	$geoData['onChangeRegion'] = 'onchange="geoDataCity();"';
	$geoData['onKeyCity'] = 'onClick="wpGeonameCityMap(v.name,v.latitude,v.longitude);"';
	// ****** TEMPLATE ********
	$inc = '';
	if(has_filter('wpGeonames_location_taxonomy_tpl')) $inc = apply_filters('wpGeonames_location_taxonomy_tpl',0);
	else if(file_exists(get_stylesheet_directory().'/templates/wp-geonames_location_taxonomy.php')) $inc = get_stylesheet_directory().'/templates/wp-geonames_location_taxonomy.php';
	else $inc = dirname( __FILE__ ).'/templates/wp-geonames_location_taxonomy.php';
	ob_start();
	include($inc);
	$out .= ob_get_clean();
	// ************************
	return $out;
}
//
// ****************** A J A X ********************************
//
function wpGeonames_ajax_wpGeonamesAddCountry() {
	// AJAX Admin
	if(empty($_REQUEST['geoToka']) || !wp_verify_nonce($_REQUEST['geoToka'],'geoToka')) return;
	$Pfil = sanitize_text_field($_POST['file']);
	$Pfrm = strip_tags($_POST['frm']);
	$Purl = strip_tags(stripslashes(filter_var($_POST['url'], FILTER_SANITIZE_URL)));
	//
	$a = explode(',', $Pfrm); $b = array();
	foreach($a as $r) if($r) $b[$r] = 1;
	$b['wpGeonamesAdd'] = $Pfil;
	wpGeonames_addZip($Purl,$b);
	echo '<span style="color:green;font-weight:700;margin:0 4px;">'.substr($Pfil,0,-4).'</span>';
}
function wpGeonames_ajax_wpGeonamesAddPostal() {
	// AJAX Admin
	if(empty($_REQUEST['geoToka']) || !wp_verify_nonce($_REQUEST['geoToka'],'geoToka')) return;
	$Pfil = sanitize_text_field($_POST['file']);
	$Pfrm = strip_tags($_POST['frm']);
	$Purl = strip_tags(stripslashes(filter_var($_POST['url'], FILTER_SANITIZE_URL)));
	//
	$a = explode(',', $Pfrm); $b = array();
	foreach($a as $r) if($r) $b[$r] = 1;
	$b['wpGeonamesPostalAdd'] = $Pfil;
	wpGeonames_postalAddZip($Purl,$b);
	echo '<span style="color:green;font-weight:700;margin:0 4px;">'.substr($Pfil,0,-4).'</span>';
}
function wpGeonames_ajax_get_city_by_country_region() {
	// AJAX Admin
	// input : $_POST iso, region, city
	if(empty($_REQUEST['geoToka']) || !wp_verify_nonce($_REQUEST['geoToka'],'geoToka')) return;
	global $wpdb;
	$Piso = preg_replace("/[^a-zA-Z0-9_,-]/","", $_POST['iso']);
	$Pregion = sanitize_text_field($_POST['region']);
	$Pcity = sanitize_text_field($_POST['city']);
	//
	$a = "admin1_code";
	if(wpGeonames_regionCode2($Piso)) $a = "admin2_code";
	$result = $wpdb->get_results("SELECT
			geonameid,
			name,
			latitude,
			longitude
		FROM
			".$wpdb->base_prefix."geonames
		WHERE
			((country_code='".$Piso."' and ".$a."='".$Pregion."')
			or country_code='".$Pregion."')
			and feature_class='P'
			and name LIKE '".$Pcity."%'
		ORDER BY name
		LIMIT 10");
	echo json_encode($result);
}
function wpGeonames_ajax_geoDataRegion() {
	// AJAX Templates
	global $wpdb;
	$Piso = preg_replace("/[^a-zA-Z0-9_,-]/","", $_POST['country']);
	//
	$result = array();
	if($Piso) {
		$a = "admin1_code"; $b = "ADM1";
		if(wpGeonames_regionCode2($Piso)) {
			$a = "admin2_code";
			$b = "ADM2";
		}
		$q = $wpdb->get_results("SELECT
				geonameid,
				name,
				".$a." AS regionid
			FROM
				".$wpdb->base_prefix."geonames
			WHERE
				feature_class='A' and feature_code='".$b."' and (country_code='".$Piso."' or cc2='".$Piso."')
					or
				feature_class='A' and feature_code='PCLD' and cc2='".$Piso."'
			ORDER BY name
			");
		$c = array();
		foreach($q as $r) {
			if($r->regionid=='00') $r->regionid = $r->country_code;
			if(!isset($c[$r->name])) {
				$result[] = $r;
				$c[$r->name] = 1;
			}
		}
	}
	echo json_encode($result);
}
function wpGeonames_ajax_geoDataCity() {
	// AJAX Templates
	global $wpdb;
	$Piso = preg_replace("/[^a-zA-Z0-9_,-]/","", $_POST['country']);
	$Preg = sanitize_text_field($_POST['region']);
	$Pcit = sanitize_text_field($_POST['city']);
	$Pnb = (!empty($_POST['nbcity'])?intval($_POST['nbcity']):10);
	//
	$result = array();
	if($Piso) {
		$a = "admin1_code";
		if(wpGeonames_regionCode2($Piso)) $a = "admin2_code";
		$result = $wpdb->get_results("SELECT
				geonameid,
				name,
				latitude,
				longitude
			FROM
				".$wpdb->base_prefix."geonames
			WHERE
				feature_class='P'
				and ((country_code='".$Piso."' and ".$a."='".$Preg."') or country_code='".$Preg."')
				and name LIKE '".$Pcit."%'
			ORDER BY name
			LIMIT ".$Pnb);
	}
	echo json_encode($result);
}
?>
