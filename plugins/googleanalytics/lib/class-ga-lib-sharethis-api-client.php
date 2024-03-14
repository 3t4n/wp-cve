<?php
/**
 * ShareThis API Client.
 *
 * @package GoogleAnalytics
 */

// Requires.
require_once 'class-ga-lib-sharethis-api-client-exception.php';
require_once 'class-ga-lib-sharethis-api-client-invaliddomain-exception.php';
require_once 'class-ga-lib-sharethis-api-client-invite-exception.php';
require_once 'class-ga-lib-sharethis-api-client-alerts-exception.php';
require_once 'class-ga-lib-sharethis-api-client-verify-exception.php';

/**
 * ShareThis API Client.
 */
class Ga_Lib_Sharethis_Api_Client extends Ga_Lib_Api_Client {

	/**
	 * ShareThis API client instance.
	 *
	 * @var Ga_Lib_Sharethis_Api_Client|null
	 */
	public static $instance = null;

	const GA_SHARETHIS_ENDPOINT = 'platform-api.sharethis.com/v1.0/property';

	const USE_CACHE = false;

	/**
	 * Private constructor.
	 */
	private function __construct() {}

	/**
	 * Returns API client instance.
	 *
	 * @return Ga_Lib_Api_Client|null
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new Ga_Lib_Sharethis_Api_Client();
		}

		return self::$instance;
	}

	/**
	 * Call API method.
	 *
	 * @param callable $callback Callable callback.
	 * @param array    $args     Array of arguments.
	 *
	 * @return false|Ga_Lib_Api_Response|mixed
	 * @throws Ga_Lib_Sharethis_Api_Client_Exception API client exception.
	 */
	public function call_api_method( $callback, $args ) {
		$callback = array( get_class( $this ), $callback );
		if ( is_callable( $callback ) ) {
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
				throw new Ga_Lib_Sharethis_Api_Client_Exception( $e->getMessage() );
			}
		} else {
			throw new Ga_Lib_Sharethis_Api_Client_Exception( wp_json_encode( array( 'error' => '[' . get_class( $this ) . ']Unknown method: ' . $callback ) ) );
		}
	}

	/**
	 * Sends request for Sharethis api
	 *
	 * @param array $query_params Query parameters.
	 *
	 * @return Ga_Lib_Api_Response Returns response object
	 * @throws Ga_Lib_Sharethis_Api_Client_InvalidDomain_Exception Invalid domain exception.
	 */
	private function ga_api_create_sharethis_property( $query_params ) {
		$request = Ga_Lib_Api_Request::get_instance( self::USE_CACHE );
		try {
			$response = $request->make_request(
				$this->add_protocol( self::GA_SHARETHIS_ENDPOINT ),
				wp_json_encode( $query_params ),
				true
			);
		} catch ( Ga_Lib_Api_Request_Exception $e ) {
			throw new Ga_Lib_Sharethis_Api_Client_InvalidDomain_Exception( $e->getMessage() );
		}

		return new Ga_Lib_Api_Response( $response );
	}

	/**
	 * Installation verification check.
	 *
	 * @param array $query_params Query parameters.
	 *
	 * @return Ga_Lib_Api_Response
	 * @throws Ga_Lib_Sharethis_Api_Client_Verify_Exception Client verification exception.
	 */
	private function ga_api_sharethis_installation_verification( $query_params ) {
		$request = Ga_Lib_Api_Request::get_instance( self::USE_CACHE );
		try {
			$response = $request->make_request( 'https://' . self::GA_SHARETHIS_ENDPOINT . '/verify', wp_json_encode( $query_params ), true );
		} catch ( Ga_Lib_Api_Request_Exception $e ) {
			throw new Ga_Lib_Sharethis_Api_Client_Verify_Exception( $e->getMessage() );
		}

		return new Ga_Lib_Api_Response( $response );
	}

	/**
	 * User invite call.
	 *
	 * @param array $query_params Query parameters.
	 *
	 * @return Ga_Lib_Api_Response
	 * @throws Ga_Lib_Sharethis_Api_Client_Invite_Exception Client invitation exception.
	 */
	private function ga_api_sharethis_user_invite( $query_params ) {
		$request = Ga_Lib_Api_Request::get_instance( self::USE_CACHE );
		try {
			$response = $request->make_request( 'https://' . self::GA_SHARETHIS_ENDPOINT . '/user/join', wp_json_encode( $query_params ), true );
		} catch ( Ga_Lib_Api_Request_Exception $e ) {
			throw new Ga_Lib_Sharethis_Api_Client_Invite_Exception( $e->getMessage() );
		}

		return new Ga_Lib_Api_Response( $response );
	}

	/**
	 * Add correct protocol based on HTTP/HTTPS.
	 *
	 * @param string $url URL string.
	 *
	 * @return string Modified URL string.
	 */
	private function add_protocol( $url ) {
		return ( is_ssl() ) ? 'https://' . $url : 'http://' . $url;
	}
}
