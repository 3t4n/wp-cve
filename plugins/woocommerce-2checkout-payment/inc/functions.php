<?php
/**
 * Helper functions
 **/
 
if( ! defined( "ABSPATH") ) die("Not Allowed");

function twoco_pa( $array ) {
		wc_print_r($array);
}

// Getting rate via free.currencyconverterapi.com
function twoco_get_rates_from_ccapi($amount, $from, $to, $curr_converter_api) {
	
	$conv_id 		= "{$from}_{$to}";
	$endpoint_url	= twoco_get_curr_endpoint();
	$req_url		= "https://{$endpoint_url}/api/v3/convert?q=$conv_id&compact=ultra&apiKey={$curr_converter_api}";
	$response 		= wp_remote_get($req_url);

	if( is_wp_error( $response ) ) {
		return false; // Bail early
	}
	
	if( wp_remote_retrieve_response_code($response) == 400 ) {
		return false;
	}
	
	// twoco_pa($response);

	$body = wp_remote_retrieve_body( $response );
	$response_json	= json_decode($body, true);

	
	return $amount * $response_json[$conv_id];
}

function twoco_get_curr_endpoint() {
	$curr_version = get_option('towco_curr_version');
	
	$endpoint_url = "";
	switch( $curr_version ) {
		
		case 'pro':
			$endpoint_url = "api.currencyconverterapi.com";
		break;
		case 'prepaid':
			$endpoint_url = "prepaid.currconv.com";
		break;
		default:
			$endpoint_url = "free.currencyconverterapi.com";
		break;
			
	}
	
	return $endpoint_url;
}


function twoco_is_currency_supported() {
  $supported_currencies = array(
    'AFN', 'ALL', 'DZD', 'ARS', 'AUD', 'AZN', 'BSD', 'BDT', 'BBD',
    'BZD', 'BMD', 'BOB', 'BWP', 'BRL', 'GBP', 'BND', 'BGN', 'CAD', 
    'CLP', 'CNY', 'COP', 'CRC', 'HRK', 'CZK', 'DKK', 'DOP', 'XCD', 
    'EGP', 'EUR', 'FJD', 'GTQ', 'HKD', 'HNL', 'HUF', 'INR', 'IDR', 
    'ILS', 'JMD', 'JPY', 'KZT', 'KES', 'LAK', 'MMK', 'LBP', 'LRD', 
    'MOP', 'MYR', 'MVR', 'MRO', 'MUR', 'MXN', 'MAD', 'NPR', 'TWD', 
    'NZD', 'NIO', 'NOK', 'PKR', 'PGK', 'PEN', 'PHP', 'PLN', 'QAR', 
    'RON', 'RUB', 'WST', 'SAR', 'SCR', 'SGD', 'SBD', 'ZAR', 'KRW', 
    'LKR', 'SEK', 'CHF', 'SYP', 'THB', 'TOP', 'TTD', 'TRY', 'UAH', 
    'AED', 'USD', 'VUV', 'VND', 'XOF', 'YER');
    if ( ! in_array( get_woocommerce_currency(), apply_filters( 'twocheckout_supported_currencies', $supported_currencies ) ) ) return false;
    return true;
}

function twoco_convertion_is_on() {
	
	// $enabled = false;
	/*$xchange_rate 		= get_option('xchange_rate');
	$curr_converter_api	= trim( get_option('curr_converter_api') );
	if( $xchange_rate == 'yes' && $curr_converter_api != '' ) {
		$enabled = true;
	}*/
	
	$curr_updatable		= get_option('twoco_currency_updated');
	if( $curr_updatable ) {
		
		$enabled = true;
	}
	
	return $enabled;
}

function twoco_get_price($price){
	
	if( twoco_is_currency_supported() ) {
		return $price;
	}
	
	if( twoco_convertion_is_on() ){
		$curr_converter_api	= trim( get_option('curr_converter_api') );
		$price = twoco_get_converted_price( $price, $curr_converter_api );
	}
	
	$price = wc_format_decimal($price, wc_get_price_decimals());
	
	return apply_filters('twoco_xchanged_price', $price);
}

function twoco_get_converted_price( $price, $curr_converter_api ) {
	
	$from_curr	= get_woocommerce_currency();
	$to_curr	= twoco_get_conversion_currency();
	
	return twoco_get_rates_from_ccapi($price, $from_curr, $to_curr, $curr_converter_api);
}
// Return label for Currency Label with current rate
function twoco_currency_label( $xchange_rate, $curr_converter_api ) {
	
	if( $xchange_rate != 'yes' ) return '';
	
	if( $curr_converter_api == '' ) return '';
	
	if( ! is_admin() ) return '';
	
	$from_curr	= get_woocommerce_currency();
	
	$label = 'Enable';
	if( ! twoco_is_currency_supported() ) {
		
		$label .= " - {$from_curr} No Supported";
	}
	
	$amount = 1;
	
	$price_converted = twoco_get_converted_price( $amount, $curr_converter_api);
	if( $price_converted === false ) {
		update_option('twoco_currency_updated', false);
		$label = "<div class='notice notice-error'>Failed to update rate, make sure API key is correct</div>";
	} else {
		
		update_option('twoco_currency_updated', true);
		$conversion_rate = wc_format_decimal( $price_converted, 4 );
		$to_curr		= twoco_get_conversion_currency();
		$label .= " - ({$amount} {$from_curr} = {$conversion_rate} {$to_curr})";
	}
	
	
	
	return $label;
}

// Get converted currency
function twoco_get_conversion_currency() {
	
	return apply_filters('twoco_conversion_currency', 'USD');
}

function twoco_log( $log ) {
	
	if ( true === true ) {
      if ( is_array( $log ) || is_object( $log ) ) {
          $resp = error_log( print_r( $log, true ), 3, plugin_dir_path(__FILE__).'twoco.log' );
      } else {
          $resp = error_log( $log, 3, plugin_dir_path(__FILE__).'twoco.log' );
      }
      
  }
}


// Get CC Type
function twoco_get_cc_type($cardNumber){
    $cardNumber = preg_replace('/\D/', '', $cardNumber);
 
	// Validate the length
    $len = strlen($cardNumber);
    if ($len < 15 || $len > 16) {
        throw new Exception("Invalid credit card number. Length does not match");
    }else{
        switch($cardNumber) {
            case(preg_match ('/^4/', $cardNumber) >= 1):
                return 'Visa';
            case(preg_match ('/^5[1-5]/', $cardNumber) >= 1):
                return 'Mastercard';
            case(preg_match ('/^3[47]/', $cardNumber) >= 1):
                return 'Amex';
            case(preg_match ('/^3(?:0[0-5]|[68])/', $cardNumber) >= 1):
                return 'Diners Club';
            case(preg_match ('/^6(?:011|5)/', $cardNumber) >= 1):
                return 'Discover';
            case(preg_match ('/^(?:2131|1800|35\d{3})/', $cardNumber) >= 1):
                return 'JCB';
            default:
                throw new Exception("Could not determine the credit card type.");
                break;
        }
    }
}

function twoco_load_template($file_name, $variables=array('')){

	if( is_array($variables) )
    extract( $variables );
    
   $file_path = TWOCO_PATH . '/templates/'.$file_name;
   if( file_exists($file_path))
   		include ($file_path);
   else
   		die('File not found'.$file_path);
}