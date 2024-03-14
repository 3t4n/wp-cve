<?php

/*
* Get authentication details for SK Posta API
*/
function tsseph_posta_api_get_auth() {
	$tsseph_options = get_option( 'tsseph_options' );

	$tsseph_options['UserId'] = (!empty($tsseph_options['UserId']) ? $tsseph_options['UserId'] : '');   
	$tsseph_options['apiKey'] = (!empty($tsseph_options['apiKey']) ? $tsseph_options['apiKey'] : '');


	$auth = array (
		'userId' => $tsseph_options['UserId'],
		'apiKey' => $tsseph_options['ApiKey']
		);

	return $auth;
}

/*
*	New API call to Slovenska Posta
*/
function tsseph_posta_api_call($api_service, $method, $body) {

	//PHP < 7.1 compatibility for json_encode decimals
	ini_set("precision", 14); 
	ini_set("serialize_precision", -1);

	$tsseph_options = get_option('tsseph_options'); 
	if (!isset($tsseph_options['LastLog'])) { $tsseph_options['LastLog'] = array('importSheet' => '','getSheetStatus' => '','getSheet' => ''); } 

	$auth = tsseph_posta_api_get_auth();

	$api_url = "https://mojezasielky.posta.sk/integration/rest/v1/" . $api_service;

	$response = wp_remote_post( $api_url, array(
		'method'      => $method,
		'headers'     => array('Content-Type' => 'application/json; charset=UTF-8',
							   'x-api-auth'	  => 'apikey ' . $auth['userId'] . ":" . $auth['apiKey']),
		'body'        => json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
		)
	);	

	$tsseph_options['LastLog'][$api_service] = serialize(array(array($body),$response));

	update_option('tsseph_options', $tsseph_options);

	//If response body exists, API call was successful
	if (is_array($response) && !empty(json_decode($response['body'],true))) {
		return json_decode($response['body'], true);
	}
	else {
		return array(
			'validation_errors' => array(
				array(
					'attribute' => 'Error',
					'reason' => $response->errors['http_request_failed'][0]
				)
			)
		);
	}
}

/*
	WSDL API call to Slovenska Posta
*/
function tsseph_posta_api($method,$data) {

	//Prepare log
	$tsseph_options = get_option('tsseph_options'); 
	if (!isset($tsseph_options['LastLog'])) { $tsseph_options['LastLog'] = array('importSheet' => '','getSheetStatus' => '','getSheet' => ''); } 

	$WSDL = 'https://mojezasielky.posta.sk/integration/webServices/api?wsdl';
	$client = new SoapClient($WSDL, array("soap_version" => SOAP_1_1,"trace" => 1));
	$response = "";

	try {
		$response = $client->__soapCall($method,array($data));
		$tsseph_options['LastLog'][$method] = serialize(array(array($data),$response));
	}	
	catch (SoapFault $exception){
		$tsseph_options['LastLog'][$method] = serialize(array(array($data),$exception));
	}

	update_option('tsseph_options', $tsseph_options);	

	return $response;
}

/*
	Posta API - Druh zasielky
*/
function tsseph_posta_api_get_druh_zasielky($eph_shipping_method) { 

    switch($eph_shipping_method) {
        case 2: 
            $druh_zasielky = array('code' => 'pl', 'name' => __('Poistený list', 'spirit-eph'));
            break;  		
        case 4: 
            $druh_zasielky = array('code' => 'b', 'name' => __('Balik', 'spirit-eph'));
            break;  
        case 8:
            $druh_zasielky = array('code' => 'ek', 'name' => __('Expres kuriér', 'spirit-eph'));
            break;   
		case 14:
			$druh_zasielky = array('code' => 'zb', 'name' => __('Zmluvni zákazníci', 'spirit-eph'));
			break; 			  
        case 30:
            $druh_zasielky = array('code' => 'olz', 'name' => __('List', 'spirit-eph')); 
            break;                      			          
        default:
            $druh_zasielky = array('code' => 'r', 'name' => __('Doporučený list', 'spirit-eph'));                 
    }

    return $druh_zasielky;
}

/*
	Posta API - Spôsob úhrady
*/
function tsseph_posta_api_get_sposob_uhrady($id) {
	
    switch($id) {
        case 1: 
			$sposob_uhrady = 'up'; //Úver poštovného
			break;
		case 2:
			$sposob_uhrady = 'vsz'; //Výplatný stroj
			break;
		case 3:
			$sposob_uhrady = 'pr'; //Platené prevodom
			break;
		case 4:
			$sposob_uhrady = 'pz'; //Poštové známky
			break;
		case 5: 
			$sposob_uhrady = 'h'; //Platené v hotovosti
			break;
		case 7:
			$sposob_uhrady = 'vps'; //Vec poštovej služby
			break;
		case 8:
			$sposob_uhrady = 'fa'; //Faktúra
			break;
		case 9:
			$sposob_uhrady = 'ol'; //Online
			break;
		
		default: $sposob_uhrady = 'up'; 
			
	}

	return $sposob_uhrady;
}

/*
	Posta API - Doplnkové služby
*/
function tsseph_posta_api_get_doplnkove_sluzby($order_data) {
	$services = array();

	if ($order_data['order_fragile'] == 1) {
		$services[] = 'f';
	}

	if ($order_data['tsseph_shipping_method_id'] == 3 || $order_data['tsseph_shipping_method_id'] == 8) {
		$services[] = 'pr';
	}	

	return $services;
}

?>