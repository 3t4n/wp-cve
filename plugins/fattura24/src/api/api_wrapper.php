<?php

/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com> 
 *
 * File di gestione delle chiamate API
 * 
 */
namespace fattura24;

if (!defined('ABSPATH')) exit;

/**
*  chiamata API F24 @param $command (es.: SaveDocument) e @param $send_data 
*  usa il metodo wp_remote_post
*/ 
function fatt_24_api_call($command, $send_data, $source)
{
	static $message_displayed;
		
    $baseUrl = empty(get_option(FATT_24_API_ENDPOINT)) ? FATT_24_API_ROOT : get_option(FATT_24_API_ENDPOINT);
	$url = implode('/', array($baseUrl, $command));

	if (!isset($send_data['apiKey'])) {
        $send_data['apiKey'] = get_option(FATT_24_OPT_API_KEY);
	}	
	$send_data['source'] = $source;
	/**
	 * Qui misuro il tempo di risposta delle chiamate API
	 * se la versione di PHP è < 7.3 uso microtime e adeguo i calcoli
	 * altrimenti uso hrtime che è più attendibile
	 */
	$php_version = phpversion(); // che versione ho di PHP?
	$phpOldVersion = substr($php_version, 0, 3) < '7.3';
	$start_time = $phpOldVersion? microtime(true) : hrtime(true);
	$response = wp_remote_post( $url, array(
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => $send_data,
				'cookies' => array()
				)
	);
	$body = wp_remote_retrieve_body($response);
	$finish_time = $phpOldVersion? microtime(true) : hrtime(true); //stop
	if ($phpOldVersion) {
		$duration = ($finish_time - $start_time) * 1000; //calcolo con microtime 
	} else {
		$duration = round(($finish_time - $start_time) / 10000000);	// formula per convertire i nanosecondi in millisecondi
	}
	
	$result = (float) number_format($duration, 0, '.', ''); // non voglio decimali
	/**
	 * Voglio sapere quale è la chiamata e quale è il tempo di risposta
	 * in millisecondi. Escludo dal controllo le chiamate come GetTemplate
	 * Davide Iandoli 30.07.2020
	 */

	// mi prendo il codice di risposta della chiamata Http
	$response_code = wp_remote_retrieve_response_code($response);
	$response_time = array('chiamata :' => $command, 'tempo di risposta in millisecondi:' => $result);
	$excluded_commands = array('TestKey', 'GetTemplate', 'GetPdc', 'GetNumerator', 'GetCallLog'); // elenco chiamate da non riportare nel file di log
	if (!in_array($command, $excluded_commands)) {
        fatt_24_trace('controllo risposte :', $response_time);
    }

	if (empty($response_code)) {
		if (is_wp_error($response)) {
			$response_code = json_encode($response);
		} else {
			$response_code = 'Errore nella chiamata al server API Fattura24 (timeout ?)';
		}
	}

	/**
	 * Qui ho aggiunto un controllo sul campo input Chiave API
	 * nelle nuove installazioni il campo è sempre vuoto perciò avrei visto 
	 * un messaggio di errore fuorviante - fix del 18.08.2020
	 */
	$filledApiKey = !empty($send_data['apiKey']);
	$notValidResponseCode = $response_code !== 200;
	//fatt_24_trace('filled api key :', $filledApiKey);
	//fatt_24_trace('response :', $response);
	//fatt_24_trace('body :', $body);
	//fatt_24_trace('response_code :', $response_code);



	if($filledApiKey && $notValidResponseCode) {
		if($message_displayed == false){
			fatt_24_trace('Api server error code:', $response_code); // voglio il codice di errore nel file di log
			$message_displayed = true;
			fatt_24_getMessageAPIError($response_code);
		}
		$APIStatus = array($body, 'code' => $response_code, 'disp_message' => $message_displayed);
		return $APIStatus;
	} 
	return $body;
}