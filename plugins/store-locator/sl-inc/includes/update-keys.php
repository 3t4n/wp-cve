<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!empty($_GET['validate_addons']) ){//&& !empty($_POST)){
	//Last save: 3/31/18 3:54:33p
	sl_update_keys($_GET);
	exit();
}

function sl_update_keys($post) {
$_POST=$post;
$partner_mode = (!empty($_POST['val_mode']) && preg_match("@partner@", $_POST['val_mode']));
$val_page = ($partner_mode)? "partner_update" : "confirm_single_license";
$val_chk = ($partner_mode)? "partner_" :  "sl_license_";
foreach ($_POST as $key=>$value) {
	if (preg_match("@$val_chk@", $key) && trim($value)!="") {
		$value=trim($value);
		$val_url = "/sl_validate/{$val_page}.php?lic=". urlencode($value) ."&url=". urlencode($_SERVER['HTTP_HOST'] )."&dir=". urlencode(str_replace("sl_license_","",$key));
		$val_url .= ($partner_mode)? "&val_mode=".trim(sanitize_text_field($_POST['val_mode'])) : "";
		$val_url .= ($partner_mode)? "&dev_mode=".trim(sanitize_text_field($_POST['dev_mode'])) : "";
		$target = "http://" . SL_HOME_URL . $val_url;
  		//exit($target);
		$remote_access_fail = false;
		$useragent = 'LotsOfLocales Store Locator Plugin';
		$request = wp_remote_get( $target,
			array(
				'timeout' => 10,
			        'user-agent' => $useragent
			)
		);
		$returned_value = wp_remote_retrieve_body($request);
	 	
	 	if (preg_match("@validated:@",$returned_value)) {
	 		$activ = ($partner_mode)? str_replace("_key","_activation", $key) : str_replace("sl_license_", "sl_activation_", $key);
			$enc1=explode(":", trim($returned_value));
			$enc=$enc1[1];
			$key_option=sl_data("$key");
			$activ_option=sl_data("$activ");
			if (empty($key_option)) {
				sl_data("$key", 'add', $value);
			} else {
				sl_data("$key", 'update', $value);
			}
			if (empty($activ_option)) {
				sl_data("$activ", 'add', $enc);
			} else {
				sl_data("$activ", 'update', $enc);
			}
			if (!$partner_mode) {	
				sl_data("sl-addon-status___".str_replace("sl_license_","",$key), "add", "on");
				global $view_link;
				print "<div class='sl_admin_success'><b>".ucwords(preg_replace("@(-|_)@", " ", str_replace("sl_license_", "", $key)))."</b> -- Successful validation using key '$value' </div><br>";
			} else {
				print "<div class='sl_admin_success'>Successful update</div><script>location.replace('".SL_ADDONS_PAGE."');</script><br>";
			}
		} elseif ($returned_value==="") {
			print "<div class='sl_admin_success' style='border-color:red; background-color:salmon'>Error: No response. Validation server may be down (or your internet connection), please try again later.</div><br>";
		} else {
			print "<div class='sl_admin_warning'>$returned_value</div><br>";
	  	}
  
  	}
}
	if (!empty($returned_value)){
		return $returned_value;
	}
}
?>