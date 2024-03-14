<?php

if (get_option('d8cf7_telephone_validation')) {
	add_filter( 'wpcf7_validate_tel', 'd8_validate_tel', 10, 2 );
	add_filter( 'wpcf7_validate_tel*', 'd8_validate_tel', 10, 2 );
	add_filter( 'wpcf7_validate_intl_tel', 'd8_validate_tel', 10, 2 );
	add_filter( 'wpcf7_validate_intl_tel*', 'd8_validate_tel', 10, 2 );
}

function d8_validate_tel($result, $tag) {
	$tag = new WPCF7_FormTag( $tag );
	$name = $tag->name;

	$value = isset( $_POST[$name] )
		? trim( strtr( (string) $_POST[$name], "\n", " " ) )
		: '';

	$defaultCountry = $tag->get_option('country', '', true);

	if(!empty($defaultCountry))
		$defaultCountry = trim($defaultCountry);

	if ($defaultCountry == ''){
		if(get_option('d8cf7_telephone_default_country'))
			$defaultCountry = get_option('d8cf7_telephone_default_country');
		else
			$defaultCountry = "44";
	}

	//get end user id if default country code is auto
	if($defaultCountry == 'auto'){
		$ip = getEndUserIp();
		$endUserIp = strval($ip);
	}else{
		$endUserIp = '';
	}

	// Get the allowed prefixes
	$allowedPrefixesInput = $tag->get_option( 'allowedPrefixes', '', true );
	$allowedPrefixes = '';

	if($allowedPrefixesInput != ''){
		$allowedPrefixArray = [];
		$allowedPrefixArray = explode("_", $allowedPrefixesInput);
		
		foreach ($allowedPrefixArray as &$prefix) {
			$allowedPrefixes = ($allowedPrefixes == '' ? $prefix : $allowedPrefixes.','.$prefix);
		}
	}
	else if(get_option('d8cf7_telephone_allowed_prefixes'))
		$allowedPrefixes = get_option('d8cf7_telephone_allowed_prefixes');
	
	// Get the barred prefixes
	$barredPrefixesInput = $tag->get_option( 'barredPrefixes', '', true );
	$barredPrefixes = '';

	if($barredPrefixesInput != ''){
		$barredPrefixArray = [];
		$barredPrefixArray = explode("_", $barredPrefixesInput);
		
		$barredPrefixes = '';
		foreach ($barredPrefixArray as &$prefix) {
			$barredPrefixes = ($barredPrefixes == '' ? $prefix : $barredPrefixes.','.$prefix);
		}
	}
	else if(get_option('d8cf7_telephone_barred_prefixes'))
		$barredPrefixes = get_option('d8cf7_telephone_barred_prefixes');

	// Required Country
	$requiredCountry = '';
	if(get_option('d8cf7_telephone_required_country'))
		$requiredCountry = get_option('d8cf7_telephone_required_country');
	
	// Check if required
	if ( $tag->is_required() && '' == $value ) 
		$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
	elseif ( '' != $value) {
		$d8cf7_ajax_key = get_option('d8cf7_ajax_key');

		if(get_option('d8cf7_ajax_key') == "")
			return $result;

		$params = array(
			'telephoneNumber' => $value,
			'defaultCountry' => $defaultCountry,
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

		$url = "https://webservices.data-8.co.uk/PhoneValidation/IsValid.json?key=" . $d8cf7_ajax_key;
		$wsresult = ajaxAsyncRequest($url, $params);

		if ($wsresult['Status']['Success'] && ($wsresult['Result']['ValidationResult'] == 'Invalid' || $wsresult['Result']['ValidationResult'] == 'Blank'))
			$result->invalidate( $tag, wpcf7_get_message( 'invalid_tel' ) );	
	}
	return $result;
}

if (get_option('d8cf7_email_validation_level') && get_option('d8cf7_email_validation_level') !== "None") {
	add_filter( 'wpcf7_validate_email', 'd8_validate_email', 10, 2 );
	add_filter( 'wpcf7_validate_email*', 'd8_validate_email', 10, 2 );
}

function d8_validate_email($result, $tag) {
	$tag = new WPCF7_FormTag( $tag );
	$name = $tag->name;

	$value = isset( $_POST[$name] )
		? trim( strtr( (string) $_POST[$name], "\n", " " ) )
		: '';

	$level = $tag->get_option( 'level', '(Syntax|MX|Server|Address)', true);

	if($level == "None"){ return $result; }
	if ($level == ''){
		if(get_option('d8cf7_email_validation_level'))
			$level = get_option('d8cf7_email_validation_level');
		else
			$level = "MX";
	}

	if ( $tag->is_required() && '' == $value ) 
		$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
	elseif ( '' != $value) {
		$d8cf7_ajax_key = get_option('d8cf7_ajax_key');
		
		if(isset($d8cf7_ajax_key))
		{
			// Set up the parameters for the web service call:
			// https://www.data-8.co.uk/support/service-documentation/email-validation/reference/isvalid
			$params = array(
				'email' => $value,
				'level' => $level,
				'options' => array (
					'ApplicationName' => 'WordPress'
				)
			);
			$url = "https://webservices.data-8.co.uk/EmailValidation/IsValid.json?key=" . $d8cf7_ajax_key;
			$wsresult = ajaxAsyncRequest($url, $params);

			if ($wsresult['Status']['Success'] && $wsresult['Result'] == 'Invalid')
				$result->invalidate( $tag, wpcf7_get_message( 'invalid_email' ) );
		}
	}
	return $result;
}

if (get_option('d8cf7_salaciousName')) {
	add_filter( 'wpcf7_validate_text', 'd8_validate_name', 10, 2 );
	add_filter( 'wpcf7_validate_text*', 'd8_validate_name', 10, 2 );
}

function d8_validate_name ($result, $tag) {
	$tag = new WPCF7_FormTag( $tag );
	$nameType = $tag->get_option( 'name_type', '', true );

	if($nameType == 'FullName'){
		$name = $tag->name;
	
		$value = isset( $_POST[$name] )
			? trim( strtr( (string) $_POST[$name], "\n", " " ) )
			: '';
			
		
		if ( $tag->is_required() && '' == $value ) 
			$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
		elseif ( '' != $value) {
			$d8cf7_ajax_key = get_option('d8cf7_ajax_key');
	
			if(get_option('d8cf7_ajax_key') == "")
				return $result;
	
			// Set up the parameters for the web service call:
			// https://www.data-8.co.uk/support/service-documentation/email-validation/reference/isvalid
			$params = array(
				'name' => array(
					'title' => null,
					'forename' => $value,
					'middlename' => null,
					'surname' => null,
				),
				'options' => array (
					'ApplicationName'=> 'WordPress'
				)
			);
			$url = "https://webservices.data-8.co.uk/SalaciousName/IsUnusableName.json?key=" . $d8cf7_ajax_key;
			$wsresult = ajaxAsyncRequest($url, $params);
	
			if ($wsresult['Status']['Success'] && $wsresult['Result'] != '')
				$result->invalidate( $tag, "Check your name" );	
		}
	}
    return $result;
}

if (get_option('d8cf7_bank_validation')) {
	add_filter('wpcf7_validate_text', 'd8_validate_bank', 10, 2);
	add_filter('wpcf7_validate_text*', 'd8_validate_bank', 10, 2);
}

$d8cf7_bank_vars = array(
	'accountNumber' => "",
	'sortCode' => ""
);

function d8_validate_bank($result, $tag){

	global $d8cf7_bank_vars;
	$tag = new WPCF7_FormTag( $tag );
	$bankType = $tag->get_option('bank_type','', true);
	$accountNumber = &$d8cf7_bank_vars['accountNumber'];
	$sortCode = &$d8cf7_bank_vars['sortCode'];

	if(get_option('d8cf7_ajax_key') == ""){
		return $result;
	}
	
	if($bankType == "d8-account-number"){
		$name = $tag->name;
		$accountNumber = isset( $_POST[$name] )
			? trim( strtr( (string) $_POST[$name], "\n", " " ) )
			: '';		
	}else if($bankType == "d8-sort-code"){
		$name = $tag->name;
		$sortCode = isset( $_POST[$name] )
			? trim( strtr( (string) $_POST[$name], "\n", " " ) )
			: '';		
	} else {
		return $result;
	}
	
	if($accountNumber !== "" && $sortCode !==""){
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
			$result->invalidate($tag, "check bank account details");
		}
	}	
	
	return $result;	
}
?>