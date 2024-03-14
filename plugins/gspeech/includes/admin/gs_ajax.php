<?php
// no direct access!
defined('ABSPATH') or die("No direct access");

global $wpdb;
// error_reporting(0);
//header('Content-type: application/json');


$type = isset($_POST['type']) ? $_POST['type'] : '';

if($type != "hide_rate_us") {

	$gsp_token_check = isset($_SESSION['gsp_token_val']) ? $_SESSION['gsp_token_val'] : '';
	$sent_token = isset($_POST["gsp_token_inner"]) ? $_POST["gsp_token_inner"] : '';

	if($sent_token == "" || $sent_token != $gsp_token_check) {

		echo "Restricted";
		exit();
	}
}

if($type == 'save_data') {

	$field = isset($_POST['field']) ? esc_html($_POST['field']) : '';
	$val = isset($_POST['val']) ? esc_html($_POST['val']) : '';

	if($field != '' && $val != '') {

		$fields = explode(',', $field);

		if(sizeof($fields) > 1) {

			$vals = explode(':', $val);
			$q = "UPDATE `".$wpdb->prefix."gspeech_data` SET ";
			$vv = "";
			for($w=0;$w<sizeof($fields);$w++) {

				$field_ind = $fields[$w];
				$field_val = $vals[$w];

				$q .= "`".$field_ind."` = %s";
				$vv .= "'".$field_val."'";
				if($w != sizeof($fields)-1) {
					$q .= ",";
					$vv .= ",";
				}
			}

			$query = $wpdb->prepare($q, $vals);
			$wpdb->query($query);

		}
		else {
			$query = $wpdb->prepare("UPDATE `".$wpdb->prefix."gspeech_data` SET `".$field."` = %s", $val);
			$wpdb->query($query);
		}
	}
}
else if($type == 'increase_index') {

	$q = "UPDATE `".$wpdb->prefix."gspeech_data` SET `version_index` = `version_index` + 1";
	$wpdb->query($q);
}
elseif($type == 'hide_rate_us') {
	$_SESSION['wpcfg_rate_us_counter'] = 100;
}

exit();
?>