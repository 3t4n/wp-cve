<?php
if(get_option('d8cf7_telephone_validation')){
    add_action('elementor_pro/forms/validation/tel', 'd8_validate_tel_ep', 10, 3);
}

function d8_validate_tel_ep($field, $record, $ajax_handler ){
	
    if(get_option('d8cf7_ajax_key') == ""){
        return;
    }

	$value = $field['value'];
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
    $requiredCounty = '';

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
		$ajax_handler->add_error( $field['id'], 'Invalid Phone Number' );

	}
    	
	return;
}


if(get_option('d8cf7_email_validation_level')){
    add_action('elementor_pro/forms/validation', 'd8_validate_email_ep', 10, 2);
}

function d8_validate_email_ep($record, $ajax_handler ){

	if(get_option('d8cf7_ajax_key') == ""){
        return;
    }

	$fields = $record->get_field([]);
	foreach($fields as $field){
		$fieldId = $field['id'];
		if(strpos($fieldId, "d8_email") !== false){
			$email = $field['value'];
			
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
				 $ajax_handler->add_error( $field['id'], 'Invalid Email' );
			}
		}
	}
	return;
}

if(get_option('d8cf7_salaciousName')){
    add_action('elementor_pro/forms/validation', 'd8_validate_name_ep', 10, 2);
}

function d8_validate_name_ep($record, $ajax_handler ){

	if(get_option('d8cf7_ajax_key') == ""){
		return;
	}

	$fields = $record->get_field([]);
	foreach($fields as $field){

		$fieldId = $field['id'];

		if(strpos($fieldId, "d8_name") !== false){
			$name = $field['value'];
			
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
			//make api call
			$wsresult = ajaxAsyncRequest($url, $params);
			
			if ($wsresult['Status']['Success'] && $wsresult['Result'] != ''){
				 $ajax_handler->add_error( $field['id'], 'Invalid Name' );
			}
		}
		//check for first name last name
		if(strpos($fieldId, "d8_first_name") !== false){
			$firstName = $field['value'];
			$firstNameId = $fieldId;
		}

		if(strpos($fieldId, "d8_last_name") !== false){
			$lastName = $field['value'];
			$lastNameId = $fieldId;
			
			if($firstName == null || $lastName == null){
				continue;
			}

			$params = array(
				'name' => array(
					'title' => null,
					'forename' => $firstName,
					'middlename' => null,
					'surname' => $lastName
				),
				'options' => array(
					'ApplicationName' => 'Wordpress'
				)
			);

			$d8cf7_ajax_key = get_option('d8cf7_ajax_key');
			$url = "https://webservices.data-8.co.uk/SalaciousName/IsUnusableName.json?key=" . $d8cf7_ajax_key;
			$wsresult = ajaxAsyncRequest($url, $params);

			if ($wsresult['Status']['Success'] && $wsresult['Result'] != ''){
				$ajax_handler->add_error( $firstNameId, 'Invalid Name' );
				$ajax_handler->add_error( $lastNameId, 'Invalid Name' );
		   }
		}
	}
	return;
}

if(get_option('d8cf7_bank_validation')){	
	add_action('elementor_pro/forms/validation', 'd8_validate_bank_ep', 10, 2);
}

function d8_validate_bank_ep($record, $ajax_handler ){
	$accountNumber = "";
	$accountNumberId = "";
	$sortCode = "";
	$sortCodeId = "";

	if(get_option('d8cf7_ajax_key') == ""){
		return;
	}
	//get all fields
	$fields = $record->get_field([]);

	foreach($fields as $field){
		$fieldId = $field['id'];
		if(strpos($fieldId, "d8_account_number") !== false){
			$accountNumber = $field['value'];
			$accountNumberId = $fieldId;
		} else if(strpos($fieldId, "d8_sort_code") !== false){
			$sortCode = $field['value'];
			$sortCodeId = $fieldId;
		} 
	}
	
	//exit if sort code is blank
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
	
	$d8cf7_ajax_key = get_option('d8cf7_ajax_key');;
	$url = "https://webservices.data-8.co.uk/BankAccountValidation/IsValid.json?key=" . $d8cf7_ajax_key;
	$wsresult = ajaxAsyncRequest($url, $params);
	
	if($wsresult['Status']['Success'] && $wsresult['Valid'] == 'Invalid'){
		$ajax_handler->add_error( $accountNumberId, 'Invalid Account Number or Sort Code' );
		$ajax_handler->add_error( $sortCodeId, 'Invalid Account Number or Sort Code' );
	}
	
}

if(get_option('d8cf7_predictiveaddress')){
	add_action('elementor/frontend/the_content', 'd8_predictiveaddress_ep_pre_render');
	add_action('wp_footer', 'd8_predictiveaddress_elementor_enqueue_scripts');
}

$d8ep_script_vars = array(
	'ajaxKey' => get_option('d8cf7_client_api_key'),
	'applicationName' => 'Wordpress',
);

function d8_predictiveaddress_ep_pre_render( $content ){	
	global $d8ep_script_vars;
	
	if(null !== get_option('d8cf7_predictiveaddress_options')){
		$options = explode("\n", str_replace('\"', '"', get_option('d8cf7_predictiveaddress_options')));
		
		foreach ( $options as $option ) {
			if ( strpos($option, ':') !== false ) {
				$optionArr = explode(":", $option, 2);
				$d8ep_script_vars[trim($optionArr[0])] = trim($optionArr[1]);
			}
		}
	}

	return $content;
}

function d8_predictiveaddress_elementor_enqueue_scripts(){
	global $d8ep_script_vars;
	wp_register_script('d8ep', 'https://webservices.data-8.co.uk/javascript/predictiveaddress_ep.js', array('jquery','d8pa'), null, true);
	wp_localize_script('d8ep', 'd8ep_script_vars', $d8ep_script_vars);
	wp_enqueue_script('d8ep', $in_footer = true);
}

?>