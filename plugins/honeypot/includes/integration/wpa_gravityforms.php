<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
add_action( 'gform_validation', 'wpa_gravityforms_extra_validation');

function wpa_gravityforms_extra_validation($validation_result ){
	if (wpa_check_is_spam($_POST)){
		$form = $validation_result['form'];
		do_action('wpa_handle_spammers','gravityforms', $_POST);
		$validation_result['is_valid'] = false;
		foreach( $form['fields'] as &$field ) {
 			if ( $field->id == '1' ) {
                $field->failed_validation = true;
                $field->validation_message = $GLOBALS['wpa_error_message'];
                break;
            }
        }
		$validation_result['form'] = $form;
	}
	return $validation_result;
}