<?php
/** This file is for rba attributes.
 *
 * @package miniorange-login-security/api
 */

/**
 * This library is miniOrange Authentication Service.
 * Contains Request Calls to Customer service.
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
require_once dirname( __FILE__ ) . '/class-momls-api.php';

if ( ! class_exists( 'Momls_Miniorange_Rba_Attributes' ) ) {
	/**
	 * Class for RBA attributes
	 */
	class Momls_Miniorange_Rba_Attributes {

		/**
		 * This function perform google authentication task.
		 *
		 * @param string $useremail user email.
		 * @param string $google_authenticator_name google auth name.
		 * @return string
		 */
		public function momls_google_auth_service( $useremail, $google_authenticator_name = '' ) {

			$momls_api    = new Momls_Api();
			$url          = MO_HOST_NAME . '/moas/api/auth/google-auth-secret';
			$customer_key = get_site_option( 'mo2f_customerKey' );
			$field_string = array(
				'customerKey'             => $customer_key,
				'username'                => $useremail,
				'googleAuthenticatorName' => $google_authenticator_name,
			);

			$http_header_array = $momls_api->get_http_header_array();

			return $momls_api->momls_http_request( $url, $field_string, $http_header_array );
		}
		/**
		 * This function validate google auth code
		 *
		 * @param string $useremail user email.
		 * @param string $otp_token otp token.
		 * @param string $secret secret.
		 * @return string
		 */
		public function momls_validate_google_auth( $useremail, $otp_token, $secret ) {

			$url       = MO_HOST_NAME . '/moas/api/auth/validate-google-auth-secret';
			$momls_api = new Momls_Api();

			$customer_key = get_site_option( 'mo2f_customerKey' );
			$field_string = array(
				'customerKey' => $customer_key,
				'username'    => $useremail,
				'secret'      => $secret,
				'otpToken'    => $otp_token,
			);

			$http_header_array = $momls_api->get_http_header_array();

			return $momls_api->momls_http_request( $url, $field_string, $http_header_array );
		}

	}
}

