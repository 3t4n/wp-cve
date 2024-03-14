<?php
/**
 * Used to send the support query if user face any issue.
 *
 * @package miniorange-login-security/controllers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
	global $mo2f_dir_name;

if ( current_user_can( 'manage_options' ) && isset( $_POST['momls-support-nonce'] ) ? wp_verify_nonce( sanitize_key( wp_unslash( $_POST['momls-support-nonce'] ) ), 'momls-support-form-nonce' ) : '0' ) {

	$option = isset( $_POST['option'] ) ? sanitize_text_field( wp_unslash( $_POST['option'] ) ) : '';
	switch ( $option ) {
		case 'momls_wpns_send_query':
			wpns_handle_support_form();
			break;
	}
}

	$current_user_info = wp_get_current_user();
	$email             = get_site_option( 'mo2f_email' );
	$phone             = get_site_option( 'momls_wpns_admin_phone' );


if ( empty( $email ) ) {
	$email = $current_user_info->user_email;
}

	require $mo2f_dir_name . 'views' . DIRECTORY_SEPARATOR . 'support.php';


	/*
	SUPPORT FORM RELATED FUNCTIONS
	 */
	// Function to handle support form submit.
/**
 * This method is used to receive the customer query.
 *
 * @return void
 */
function wpns_handle_support_form() {
	$nonce = isset( $_POST['momls-support-nonce'] ) ? sanitize_key( wp_unslash( $_POST['momls-support-nonce'] ) ) : null;

	if ( ! wp_verify_nonce( $nonce, 'momls-support-form-nonce' ) ) {
		do_action( 'wpns_momls_show_message', 'Something wrong please try again', 'ERROR' );
	}

	$email      = isset( $_POST['query_email'] ) ? sanitize_email( wp_unslash( $_POST['query_email'] ) ) : '';
	$query      = isset( $_POST['query'] ) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : '';
	$phone      = isset( $_POST['query_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['query_phone'] ) ) : '';
	$contact_us = new Momls_Curl();
	$submited   = json_decode( $contact_us->momls_submit_contact_us( $email, $phone, $query ), true );

	if ( json_last_error() === JSON_ERROR_NONE && $submited ) {
		do_action( 'wpns_momls_show_message', Momls_Wpns_Messages::momls_show_message( 'SUPPORT_FORM_SENT' ), 'SUCCESS' );
		return;
	}

	do_action( 'wpns_momls_show_message', Momls_Wpns_Messages::momls_show_message( 'SUPPORT_FORM_ERROR' ), 'ERROR' );
}
