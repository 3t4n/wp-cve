<?php
/**
 * Description: File contains functions to register, verify and save the information for customer account.
 *
 * @package miniorange-login-security/controllers.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
	global $momls_wpns_utility,$mo2f_dir_name,$momlsdb_queries;
	$nonce = isset( $_POST['mo2f_general_nonce'] ) ? sanitize_key( wp_unslash( $_POST['mo2f_general_nonce'] ) ) : '';
if ( wp_verify_nonce( $nonce, 'miniOrange_2fa_nonce' ) ) {
	if ( current_user_can( 'manage_options' ) && isset( $_POST['option'] ) ) {
		$option = trim( isset( $_POST['option'] ) ? sanitize_text_field( wp_unslash( $_POST['option'] ) ) : null );
		switch ( $option ) {
			case 'momls_wpns_register_customer':
				momls_register_customer( $_POST );
				break;
			case 'momls_wpns_verify_customer':
				momls_verify_customer( $_POST );
				break;
			case 'momls_wpns_cancel':
				momls_revert_back_registration();
				break;
			case 'momls_wpns_reset_password':
				momls_reset_password();
				break;
			case 'mo2f_goto_verifycustomer':
				momls_goto_sign_in_page();
				break;
		}
	}
}

	$user                             = wp_get_current_user();
	$mo2f_current_registration_status = $momlsdb_queries->momls_get_user_detail( 'mo_2factor_user_registration_status', $user->ID );

if ( ( get_site_option( 'momls_wpns_registration_status' ) === 'MO_OTP_DELIVERED_SUCCESS'
		|| get_site_option( 'momls_wpns_registration_status' ) === 'MO_OTP_VALIDATION_FAILURE'
		|| get_site_option( 'momls_wpns_registration_status' ) === 'MO_OTP_DELIVERED_FAILURE' ) && in_array( $mo2f_current_registration_status, array( 'MO_2_FACTOR_OTP_DELIVERED_SUCCESS', 'MO_2_FACTOR_OTP_DELIVERED_FAILURE' ), true ) ) {
	$admin_phone = get_site_option( 'momls_wpns_admin_phone' ) ? get_site_option( 'momls_wpns_admin_phone' ) : '';
	include $mo2f_dir_name . 'views' . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'verify.php';
} elseif ( ( get_site_option( 'momls_wpns_verify_customer' ) === 'true' || ( get_site_option( 'mo2f_email' ) && ! get_site_option( 'mo2f_customerKey' ) ) ) && 'MOMLS_VERIFY_CUSTOMER' === $mo2f_current_registration_status ) {
	$admin_email = get_site_option( 'mo2f_email' ) ? get_site_option( 'mo2f_email' ) : '';
	include $mo2f_dir_name . 'views' . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'login.php';
} elseif ( ! $momls_wpns_utility->momls_icr() ) {
	delete_site_option( 'password_mismatch' );
	update_site_option( 'momls_wpns_new_registration', 'true' );
	$momlsdb_queries->update_user_details( $user->ID, array( 'mo_2factor_user_registration_status' => 'REGISTRATION_STARTED' ) );
	include $mo2f_dir_name . 'views' . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'register.php';
} else {
	$email = get_site_option( 'mo2f_email' );
	$key   = get_site_option( 'mo2f_customerKey' );
	$api   = get_site_option( 'Momls_Api_key' );
	$token = get_site_option( 'mo2f_customer_token' );
	include $mo2f_dir_name . 'views' . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'profile.php';
}





	/* REGISTRATION RELATED FUNCTIONS */

/**
 * Description: Function to register the customer in miniOrange.
 *
 * @param array $post array of customer details .
 * @return void
 */
function momls_register_customer( $post ) {
	global $momls_wpns_utility, $momlsdb_queries;
	$user    = wp_get_current_user();
	$email   = sanitize_email( $post['email'] );
	$company = isset( $_SERVER['SERVER_NAME'] ) ? esc_url_raw( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : null;

	$password         = $post['password'];
	$confirm_password = $post['confirmPassword'];

	if ( strlen( $password ) < 6 || strlen( $confirm_password ) < 6 ) {
		do_action( 'wpns_momls_show_message', Momls_Wpns_Messages::momls_show_message( 'PASS_LENGTH' ), 'ERROR' );
		return;
	}

	if ( $password !== $confirm_password ) {
		do_action( 'wpns_momls_show_message', Momls_Wpns_Messages::momls_show_message( 'PASS_MISMATCH' ), 'ERROR' );
		return;
	}
	if ( Momls_Wpns_Utility::momls_check_empty_or_null( $email ) || Momls_Wpns_Utility::momls_check_empty_or_null( $password )
		|| Momls_Wpns_Utility::momls_check_empty_or_null( $confirm_password ) ) {
		do_action( 'wpns_momls_show_message', Momls_Wpns_Messages::momls_show_message( 'REQUIRED_FIELDS' ), 'ERROR' );
		return;
	}

	update_site_option( 'mo2f_email', $email );

	update_site_option( 'momls_wpns_company', $company );

	update_site_option( 'momls_wpns_password', $password );

	$customer = new Momls_Curl();
	$content  = json_decode( $customer->momls_check_customer( $email ), true );
	$momlsdb_queries->momls_insert_user( $user->ID );

	switch ( $content['status'] ) {
		case 'CUSTOMER_NOT_FOUND':
			$customer_key = json_decode( $customer->momls_create_customer( $email, $company, $password, $phone = '', $first_name = '', $last_name = '' ), true );
			$message      = isset( $customer_key['message'] ) ? $customer_key['message'] : esc_html_e( 'Error occured while creating an account.', 'miniorange-login-security' );
			if ( strcasecmp( $customer_key['status'], 'SUCCESS' ) === 0 ) {
					save_success_customer_config( $email, $customer_key['id'], $customer_key['apiKey'], $customer_key['token'], $customer_key['appSecret'] );
					momls_get_current_customer( $email, $password );
			} else {
				do_action( 'wpns_momls_show_message', Momls_Wpns_Messages::momls_show_message( 'CURL_ERROR' ), 'ERROR' );
			}
			break;
		default:
			momls_get_current_customer( $email, $password );
	}
}

/**
 * Description: Function redirects the user to signin page after verification.
 *
 * @return void
 */
function momls_goto_sign_in_page() {
	global  $momlsdb_queries;
	$user = wp_get_current_user();
	update_site_option( 'momls_wpns_verify_customer', 'true' );
	$momlsdb_queries->update_user_details( $user->ID, array( 'mo_2factor_user_registration_status' => 'MOMLS_VERIFY_CUSTOMER' ) );
}
/**
 * Description: Function to redirect the user back to registration page.
 *
 * @return void
 */
function momls_revert_back_registration() {
	global $momlsdb_queries;
	$user = wp_get_current_user();
	delete_site_option( 'mo2f_email' );
	delete_site_option( 'momls_wpns_registration_status' );
	delete_site_option( 'momls_wpns_verify_customer' );
	$momlsdb_queries->update_user_details( $user->ID, array( 'mo_2factor_user_registration_status' => '' ) );
}

/**
 * Description: Function to reset password of account
 *
 * @return void
 */
function momls_reset_password() {
	$customer                 = new Momls_Curl();
	$forgot_password_response = json_decode( $customer->momls_wpns_forgot_password() );
	if ( 'SUCCESS' === $forgot_password_response->status ) {
		do_action( 'wpns_momls_show_message', Momls_Wpns_Messages::momls_show_message( 'RESET_PASS' ), 'SUCCESS' );
	}
}

/**
 * Description: Function for verifying the customer.
 *
 * @param array $post Post variable array of customer details.
 * @return void
 */
function momls_verify_customer( $post ) {
	global $momls_wpns_utility;
	$email    = sanitize_email( $post['email'] );
	$password = sanitize_text_field( $post['password'] );

	if ( $momls_wpns_utility->momls_check_empty_or_null( $email ) || $momls_wpns_utility->momls_check_empty_or_null( $password ) ) {
		do_action( 'wpns_momls_show_message', Momls_Wpns_Messages::momls_show_message( 'REQUIRED_FIELDS' ), 'ERROR' );
		return;
	}
	momls_get_current_customer( $email, $password );
}

/**
 * Description: Function to fetch current user
 *
 * @param string $email Email of the user.
 * @param string $password Password of the user.
 * @return void
 */
function momls_get_current_customer( $email, $password ) {
	global $momlsdb_queries;
	$user         = wp_get_current_user();
	$customer     = new Momls_Curl();
	$content      = $customer->momls_get_customer_key( $email, $password );
	$customer_key = json_decode( $content, true );
	if ( json_last_error() === JSON_ERROR_NONE ) {
		if ( isset( $customer_key['phone'] ) ) {
			update_site_option( 'momls_wpns_admin_phone', $customer_key['phone'] );
			$momlsdb_queries->update_user_details( $user->ID, array( 'mo2f_user_phone' => $customer_key['phone'] ) );
		}
		update_site_option( 'mo2f_email', $email );
		save_success_customer_config( $email, $customer_key['id'], $customer_key['apiKey'], $customer_key['token'], $customer_key['appSecret'] );
		do_action( 'wpns_momls_show_message', Momls_Wpns_Messages::momls_show_message( 'REG_SUCCESS' ), 'SUCCESS' );
	} else {
		$momlsdb_queries->update_user_details( $user->ID, array( 'mo_2factor_user_registration_status' => 'MOMLS_VERIFY_CUSTOMER' ) );
		update_site_option( 'momls_wpns_verify_customer', 'true' );
		delete_site_option( 'momls_wpns_new_registration' );
		do_action( 'wpns_momls_show_message', Momls_Wpns_Messages::momls_show_message( 'ACCOUNT_EXISTS' ), 'ERROR' );
	}
}


/**
 * Description: Save all required fields on customer registration/retrieval complete.
 *
 * @param string $email Customer Email.
 * @param int    $id Customer Id.
 * @param string $api_key Customer apikey.
 * @param string $token Customer token key.
 * @param string $app_secret Customer appSecret.
 * @return void
 */
function save_success_customer_config( $email, $id, $api_key, $token, $app_secret ) {
	global $momlsdb_queries;

	$user = wp_get_current_user();
	update_site_option( 'mo2f_customerKey', $id );
	update_site_option( 'Momls_Api_key', $api_key );
	update_site_option( 'mo2f_customer_token', $token );
	update_site_option( 'mo2f_app_secret', $app_secret );
	update_site_option( 'momls_wpns_enable_log_requests', true );
	update_site_option( 'mo2f_miniorange_admin', $user->ID );
	update_site_option( 'mo_2factor_admin_registration_status', 'MO_2_FACTOR_CUSTOMER_REGISTERED_SUCCESS' );

	$momlsdb_queries->momls_insert_user( $user->ID );
	$momlsdb_queries->update_user_details(
		$user->ID,
		array(
			'mo2f_user_email'                     => $email,
			'user_registration_with_miniorange'   => 'SUCCESS',
			'mo2f_2factor_enable_2fa_byusers'     => 1,
			'mo_2factor_user_registration_status' => 'MO_2_FACTOR_PLUGIN_SETTINGS',
		)
	);
	$enduser            = new Momls_Two_Factor_Setup();
	$userinfo           = json_decode( $enduser->momls_get_userinfo( $email ), true );
	$mo2f_second_factor = 'NONE';
	if ( json_last_error() === JSON_ERROR_NONE ) {
		if ( 'SUCCESS' === $userinfo['status'] ) {
			$mo2f_second_factor = momls_update_and_sync_user_two_factor( $user->ID, $userinfo );
		}
	}
	$configured_2famethod = '';
	if ( 'NONE' !== $mo2f_second_factor ) {
		$configured_2famethod = Momls_Utility::momls_decode_2_factor( $mo2f_second_factor, 'servertowpdb' );
		if ( get_site_option( 'mo2f_is_NC' ) === 0 ) {
			$auth_method_abr = str_replace( ' ', '', $configured_2famethod );
			$momlsdb_queries->update_user_details(
				$user->ID,
				array(
					'mo2f_configured_2FA_method' => $configured_2famethod,
					'mo2f_' . $auth_method_abr . '_config_status' => true,
				)
			);
		} else {
			if ( in_array(
				$configured_2famethod,
				array(
					'Authy Authenticator',
				),
				true
			) ) {
				$enduser->momls_update_userinfo( $email, 'NONE', null, '', true );
			}
		}
	}

	$mo2f_message = Momls_Constants::momls_lang_translate( 'ACCOUNT_RETRIEVED_SUCCESSFULLY' );
	if ( 'NONE' !== $configured_2famethod && get_site_option( 'mo2f_is_NC' ) === 0 ) {
		$mo2f_message .= ' <b>' . $configured_2famethod . '</b> ' . Momls_Constants::momls_lang_translate( 'DEFAULT_2ND_FACTOR' ) . '. ';
	}
	$mo2f_message .= '<a href=\"admin.php?page=mo_2fa_two_fa\" >' . Momls_Constants::momls_lang_translate( 'CLICK_HERE' ) . '</a> ' . Momls_Constants::momls_lang_translate( 'CONFIGURE_2FA' );

	delete_user_meta( $user->ID, 'register_account' );

	if ( 'NONE' === $mo2f_second_factor ) {
		if ( get_user_meta( $user->ID, 'register_account_popup', true ) ) {
			update_user_meta( $user->ID, 'configure_2FA', 1 );
		}
	}
	update_site_option( 'mo2f_message', $mo2f_message );
	delete_user_meta( $user->ID, 'register_account_popup' );
	delete_site_option( 'momls_wpns_verify_customer' );
	delete_site_option( 'momls_wpns_registration_status' );
	delete_site_option( 'momls_wpns_password' );
}
