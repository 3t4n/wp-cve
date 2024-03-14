<?php
require_once dirname(__DIR__). '/functions/logger.php';
require_once dirname(__DIR__). '/functions/common.php';

function shipday_post_orders(array $payloads) {
	global $shipday_debug_flag;
    $success = false;
    shipday_logger('INFO', json_encode($payloads));

    foreach ($payloads as $api_key => $payload_array) {
        $api_key = trim($api_key);
		foreach ($payload_array as $payload){
			$response = shipday_post_order($payload, $api_key, get_shipday_api_url());
            $success |= ($response['http_code'] == 200);
			if ($response['http_code'] != 200) {
                shipday_logger('error', 'Post failed for API key: '.$api_key );
            }
			if ($shipday_debug_flag == true) shipday_post_order(
                array(
                    'payload' => $payload,
                    'response' => $response
                ),
                $api_key, get_shipday_debug_api_url()
            );
		}
	}
    return $success;
}

function shipday_post_order(array $payload, string $api_key, $url) {
	if (strlen($api_key) < 3) return false;
	$response = shipday_curl_post_order($payload, $api_key, $url);
    if ($response['http_code'] != 200) {
        shipday_logger('error', 'Curl failed. Re-trying with stream');
        $response = streams_post_order($payload, $api_key, $url);
    }
	return $response;
}

function streams_post_order(array $payload, string $api_key, $url) {
	$opts = array(
		'http' => array(
			'method' => 'POST',
			'header' => array(
				'Content-Type: application/json',
				'Authorization: Basic '.$api_key,
			),
			'content' => json_encode($payload)
		)
	);
	$context = stream_context_create($opts);
	file_get_contents($url, false, $context);
	return $http_response_header;
}

function shipday_curl_post_order(array $payload, string $api_key, $url) {
	$curl = curl_init();
	curl_setopt_array(
		$curl,
		array(
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => '',
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => 'POST',
			CURLOPT_POSTFIELDS     => remove_emoji(json_encode($payload)),
			CURLOPT_HTTPHEADER     => array(
				'Authorization: Basic '.$api_key,
				'Content-Type: application/json'
			)
		)
	);
	$response = curl_exec($curl);
	return curl_getinfo($curl);
}
