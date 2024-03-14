<?php

die('WARNING: Only use this script if you know what you\'re doing.');

error_reporting(E_ALL);

ini_set('display_errors', 1);

date_default_timezone_set('Europe/Amsterdam');

// Base API url
define('BASE_URL', 'https://www.werkaandemuur.nl/api/');

// Your Artist ID
define('API_ARTIST_ID', '');

// Your API Key
define('API_KEY', '');


/**
 * Create stream context for api request
 *
 * @return resource
 */
function getStreamContext()
{
	$options = array('http' =>
		array(
			'method' => 'GET',
			'header' =>
				"Authorization: Basic " . base64_encode(API_ARTIST_ID . ':' . API_KEY) . "\r\n"
//				"Cookie: PHPSESSID=" . md5(API_KEY . API_ARTIST_ID . rand(1,1000) . date('Y-m-d')) . "\r\n"
			,
//			'ignore_errors' => true,
			'timeout' => 10,
		),
	);

	// Dump created stream context options
	var_dump($options);

	return stream_context_create($options);
}

// Do an authentication test request
echo 'Connectiontest:';

$result = file_get_contents(BASE_URL . 'connectiontest', false, getStreamContext());

// Dump result
var_dump($result);
var_dump(json_decode($result));

echo '<hr />';

// Do an authentication test request
echo 'Authenticationtest:';

$result = file_get_contents(BASE_URL . 'authenticationtest', false, getStreamContext());

// Dump result
var_dump($result);
var_dump(json_decode($result));

echo '<hr />';