<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
function wpa_elementor_extra_validation( $record, $ajax_handler ) { 
   	if (wpa_check_is_spam($_POST)){
   		$all_fields = $record->get( 'fields' );
		$firstField = array_key_first($all_fields);
		do_action('wpa_handle_spammers','elementor', $_POST);
		$ajax_handler->add_error($all_fields[$firstField]['id'], $GLOBALS['wpa_error_message']);
	}
};
add_action( 'elementor_pro/forms/validation', 'wpa_elementor_extra_validation', 10, 2 );