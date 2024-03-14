<?php
/** MiniOrange provides to functionality to protect WP Media from anonymous user and provide an authorized access to different WP Media.
Copyright (C) 2015  miniOrange

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

 * @package         miniOrange OAuth
 * @license         https://docs.miniorange.com/mit-license MIT/Expat
 */

/**
This library is miniOrange Authentication Service.
Contains Request Calls to Customer service.
 **/
class Miniorange_Media_Restriction_Customer {

	/**
	 * Email value.
	 *
	 * @var string
	 */
	public $email;
	/**
	 * Phone value.
	 *
	 * @var mixed
	 */
	public $phone;

	/**
	 * Default customer key.
	 *
	 * @var string
	 */
	private $default_customer_key = '16555';
	/**
	 * Default API key.
	 *
	 * @var string
	 */
	private $default_api_key = 'fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq';

	/**
	 * Create customer.
	 *
	 * @param string $password cred.
	 *
	 * @return array|null
	 */
	public function create_customer( $password ) {
		$url         = get_option( 'host_name' ) . '/moas/rest/customer/add';
		$this->email = get_option( 'mo_media_restriction_admin_email' );
		$this->phone = get_option( 'mo_media_restriction_admin_phone' );
		$first_name  = get_option( 'mo_media_restriction_admin_fname' );
		$last_name   = get_option( 'mo_media_restriction_admin_lname' );
		$company     = get_option( 'mo_media_restriction_admin_company' );

		$fields       = array(
			'companyName'    => $company,
			'areaOfInterest' => 'WP Prevent Files / Folders Access',
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
			'timeout'     => '15',
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
	 * Check customer
	 *
	 * @return array|null
	 */
	public function check_customer() {
		$url   = get_option( 'host_name' ) . '/moas/rest/customer/check-if-exists';
		$email = get_option( 'mo_media_restriction_admin_email' );

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
			'timeout'     => '15',
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
	 * Get timestamp
	 *
	 * @return mixed
	 */
	public function get_timestamp() {
		$url     = get_option( 'host_name' ) . '/moas/rest/mobile/get-timestamp';
		$headers = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF - 8',
			'Authorization' => 'Basic',
		);
		$args    = array(
			'method'      => 'POST',
			'body'        => array(),
			'timeout'     => '15',
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
	 * Send otp token.
	 *
	 * @param string  $email  email.
	 * @param int     $phone  phone number.
	 * @param boolean $send_to_email send email.
	 * @param boolean $send_to_phone send phone number.
	 * @return mixed
	 */
	public function send_otp_token( $email, $phone, $send_to_email = true, $send_to_phone = false ) {
		$url = get_option( 'host_name' ) . '/moas/api/auth/challenge';

		$customer_key = $this->default_customer_key;
		$api_key      = $this->default_api_key;

		$username = get_option( 'mo_media_restriction_admin_email' );
		$phone    = get_option( 'mo_media_restriction_admin_phone' );
		/* Current time in milliseconds since midnight, January 1, 1970 UTC. */
		$current_time_in_millis = self::get_timestamp();

		/* Creating the Hash using SHA-512 algorithm */
		$string_to_hash = $customer_key . $current_time_in_millis . $api_key;
		$hash_value     = hash( 'sha512', $string_to_hash );

		$customer_key_header  = 'Customer-Key: ' . $customer_key;
		$timestamp_header     = 'Timestamp: ' . $current_time_in_millis;
		$authorization_header = 'Authorization: ' . $hash_value;

		if ( $send_to_email ) {
			$fields = array(
				'customerKey' => $customer_key,
				'email'       => $username,
				'authType'    => 'EMAIL',
			);} else {
			$fields = array(
				'customerKey' => $customer_key,
				'phone'       => $phone,
				'authType'    => 'SMS',
			);
			}
			$field_string = wp_json_encode( $fields );

			$headers                  = array( 'Content-Type' => 'application/json' );
			$headers['Customer-Key']  = $customer_key;
			$headers['Timestamp']     = $current_time_in_millis;
			$headers['Authorization'] = $hash_value;
			$args                     = array(
				'method'      => 'POST',
				'body'        => $field_string,
				'timeout'     => '15',
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
	 * Get customer key
	 *
	 * @param string $password cred.
	 *
	 * @return array|null
	 */
	public function get_customer_key( $password ) {
		$url   = get_option( 'host_name' ) . '/moas/rest/customer/key';
		$email = get_option( 'mo_media_restriction_admin_email' );

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
			'timeout'     => '15',
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
	 * Validated otp token
	 *
	 * @param string $transaction_id transaction id.
	 * @param string $otp_token otp token.
	 * @return mixed
	 */
	public function validate_otp_token( $transaction_id, $otp_token ) {
		$url = get_option( 'host_name' ) . '/moas/api/auth/validate';

		$customer_key = $this->default_customer_key;
		$api_key      = $this->default_api_key;

		$username = get_option( 'mo_media_restriction_admin_email' );

		/* Current time in milliseconds since midnight, January 1, 1970 UTC. */
		$current_time_in_millis = self::get_timestamp();

		/* Creating the Hash using SHA-512 algorithm */
		$string_to_hash = $customer_key . $current_time_in_millis . $api_key;
		$hash_value     = hash( 'sha512', $string_to_hash );

		$customer_key_header  = 'Customer-Key: ' . $customer_key;
		$timestamp_header     = 'Timestamp: ' . $current_time_in_millis;
		$authorization_header = 'Authorization: ' . $hash_value;

		$fields = '';

		// *check for otp over sms/email
		$fields = array(
			'txId'  => $transaction_id,
			'token' => $otp_token,
		);

		$field_string = wp_json_encode( $fields );

		$headers                  = array( 'Content-Type' => 'application/json' );
		$headers['Customer-Key']  = $customer_key;
		$headers['Timestamp']     = $current_time_in_millis;
		$headers['Authorization'] = $hash_value;
		$args                     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '15',
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
	 * Submit contact us form
	 *
	 * @param string $email email.
	 * @param int    $phone phone number.
	 * @param string $query query.
	 * @return boolean|null
	 */
	public function submit_contact_us( $email, $phone, $query ) {
		global $current_user;
		wp_get_current_user();
		$query        = '[WP Prevent Files / Folders Plugin - ' . MO_MEDIA_RESTRICTION_PLUGIN_NAME_VERSION . ' ] - ' . $query;
		$fields       = array(
			'firstName' => $current_user->user_firstname,
			'lastName'  => $current_user->user_lastname,
			'company'   => ! empty( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '',
			'email'     => $email,
			'ccEmail'   => 'oauthsupport@xecurify.com',
			'phone'     => $phone,
			'query'     => $query,
		);
		$field_string = wp_json_encode( $fields );

		$url = get_option( 'host_name' ) . '/moas/rest/customer/contact-us';

		$headers = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF - 8',
			'Authorization' => 'Basic',
		);
		$args    = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '15',
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

		return true;
	}

	/**
	 * Send email alert.
	 *
	 * @param string $email email.
	 * @param int    $phone phone number.
	 * @param string $message message.
	 * @param string $subject subject.
	 * @return boolean|null
	 */
	public function mo_media_restriction_send_email_alert( $email, $phone, $message, $subject ) {

		$url = get_option( 'host_name' ) . '/moas/api/notify/send';

		$last_requested_api = get_option( 'mo_media_restriction_last_requested_api' );
		$customer_key       = $this->default_customer_key;
		$api_key            = $this->default_api_key;

		$current_time_in_millis = self::get_timestamp();
		$string_to_hash         = $customer_key . $current_time_in_millis . $api_key;
		$hash_value             = hash( 'sha512', $string_to_hash );
		$customer_key_header    = 'Customer-Key: ' . $customer_key;
		$timestamp_header       = 'Timestamp: ' . $current_time_in_millis;
		$authorization_header   = 'Authorization: ' . $hash_value;
		$from_email             = $email;
		$site_url               = site_url();
		$apis                   = '';

		if ( ! empty( $last_requested_api ) ) {
			foreach ( $last_requested_api as $api => $method ) {
				$apis .= $method . ' ' . $api . '<br>';
			}
		}
		global $user;
		$user  = wp_get_current_user();
		$query = '[WP Prevent Files / Folders Plugin - ' . MO_MEDIA_RESTRICTION_PLUGIN_NAME_VERSION . ' ] : ' . $message;

		$content = '<div >Hello, <br><br>First Name :' . $user->user_firstname . '<br><br>Last  Name :' . $user->user_lastname . '   <br><br>Company :<a href="' . $site_url . '" target="_blank" >' . $site_url . '</a><br><br>Phone Number :' . $phone . '<br><br>Email :<a href="mailto:' . $from_email . '" target="_blank">' . $from_email . '</a><br><br>Query :' . $query . '</div>';

		$fields                   = array(
			'customerKey' => $customer_key,
			'sendEmail'   => true,
			'email'       => array(
				'customerKey' => $customer_key,
				'fromEmail'   => $from_email,
				'bccEmail'    => 'oauthsupport@xecurify.com',
				'fromName'    => 'miniOrange',
				'toEmail'     => 'oauthsupport@xecurify.com',
				'toName'      => 'oauthsupport@xecurify.com',
				'subject'     => $subject,
				'content'     => $content,
			),
		);
		$field_string             = wp_json_encode( $fields );
		$headers                  = array( 'Content-Type' => 'application/json' );
		$headers['Customer-Key']  = $customer_key;
		$headers['Timestamp']     = $current_time_in_millis;
		$headers['Authorization'] = $hash_value;
		$args                     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '15',
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

		return true;
	}

	/**
	 * Send demo alert.
	 *
	 * @param string $email email.
	 * @param string $demo_plan demo plan name.
	 * @param string $message message.
	 * @param string $subject subject.
	 * @return mixed
	 */
	public function mo_api_auth_send_demo_alert( $email, $demo_plan, $message, $subject ) {

		if ( ! $this->mo_media_restriction_check_internet_connection() ) {
			return;
		}
		$url = get_option( 'host_name' ) . '/moas/api/notify/send';

		$customer_key = $this->default_customer_key;
		$api_key      = $this->default_api_key;

		$current_time_in_millis = self::get_timestamp();
		$string_to_hash         = $customer_key . $current_time_in_millis . $api_key;
		$hash_value             = hash( 'sha512', $string_to_hash );
		$customer_key_header    = 'Customer-Key: ' . $customer_key;
		$timestamp_header       = 'Timestamp: ' . $current_time_in_millis;
		$authorization_header   = 'Authorization: ' . $hash_value;
		$from_email             = $email;
		$site_url               = site_url();

		global $user;
		$user     = wp_get_current_user();
		$use_case = '[WP Prevent Files / Folders Plugin - ' . MO_MEDIA_RESTRICTION_PLUGIN_NAME_VERSION . ' ] : ' . $message;

		$content                  = '<div>Hello, <br><br>Demo Plan :' . $demo_plan . '<br><br>Email :<a href="mailto:' . $from_email . '" target="_blank">' . $from_email . '</a><br><br>Usecase :' . $use_case . '</div>';
		$fields                   = array(
			'customerKey' => $customer_key,
			'sendEmail'   => true,
			'email'       => array(
				'customerKey' => $customer_key,
				'fromEmail'   => $from_email,
				'bccEmail'    => 'oauthsupport@xecurify.com',
				'fromName'    => 'miniOrange',
				'toEmail'     => 'oauthsupport@xecurify.com',
				'toName'      => 'oauthsupport@xecurify.com',
				'subject'     => $subject,
				'content'     => $content,
			),
		);
		$field_string             = wp_json_encode( $fields );
		$headers                  = array( 'Content-Type' => 'application/json' );
		$headers['Customer-Key']  = $customer_key;
		$headers['Timestamp']     = $current_time_in_millis;
		$headers['Authorization'] = $hash_value;
		$args                     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '15',
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
	}
	/**
	 * Check internet connection.
	 *
	 * @return mixed
	 */
	public function mo_media_restriction_check_internet_connection() {
		return (bool) @fsockopen( 'login.xecurify.com', 443, $errno, $errstr, 5 ); //phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fsockopen, WordPress.PHP.NoSilencedErrors.Discouraged -- Using default PHP function to check socket connection.
	}
}
