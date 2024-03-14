<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
add_filter('cred_form_validate','wpa_toolsetform_extra_validation',20,2);

function wpa_toolsetform_extra_validation($error_fields, $form_data)
{
    list($fields,$errors)=$error_fields;
    if (wpa_check_is_spam($_POST)){
		do_action('wpa_handle_spammers','toolset_form', $_POST);
		die($GLOBALS['wpa_error_message']);
	}
    return array($fields,$errors);
}