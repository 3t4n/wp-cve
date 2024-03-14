<?php

if (get_option('d8cf7_email_validation_level')){
	add_action( 'wpforms_process', 'd8_validate_email_wpf', 10, 3 );
}

function d8_validate_email_wpf( $fields, $entry, $form_data ) {
    
	$email = "";

	if(get_option('d8cf7_ajax_key') == ""){
		return;
	}
	
    //check fields for email
	foreach ( $fields as $field_id => $field ) {
		//if field type is email run validation logic
        if($field['type'] == "email"){
			$email = $field['value'];
			$emailId = $field['id'];

			//return if email blank, setting field to required can be done in WPForms
			if($email == ""){
				continue;
			}
			
			$level = get_option('d8cf7_email_validation_level');
			if($level == "None"){
				continue;
			}
			if($level == ""){
				$level = 'MX';
			}	

			// Set up the parameters for the web service call:
			// https://www.data-8.co.uk/support/service-documentation/email-validation/reference/isvalid

			$params = array(
				'email' => $email,
				'level' => $level,
				'options' => array(
					'ApplicationName' => 'WordPress'
				)
			);
			
			$d8cf7_ajax_key = get_option('d8cf7_ajax_key');
			$url = "https://webservices.data-8.co.uk/EmailValidation/IsValid.json?key=" . $d8cf7_ajax_key;
			//make api call
			$wsresult = ajaxAsyncRequest($url, $params);
			
			if ($wsresult['Status']['Success'] && $wsresult['Result'] == 'Invalid'){
				wpforms()->process->errors[ $form_data['id']]['header'] = esc_html__( 'Details invalid', 'plugin-domain' );
				wpforms()->process->errors[ $form_data['id'] ] [$emailId] = esc_html__('invalid email', 'plugin-domain');
			}
		} 	
    }
}


if (get_option('d8cf7_telephone_validation')){
	add_action( 'wpforms_process', 'd8_validate_tel_wpf', 10, 3 );
}

function d8_validate_tel_wpf( $fields, $entry, $form_data ) {
	if(get_option('d8cf7_ajax_key') == ""){
		return;
	}

    foreach ( $fields as $field_id => $field ) { 
        if($field['type'] == "phone"){
            $value = $field['value'];
            $phoneId = $field['id'];

            if($value == null){
                return;
            }
            $defaultCountry = get_option('d8cf7_telephone_default_country');
            if(!empty($defaultCountry)){
                $defaultCountry = trim($defaultCountry);
            }
            if(empty($defaultCountry)){
                $defaultCountry = "44";
            }

            $country = $defaultCountry;
            $allowedPrefixes = '';
            $barredPrefixes = '';
            $requiredCountry = '';

            if($defaultCountry == 'auto'){
                $ip = getEndUserIp();
                $endUserIp = strval($ip);
            }else{
                $endUserIp = '';
            }

            if(get_option('d8cf7_telephone_allowed_prefixes')){
                $allowedPrefixes = get_option('d8cf7_telephone_allowed_prefixes');
            }

            if(get_option('d8cf7_telephone_barred_prefixes')){
                $barredPrefixes = get_option('d8cf7_telephone_barred_prefixes');
            }

            if(get_option('d8cf7_telephone_required_country')){
                $requiredCountry = get_option('d8cf7_telephone_required_country');
            }

            // Set up the parameters for the web service call:
            //https://www.data-8.co.uk/resources/api-reference/phonevalidation/isvalid/

            $params = array(
                'telephoneNumber' => $value,
                'defaultCountry' => $country,
                'options' => array(
                    'AllowedPrefixes' => $allowedPrefixes,
                    'BarredPrefixes' => $barredPrefixes,
                    'RequiredCountry' => $requiredCountry,
                    'ApplicationName' => 'WordPress',
                    'EndUserIPAddress' => $endUserIp
                )
            );

            if(null !== get_option('d8cf7_telephone_advanced_options')){
                $options = explode("\n", str_replace('\"', '"', get_option('d8cf7_telephone_advanced_options')));
                
                foreach ( $options as $option ) {
                    if ( strpos($option, ':') !== false ) {
                        $optionArr = explode(":", $option, 2);
                        $params['options'] += [$optionArr[0] => $optionArr[1]];
                    }
                }
            }

            $d8cf7_ajax_key = get_option('d8cf7_ajax_key');
            $url = "https://webservices.data-8.co.uk/PhoneValidation/IsValid.json?key=" . $d8cf7_ajax_key;
            $wsresult = ajaxAsyncRequest($url, $params);


            if ($wsresult['Status']['Success'] && $wsresult['Result']['ValidationResult'] == 'Invalid'){
                wpforms()->process->errors[ $form_data['id']]['header'] = esc_html__( 'Details invalid', 'plugin-domain' );
                wpforms()->process->errors[ $form_data['id'] ] [$phoneId] = esc_html__('invalid phone number', 'plugin-domain');
            } 
        }
    }
}

if (get_option('d8cf7_salaciousName')){
	add_action( 'wpforms_process', 'd8_validate_name_wpf', 10, 3 );
}

function d8_validate_name_wpf( $fields, $entry, $form_data ){
	$name = "";

	if(get_option('d8cf7_ajax_key') == ""){
		return;
	}
	
    //check fields for name type
	foreach ( $fields as $field_id => $field ) {
        if($field['type'] == "name"){
			$name = $field['value'];
			//name field id
			$nameId = $field['id'];
			
			if($name == ""){
				continue;
			}
				
			// Set up the parameters for the web service call:
			// https://www.data-8.co.uk/resources/api-reference/salaciousname/isunusablename/
		
			$params = array(
				'name' => array(
					'title' => null,
					'forename' => $name,
					'middlename' => null,
					'surname' => null,
				),
				'options' => array(
					'ApplicationName' => 'Wordpress'
				)
			);
		
			$d8cf7_ajax_key = get_option('d8cf7_ajax_key');
			$url = "https://webservices.data-8.co.uk/SalaciousName/IsUnusableName.json?key=" . $d8cf7_ajax_key;
			$wsresult = ajaxAsyncRequest($url, $params);
		
			if ($wsresult['Status']['Success'] && $wsresult['Result'] != ''){
				wpforms()->process->errors[ $form_data['id']]['header'] = esc_html__( 'Details invalid', 'plugin-domain' );
				wpforms()->process->errors[ $form_data['id'] ] [$nameId] = esc_html__('invalid name', 'plugin-domain');
			}
		} 	
    }
}

if(get_option('d8cf7_bank_validation')){
	add_action( 'wpforms_process', 'd8_validate_bank_wpf', 10, 3 );
}

function d8_validate_bank_wpf( $fields, $entry, $form_data ) {
    
	$sortCode = "";
	$accountNumber = "";

	if(get_option('d8cf7_ajax_key') == ""){
		return;
	}

	foreach ( $form_data['fields'] as $field ) {
        if(strpos($field['css'],"d8-sortcode") !== false){
			$sortCodeId = $field['id'];
			$sortCode = $fields[$sortCodeId]['value'];
		} else if(strpos($field['css'],"d8-account-number") !== false){			
			$accountNumberId = $field['id'];
			$accountNumber = $fields[$accountNumberId]['value'];
		}	
    }
	
	//if sort code or account number fields dont exist: exit
	if($sortCodeId == null){
		return;
	}
    //return if details blank
	if($sortCode == ""){
		return;		
	}	
	

	// Set up the parameters for the web service call:
	// https://www.data-8.co.uk/resources/api-reference/bankaccountvalidation/isvalid/

	$params = array(
		'sortCode' => $sortCode,
		'bankAccountNumber' => $accountNumber,
		'options' => array(
			'ApplicationName' => 'WordPress'
		)
	);
	
	$d8cf7_ajax_key = get_option('d8cf7_ajax_key');
	$url = "https://webservices.data-8.co.uk/BankAccountValidation/IsValid.json?key=" . $d8cf7_ajax_key;
    //make api call
	$wsresult = ajaxAsyncRequest($url, $params);
	
	if($wsresult['Status']['Success'] && $wsresult['Valid'] == 'Invalid'){
		wpforms()->process->errors[ $form_data['id']]['header'] = esc_html__( 'Details invalid', 'plugin-domain' );
		wpforms()->process->errors[ $form_data['id']][$sortCodeId] = esc_html__('invalid account number or sort code', 'plugin-domain');
		wpforms()->process->errors[ $form_data['id']][$accountNumberId] = esc_html__('invalid account number or sort code', 'plugin-domain');
	}
	
}

//predictive address
if (get_option('d8cf7_predictiveaddress')) {
	add_action( 'wpforms_display_field_before', 'd8_predictiveaddress_wpf_pre_render', 10, 2 );
	add_action( 'wp_footer', 'd8_predictiveaddress_wpforms_enqueue_scripts');
}

//store field information in global array
$d8wpf_script_vars = array(
	'ajaxKey' => get_option('d8cf7_client_api_key'),
	'applicationName' => 'WordPress',
	'addressFields' => array()
);

function d8_predictiveaddress_wpf_pre_render($field, $form_data){
	
	global $d8wpf_script_vars;
	$addressFields = &$d8wpf_script_vars['addressFields'];
	$fieldId = $field['id'];
	$formId = $form_data['id'];
	//create unique id for each form field
	$addressFieldId = $formId . "/" . $fieldId;
	
	if(null !== get_option('d8cf7_predictiveaddress_options')){
		$options = explode("\n", str_replace('\"', '"', get_option('d8cf7_predictiveaddress_options')));
		
		foreach ( $options as $option ) {
			if ( strpos($option, ':') !== false ) {
				$optionArr = explode(":", $option, 2);
				$d8wpf_script_vars[trim($optionArr[0])] = trim($optionArr[1]);
			}
		}
	}
	//get field information for address fields and add to global variable
	if ( $field['type'] == 'address' ){		
		$addressFields[$addressFieldId] = $field;	
	}

	return;	
}

function d8_predictiveaddress_wpforms_enqueue_scripts() {
	global $d8wpf_script_vars;
	
	if ( count($d8wpf_script_vars['addressFields']) > 0 ) {
		wp_register_script('d8wpf', 'https://webservices.data-8.co.uk/javascript/predictiveaddress_wpf.js', array('jquery', 'd8pa'), null, true);
		wp_localize_script('d8wpf', 'd8wpf_script_vars', $d8wpf_script_vars);
		wp_enqueue_script('d8wpf', $in_footer = true);
	}
}

add_filter( 'wpforms_address_schemes', 'wpf_new_address_scheme', 10, 1 );

function wpf_new_address_scheme( $schemes ) {
 
    $schemes[ 'Data8' ] = array(
            'label'          => 'Data8',
        'address1_label' => 'Address Line 1',
        'address2_label' => 'Address Line 2',
        'city_label'     => 'City',
        'postal_label'   => 'Postal Code',
        'state_label'    => 'State / Province / Region',
        'states'         => '',
        'countries'      => array(
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
        'AC' => 'Ascension Island',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
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
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'VG' => 'British Virgin Islands',
        'BN' => 'Brunei',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'IC' => 'Canary Islands',
        'CV' => 'Cape Verde',
        'BQ' => 'Caribbean Netherlands',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'EA' => 'Ceuta and Melilla',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CP' => 'Clipperton Island',
        'CC' => 'Cocos Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CD' => 'Democratic Republic of the Congo',
		'CG' => 'Republic of the Congo',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CW' => 'Curaçao',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark (Danmark)',
        'DG' => 'Diego Garcia',
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
        'FK' => 'Falkland Islands',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
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
        'HM' => 'Heard Island & McDonald Islands',
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
		'CI' => 'Ivory Coast',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'XK' => 'Kosovo',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Laos',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macau',
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
        'NL' => 'Netherlands',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'KP' => 'North Korea',
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
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Réunion',
        'RO' => 'Romania',
        'RU' => 'Russia',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthélemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre and Miquelon',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'São Tomé and Príncipe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SX' => 'Sint Maarten',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia & South Sandwich Islands',
        'KR' => 'South Korea',
        'SS' => 'South Sudan',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'VC' => 'St. Vincent & Grenadines',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard and Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syria',
        'TW' => 'Taiwan, Province of China',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TA' => 'Tristan da Cunha',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UM' => 'U.S. Outlying Islands',
        'VI' => 'U.S. Virgin Islands',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VA' => 'Vatican',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',
        ),
    );
 
    return $schemes;
 
}

?>