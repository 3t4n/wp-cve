<?php
/**
 * Customer account screens handler
 *
 * @package firebase-authentication
 */

/**
 * Including required files with login registration controls
 */
require 'login' . DIRECTORY_SEPARATOR . 'register.php';
require 'login' . DIRECTORY_SEPARATOR . 'verify-password.php';

/**
 * Customer admin screens handler class
 */
class Mo_Firebase_Authentication_Admin_Account {

	/**
	 * Invoke login screen
	 *
	 * @return void
	 */
	public static function verify_password() {
		mo_firebase_auth_verify_password_ui();
	}

	/**
	 * Invoke registration screen
	 *
	 * @return void
	 */
	public static function register() {
		if ( ! mo_firebase_authentication_is_customer_registered() ) {
			mo_firebase_auth_register_ui();
		} else {
			mo_firenase_auth_show_customer_info();
		}
	}
}
