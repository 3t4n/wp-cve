<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function famisp_save_all_settings_via_ajax() {
	
	$response = array(
		'message' => array(),
		'html'    => '',
		'err'     => 'no'
	);
	
	$nonce = isset( $_POST['nonce'] ) ? famisp_clean( $_POST['nonce'] ) : '';
	
	if ( ! current_user_can( 'manage_options' ) ) {
		$response['message'][] = esc_html__( 'Cheating!? Huh?', 'famisp' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	if ( ! wp_verify_nonce( $nonce, 'famisp_backend_nonce' ) ) {
		$response['message'][] = esc_html__( 'Security check error!', 'famisp' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$all_settings             = isset( $_POST['all_settings'] ) ? famisp_clean( $_POST['all_settings'] ) : array();
	$response['all_settings'] = $all_settings;
	$new_all_settings         = array();
	
	if ( isset( $all_settings['famisp_settings'] ) ) {
		if ( ! empty( $all_settings['famisp_settings'] ) ) {
			foreach ( $all_settings['famisp_settings'] as $setting ) {
				$new_all_settings[ $setting['setting_key'] ] = $setting['setting_val'];
			}
		}
	}
	
	if ( isset( $all_settings['all_addresses'] ) ) {
		$all_addresses = array();
		if ( ! empty( $all_settings['all_addresses'] ) ) {
			foreach ( $all_settings['all_addresses'] as $address ) {
				$all_addresses[] = $address;
			}
		}
		$new_all_settings['all_addresses'] = $all_addresses;
	}
	
	update_option( 'famisp_all_settings', $new_all_settings );
//	$response['all_settings']     = $all_settings;
//	$response['new_all_settings'] = $new_all_settings;
	
	$response['message'][] = esc_html__( 'All settings saved', 'famisp' );
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_famisp_save_all_settings_via_ajax', 'famisp_save_all_settings_via_ajax' );