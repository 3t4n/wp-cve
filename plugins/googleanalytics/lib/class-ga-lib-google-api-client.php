<?php
/**
 * API Client library.
 *
 * @package GoogleAnalytics
 */
use Google\Client;

// phpcs:disable Squiz.Commenting.FunctionCommentThrowTag.WrongNumber

require_once trailingslashit( __DIR__ ) . 'class-ga-lib-api-client-exception.php';
require_once trailingslashit( __DIR__ ) . 'class-ga-lib-google-api-client-exception.php';
require_once trailingslashit( __DIR__ ) . 'class-ga-lib-google-api-client-data-exception.php';
require_once trailingslashit( __DIR__ ) . 'class-ga-lib-google-api-client-authcode-exception.php';
require_once trailingslashit( __DIR__ ) . 'class-ga-lib-google-api-client-accountsummaries-exception.php';

/**
 * API Client.
 */
class Ga_Lib_Google_Api_Client extends Ga_Lib_Api_Client {

	/**
	 * Instance object.
	 *
	 * @var Ga_Lib_Api_Client|null
	 */
	private static $instance = null;

	const OAUTH2_REVOKE_ENDPOINT                 = 'https://accounts.google.com/o/oauth2/revoke';
	const OAUTH2_TOKEN_ENDPOINT                  = 'https://accounts.google.com/o/oauth2/token';
	const OAUTH2_AUTH_ENDPOINT                   = 'https://accounts.google.com/o/oauth2/auth';
	const OAUTH2_FEDERATED_SIGNON_CERTS_ENDPOINT = 'https://www.googleapis.com/oauth2/v1/certs';
	const GA_ACCOUNT_SUMMARIES_ENDPOINT          = 'https://www.googleapis.com/analytics/v3/management/accountSummaries';
	const GA_DATA_ENDPOINT                       = 'https://analyticsreporting.googleapis.com/v4/reports:batchGet';
	const OAUTH2_CALLBACK_URI                    = 'urn:ietf:wg:oauth:2.0:oob';

	const USE_CACHE = true;

	/**
	 * Disable cache?
	 *
	 * @var bool
	 */
	private $disable_cache = false;

	/**
	 * Pre-defined API credentials.
	 *
	 * @var array
	 */
	private $config = array(
		'access_type'      => 'offline',
		'application_name' => 'Google Analytics',
		'client_id'        => '207216681371-433ldmujuv4l0743c1j7g8sci57cb51r.apps.googleusercontent.com',
		'client_secret'    => 'y0B-K-ODB1KZOam50aMEDhyc',
		'scopes'           => array( 'https://www.googleapis.com/auth/analytics.readonly' ),
		'approval_prompt'  => 'force',
	);

	/**
	 * Keeps Access Token information.
	 *
	 * @var array
	 */
	private $token;

	/**
	 * Constructor.
	 */
	private function __construct() {
	}

	/**
	 * Returns API client instance.
	 *
	 * @return Ga_Lib_Api_Client|null
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new Ga_Lib_Google_Api_Client();
		}

		return self::$instance;
	}

	/**
	 * Set disable cache.
	 *
	 * @param bool $value True if yes, false if no.
	 */
	public function set_disable_cache( $value ) {
		$this->disable_cache = $value;
	}

	/**
	 * Call API method.
	 *
	 * @param callable $callback Callable function.
	 * @param array    $args     Array of arguments.
	 *
	 * @return false|Ga_Lib_Api_Response|mixed
	 * @throws Ga_Lib_Google_Api_Client_Exception Exception if callable if unknown.
	 */
	public function call_api_method( $callback, $args ) {
		$callback = array( get_class( $this ), $callback );
		if ( true === is_callable( $callback ) ) {
			try {
				if ( ! empty( $args ) ) {
					if ( is_array( $args ) ) {
						return call_user_func_array( $callback, $args );
					} else {
						return call_user_func_array( $callback, array( $args ) );
					}
				} else {
					return call_user_func( $callback );
				}
			} catch ( Ga_Lib_Api_Request_Exception $e ) {
				throw new Ga_Lib_Google_Api_Client_Exception( $e->getMessage() );
			}
		} else {
			throw new Ga_Lib_Google_Api_Client_Exception(
				'[' . get_class( $this ) . ']Unknown method: ' . print_r( // phpcs:ignore
					$callback,
					true
				)
			);
		}
	}

	/**
	 * Sets access token.
	 *
	 * @param string $token Token string.
	 */
	public function set_access_token( $token ) {
		$this->token = $token;
	}

	/**
	 * Returns Google Oauth2 redirect URL.
	 *
	 * @return string
	 */
	private function get_redirect_uri() {
		return self::OAUTH2_CALLBACK_URI;
	}

	/**
	 * Creates Google Oauth2 authorization URL.
	 *
	 * @return string
	 */
	public function create_auth_url() {
		$params = array(
			'response_type'   => 'code',
			'redirect_uri'    => $this->get_redirect_uri(),
			'client_id'       => rawurlencode( $this->config['client_id'] ),
			'scope'           => implode( ' ', $this->config['scopes'] ),
			'access_type'     => rawurlencode( $this->config['access_type'] ),
			'approval_prompt' => rawurlencode( $this->config['approval_prompt'] ),
		);

		return self::OAUTH2_AUTH_ENDPOINT . '?' . http_build_query( $params, null, '&' );
	}

	/**
	 * Sends request for Access Token during Oauth2 process.
	 *
	 * @param string $access_code Access code.
	 *
	 * @return Ga_Lib_Api_Response Returns response object
	 * @throws Ga_Lib_Google_Api_Client_AuthCode_Exception Exception thrown for auth error.
	 */
	private function ga_auth_get_access_token( $access_code ) {
		$request = array(
			'code'          => $access_code,
			'grant_type'    => 'authorization_code',
			'redirect_uri'  => $this->get_redirect_uri(),
			'client_id'     => $this->config['client_id'],
			'client_secret' => $this->config['client_secret'],
		);
		try {
			$response = Ga_Lib_Api_Request::get_instance()->make_request(
				self::OAUTH2_TOKEN_ENDPOINT,
				$request,
				false,
				true
			);
		} catch ( Ga_Lib_Api_Request_Exception $e ) {
			throw new Ga_Lib_Google_Api_Client_AuthCode_Exception( $e->getMessage() );
		}

		return new Ga_Lib_Api_Response( $response );
	}

	/**
	 * Sends request to refresh Access Token.
	 *
	 * @param string $refresh_token Refresh token string.
	 *
	 * @return Ga_Lib_Api_Response
	 * @throws Ga_Lib_Google_Api_Client_RefreshToken_Exception Throws error on failed refresh token fetch.
	 */
	private function ga_auth_refresh_access_token( $refresh_token ) {
		$request = array(
			'refresh_token' => $refresh_token,
			'grant_type'    => 'refresh_token',
			'client_id'     => $this->config['client_id'],
			'client_secret' => $this->config['client_secret'],
		);

		try {
			$response = Ga_Lib_Api_Request::get_instance()->make_request(
				self::OAUTH2_TOKEN_ENDPOINT,
				$request,
				false,
				true
			);
		} catch ( Ga_Lib_Api_Request_Exception $e ) {
			throw new Ga_Lib_Google_Api_Client_RefreshToken_Exception( $e->getMessage() );
		}

		return new Ga_Lib_Api_Response( $response );
	}

	/**
	 * Get list of the analytics accounts.
	 *
	 * @return Ga_Lib_Api_Response Returns response object
	 * @throws Ga_Lib_Google_Api_Client_AccountSummaries_Exception On failed account summaries fetch.
	 */
	private function ga_api_account_summaries() {
		$request = Ga_Lib_Api_Request::get_instance();
		$request = $this->sign( $request );
		try {
			$response = $request->make_request( self::GA_ACCOUNT_SUMMARIES_ENDPOINT, null, false, true );
		} catch ( Ga_Lib_Api_Request_Exception $e ) {
			throw new Ga_Lib_Google_Api_Client_AccountSummaries_Exception( $e->getMessage() );
		}

		return new Ga_Lib_Api_Response( $response );
	}

	/**
	 * Sends request for Google Analytics data using given query parameters.
	 *
	 * @param array $query_params Query params array.
	 *
	 * @return Ga_Lib_Api_Response Returns response object.
	 * @throws Ga_Lib_Google_Api_Client_Data_Exception On failed client data.
	 * @throws Ga_Lib_Api_Client_Exception On failed data fetch.
	 */
	private function ga_api_data( $query_params ) {
		$request           = Ga_Lib_Api_Request::get_instance( $this->is_cache_enabled(), Ga_Helper::get_account_id() );
		$request           = $this->sign( $request );
		$current_user      = wp_get_current_user();
		$quota_user_string = '';

		if ( ! empty( $current_user ) ) {
			$blogname          = get_option( 'blogname' );
			$quota_user        = md5( $blogname . $current_user->user_login );
			$quota_user_string = '?quotaUser=' . $quota_user;
		}

		try {
			$response = $request->make_request(
				self::GA_DATA_ENDPOINT . $quota_user_string,
				wp_json_encode( $query_params ),
				true
			);

		} catch ( Ga_Lib_Api_Request_Exception $e ) {
			throw new Ga_Lib_Google_Api_Client_Data_Exception( $e->getMessage() );
		}

		return new Ga_Lib_Api_Response( $response );
	}

	/**
	 * Sign request with Access Token.
	 * Adds Access Token to the request's headers.
	 *
	 * @param Ga_Lib_Api_Request $request Request object.
	 *
	 * @return Ga_Lib_Api_Request Returns response object
	 * @throws Ga_Lib_Api_Client_Exception Throws client exception if access token not available.
	 */
	private function sign( Ga_Lib_Api_Request $request ) {
		if ( empty( $this->token ) ) {
			throw new Ga_Lib_Api_Client_Exception( 'Access Token is not available. Please reauthenticate' );
		}

		// Check if the token is set to expire in the next 30 seconds
		// (or has already expired).
		$this->check_access_token();

		// Add the OAuth2 header to the request.
		$request->set_request_headers( array( 'Authorization: Bearer ' . $this->token['access_token'] ) );

		return $request;
	}

	/**
	 * Refresh and save refreshed Access Token.
	 *
	 * @param string $refresh_token Refresh token string.
	 *
	 * @throws Ga_Lib_Google_Api_Client_Exception Throws exception if refresh token fails.
	 */
	public function refresh_access_token( $refresh_token ) {
		// Request for a new Access Token.
		$response = $this->call_api_method( 'ga_auth_refresh_access_token', array( $refresh_token ) );

		Ga_Admin::save_access_token( $response, $refresh_token );

		// Set new access token.
		$token = Ga_Helper::get_option( Ga_Admin::GA_OAUTH_AUTH_TOKEN_OPTION_NAME );
		$this->set_access_token( json_decode( $token, true ) );
	}

	/**
	 * Checks if Access Token is valid.
	 *
	 * @return bool
	 */
	public function is_authorized() {
		if ( ! empty( $this->token ) ) {
			try {
				$this->check_access_token();
			} catch ( Ga_Lib_Api_Client_Exception $e ) {
				$this->add_error( $e );
			} catch ( Exception $e ) {
				$this->add_error( $e );
			}
		}

		return ! empty( $this->token ) && ! $this->is_access_token_expired();
	}

	/**
	 * Returns if the access_token is expired.
	 *
	 * @return bool Returns True if the access_token is expired.
	 */
	public function is_access_token_expired() {
		if ( null === $this->token ) {
			return true;
		}

		if ( ! empty( $this->token['error'] ) ) {
			return true;
		}
		// Check if the token is expired in the next 30 seconds.
		$expired = ( $this->token['created'] + ( $this->token['expires_in'] - 30 ) ) < time();

		return $expired;
	}

	/**
	 * Check access token.
	 *
	 * @return void
	 * @throws Ga_Lib_Api_Client_Exception Throws client exception for failed fresh token.
	 * @throws Ga_Lib_Google_Api_Client_Exception Throws Api client exception for failed fresh token.
	 */
	private function check_access_token() {
		if ( true === $this->is_access_token_expired() ) {
			// Use GA4 refresh method if using ga4token.
			if ( true === empty( get_option( 'googleanalytics_oauth_auth_token' ) ) ) {
				$ga_admin = new Ga_Admin();

				$ga_admin->getGa4Client();
			} else {
				if ( true === empty( $this->token['refresh_token'] ) ) {
					throw new Ga_Lib_Api_Client_Exception(
						__( 'Refresh token is not available. Please re-authenticate.' )
					);
				} else {
					$this->refresh_access_token( $this->token['refresh_token'] );
				}
			}
		}
	}

	/**
	 * Is cache enabled?
	 *
	 * @return bool
	 */
	public function is_cache_enabled() {
		return true === self::USE_CACHE && false === $this->disable_cache;
	}
}
