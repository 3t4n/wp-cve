<?php
/**
 * Firebase Authentication Plugin Contact Us
 *
 * @package Firebase Authentication Contact Us
 */

/**
 * Handling the query form
 *
 * @copyright  miniOrange
 * @license    PHP License 3.0
 * @since      Class available since Release
 */
class MO_Firebase_Contact_Us {
	/**
	 * Default customer key
	 *
	 * @var string
	 */
	private $default_customer_key = '16555';
	/**
	 * Default customer api key
	 *
	 * @var string
	 */
	private $default_api_key = 'fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq';

	/**
	 * Function for processing contact us request
	 *
	 * @param string $email Email.
	 * @param string $phone phone.
	 * @param string $query query.
	 */
	public function mo_firebase_auth_contact_us( $email, $phone, $query ) {
		$current_user = wp_get_current_user();
		$version      = get_option( 'mo_firebase_authentication_current_plugin_version' );
		$query        = '[WP Firebase Authentication Plugin] ' . $version . ' - ' . $query;
		$fields       = array(
			'firstName' => $current_user->user_firstname,
			'lastName'  => $current_user->user_lastname,
			'company'   => site_url(),
			'email'     => $email,
			'ccEmail'   => 'oauthsupport@xecurify.com',
			'phone'     => $phone,
			'query'     => $query,
		);
		$field_string = wp_json_encode( $fields );

		$url = 'https://login.xecurify.com/moas/rest/customer/contact-us';

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
	 * Function for processing feedback form
	 *
	 * @param string $email Email.
	 * @param string $message Message.
	 * @param string $subject Subject.
	 */
	public function mo_firebase_auth_send_email_alert( $email, $message, $subject ) {

		if ( ! $this->check_internet_connection() ) {
			return;
		}

		$url                    = get_option( 'mo_fb_host_name' ) . '/moas/api/notify/send';
		$customer_key           = $this->default_customer_key;
		$api_key                = $this->default_api_key;
		$current_time_in_millis = self::get_timestamp();
		$string_to_hash         = $customer_key . $current_time_in_millis . $api_key;
		$hash_value             = hash( 'sha512', $string_to_hash );
		$from_email             = $email;
		$subject                = 'Feedback: WP Firebase Authentication Plugin';
		$site_url               = site_url();

		global $user;
		$user  = wp_get_current_user();
		$query = '[WP Firebase Authentication] : ' . $message;

		$content = '<div >Hello, <br><br>First Name :' . $user->user_firstname . '<br><br>Last  Name :' . $user->user_lastname . '   <br><br>Company :<a href="' . $site_url . '" target="_blank" >' . $site_url . '</a><br><br>Email :<a href="mailto:' . $from_email . '" target="_blank">' . $from_email . '</a><br><br>Query :' . $query . '</div>';

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

		$args = array(
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
	 * Function to check internet connection
	 */
	public function check_internet_connection() {
		return (bool) @fsockopen( 'login.xecurify.com', 443, $i_errno, $s_err_str, 5 ); //phpcs:ignore -- Ignoring AlternativeFunctions warning for file handling functions.
	}

	/**
	 * Function to get current timestamp
	 */
	public function get_timestamp() {
			$url     = get_option( 'mo_fb_host_name' ) . '/moas/rest/mobile/get-timestamp';
			$headers = array(
				'Content-Type'  => 'application/json',
				'charset'       => 'UTF - 8',
				'Authorization' => 'Basic',
			);
			$args    = array(
				'method'      => 'POST',
				'body'        => array(),
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

