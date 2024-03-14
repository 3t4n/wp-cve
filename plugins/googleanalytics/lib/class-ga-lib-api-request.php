<?php
/**
 * API Request library.
 *
 * @package GoogleAnalytics
 */

// phpcs:disable Squiz.Commenting.FunctionCommentThrowTag.WrongNumber
// phpcs:disable WordPress.WP.AlternativeFunctions
// NOTE: Alternative function recommendation for curl noted! Plans to switch to google service library in backlog.

require_once 'class-ga-lib-api-request-exception.php';

/**
 * API Request Library response.
 */
class Ga_Lib_Api_Request {

	/**
	 * Instance object.
	 *
	 * @var Ga_Lib_Api_Request|null
	 */
	public static $instance = null;

	const HEADER_CONTENT_TYPE      = 'application/x-www-form-urlencoded';
	const HEADER_CONTENT_TYPE_JSON = 'Content-type: application/json';
	const HEADER_ACCEPT            = 'Accept: application/json, text/javascript, */*; q=0.01';
	const TIMEOUT                  = 5;
	const USER_AGENT               = 'googleanalytics-wordpress-plugin';

	/**
	 * Headers array.
	 *
	 * @var array
	 */
	private $headers = array();

	/**
	 * Whether to cache or not.
	 *
	 * @var bool
	 */
	private $cache = false;

	/**
	 * Appendix.
	 *
	 * @var string
	 */
	private $appendix = '';

	/**
	 * Constructor.
	 *
	 * @param bool   $cache    Whether to cache or not.
	 * @param string $appendix Appendix string.
	 */
	private function __construct( $cache = false, $appendix = '' ) {
		$this->cache    = $cache;
		$this->appendix = $appendix;
	}

	/**
	 * Returns API client instance.
	 *
	 * @param bool   $cache    Whether to cache.
	 * @param string $appendix Appendix string.
	 *
	 * @return Ga_Lib_Api_Request|null
	 */
	public static function get_instance( $cache = false, $appendix = '' ) {
		if ( null === self::$instance ) {
			self::$instance = new Ga_Lib_Api_Request( $cache, $appendix );
		}

		return self::$instance;
	}

	/**
	 * Sets request headers.
	 *
	 * @param array|mixed $headers Headers array.
	 */
	public function set_request_headers( $headers ) {
		if ( is_array( $headers ) ) {
			$this->headers = array_merge( $this->headers, $headers );
		} else {
			$this->headers[] = $headers;
		}
	}

	/**
	 * Perform HTTP request.
	 *
	 * @param string  $url            URL address.
	 * @param string  $raw_post_body  Raw Post body.
	 * @param boolean $json           Whether to append JSON content type.
	 * @param boolean $force_no_cache Whether to force not to cache response data even if cache property is set to true.
	 *
	 * @return array Response object.
	 * @throws Exception Throws exception on error.
	 */
	public function make_request( $url, $raw_post_body = null, $json = false, $force_no_cache = false ) {
		// Return cached data if exist.
		if ( false === $force_no_cache ) {
			if ( $this->cache ) {
				$wp_transient_name = Ga_Cache::get_transient_name( $url, $raw_post_body, $this->appendix );
				$cached            = Ga_Cache::get_cached_result( $wp_transient_name );

				if ( false === empty( $cache ) ) {
					if ( ! Ga_Cache::is_data_cache_outdated( $wp_transient_name, $this->appendix ) ) {
						return $cached;
					}
				}

				// Check if the next request after error is allowed.
				if ( false === Ga_Cache::is_next_request_allowed( $wp_transient_name ) ) {
					throw new Ga_Lib_Api_Client_Exception(
						__( 'There are temporary connection issues, please try again later.' )
					);
				}
			}
		}

		if ( false === function_exists( 'curl_init' ) ) {
			throw new Ga_Lib_Api_Client_Exception(
				__( 'cURL functions are not available' )
			);
		}

		// Set default headers.
		$this->set_request_headers(
			array(
				( $json ? self::HEADER_CONTENT_TYPE_JSON : self::HEADER_CONTENT_TYPE ),
				self::HEADER_ACCEPT,
			)
		);

		$ch      = curl_init( $url );
		$headers = $this->headers;

		$curl_timeout       = self::TIMEOUT;
		$php_execution_time = ini_get( 'max_execution_time' );
		if ( false === empty( $php_execution_time ) && true === is_numeric( $php_execution_time ) ) {
			if ( $php_execution_time < 36 && $php_execution_time > 9 ) {
				$curl_timeout = $php_execution_time - 5;
			} elseif ( $php_execution_time < 10 ) {
				$curl_timeout = 5;
			}
		}

		// Set the proxy configuration. The user can provide this in wp-config.php.
		if ( defined( 'WP_PROXY_HOST' ) ) {
			curl_setopt( $ch, CURLOPT_PROXY, WP_PROXY_HOST );
		}
		if ( defined( 'WP_PROXY_PORT' ) ) {
			curl_setopt( $ch, CURLOPT_PROXYPORT, WP_PROXY_PORT );
		}
		if ( defined( 'WP_PROXY_USERNAME' ) ) {
			$auth = WP_PROXY_USERNAME;
			if ( defined( 'WP_PROXY_PASSWORD' ) ) {
				$auth .= ':' . WP_PROXY_PASSWORD;
			}
			curl_setopt( $ch, CURLOPT_PROXYUSERPWD, $auth );
		}

		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $curl_timeout );
		curl_setopt( $ch, CURLOPT_TIMEOUT, $curl_timeout );
		curl_setopt( $ch, CURLOPT_HEADER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );

		if ( false === function_exists( 'ini_get' ) || false === ini_get( 'curl.cainfo' ) ) {
			curl_setopt( $ch, CURLOPT_CAINFO, $this->get_cert_path() );
		}

		curl_setopt( $ch, CURLINFO_HEADER_OUT, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_USERAGENT, self::USER_AGENT );
		if ( defined( 'CURLOPT_IPRESOLVE' ) && defined( 'CURL_IPRESOLVE_V4' ) ) {
			curl_setopt( $ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		}

		// POST body.
		if ( false === empty( $raw_post_body ) ) {
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt(
				$ch,
				CURLOPT_POSTFIELDS,
				( $json ? $raw_post_body : http_build_query( $raw_post_body, null, '&' ) )
			);
		}

		// Execute request.
		$response = curl_exec( $ch );
		$error    = curl_error( $ch );

		if ( false === empty( $error ) ) {
			$errno = curl_errno( $ch );
			curl_close( $ch );

			// Store last cache time when unsuccessful.
			if ( false === $force_no_cache && true === $this->cache ) {
				Ga_Cache::set_last_cache_time( $wp_transient_name );
				Ga_Cache::set_last_time_attempt();
			}

			throw new Ga_Lib_Api_Client_Exception( $error . ' (' . $errno . ')' );
		} else {
			$http_code   = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
			$header_size = curl_getinfo( $ch, CURLINFO_HEADER_SIZE );
			$header      = substr( $response, 0, $header_size );
			$body        = substr( $response, $header_size, strlen( $response ) );

			if ( preg_match( '/^(4|5)[0-9]{2}/', $http_code ) ) {
				throw new Ga_Lib_Api_Request_Exception(
					404 === $http_code ? sprintf(
						/* translators: %s stands for the URL. */
						__( 'Requested URL doesn\'t exists: %s', 'googleanalytics' ),
						$url
					) : $body
				);
			}

			curl_close( $ch );

			$response_data = array( $header, $body );

			// Cache result.
			if ( false === $force_no_cache ) {
				if ( true === $this->cache ) {
					Ga_Cache::set_cache( $wp_transient_name, $response_data );
				}
			}

			return $response_data;
		}
	}
}
