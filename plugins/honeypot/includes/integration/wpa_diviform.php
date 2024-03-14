<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
foreach($_POST as $param => $value){
	if(strpos($param, 'et_pb_contactform_submit') === 0){
		$is_divi_form = 'true';
		$divi_form_additional = esc_attr(str_replace('et_pb_contactform_submit', '', $param));	
	}
}

if(!empty($is_divi_form) && $is_divi_form == 'true'){
	if (wpa_check_is_spam($_POST)){
		do_action('wpa_handle_spammers','divi_form', $_POST);
		echo "<div id='et_pb_contact_form{$divi_form_additional}'><p>".esc_html($GLOBALS['wpa_error_message'])."</p><div></div></div>";
		die();
	} else { // REMOVE OUR TEST FIELD BEFORE SENDING TO DIVI
		$fields_data_json  = str_replace( '\\', '',$_POST['et_pb_contact_email_fields'.$divi_form_additional]);
		$fields_data_array = json_decode( $fields_data_json, true );
		$filteredArray = array_filter($fields_data_array, function ($item) {
		    return $item['field_id'] !== 'alt_s';
		});
		$_POST['et_pb_contact_email_fields'.$divi_form_additional] = json_encode( $filteredArray, JSON_UNESCAPED_UNICODE );
	}
}