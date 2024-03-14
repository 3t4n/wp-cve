<?php

if( !defined( 'ABSPATH' ) ) { exit; }

function rs_eucc_option_defaults() {
	$array = array(
		'cookie_consent_status' => 'off',
		'visitor_message' => 'This website uses cookies to ensure you get the best experience on our website.',
		'dismiss_text' => 'Got it!',
		'policy_link_text' => 'More info',
		'policy_link_url' => '',
		'colour_scheme' => 'light',
		'notice_position' => 'bottom',
		'delete_option_on_deactivate' => '0',
	);
	return $array;
}

function rs_eucc_get_option() {
	return get_option( RS_EUCC__OPTION );
}

function rs_eucc_update_option( $new_option ) {
	$update = update_option( RS_EUCC__OPTION, $new_option );
	return $update;
}

function rs_eucc_option_exists() {
	if( !rs_eucc_get_option() ) { return FALSE; }
	else { return TRUE; }
}

function rs_eucc_update_settings() {
	if( array_key_exists( '_wpnonce', $_POST ) ) {
		if( !wp_verify_nonce( $_POST['_wpnonce'], 'rs_eucc_update_settings' ) ) { return; }
		else {
			$option = rs_eucc_get_option();
			$new_option = array();
			$new_option['cookie_consent_status'] = sanitize_text_field( $_POST['cookie_consent_status'] );
			$new_option['visitor_message'] = sanitize_text_field( $_POST['visitor_message'] );
			$new_option['dismiss_text'] = sanitize_text_field( $_POST['dismiss_text'] );
			$new_option['policy_link_text'] = sanitize_text_field( $_POST['policy_link_text'] );
			$new_option['policy_link_url'] = sanitize_text_field( $_POST['policy_link_url'] );
			$new_option['colour_scheme'] = sanitize_text_field( $_POST['colour_scheme'] );
			$new_option['notice_position'] = sanitize_text_field( $_POST['notice_position'] );
			if( array_key_exists( 'delete_option_on_deactivate', $_POST ) ) { $new_option['delete_option_on_deactivate'] = sanitize_text_field( $_POST['delete_option_on_deactivate'] ); }
			else { $new_option['delete_option_on_deactivate'] = $option['delete_option_on_deactivate']; }
			foreach( $new_option as $key => $value ) {
				if( $value !== $option[$key] ) {
					$update_option = 1;
				}
			}
			if( isset( $update_option ) ) {
				if( $update_option == 1 ) {
					$update = rs_eucc_update_option( $new_option );
					return $update;
				}
			}
		}
	}
}