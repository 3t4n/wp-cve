<?php

//GPL license

include sanitize_file_name('gplLicense.php');

/*to get status of currently running test*/
function Beagle_WP_getResultOf_CurrentTestData() {

	global $wpdb;

	global $apiServerBaseUrl;

	$Beagle_WP_scan_table = $wpdb->prefix."beagleScanData";

	$getTableData = $wpdb->get_results($wpdb->prepare("SELECT * FROM $Beagle_WP_scan_table"));

	foreach ( $getTableData as $entryOne ){
		$Beagle_WP_access_token = $entryOne->access_token;
		$Beagle_WP_application_token = $entryOne->application_token;
		$BWP_result_token = $entryOne->result_token;
	}

	try {

		if($Beagle_WP_access_token != null || $Beagle_WP_application_token != null) {

			$beaglrURL = $apiServerBaseUrl.'test/result';

			$getStatusDataTest = array("access_token" => $Beagle_WP_access_token, "application_token" => $Beagle_WP_application_token, "result_token" => $BWP_result_token);

			$request = wp_remote_post($beaglrURL, array(
				'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
				'body'        => json_encode($getStatusDataTest),
				'method'      => 'POST',
				'data_format' => 'body',
			));
			$statusResponse = json_decode(wp_remote_retrieve_body( $request ));
			try {
				if($statusResponse){
					$updateData =  $wpdb->query( $wpdb->prepare("UPDATE $Beagle_WP_scan_table  SET runningStatus = %s  WHERE application_token = %s","notRunning", $Beagle_WP_application_token));
				}
			}
			catch(Exception $e) {
			}
			echo json_encode($statusResponse);
			exit;
		}
	}
	catch(Exception $e) {
	}
}