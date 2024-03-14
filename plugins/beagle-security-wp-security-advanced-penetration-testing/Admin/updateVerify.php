<?php

//GPL license
include sanitize_file_name('gplLicense.php');

/*Updating database after verifying signature*/
function Beagle_WP_verify_Token_Update(){

	global $wpdb;

	$Beagle_WP_scan_table = $wpdb->prefix."beagleScanData";

	$getTokenFromTbl = $wpdb->get_results($wpdb->prepare("SELECT * FROM $Beagle_WP_scan_table"));

	$Beagle_WP_application_token = $getTokenFromTbl[0]->application_token;

	$updateData = $wpdb->query( $wpdb->prepare("UPDATE $Beagle_WP_scan_table  SET verified = %d  WHERE application_token = %s",1, $Beagle_WP_application_token));
	try{
		if($updateData) {
			echo json_encode("succeess");
			exit;
		} else {
			echo json_encode("failed");
			exit;
		}
	} catch (Exception $e) {
	}
}