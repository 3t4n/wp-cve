<?php

//GPL license

include sanitize_file_name('gplLicense.php');

/*to verify the signature token*/
function Beagle_WP_verify_Token()
{

	global $wpdb;

	global $apiServerBaseUrl;

	$Beagle_WP_scan_table = $wpdb->prefix . "beagleScanData";

	$getTokenFromTbl = $wpdb->get_results($wpdb->prepare("SELECT * FROM $Beagle_WP_scan_table"));

	foreach ($getTokenFromTbl as $print) {
		$Beagle_WP_access_token = $print->access_token;
		$Beagle_WP_application_token = $print->application_token;
	}

	$beagleVerifyURL = $apiServerBaseUrl . 'test/signature/verify';

	$verifySignature = array("access_token" => $Beagle_WP_access_token, "application_token" => $Beagle_WP_application_token, "type" => "WORDPRESS");

	$verifyResponse = wp_remote_post($beagleVerifyURL, array(
		'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
		'body'        => json_encode($verifySignature),
		'method'      => 'POST',
		'data_format' => 'body',
	));

	$statusResponse = json_decode(wp_remote_retrieve_body($verifyResponse));
	try {
		echo json_encode($statusResponse);
		exit;
	} catch (Exception $e) {
	}
}
