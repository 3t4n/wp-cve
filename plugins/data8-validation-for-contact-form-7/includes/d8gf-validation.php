<?php

if (get_option('d8cf7_predictiveaddress')) {
	add_filter( 'gform_pre_render', 'd8_predictiveaddress_gform_pre_render', 10, 2 );
	add_action( 'wp_footer', 'd8_predictiveaddress_gform_enqueue_scripts');
}

$d8pagf_script_vars = array(
	'ajaxKey' => get_option('d8cf7_client_api_key'),
	'applicationName' => 'WordPress',
	'forms' => array()
);

function d8_predictiveaddress_gform_pre_render( $form, $is_ajax ) {
	global $d8pagf_script_vars;
	$forms = &$d8pagf_script_vars['forms'];
	$formId = $form['id'];
	$fields = array();

	if(null !== get_option('d8cf7_predictiveaddress_options')){
		$options = explode("\n", str_replace('\"', '"', get_option('d8cf7_predictiveaddress_options')));
		
		foreach ( $options as $option ) {
			if ( strpos($option, ':') !== false ) {
				$optionArr = explode(":", $option, 2);
				$d8pagf_script_vars[trim($optionArr[0])] = trim($optionArr[1]);
			}
		}
	}
	
	foreach ( $form['fields'] as &$field ) {
		if ( $field->get_input_type() === 'address' )
			array_push($fields, $field);
	}
	
	$forms[$formId] = $fields;
	
	return $form;
}

function d8_predictiveaddress_gform_enqueue_scripts() {
	global $d8pagf_script_vars;
	
	if ( count($d8pagf_script_vars['forms']) > 0 ) {
		wp_register_script('d8pagf', 'https://webservices.data-8.co.uk/javascript/predictiveaddress_gf.js', array('jquery', 'd8pa'), null, true);
		wp_localize_script('d8pagf', 'd8pagf_script_vars', $d8pagf_script_vars);
		wp_enqueue_script('d8pagf', $in_footer = true);
	}
}

if (get_option('d8cf7_telephone_validation')) 
	add_filter( 'gform_field_validation', 'd8_validate_tel_gf', 10, 4 );

function startsWith($haystack, $needle) {
	$length = strlen($needle);
	return (substr($haystack, 0, $length) == $needle);
}

function d8_validate_tel_gf( $result, $value, $form, $field ) {
	$current_page = rgpost( 'gform_source_page_number_' . $form['id'] ) ? rgpost( 'gform_source_page_number_' . $form['id'] ) : 1;
	if ( $field->get_input_type() === 'phone' && $result['is_valid'] && $value != '' && $field->pageNumber == $current_page) {

		$classes = explode(' ', $field->cssClass);

		$defaultCountry = get_option('d8cf7_telephone_default_country');
		if(!empty($defaultCountry))
			$defaultCountry = trim($defaultCountry);
		
		if (empty($defaultCountry))
			$defaultCountry = "44";

		$country = $defaultCountry; 
		$allowedPrefixes = '';
		$barredPrefixes = '';
		$requiredCountry = '';

		//get end user id if default country code is auto
		if($defaultCountry == 'auto'){
			$ip = getEndUserIp();
			$endUserIp = strval($ip);
		}else{
			$endUserIp = '';
		}
		
		foreach ($classes as $class) {
			if (startsWith($class, 'd8country_'))
				$country = substr($class, 10);
			
			// Pull out a list of Allowed Prefixes (if specified)
			if (startsWith($class, 'd8AllowedPrefixes_')){
				$allowedPrefixesInput = substr($class, 10);
				$allowedPrefixArray = [];
				if ($allowedPrefixesInput != '') 
					$allowedPrefixArray = explode("_", $class);
				
				foreach ($allowedPrefixArray as &$prefix) {
					if ($prefix != 'd8AllowedPrefixes')
						$allowedPrefixes = ($allowedPrefixes == '' ? '+'.$prefix : $allowedPrefixes.','.'+'.$prefix);
				}
			}

			// Pull out a list of Barred Prefixes (if specified)
			if (startsWith($class, 'd8BarredPrefixes_')){
				$barredPrefixesInput = substr($class, 10);
				$barredPrefixArray = [];
				if ($barredPrefixesInput != '')
					$barredPrefixArray = explode("_", $class);
				
				foreach ($barredPrefixArray as &$prefix) {
					if ($prefix != 'd8BarredPrefixes_')
						$barredPrefixes = ($barredPrefixes == '' ? '+'.$prefix : $barredPrefixes.','.'+'.$prefix);
				}
			}
		}

		if($allowedPrefixes == '' && get_option('d8cf7_telephone_allowed_prefixes'))
			$allowedPrefixes = get_option('d8cf7_telephone_allowed_prefixes');

		if($barredPrefixes == '' && get_option('d8cf7_telephone_barred_prefixes'))
			$barredPrefixes = get_option('d8cf7_telephone_barred_prefixes');

		if($requiredCountry == '' && get_option('d8cf7_telephone_required_country'))
			$requiredCountry = get_option('d8cf7_telephone_required_country');
			
		$params = array(
			'telephoneNumber' => $value,
			'defaultCountry' => $country,
			'options' => array (
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
		if($d8cf7_ajax_key ==  "")
			return $result;

		$url = "https://webservices.data-8.co.uk/PhoneValidation/IsValid.json?key=" . $d8cf7_ajax_key;
		$wsresult = ajaxAsyncRequest($url, $params);
		
		if ($wsresult['Status']['Success'] && ($wsresult['Result']['ValidationResult'] == 'Invalid' || $wsresult['Result']['ValidationResult'] == "Blank")){
			$result['is_valid'] = false;
			$result['message'] = "Invalid phone number.";
		}
	}
	return $result;
}

if (get_option('d8cf7_email_validation_level'))
	add_filter( 'gform_field_validation', 'd8_validate_email_gf', 10, 4 );

function d8_validate_email_gf( $result, $value, $form, $field ) {
	$current_page = rgpost( 'gform_source_page_number_' . $form['id'] ) ? rgpost( 'gform_source_page_number_' . $form['id'] ) : 1;
	if ( $field->get_input_type() === 'email' && $result['is_valid'] && $value != '' && $field->pageNumber == $current_page) {
		$classes = explode(' ', $field->cssClass);

		$level = get_option('d8cf7_email_validation_level');
		if($level == "None")
			return $result;
		if($level == "")
			$level = 'MX';
		
		foreach ($classes as $class) {
			if (startsWith($class, 'd8level_'))
				$level = substr($class, 8);
		}
	
		// Set up the parameters for the web service call:
		// https://www.data-8.co.uk/support/service-documentation/email-validation/reference/isvalid
		$params = array(
			'email' => $value,
			'level' => $level,
			'options' => array (
				'ApplicationName' => 'WordPress'
			)
		);
		
		$d8cf7_ajax_key = get_option('d8cf7_ajax_key');
		if($d8cf7_ajax_key ==  "")
			return $result;

		$url = "https://webservices.data-8.co.uk/EmailValidation/IsValid.json?key=" . $d8cf7_ajax_key;
		$wsresult = ajaxAsyncRequest($url, $params);
		
		if ($wsresult['Status']['Success'] && $wsresult['Result'] == 'Invalid') {
			$result['is_valid'] = false;
			$result['message'] = "Invalid email address.";
		}
	}
	return $result;
}

if (get_option('d8cf7_salaciousName'))
	add_filter( 'gform_field_validation', 'd8_validate_name_gf', 10, 4 );

function d8_validate_name_gf ( $result, $value, $form, $field ) {
	$current_page = rgpost( 'gform_source_page_number_' . $form['id'] ) ? rgpost( 'gform_source_page_number_' . $form['id'] ) : 1;
	if ( $field->type == 'name' && $field->pageNumber == $current_page) {
		// Input values
		$prefix = rgar( $value, $field->id . '.2' );
		$first  = rgar( $value, $field->id . '.3' );
		$middle = rgar( $value, $field->id . '.4' );
		$last   = rgar( $value, $field->id . '.6' );
		$suffix = rgar( $value, $field->id . '.8' );
	
		// Set up the parameters for the web service call:
		// http://webservices.data-8.co.uk/salaciousname.asmx?op=IsUnusableName
		$params = array(
			'name' => array(
				'title' => $prefix,
				'forename' => $first,
				'middlename' => $middle,
				'surname' => $last
			),
			'options' => array (
				'ApplicationName' => 'WordPress'
			)
		);
		$d8cf7_ajax_key = get_option('d8cf7_ajax_key');
		if($d8cf7_ajax_key ==  "")
			return $result; 

		$url = "https://webservices.data-8.co.uk/SalaciousName/IsUnusableName.json?key=" . $d8cf7_ajax_key;
		$wsresult = ajaxAsyncRequest($url, $params);

		if ($wsresult['Status']['Success'] && $wsresult['Result'] != '') {
			$result['is_valid'] = false;
			$result['message'] = "Invalid name.";
		}
	}
    return $result;
}

if(get_option('d8cf7_bank_validation')){
	add_filter('gform_field_validation', 'd8_validate_bank_gf', 10, 4);
}

//global variable populated after function runs for relevant field
$d8gf_bank_vars = array(
	'accountNumber' => "",
	'sortCode' => ""
);


function d8_validate_bank_gf( $result, $value, $form, $field ) {
	global $d8gf_bank_vars;
	$accountNumber = &$d8gf_bank_vars['accountNumber'];
	$sortCode = &$d8gf_bank_vars['sortCode'];
	
	if(strpos($field['cssClass'], "d8-sortcode") !== false){
		$sortCode = $value;
	} else if(strpos($field['cssClass'], "d8-account-number") !== false){
		$accountNumber = $value;
	} else {
		return $result;
	}

	if(get_option('d8cf7_ajax_key') == ""){
		return $result;
	}
	
	//once global variables have values for sort code and account number: validate
	if($accountNumber !== "" && $sortCode !== ""){
		$params = array(
			'sortCode' => $sortCode,
			'bankAccountNumber' => $accountNumber,
			'options' => array(
				'ApplicationName' => 'WordPress'
			)
		);
		
		$d8cf7_ajax_key = get_option('d8cf7_ajax_key');
		$url = "https://webservices.data-8.co.uk/BankAccountValidation/IsValid.json?key=" . $d8cf7_ajax_key;
		$wsresult = ajaxAsyncRequest($url, $params);
		
		if($wsresult['Status']['Success'] && $wsresult['Valid'] == 'Invalid'){
			$result['is_valid'] = false;
			$result['message'] = "Invalid bank details.";
		}
		
	} 
	
	return $result;
	
}

?>