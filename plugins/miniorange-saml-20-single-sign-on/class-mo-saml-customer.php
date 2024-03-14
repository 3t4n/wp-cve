<?php
/** This file takes care of making API requests for interacting with the customer’s miniOrange account.
 *
 * @package     miniorange-saml-20-single-sign-on
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __FILE__ ) . '/includes/lib/class-mo-saml-options-enum.php';
require_once 'class-mo-saml-utilities.php';

/**
 * This class Mo_SAML_Customer contains functions to handle all the customer related functionalities like sending support query, feedback.
 */
class Mo_SAML_Customer {

	/**
	 * Customer Email.
	 *
	 * @access   public
	 * @var      string $email Customer's Email.
	 */
	public $email;

	/**
	 * Customer Phone.
	 *
	 * @access   public
	 * @var      string $phone Customer's Phone.
	 */
	public $phone;

	/**
	 * Customer Key.
	 * Initial values are hardcoded to support the miniOrange framework to generate OTP for email.
	 * We need the default value for creating the first time,
	 * As we don't have the Default keys available before registering the user to our server.
	 * This default values are only required for sending an One Time Passcode at the user provided email address.
	 *
	 * @access   private
	 * @var      string $default_customer_key Customer's Customer Key.
	 */
	private $default_customer_key = '16555';

	/**
	 * Customer API Key.
	 * Initial values are hardcoded to support the miniOrange framework to generate OTP for email.
	 * We need the default value for creating the first time,
	 * As we don't have the Default keys available before registering the user to our server.
	 * This default values are only required for sending an One Time Passcode at the user provided email address.
	 *
	 * @access   private
	 * @var      string $default_api_key Customer's Customer Key.
	 */
	private $default_api_key = 'fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq';

	/**
	 * This function is used for creating customer by making a call to the /rest/customer/add endpoint.
	 *
	 * @return array $response Response of the API call for creating Customer.
	 */
	public function mo_saml_create_customer() {
		$url          = Mo_Saml_Options_Plugin_Constants::HOSTNAME . '/moas/rest/customer/add';
		$current_user = wp_get_current_user();
		$this->email  = get_option( Mo_Saml_Customer_Constants::ADMIN_EMAIL );
		$password     = get_option( Mo_Saml_Customer_Constants::ADMIN_PASSWORD );

		$fields       = array(
			'areaOfInterest' => 'WP miniOrange SAML 2.0 SSO Plugin',
			'email'          => $this->email,
			'password'       => $password,
		);
		$field_string = wp_json_encode( $fields );

		$headers = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF-8',
			'Authorization' => 'Basic',
		);

		$args     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '10',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);
		$response = Mo_SAML_Utilities::mo_saml_wp_remote_post( $url, $args );
		return $response;

	}

	/**
	 * This function is used for getting customer key.
	 *
	 * @return array $response Response of the API call for fetching Customer key by making a call to the /rest/customer/key endpoint.
	 */
	public function mo_saml_get_customer_key() {
		$url = Mo_Saml_Options_Plugin_Constants::HOSTNAME . '/moas/rest/customer/key';

		$email = get_option( Mo_Saml_Customer_Constants::ADMIN_EMAIL );

		$password = get_option( Mo_Saml_Customer_Constants::ADMIN_PASSWORD );

		$fields       = array(
			'email'    => $email,
			'password' => $password,
		);
		$field_string = wp_json_encode( $fields );

		$headers  = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF-8',
			'Authorization' => 'Basic',
		);
		$args     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '10',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);
		$response = Mo_SAML_Utilities::mo_saml_wp_remote_post( $url, $args );
		return $response;

	}

	/**
	 * This function is used for checking if customer exists by making a call to the /rest/customer/check-if-exists endpoint.
	 *
	 * @return string $response Response of the API call for customer validity.
	 */
	public function mo_saml_check_customer() {
		$url = Mo_Saml_Options_Plugin_Constants::HOSTNAME . '/moas/rest/customer/check-if-exists';

		$email = get_option( Mo_Saml_Customer_Constants::ADMIN_EMAIL );

		$fields       = array(
			'email' => $email,
		);
		$field_string = wp_json_encode( $fields );

		$headers  = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF-8',
			'Authorization' => 'Basic',
		);
		$args     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '10',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);
		$response = Mo_SAML_Utilities::mo_saml_wp_remote_post( $url, $args );
		return $response;
	}

	/**
	 * This function is used for sending support query from plugin by making a call to the rest/customer/contact-us endpoint.
	 *
	 * @param string $email       Customer's Email.
	 * @param string $phone       Customer's Phone.
	 * @param string $query       Customer's Query.
	 * @param bool   $call_setup  Customer's Request for call.
	 *
	 * @return array $response    Response of the API call for call request.
	 */
	public function mo_saml_submit_contact_us( $email, $phone, $query, $call_setup ) {
		$url          = Mo_Saml_Options_Plugin_Constants::HOSTNAME . '/moas/rest/customer/contact-us';
		$current_user = wp_get_current_user();

		if ( $call_setup ) {
			$query = '[Call Request - WP SAML SP SSO Plugin] ' . $query;
		} else {
			$query = '[WP SAML 2.0 SP SSO Plugin] ' . $query;
		}

		if ( isset( $_SERVER['SERVER_NAME'] ) ) {
			$server_name = sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) );
		} else {
			$server_name = '';
		}

		$fields = array(
			'firstName' => $current_user->user_firstname,
			'lastName'  => $current_user->user_lastname,
			'company'   => $server_name,
			'email'     => $email,
			'ccEmail'   => 'samlsupport@xecurify.com',
			'phone'     => $phone,
			'query'     => $query,
		);

		$field_string = wp_json_encode( $fields );

		$headers  = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF-8',
			'Authorization' => 'Basic',
		);
		$args     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '10',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);
		$response = Mo_SAML_Utilities::mo_saml_wp_remote_post( $url, $args );
		return $response;

	}

	/**
	 * This function is used for sending the query for demo requests and feedback for the plugin by making a call to the /api/notify/send endpoint.
	 *
	 * @param string $email        Customer's Email.
	 * @param string $phone        Customer's Phone.
	 * @param string $message      Customer's Message.
	 *
	 * @return string $response     Response of the API call for demo request and feedback.
	 */
	public function mo_saml_send_email_alert( $email, $phone, $message ) {

		$url = Mo_Saml_Options_Plugin_Constants::HOSTNAME . '/moas/api/notify/send';

		$customer_key = $this->default_customer_key;
		$api_key      = $this->default_api_key;

		$current_time_in_millis = self::mo_saml_get_timestamp();
		$current_time_in_millis = number_format( $current_time_in_millis, 0, '', '' );
		$string_to_hash         = $customer_key . $current_time_in_millis . $api_key;
		$hash_value             = hash( 'sha512', $string_to_hash );
		$from_email             = 'no-reply@xecurify.com';
		$subject                = 'Feedback: WordPress SAML 2.0 SSO Plugin';
		$site_url               = site_url();

		global $user;
		$user = wp_get_current_user();

		$query = '[WordPress SAML SSO 2.0 Plugin: ]: ' . $message;

		if ( isset( $_SERVER['SERVER_NAME'] ) ) {
			$server_name = sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) );
		} else {
			$server_name = '';
		}

		$content = '<div>Hello, <br><br>First Name :' . esc_html( $user->user_firstname ) . '<br><br>Last  Name :' . esc_html( $user->user_lastname ) . '   <br><br>Company :<a href="' . esc_html( $server_name ) . '" target="_blank" >' . esc_html( $server_name ) . '</a><br><br>Phone Number :' . esc_html( $phone ) . '<br><br>Email :<a href="mailto:' . esc_attr( $email ) . '" target="_blank">' . esc_html( $email ) . '</a><br><br>Query :' . wp_kses( $query, array( 'br' => array() ) ) . '</div>';

		$fields       = array(
			'customerKey' => $customer_key,
			'sendEmail'   => true,
			'email'       => array(
				'customerKey' => $customer_key,
				'fromEmail'   => $from_email,
				'fromName'    => 'Xecurify',
				'toEmail'     => 'info@xecurify.com',
				'toName'      => 'samlsupport@xecurify.com',
				'bccEmail'    => 'samlsupport@xecurify.com',
				'subject'     => $subject,
				'content'     => $content,
			),
		);
		$field_string = wp_json_encode( $fields );

		$headers  = array(
			'Content-Type'  => 'application/json',
			'Customer-Key'  => $customer_key,
			'Timestamp'     => $current_time_in_millis,
			'Authorization' => $hash_value,
		);
		$args     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '10',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);
		$response = Mo_SAML_Utilities::mo_saml_wp_remote_post( $url, $args );
		return $response;

	}

	/**
	 * This function is used for resetting the account password by making a call to the /rest/customer/password-reset endpoint.
	 *
	 * @param  string $email    Customer's Email.
	 *
	 * @return array  $response Response of the API call for resetting account's password.
	 */
	public function mo_saml_forgot_password( $email ) {
		$url = Mo_Saml_Options_Plugin_Constants::HOSTNAME . '/moas/rest/customer/password-reset';

		/* The customer Key provided to you */
		$customer_key = get_option( Mo_Saml_Customer_Constants::CUSTOMER_KEY );

		/* The customer API Key provided to you */
		$api_key = get_option( Mo_Saml_Customer_Constants::API_KEY );

		/* Current time in milliseconds since midnight, January 1, 1970 UTC. */
		$current_time_in_millis = round( microtime( true ) * 1000 );

		/* Creating the Hash using SHA-512 algorithm */
		$string_to_hash = $customer_key . number_format( $current_time_in_millis, 0, '', '' ) . $api_key;
		$hash_value     = hash( 'sha512', $string_to_hash );

		$fields = '';

		// *check for otp over sms/email
		$fields = array(
			'email' => $email,
		);

		$field_string = wp_json_encode( $fields );
		$headers      = array(
			'Content-Type'  => 'application/json',
			'Customer-Key'  => $customer_key,
			'Timestamp'     => $current_time_in_millis,
			'Authorization' => $hash_value,
		);
		$args         = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '10',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);
		$response     = Mo_SAML_Utilities::mo_saml_wp_remote_post( $url, $args );
		return $response;

	}

	/**
	 * This function is used to get time when the Support query or the demo request has been raised by making a call to the /rest/mobile/get-timestamp endpoint.
	 *
	 * @return string $response This is time when query was raised.
	 */
	public function mo_saml_get_timestamp() {
		$url      = Mo_Saml_Options_Plugin_Constants::HOSTNAME . '/moas/rest/mobile/get-timestamp';
		$response = Mo_SAML_Utilities::mo_saml_wp_remote_post( $url );
		return $response;
	}
}
