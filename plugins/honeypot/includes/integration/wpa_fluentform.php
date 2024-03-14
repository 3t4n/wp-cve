<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
function wpa_fluent_form_extra_validation($insertData, $data, $form) { 
   if (wpa_check_is_spam($data)){
   		do_action('wpa_handle_spammers','fluent_forms', $data);
			//die($GLOBALS['wpa_error_message']);
			wp_send_json_error(['errors' => $GLOBALS['wpa_error_message']]);
			wp_die();
	}
};
//add_action( 'fluentform_before_insert_submission', 'wpa_fluent_form_extra_validation', 10, 3 );
add_action( 'fluentform/before_insert_submission', 'wpa_fluent_form_extra_validation', 10, 3 );