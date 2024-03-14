<?php
namespace SG_Email_Marketing\Services\Mailer_Api;

/**
 * Mailer Api class.
 */
class Mailer_Api {

	const API_URL = 'https://mt.siteground.com/api';

	/**
	 * The constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->token = get_option( 'sg_email_marketing_token', false );
	}

	/**
	 * Call the mailer api.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $route  The mailer route.
	 * @param  array  $data   Request data.
	 * @param  string $method Method type.
	 *
	 * @throws \Exception An exception if something went wrong.
	 *
	 * @return array         The response from the mailer api.
	 */
	private function call( $route, $data = array(), $method = 'GET' ) {
		if ( empty( $this->token ) ) {
			throw new \Exception( __( 'Missing api token.', 'siteground-email-marketing' ), 400 );
		}

		// Load .env and retrieve constants.
		$api_url  = ! isset( $_ENV['SG_EM_API_URL'] ) ? self::API_URL : $_ENV['SG_EM_API_URL'];
		$api_host = ! isset( $_ENV['SG_EM_API_HOST'] ) ? '' : $_ENV['SG_EM_API_HOST'];

		$headers = array(
			'Authorization' => 'Bearer: ' . $this->token,
			'Accept'        => 'application/json',
			'Content-Type'  => 'application/json',
		);

		$request_args = array(
			'method'  => $method,
			'body'    => $data,
			'headers' => $headers,
		);

		// Set API host header if defined.
		if ( ! empty( $api_host ) ) {
			$request_args['headers']['Host'] = $api_host;
			$request_args['sslverify']       = false;
		}

		return wp_remote_request(
			$api_url . $route,
			$request_args
		);
	}

	/**
	 * Get labels.
	 *
	 * @since  1.0.0
	 */
	public function get_labels() {
		if ( ! $this->token ) {
			return array();
		}

		return $this->prepare_response(
			$this->call(
				'/labels',
				array(
					'pagination' => 'false',
					'token'      => $this->token,
					'type'       => 'manual',
				)
			),
			'labels'
		);
	}

	/**
	 * Get status.
	 *
	 * @since  1.0.0
	 *
	 * @return array $data The prepared data.
	 */
	public function get_status() {
		if ( ! $this->token ) {
			return array(
				'status_code' => 403,
				'status'      => 'disconnected',
			);
		}

		$response = $this->prepare_response( $this->call( '/token/health', array( 'token' => $this->token ) ), 'get_status' );

		// If termirnated or token is invalid - disconnect the token.
		if ( 401 === $response['status_code'] ) {
			delete_option( 'sg_email_marketing_token' );
			delete_option( 'sg_email_marketing_token_status' );
		}

		// If status is active or suspended - save the status.
		if ( in_array( $response['status_code'], array( 204, 403 ), true ) ) {
			update_option( 'sg_email_marketing_token_status', $response );
		}

		return $response;
	}

	/**
	 * Check the connection and if the token is valid upon initial connection.
	 *
	 * @since 1.0.0
	 *
	 * @param string $token The authentication token.
	 *
	 * @return array        The prepared data.
	 */
	public function connect( $token ) {
		$this->token = $token;
		return $this->prepare_response( $this->call( '/token/health', array( 'token' => $this->token ) ), 'connect' );
	}

	/**
	 * Terminate the connection.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $token The authentication token.
	 *
	 * @return array        The prepared data.
	 */
	public function disconnect( $token ) {
		if (
			! ( $this->token === $token ) ||
			! delete_option( 'sg_email_marketing_token' )
		) {
			return array(
				'status' => 403,
				'message' => __( 'Could not disconnect from email marketing.' ),
			);
		}

		return array(
			'status' => 200,
			'message' => __( 'Disconnected from email marketing.' ),
		);
	}

	/**
	 * Send data to the API.
	 *
	 * @since  1.0.0
	 *
	 * @param  array $data Data to be send.
	 */
	public function send_data( $data ) {
		$response = $this->call( '/import-wp-contacts', wp_json_encode( $data ), 'POST' );

		if ( is_wp_error( $response ) ) {
			return;
		}

		return wp_remote_retrieve_response_code( $response );
	}

	/**
	 * Prepare the response and add the necessary data.
	 *
	 * @since 1.0.0
	 *
	 * @param  WP_REST_Response $response The response from the mailer API.
	 * @param  boolean|string   $type     The type of data.
	 *
	 * @return array            $data     The prepared data.
	 */
	public function prepare_response( $response, $type = false ) {
		$status_code = wp_remote_retrieve_response_code( $response );
		$api_message = wp_remote_retrieve_response_message( $response );

		$data = array(
			'status'  => $status_code,
			'message' => $api_message,
		);

		// Add data only if we are calling the labels, and the account is not suspended. In all other cases, the data will be present.
		if ( 'labels' === $type && 401 !== $status_code ) {
			$data['data'] = json_decode( wp_remote_retrieve_body( $response ), true );
		}

		if ( 'connect' === $type ) {
			$data['status_code'] = $status_code;
			$data['status']      = $this->get_connection_status( $status_code );
			$data['message']     = $this->get_connect_messages( $status_code );

			if ( 401 === $status_code ) {
				throw new \Exception( __( 'Please provide a valid token', 'siteground-email-marketing' ), 403 );
			}
		}

		if ( 'get_status' === $type ) {
			$data['status_code'] = $status_code;
			$data['status']      = $this->get_connection_status( $status_code );
			$data['message']     = $this->get_status_messages( $status_code );

			if ( is_wp_error( $response ) ) {
				$old_status = get_option(
					'sg_email_marketing_token_status',
					array(
						'status'      => 'disconnected',
						'status_code' => 403,
						'token'       => $this->token,
					)
				);

				$old_status['message'] = __( 'Connection status could not be updated.', 'siteground-email-marketing' );

				return $old_status;
			}
		}

		if ( is_wp_error( $response ) ) {
			throw new \Exception( __( 'WordPress cannot process the request.', 'siteground-email-marketing' ), 400 );
		}

		return $data;
	}

	/**
	 * Get messages based on status codes from the API.
	 *
	 * @since 1.0.0
	 *
	 * @param  int $status The status code.
	 *
	 * @return string      The message.
	 */
	public function get_connect_messages( $status ) {
		$messages = array(
			200 => __( 'Connection successful.', 'siteground-email-marketing' ),
			202 => __( 'Connection successful.', 'siteground-email-marketing' ),
			204 => __( 'Connection successful.', 'siteground-email-marketing' ),
			401 => __( 'Please provide a valid token.', 'siteground-email-marketing' ),
			403 => __( 'Connection successful.', 'siteground-email-marketing' ),
		);

		return $messages[ $status ];
	}

	/**
	 * Get messages based on status codes from the API.
	 *
	 * @since 1.0.0
	 *
	 * @param  int $status The status code.
	 *
	 * @return string      The message.
	 */
	public function get_status_messages( $status ) {
		$messages = array(
			200 => __( 'Connection status updated.', 'siteground-email-marketing' ),
			202 => __( 'Connection status updated.', 'siteground-email-marketing' ),
			204 => __( 'Connection status updated.', 'siteground-email-marketing' ),
			401 => __( 'Connection token is invalid.', 'siteground-email-marketing' ),
			403 => __( 'Email marketing account suspended.', 'siteground-email-marketing' ),
		);

		return $messages[ $status ];
	}

	/**
	 * Get status based on status codes from the API.
	 *
	 * @since 1.0.0
	 *
	 * @param  int $status The status code.
	 *
	 * @return string      The status.
	 */
	public function get_connection_status( $status ) {
		$statuses = array(
			'connected'    => array( 200, 202, 204 ),
			'disconnected' => array( 401 ),
			'suspended'    => array( 403 ),
		);

		foreach ( $statuses as $type => $codes ) {
			if ( in_array( $status, $codes ) ) {
				return $type;
			}
		}

		return 'unknown';
	}
}
