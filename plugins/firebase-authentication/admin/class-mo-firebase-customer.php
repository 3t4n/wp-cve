<?php
/**
 * Firebase Authentication Customer account
 *
 * @package firebase-authentication
 */

/**
 * This class contains the implementation of the miniOrange API requests
 */
class MO_Firebase_Customer {

	/**
	 * Email id
	 *
	 * @var string
	 */
	public $email;
	/**
	 * Phone no
	 *
	 * @var string
	 */
	public $phone;

	/**
	 * Create_customer in miniOrange
	 *
	 * @return string
	 */
	public function create_customer() {
		$url         = get_option( 'mo_fb_host_name' ) . '/moas/rest/customer/add';
		$this->email = get_option( 'mo_firebase_authentication_admin_email' );
		$this->phone = get_option( 'mo_firebase_authentication_admin_phone' );
		$password    = get_option( 'password' );
		$first_name  = get_option( 'mo_firebase_authentication_admin_fname' );
		$last_name   = get_option( 'mo_firebase_authentication_admin_lname' );
		$company     = get_option( 'mo_firebase_authentication_admin_company' );

		$fields       = array(
			'companyName'    => $company,
			'areaOfInterest' => 'WP Firebase Authentication',
			'firstname'      => $first_name,
			'lastname'       => $last_name,
			'email'          => $this->email,
			'phone'          => $this->phone,
			'password'       => $password,
		);
		$field_string = wp_json_encode( $fields );
		$headers      = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF - 8',
			'Authorization' => 'Basic',
		);
		$args         = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,

		);

		$response = wp_remote_post( $url, $args );
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo 'Something went wrong: ' . esc_attr( $error_message );
			exit();
		}

		return wp_remote_retrieve_body( $response );
	}

	/**
	 * Check if customer exists
	 *
	 * @return string
	 */
	public function check_customer() {
		$url   = get_option( 'mo_fb_host_name' ) . '/moas/rest/customer/check-if-exists';
		$email = get_option( 'mo_firebase_authentication_admin_email' );

		$fields       = array(
			'email' => $email,
		);
		$field_string = wp_json_encode( $fields );
		$headers      = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF - 8',
			'Authorization' => 'Basic',
		);
		$args         = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);

		$response = wp_remote_post( $url, $args );
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo 'Something went wrong: ' . esc_attr( $error_message );
			exit();
		}

		return wp_remote_retrieve_body( $response );
	}

	/**
	 * Get miniOrange customer_key
	 *
	 * @return string
	 */
	public function mo_firebase_auth_get_customer_key() {
		$url   = get_option( 'mo_fb_host_name' ) . '/moas/rest/customer/key';
		$email = get_option( 'mo_firebase_authentication_admin_email' );

		$password = get_option( 'password' );

		$fields       = array(
			'email'    => $email,
			'password' => $password,
		);
		$field_string = wp_json_encode( $fields );

		$headers = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF - 8',
			'Authorization' => 'Basic',
		);
		$args    = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,

		);

		$response = wp_remote_post( $url, $args );
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo 'Something went wrong: ' . esc_attr( $error_message );
			exit();
		}

		return wp_remote_retrieve_body( $response );
	}

	/**
	 * Customer update call
	 *
	 * @return string
	 */
	public function mo_firebase_authentication_submit_support_request() {

		$url                    = get_option( 'mo_fb_host_name' ) . '/moas/api/backupcode/updatestatus';
		$customer_key           = get_option( 'mo_firebase_authentication_admin_customer_key' );
		$api_key                = get_option( 'mo_firebase_authentication_admin_api_key' );
		$current_time_in_millis = round( microtime( true ) * 1000 );
		$current_time_in_millis = number_format( $current_time_in_millis, 0, '', '' );
		$string_to_hash         = $customer_key . $current_time_in_millis . $api_key;
		$hash_value             = hash( 'sha512', $string_to_hash );
		$code                   = mo_firebase_authentication_decrypt( get_option( 'mo_firebase_authentication_lk' ) );
		$fields                 = array(
			'code'             => $code,
			'customerKey'      => $customer_key,
			'additionalFields' => array( 'field1' => home_url() ),
		);
		$field_string           = wp_json_encode( $fields );

		$headers = array(
			'Content-Type'  => 'application/json',
			'Customer-Key'  => $customer_key,
			'Timestamp'     => $current_time_in_millis,
			'Authorization' => $hash_value,
		);
		$args    = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,

		);

		$response = wp_remote_post( $url, $args );
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo 'Something went wrong: ' . esc_attr( $error_message );
			exit();
		}

		return wp_remote_retrieve_body( $response );
	}

}
