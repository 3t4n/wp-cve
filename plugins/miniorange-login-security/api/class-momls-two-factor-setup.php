<?php
/** This file contains functions to create, update users.
 *
 * @package        miniorange-login-security/api
 */

/**
 * This library is miniOrange Authentication Service.
 * Contains Request Calls to Customer service.
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once dirname( __FILE__ ) . '/class-momls-api.php';
if ( ! class_exists( 'Momls_Two_Factor_Setup' ) ) {
	/**
	 * Class contains function to create, update users.
	 */
	class Momls_Two_Factor_Setup {
		/**
		 * Email id of user.
		 *
		 * @var string
		 */
		public $email;

		/**
		 * Function to get the information of user.
		 *
		 * @param string $email Email id of user.
		 * @return string
		 */
		public function momls_get_userinfo( $email ) {
			$url               = MO_HOST_NAME . '/moas/api/admin/users/get';
			$customer_key      = get_site_option( 'mo2f_customerKey' );
			$fields            = array(
				'customerKey' => $customer_key,
				'username'    => $email,
			);
			$mo2f_api          = new Momls_Api();
			$http_header_array = $mo2f_api->get_http_header_array();
			return $mo2f_api->momls_http_request( $url, $fields, $http_header_array );
		}
		/**
		 * Function to update the user information.
		 *
		 * @param string  $email Email id of user.
		 * @param string  $auth_type Authentication method of user.
		 * @param int     $phone Phone number of user.
		 * @param string  $tname Transaction name to verify the form of transaction.
		 * @param boolean $enable_admin_second_factor Second factor for user enabled by admin or not.
		 * @return string
		 */
		public function momls_update_userinfo( $email, $auth_type, $phone, $tname, $enable_admin_second_factor ) {

			$url          = MO_HOST_NAME . '/moas/api/admin/users/update';
			$customer_key = get_site_option( 'mo2f_customerKey' );
			$fields       = array(
				'customerKey'            => $customer_key,
				'username'               => $email,
				'phone'                  => $phone,
				'authType'               => $auth_type,
				'transactionName'        => $tname,
				'adminLoginSecondFactor' => $enable_admin_second_factor,
			);

			$mo2f_api = new Momls_Api();

			$http_header_array = $mo2f_api->get_http_header_array();

			return $mo2f_api->momls_http_request( $url, $fields, $http_header_array );
		}
		/**
		 * Function to register the kba information with miniOrange.
		 *
		 * @param string $email Email id of user.
		 * @param string $question1 Question 1 selected by user.
		 * @param string $answer1 Answer 1 given by the user.
		 * @param string $question2 Question 2 selected by user.
		 * @param string $answer2 Answer 2 given by the user.
		 * @param string $question3 Question 3 selected by user.
		 * @param string $answer3 Answer 3 given by the user.
		 * @return string
		 */
		public function momls_register_kba_details( $email, $question1, $answer1, $question2, $answer2, $question3, $answer3 ) {
			$url          = MO_HOST_NAME . '/moas/api/auth/register';
			$customer_key = get_site_option( 'mo2f_customerKey' );
			$q_and_a_list = '[{"question":"' . $question1 . '","answer":"' . $answer1 . '" },{"question":"' . $question2 . '","answer":"' . $answer2 . '" },{"question":"' . $question3 . '","answer":"' . $answer3 . '" }]';
			$field_string = '{"customerKey":"' . $customer_key . '","username":"' . $email . '","questionAnswerList":' . $q_and_a_list . '}';

			$mo2f_api          = new Momls_Api();
			$http_header_array = $mo2f_api->get_http_header_array();

			return $mo2f_api->momls_http_request( $url, $field_string, $http_header_array );

		}
	}
}


