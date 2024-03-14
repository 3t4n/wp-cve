<?php
/**
 * OAuth version 2 implementation for interacting with Formstack API version 2.
 * @package Formstack
 * @since   2.0.0
 */

/**
 * Class Formstack_API_V2
 */
class Formstack_API_V2 {

	/**
	 * Authorization URI
	 * @since 2.0.0
	 */
	const AUTH_URI = 'https://www.formstack.com/api/v2/oauth2/authorize';

	/**
	 * Token URI
	 * @since 2.0.0
	 */
	const TOKEN_URI = 'https://www.formstack.com/api/v2/oauth2/token';

	/**
	 * Forms endpoint URI
	 * @since 2.0.0
	 */
	const FORMS_URI = 'https://www.formstack.com/api/v2/form.json';

	/**
	 * App client ID.
	 * @var string
	 * @since 2.0.0
	 */
	private $client_id = '';

	/**
	 * App client secret ID.
	 * @var string
	 * @since 2.0.0
	 */
	private $client_secret = '';

	/**
	 * URI to redirect to, after authorization.
	 * @var string
	 * @since 2.0.0
	 */
	private $redirect_uri = '';

	/**
	 * Returned access token.
	 * @var string
	 * @since 2.0.0
	 */
	private $access_token = '';

	/**
	 * Token to use to refresh access without re-authorization.
	 * @var string
	 * @since 2.0.0
	 */
	private $refresh_token = '';

	/**
	 * Total form count from the API.
	 * @var string
	 * @since 2.0.0
	 */
	public $total_forms = '';

	private $errors = array();

	/**
	 * Formstack_API_V2 constructor.
	 *
	 * @param array $args Array of arguments for instance.
	 */
	public function __construct( $args = array() ) {
		$this->client_id     = isset( $args['client_id'] )     ? $args['client_id']     : '';
		$this->client_secret = isset( $args['client_secret'] ) ? $args['client_secret'] : '';
		$this->redirect_uri  = isset( $args['redirect_uri'] )  ? $args['redirect_uri']  : '';
		$this->code          = isset( $args['code'] )          ? $args['code']          : '';

		$this->access_token  = $this->get_access_token();
		#$this->refresh_token = $this->get_refresh_token();
	}

	/**
	 * Returns a link to use to request authorization tokens.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_authentication_link() {
		$link = add_query_arg(
			array(
				'client_id'     => $this->client_id,
				'redirect_uri'  => rawurlencode( $this->redirect_uri ),
				'response_type' => 'code',
			),
			self::AUTH_URI
		);

		return esc_url( $link );
	}

	/**
	 * Returns a button to use for authentication.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_authentication_button() {
		return sprintf(
			'<div class="formstack-button"><p class="submit"><a class="wp-ui-highlight" href="%s">%s</a></div>',
			$this->get_authentication_link(),
			esc_html__( 'Authenticate with Formstack API', 'formstack' )
		);
	}

	/**
	 * Returns a button to force refresh tokens.
	 *
	 * Not presently used.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_refresh_token_button() {
		return sprintf(
			'<div class="formstack-button"><p class="submit"><a class="wp-ui-highlight" href="%s">%s</a></div>',
			add_query_arg( array( 'refresh_formstack_token' => 'true' ), admin_url( 'admin.php?page=Formstack' ) ),
			esc_html__( 'Force API token refresh', 'formstack' )
		);
	}

	/**
	 * Retrieve an access token to use with future requests.
	 *
	 * We will store current tokens and refresh tokens into options.
	 * Access tokens are good for 3600 seconds. Once time has elapsed, we
	 * need to request a new one using the refresh token.
	 *
	 * @since 2.0.0
	 */
	public function get_fresh_token() {
		/*if ( $this->is_token_expired() && $this->refresh_token ) {
			$this->refresh_token();
		}*/

		if ( empty( $this->access_token ) ) {
			$result = wp_remote_post( self::TOKEN_URI,
				array(
					'body' => array(
						'grant_type'    => 'authorization_code',
						'client_id'     => $this->client_id,
						'redirect_uri'  => $this->redirect_uri,
						'client_secret' => $this->client_secret,
						'code'          => $this->code,
					)
				)
			);

			if ( is_wp_error( $result ) ) {
				$error = sprintf(
					__( 'WordPress error: %s', 'formstack' ),
					$result->get_error_message()
				);
				formstack_log_message( $error );
				$this->add_error( $error );
				return;
			}

			$response = json_decode( wp_remote_retrieve_body( $result ) );
			if ( 200 !== wp_remote_retrieve_response_code( $result ) ) {
				if ( $response->error_description ) {
					$error = sprintf(
						__( 'Authentication error: %s', 'formstack' ),
						$response->error_description
					);
					formstack_log_message( $error );
					$this->add_error( $error );
				}
				return;
			}

			$this->set_access_token( $response );
			#$this->set_refresh_token( $response );
			delete_option( 'formstack_forms' );
			$this->get_forms();
		}
	}

	/**
	 * Sets our access token from our API response.
	 *
	 * @since 2.0.0
	 *
	 * @param object $response
	 */
	public function set_access_token( $response ) {
		update_option( 'formstack_access_token', $response->access_token );
		#$this->set_token_expiration( $response->expires_in );
		$this->access_token = $response->access_token;
	}

	/**
	 * Return our current token.
	 *
	 * @since 2.0.0
	 */
	public function get_access_token() {
		return get_option( 'formstack_access_token', '' );
	}

	/**
	 * Sets our refresh token from our API response.
	 *
	 * @since 2.0.0
	 *
	 * @param object $response
	 */
	public function set_refresh_token( $response ) {
		update_option( 'formstack_refresh_token', $response->refresh_token );
		$this->refresh_token = $response->refresh_token;
	}

	/**
	 * Return our current refresh token.
	 *
	 * @since 2.0.0
	 */
	public function get_refresh_token() {
		return get_option( 'formstack_refresh_token', '' );
	}

	/**
	 * Check whether or not we have our tokens set.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function has_tokens() {
		$has = false;
		if ( ! empty( $this->access_token ) ) {
			$has = true;
		}
		return $has;
	}

	/**
	 * Returns array of available forms for an account.
	 *
	 * For performance sake, we strive to cache the results.
	 *
	 * @since 2.0.0
	 *
	 * @return array|mixed|object
	 */
	public function get_forms() {

		$forms = get_option( 'formstack_forms', '' );
		if ( ! empty( $forms ) ) {
			return $forms;
		}

		if ( empty( $this->access_token ) ) {
			$this->get_access_token();
		}

		/*if ( $this->is_token_expired() ) {
			$this->refresh_token();
		}*/
		if ( empty( $this->access_token ) ) {
			return array();
		}

		$args = array(
			'oauth_token' => $this->access_token,
			'per_page' => 50,
		);
		$url = add_query_arg( $args, self::FORMS_URI );

		$request_args = array(
			'timeout' => 120
		);

		$result = wp_remote_get( $url, $request_args );
		if ( is_wp_error( $result ) ) {
			return array();
		}
		// Initial request used mostly just to get a total form count. We will re-fetch the same results on a first request later.
		$initial_request = json_decode( wp_remote_retrieve_body( $result ) );
		if ( 200 !== wp_remote_retrieve_response_code( $result ) ) {
			if ( $initial_request->error ) {
				$error = sprintf(
					__( 'Form retrieval error: %s', 'formstack' ),
					$initial_request->error
				);
				formstack_log_message( $error );
				$this->add_error( $error );
			}

			return array();
		}

		$total_forms = $initial_request->total;

		if ( $total_forms === 0 ) {
			return array();
		}

		$total_calls = 1; // Default to one call. It is a repeat of above.
		if ( $total_forms > 50 ) {
			$total_calls = ceil( $total_forms / 50 );
		}

		$counter = 1;
		$forms = array( 'forms' => array(), 'total' => $total_forms ); // Initialize the variable that we will be saving to an option.
		while( $counter <= $total_calls ) {
			$args['page'] = $counter;
			$url = add_query_arg( $args, self::FORMS_URI );
			$api_response = wp_remote_get( $url, $request_args );
			$new_forms = json_decode( wp_remote_retrieve_body( $api_response ) );
			if ( is_array( $new_forms->forms ) ) {
				$forms['forms'] = array_merge( $forms['forms'], $new_forms->forms );
			}
			$counter++;
		}

		update_option( 'formstack_forms', $forms );
		$this->set_form_count( $forms['total'] );

		return $forms;
	}

	/**
	 * Set an expiration time option so we can check if we should refresh.
	 *
	 * @since 2.0.0
	 *
	 * @param string $duration Time the token persists.
	 */
	public function set_token_expiration( $duration ) {
		update_option( 'formstack_token_expiration', time() + $duration );
	}

	/**
	 * Check whether or not we need to renew tokens.
	 *
	 * @since 2.0.0
	 *
	 * @return mixed
	 */
	public function is_token_expired() {
		$expires = get_option( 'formstack_token_expiration', '' );

		// Returning the default string instead of bool
		// for the sake of providing a way to display "none set".
		if ( empty( $expires ) ) {
			return $expires;
		}

		if ( time() < $expires ) {
			return false;
		}

		return true;
	}

	/**
	 * Requests some new tokens, using our current refresh token.
	 *
	 * @since 2.0.0
	 */
	public function refresh_token() {
		$args = array(
			'body' => array(
				'grant_type'    => 'refresh_token',
				'refresh_token' => $this->refresh_token,
				'client_id'     => $this->client_id,
				'client_secret' => $this->client_secret,
			)
		);
		$result = wp_remote_post( self::TOKEN_URI, $args );
		if ( is_wp_error( $result ) ) {

			$error = sprintf(
				__( 'WordPress error: %s', 'formstack' ),
				$result->get_error_message()
			);
			formstack_log_message( $error );
			$this->add_error( $error );
			return;
		}

		$response = json_decode( wp_remote_retrieve_body( $result ) );
		if ( 200 === wp_remote_retrieve_response_code( $result ) ) {
			$this->set_access_token( $response );
			#$this->set_refresh_token( $response );
		} else {
			if ( $response->error_description ) {
				$error = sprintf(
					__( 'Token refresh error: %s', 'formstack' ),
					$response->error_description
				);
				formstack_log_message( $error );
				$this->add_error( $error );
			}
		}
	}

	/**
	 * Sets our total form count, for some statistics.
	 *
	 * @since 2.0.0
	 *
	 * @since 2.0.0
	 */
	public function set_form_count( $total ) {
		update_option( 'formstack_form_count', $total );
	}

	/**
	 * Returns our total form count.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_form_count() {
		return get_option( 'formstack_form_count', '' );
	}

	/**
	 * Adds an error to our array of errors to display.
	 *
	 * @since 2.0.0
	 *
	 * @param string $error Error to display.
	 */
	public function add_error( $error ) {
		$this->errors[] = $error;
	}

	/**
	 * Output all errors we have.
	 *
	 * @since 2.0.0
	 */
	public function display_errors() {
		foreach( $this->errors as $error ) {
			echo '<p class="error"><strong>' . $error . '</strong></p>';
		}
	}
}
