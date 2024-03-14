<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
function wpa_calderaforms_extra_validation(  ) { 
   	if (wpa_check_is_spam($_POST)){
		do_action('wpa_handle_spammers','calderaforms', $_POST);
		die($GLOBALS['wpa_error_message']);
	}
};
add_action( 'caldera_forms_pre_load_processors', 'wpa_calderaforms_extra_validation', 10, 0 );