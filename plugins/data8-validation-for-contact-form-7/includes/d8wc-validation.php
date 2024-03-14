<?php

if (get_option('d8cf7_predictiveaddress')) {
	add_filter( 'woocommerce_after_checkout_billing_form', 'd8_predictiveaddress_wc', 10, 2 );
	add_filter( 'woocommerce_after_checkout_shipping_form', 'd8_predictiveaddress_wc', 10, 2 );
	add_filter( 'woocommerce_after_edit_account_address_form', 'd8_predictiveaddress_wc', 10, 2 );
}

function d8_predictiveaddress_wc($checkout) {
	$d8pawc_script_vars = array(
		'ajaxKey' => get_option('d8cf7_client_api_key'),
		'applicationName' => 'WordPress');

	if(null !== get_option('d8cf7_predictiveaddress_options')){
		$options = explode("\n", str_replace('\"', '"', get_option('d8cf7_predictiveaddress_options')));
		
		foreach ( $options as $option ) {
			if ( strpos($option, ':') !== false ) {
				$optionArr = explode(":", $option, 2);
				$d8pawc_script_vars[trim($optionArr[0])] = trim($optionArr[1]);
			}
		}
	}
	
	wp_register_script('d8pawc', 'https://webservices.data-8.co.uk/javascript/predictiveaddress_wc.js', array('jquery', 'd8pa'), null, true);
	wp_localize_script('d8pawc', 'd8pawc_script_vars', $d8pawc_script_vars);
	wp_enqueue_script('d8pawc');
}

add_action('woocommerce_checkout_process', 'd8wc_validate');

function d8wc_validate(){
	// telephone validation
	if(get_option('d8cf7_telephone_validation'))
		d8wc_validate_tel('billing_phone');

	// email validation
	if(get_option('d8cf7_email_validation_level') && get_option('d8cf7_email_validation_level') !== "None")
		d8wc_validate_email('billing_email');

	// name validation
	if(get_option('d8cf7_salaciousName')){
		if(array_key_exists ('billing_first_name', $_POST))
			d8wc_validate_name(array('billing_first_name', 'billing_last_name'));
		
		if(array_key_exists ('shipping_first_name', $_POST))
			d8wc_validate_name(array('shipping_first_name', 'shipping_last_name'));
		
	}
}

function d8wc_validate_tel($name) {
	$value = isset( $_POST[$name] )
		? trim( strtr( (string) $_POST[$name], "\n", " " ) )
		: '';

	$country = $_POST['billing_country'];

	$allowedPrefixes = '';
	if(get_option('d8cf7_telephone_allowed_prefixes'))
		$allowedPrefixes = get_option('d8cf7_telephone_allowed_prefixes');
	
	$barredPrefixes = '';
	if(get_option('d8cf7_telephone_barred_prefixes'))
		$barredPrefixes = get_option('d8cf7_telephone_barred_prefixes');

	$requiredCountry = '';
	if(get_option('d8cf7_telephone_required_country'))
		$requiredCountry = get_option('d8cf7_telephone_required_country');

	if ($country == ''){
		if(get_option('d8cf7_telephone_default_country'))
			$country = get_option('d8cf7_telephone_default_country');
		else
			$country = '44';
	}
	//get end user id if default country code is auto
	if($defaultCountry == 'auto'){
		$ip = getEndUserIp();
		$endUserIp = strval($ip);
	}else{
		$endUserIp = '';
	}

	if ('' == $value) 
		return;
	elseif ( '' != $value) {
		$d8cf7_ajax_key = get_option('d8cf7_ajax_key');

		if(get_option('d8cf7_ajax_key') != ""){

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

			$url = "https://webservices.data-8.co.uk/PhoneValidation/IsValid.json?key=" . $d8cf7_ajax_key;
			$wsresult = ajaxAsyncRequest($url, $params);

			if ($wsresult['Status']['Success'] && $wsresult['Result']['ValidationResult'] == 'Invalid')
				wc_add_notice(__('Invalid telephone number. If the number is correct, try adding the area code i.e. +44 for UK'), 'error' );
		}
	}
}

function d8wc_validate_email($name) {
	$value = isset( $_POST[$name] )
		? trim( strtr( (string) $_POST[$name], "\n", " " ) )
		: '';

	$level = get_option('d8cf7_email_validation_level');

	if($level != "None"){ 
		if ($level == '')
			$level = "MX";

		if ('' == $value) 
			return;
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
					wc_add_notice(__('Invalid email address.'), 'error' );
			}
		}
	}
}

function d8wc_validate_name ($nameArray) {
	$firstValue = isset( $_POST[$nameArray[0]] )
		? trim( strtr( (string) $_POST[$nameArray[0]], "\n", " " ) )
		: '';
		
	$lastValue = isset( $_POST[$nameArray[1]] )
	? trim( strtr( (string) $_POST[$nameArray[1]], "\n", " " ) )
	: '';
		
	if ('' == $firstValue)
		return;
	
	if ('' == $lastValue) 
		return;

	if('' != $firstValue && '' != $lastValue) {
		$d8cf7_ajax_key = get_option('d8cf7_ajax_key');

		if(get_option('d8cf7_ajax_key') == "")
			return;

		// Set up the parameters for the web service call:
		// https://www.data-8.co.uk/support/service-documentation/email-validation/reference/isvalid
		$params = array(
			'name' => array(
				'title' => null,
				'forename' => $firstValue,
				'middlename' => null,
				'surname' => $lastValue
			),
			'options' => array (
				'ApplicationName' => 'WordPress'
			)
		);
		$url = "https://webservices.data-8.co.uk/SalaciousName/IsUnusableName.json?key=" . $d8cf7_ajax_key;
		$wsresult = ajaxAsyncRequest($url, $params);

		if ($wsresult['Status']['Success'] && $wsresult['Result'] != '')
			wc_add_notice(__('Invalid name.'), 'error' );
	}
}

?>