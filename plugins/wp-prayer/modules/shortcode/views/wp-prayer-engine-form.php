<?php
// Options are coming straight from the $atts send from shortcode
$shortcode_atts = $options;
$prayerType = null;
if(isset($shortcode_atts)) {
	if(array_key_exists('type', $shortcode_atts)) {
		$prayerType = $shortcode_atts['type'];
		if(array_key_exists('form', $shortcode_atts)) {$isForm = $shortcode_atts['form'];}
	} else {
		//do nothing
	}
}

$settings = unserialize(get_option('_wpe_prayer_engine_settings'));
$prayerType1= __( 'Request', WPE_TEXT_DOMAIN );if($prayerType === 'praise')  {$prayerType1 = __( 'Praise Report', WPE_TEXT_DOMAIN );}
//making prayer type compatible with our below code
if($prayerType === 'prayer')  $prayerType = 'prayer_request';
elseif($prayerType === 'praise') $prayerType = 'praise_report';
else // do nothing


/**
* Render Shortcode To Display Prayer Request From.
* @author Flipper Code <hello@flippercode.com>
*/

//if ( isset( $_REQUEST['_wpnonce'] ) ) {
//	$nonce = sanitize_text_field( $_REQUEST['_wpnonce'] );
//	if ( ! wp_verify_nonce( $nonce, 'wpgmp-nonce' ) ) {
//		die( 'Cheating...' );
//	} else {
if(isset($_POST)){$data = $_POST;}
//	}
//}

global $wpdb, $post;

$modelFactory = new FactoryModelWPE();
ob_start();

wp_enqueue_script( 'wpp-frontend' );

wp_enqueue_style( 'wpp-frontend' );

if ( isset($settings['wpe_login_required']) && $settings['wpe_login_required']!='false' && !is_user_logged_in() ) {

	echo __('Please login to request prayer.',WPE_TEXT_DOMAIN).'<br /><br />';

	printf( '<a href="%s">'.__('Login',WPE_TEXT_DOMAIN), wp_login_url() );

	return;

}

$shortcode_obj = $modelFactory->create_object( 'shortcode' );

$wcsl_js_lang = array(
	'ajax_url' => admin_url('admin-ajax.php'),
	'nonce' => wp_create_nonce('wpe-call-nonce'),
	'confirm' => __('Are you sure to delete item ?', 'wpe-text-domain'),
);
$script = "var wcsl_js_lang = " . wp_json_encode($wcsl_js_lang) . ";";
if (isset($data)) {$wcsl_js_lang['pagination_style'] = isset($data['layout_post_setting']['pagination_style']);}


wp_enqueue_script('wpp-frontend');
wp_add_inline_script('wpp-frontend', $script, 'before');

if(isset($_POST)){$data = $_POST;}
$form  = new FlipperCode_WPE_HTML_Markup();


if (empty($header)) {$header=0;}
$form->set_header( $header, $response, __( 'Manage Prayers', WPE_TEXT_DOMAIN ), 'wpe_manage_prayer' );


$form->add_element( 'text', 'prayer_author_name', array(

'label' => __( 'Name', WPE_TEXT_DOMAIN ),

'value' => (isset( $data['prayer_author_name'] ) and ! empty( $data['prayer_author_name'] )) ? $data['prayer_author_name'] : '',



'required' => true,

));


$form->add_element( 'hidden', 'honeypot', array(
	'label' => 'url',

	'value' => (isset( $data['honeypot'] ) and ! empty( $data['honeypot'] )) ? htmlspecialchars($data['honeypot']) : '',

	'required' => false,
	));

if($settings['wpe_hide_email']!=='true'){

	$form->add_element( 'text', 'prayer_author_email', array(

	'label' => __( 'Email', WPE_TEXT_DOMAIN ),

	'value' => (isset( $data['prayer_author_email'] ) and ! empty( $data['prayer_author_email'] )) ? $data['prayer_author_email'] : '',



	'required' => true,

	));

}



$options = array(
'prayer_request' => __( 'Prayer Request',WPE_TEXT_DOMAIN ),
'praise_report' => __( 'Praise Report',WPE_TEXT_DOMAIN ),
);


//Select whether to show form praise of
// $data['request_type'] this was returning the type now we will return the type of our code

if(isset($prayerType) && !is_null($prayerType)) {

	//Type of prayer
	$form->add_element('hidden','request_type', array(
	'value'	=>  $prayerType,
	'default_value' => 'prayer_request',
	'required'	=> true,
	));

} else {

	$form->add_element( 'radio', 'request_type', array(

	'label' => __( 'Request Type', WPE_TEXT_DOMAIN ),

	'radio-val-label' => $options,

	'current' => (isset( $data['request_type'] ) and ! empty( $data['request_type'] )) ? $data['request_type'] : '',

	'class' => 'chkbox_class',

	'default_value' => 'prayer_request',

	'required' => true,

	'before' => '<div class="col-md-8 radio-col">',

	'after' => '</div><br/>',

	));
}

if(isset($settings['wpe_category']) && $settings['wpe_category']=='true'){
	$categorylist= (isset( $settings['wpe_categorylist'] ) and ! empty( $settings['wpe_categorylist'] )) ? $settings['wpe_categorylist'] : 'Deliverance,Generational Healing,Inner Healing,Physical Healing,Protection,Relationships,Salvation,Spiritual Healing';

	$select_category = explode(",", $categorylist);

	$form->add_element( 'select', 'prayer_category', array(
	'label' => __( 'Category', WPE_TEXT_DOMAIN ),
	'current' => (isset( $data['prayer_category'] ) and ! empty( $data['prayer_category'] )) ? $data['prayer_category'] : '',

	'options' => $select_category,

	));



}

if(isset($settings['wpe_country']) && $settings['wpe_country']=='true'){

	$select_country = array(
					"US"=> "United States",
                    "AF"=> "Afghanistan",
					"AL"=> "Albania",
					"DZ"=> "Algeria",
					"AS"=> "American Samoa",
					"AD"=> "Andorra",
					"AO"=> "Angola",
					"AI"=> "Anguilla",
					"AQ"=> "Antarctica",
					"AG"=> "Antigua & Barbuda",
					"AR"=> "Argentina",
					"AM"=> "Armenia",
					"AW"=> "Aruba",
					"AU"=> "Australia",
					"AT"=> "Austria",
					"AZ"=> "Azerbaijan",
					"BS"=> "Bahamas",
					"BH"=> "Bahrain",
					"BD"=> "Bangladesh",
					"BB"=> "Barbados",
					"BY"=> "Belarus",
					"BE"=> "Belgium",
					"BZ"=> "Belize",
					"BJ"=> "Benin",
					"BM"=> "Bermuda",
					"BT"=> "Bhutan",
					"BO"=> "Bolivia",
					"BQ"=> "Bonaire",
					"BA"=> "Bosnia/Herzegovina",
					"BW"=> "Botswana",
					"BV"=> "Bouvet Island",
					"BR"=> "Brazil",
					"IO"=> "British Indian Ocean",
					"BN"=> "Brunei Darussalam",
					"BG"=> "Bulgaria",
					"BF"=> "Burkina Faso",
					"BI"=> "Burundi",
					"KH"=> "Cambodia",
					"CM"=> "Cameroon",
					"CA"=> "Canada",
					"CV"=> "Cape Verde",
					"KY"=> "Cayman Islands",
					"CF"=> "Central African Rep",
					"TD"=> "Chad",
					"CL"=> "Chile",
					"CN"=> "China",
					"CX"=> "Christmas Island",
					"CC"=> "Cocos Islands",
					"CO"=> "Colombia",
					"KM"=> "Comoros",
					"CG"=> "Congo",
					"CD"=> "Democratic Rep Congo",
					"CK"=> "Cook Islands",
					"CR"=> "Costa Rica",
					"HR"=> "Croatia",
					"CU"=> "Cuba",
					"CW"=> "Curacao",
					"CY"=> "Cyprus",
					"CZ"=> "Czech Republic",
					"CI"=> "Cote d'Ivoire",
					"DK"=> "Denmark",
					"DJ"=> "Djibouti",
					"DM"=> "Dominica",
					"DO"=> "Dominican Republic",
					"EC"=> "Ecuador",
					"EG"=> "Egypt",
					"SV"=> "El Salvador",
					"GQ"=> "Equatorial Guinea",
					"ER"=> "Eritrea",
					"EE"=> "Estonia",
					"ET"=> "Ethiopia",
					"FK"=> "Falkland Islands",
					"FO"=> "Faroe Islands",
					"FJ"=> "Fiji",
					"FI"=> "Finland",
					"FR"=> "France",
					"GF"=> "French Guiana",
					"PF"=> "French Polynesia",
					"GA"=> "Gabon",
					"GM"=> "Gambia",
					"GE"=> "Georgia",
					"DE"=> "Germany",
					"GH"=> "Ghana",
					"GI"=> "Gibraltar",
					"GR"=> "Greece",
					"GL"=> "Greenland",
					"GD"=> "Grenada",
					"GP"=> "Guadeloupe",
					"GU"=> "Guam",
					"GT"=> "Guatemala",
					"GG"=> "Guernsey",
					"GN"=> "Guinea",
					"GW"=> "Guinea-Bissau",
					"GY"=> "Guyana",
					"HT"=> "Haiti",
					"VA"=> "Holy See",
					"HN"=> "Honduras",
					"HK"=> "Hong Kong",
					"HU"=> "Hungary",
					"IS"=> "Iceland",
					"IN"=> "India",
					"ID"=> "Indonesia",
					"IR"=> "Iran",
					"IQ"=> "Iraq",
					"IE"=> "Ireland",
					"IM"=> "Isle of Man",
					"IL"=> "Israel",
					"IT"=> "Italy",
					"JM"=> "Jamaica",
					"JP"=> "Japan",
					"JE"=> "Jersey",
					"JO"=> "Jordan",
					"KZ"=> "Kazakhstan",
					"KE"=> "Kenya",
					"KI"=> "Kiribati",
					"KP"=> "North Korea",
					"KR"=> "South Korea",
					"KW"=> "Kuwait",
					"KG"=> "Kyrgyzstan",
					"LA"=> "Laos",
					"LV"=> "Latvia",
					"LB"=> "Lebanon",
					"LS"=> "Lesotho",
					"LR"=> "Liberia",
					"LY"=> "Libya",
					"LI"=> "Liechtenstein",
					"LT"=> "Lithuania",
					"LU"=> "Luxembourg",
					"MO"=> "Macao",
					"MK"=> "Macedonia",
					"MG"=> "Madagascar",
					"MW"=> "Malawi",
					"MY"=> "Malaysia",
					"MV"=> "Maldives",
					"ML"=> "Mali",
					"MT"=> "Malta",
					"MH"=> "Marshall Islands",
					"MQ"=> "Martinique",
					"MR"=> "Mauritania",
					"MU"=> "Mauritius",
					"YT"=> "Mayotte",
					"MX"=> "Mexico",
					"FM"=> "Micronesia",
					"MD"=> "Moldova",
					"MC"=> "Monaco",
					"MN"=> "Mongolia",
					"ME"=> "Montenegro",
					"MS"=> "Montserrat",
					"MA"=> "Morocco",
					"MZ"=> "Mozambique",
					"MM"=> "Myanmar",
					"NA"=> "Namibia",
					"NR"=> "Nauru",
					"NP"=> "Nepal",
					"NL"=> "Netherlands",
					"NC"=> "New Caledonia",
					"NZ"=> "New Zealand",
					"NI"=> "Nicaragua",
					"NE"=> "Niger",
					"NG"=> "Nigeria",
					"NU"=> "Niue",
					"NF"=> "Norfolk Island",
					"NO"=> "Norway",
					"OM"=> "Oman",
					"PK"=> "Pakistan",
					"PW"=> "Palau",
					"PS"=> "Palestine",
					"PA"=> "Panama",
					"PG"=> "Papua New Guinea",
					"PY"=> "Paraguay",
					"PE"=> "Peru",
					"PH"=> "Philippines",
					"PN"=> "Pitcairn",
					"PL"=> "Poland",
					"PT"=> "Portugal",
					"PR"=> "Puerto Rico",
					"QA"=> "Qatar",
					"RO"=> "Romania",
					"RU"=> "Russian Federation",
					"RW"=> "Rwanda",
					"RE"=> "Reunion",
					"BL"=> "Saint Barthelemy",
					"SH"=> "Saint Helena",
					"KN"=> "Saint Kitts/Nevis",
					"LC"=> "Saint Lucia",
					"MF"=> "Saint Martin",
					"PM"=> "Saint Pierre",
					"VC"=> "Saint Vincent",
					"WS"=> "Samoa",
					"SM"=> "San Marino",
					"ST"=> "Sao Tome/Principe",
					"SA"=> "Saudi Arabia",
					"SN"=> "Senegal",
					"RS"=> "Serbia",
					"SC"=> "Seychelles",
					"SL"=> "Sierra Leone",
					"SG"=> "Singapore",
					"SX"=> "Sint Maarten",
					"SK"=> "Slovakia",
					"SI"=> "Slovenia",
					"SB"=> "Solomon Islands",
					"SO"=> "Somalia",
					"ZA"=> "South Africa",
					"SS"=> "South Sudan",
					"ES"=> "Spain",
					"LK"=> "Sri Lanka",
					"SD"=> "Sudan",
					"SR"=> "Suriname",
					"SJ"=> "Svalbard/Jan Mayen",
					"SZ"=> "Swaziland",
					"SE"=> "Sweden",
					"CH"=> "Switzerland",
					"SY"=> "Syrian Arab Republic",
					"TW"=> "Taiwan",
					"TJ"=> "Tajikistan",
					"TZ"=> "Tanzania",
					"TH"=> "Thailand",
					"TL"=> "Timor-Leste",
					"TG"=> "Togo",
					"TK"=> "Tokelau",
					"TO"=> "Tonga",
					"TT"=> "Trinidad & Tobago",
					"TN"=> "Tunisia",
					"TR"=> "Turkey",
					"TM"=> "Turkmenistan",
					"TC"=> "Turks/Caicos Islands",
					"TV"=> "Tuvalu",
					"UG"=> "Uganda",
					"UA"=> "Ukraine",
					"AE"=> "United Arab Emirates",
					"GB"=> "United Kingdom",
					"UY"=> "Uruguay",
					"UZ"=> "Uzbekistan",
					"VU"=> "Vanuatu",
					"VE"=> "Venezuela",
					"VN"=> "Viet Nam",
					"VG"=> "British Virgin",
					"VI"=> "US Virgin Islands",
					"WF"=> "Wallis & Futuna",
					"EH"=> "Western Sahara",
					"YE"=> "Yemen",
					"ZM"=> "Zambia",
					"ZW"=> "Zimbabwe",
	);

	$ip = $_SERVER['REMOTE_ADDR']; // the IP address to query
	//$ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);


	

	$form->add_element( 'select', 'prayer_country', array(
	'label' => __( 'Country', WPE_TEXT_DOMAIN ),
	'current' => (isset( $data['prayer_country'] ) and ! empty( $data['prayer_country'] )) ? $data['prayer_country'] : '',

	'options' => $select_country,

	));
}

$form->add_element( 'textarea', 'prayer_messages', array(

    'label' => $prayerType1,

    'value' => (isset( $data['prayer_messages'] ) and ! empty( $data['prayer_messages'] )) ?  $data['prayer_messages']  : '',



    'textarea_rows' => 10,

    'required' => true,

    'textarea_name' => 'prayer_messages',

    'class' => 'form-control',

));
if ($prayerType <> 'praise_report' && isset($settings['wpe_share']) && $settings['wpe_share']=='true') {
$form->add_element( 'checkbox', 'prayer_public', array(
'desc' => __( 'Do not share this request', WPE_TEXT_DOMAIN ),
'current' => (isset( $data['prayer_public'] ) and ! empty( $data['prayer_public'] )) ? $data['prayer_public'] : '',
'default_value' => 'unchecked',
'class' => 'form-control ',
//'before' => '<div class="col-md-6">',
//'after' => '</div>',
));
}

if($settings['wpe_hide_email']!=='true') {
	if ( $prayerType <> 'praise_report' && isset( $settings['wpe_autoemail'] ) && $settings['wpe_autoemail'] == 'true' ) {
		$form->add_element( 'checkbox', 'prayer_notify', array(
			'desc'          => __( 'Notify me when someone prays for this request', WPE_TEXT_DOMAIN ),
			'current'       => ( isset( $data['prayer_notify'] ) and ! empty( $data['prayer_notify'] ) ) ? $data['prayer_notify'] : '',
			'default_value' => 'unchecked',
			'class' => 'form-control ',
//			'before'        => '<div class="col-md-6">',
		) );
	}
}
if ( isset($settings['wpe_login_required']) && $settings['wpe_login_required']!='false' && !is_user_logged_in() ) {

// 	if($settings['wpe_captcha']=='false'){

// 		$form->set_col(2);



// 		$form->add_element('image','captcha_img',array(

// 		'label' => __( 'Captcha', WPE_TEXT_DOMAIN ),

// 		'src' => WPE_INC_URL.'captcha.php?rand='.rand(),

// 		'id' => 'captchaimg',

// 		'required' => true,

// 		'class' => 'form-control ',

// 		'before' => '<div class="col-md-3">',

// 		'after' => '</div>',

// 		));



// 		$form->add_element( 'text', 'captcha_code', array(

// 		'value' => '',

// 		'class' => 'form-control ',

// 		'before' => '<div class="col-md-3 wsl_captcha_field">',

// 		'after' => '</div>',

// 		));



// 		$form->set_col(1);



// 		$form->add_element('html','description',array(

// 		'html'=>'<p class="help-block wsl_desc_field">'.__( 'Enter here captcha. Can\'t read the image? Click <a href="javascript: refreshCaptcha();">HERE</a> to refresh.', WPE_TEXT_DOMAIN ).'</p>'

// 		));

// 	}

}
	// $form->add_element( 'submit', 'save_entity_data', array(
  //
	// 'value' => __( 'Submit',WPE_TEXT_DOMAIN ),
  //
	// 'before' => '<div class="col-md-8 wsl_submit_field">',
  //
	// 'after' => '</div>',
  //
	// ));

	$form->add_element( 'hidden', 'operation', array(

	'value' => 'save',

	));

	$form->render(true,true);
?>
<script>
window.onpageshow = function(event) {
    if (event.persisted || performance.getEntriesByType("navigation")[0].type === 'back_forward') {
        location.reload();
    }
};
</script>