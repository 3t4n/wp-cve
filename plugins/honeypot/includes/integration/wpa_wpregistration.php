<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'register_form', 'wpa_wpregistration_add_initiator_field' ); 
function wpa_wpregistration_add_initiator_field() {
	echo '<input type="hidden" id="wpa_initiator" class="wpa_initiator" name="wpa_initiator" value="" />';
}

add_filter( 'registration_errors', 'wpa_wpregistration_extra_validation', 10, 3 );

function wpa_wpregistration_extra_validation( $errors, $sanitized_user_login, $user_email ) {
	if (wpa_check_is_spam($_POST)){
		do_action('wpa_handle_spammers','wpregistration', $_POST);
		$errors->add( 'wpa_extra_email', __($GLOBALS['wpa_error_message']) );
	}
	return $errors;
}