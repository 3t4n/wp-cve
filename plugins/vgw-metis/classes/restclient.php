<?php

namespace WP_VGWORT;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

/**
 * Metis Restclient
 *
 * Singleton wrapper class for Guzzle REST API.
 * Needs initialization in order to initialize the static rest client instance.
 * Can be instantiated with custom handler (e.g. for testing purposes).
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class Restclient {
	/**
	 * @var object rest client singleton instance, can be reinstantiated with change_handler function
	 */
	public static object $client;

	/**
	 * @var string holds the base url, set on init or change_handler function
	 */
	private static string $base_url;

	/**
	 * @var array holds the headers, set on init or change_handler function
	 */
	private static array $headers = [];

	/**
	 * @var bool holds the verify value, responsible for enabling / disabling SSL cert verification
	 */
	private static bool $verify = false;

	/**
	 * Initialize the rest api client
	 *
	 * creates the initial static client instance with base url, headers and ssl verify flag
	 *
	 * @param string $base_url base url for all requests
	 * @param array $headers   assoc headers array for all requests
	 * @param bool $verify     enable / disable ssl cert check
	 *
	 * @return void
	 */
	public static function init( string $base_url, array $headers = [], bool $verify = false ): void {
		if ( ! $base_url ) {
			wp_die( 'Metis_Restclient > init > no base url' );
		}

		self::setBaseUrl( $base_url );
		self::setHeaders( $headers );
		self::setVerify( $verify );

		self::$client = new Client( [
			'base_uri' => self::$base_url,
			'verify'   => self::$verify,
			'headers'  => self::$headers
		] );
	}

	/**
	 * replaces current rest api instance with one with a custom handler
	 *
	 * replaces the current rest api client instance with new one with given handler.
	 * Optionally use different host, headers and verify ssl flag.
	 * used in phpunit tests for mocking.
	 *
	 * @param object $handler       the guzzle handler for requests / responses
	 * @param string|null $base_url rest client base url
	 * @param array|null $headers   assoc headers array for all requests
	 * @param bool|null $verify     enable / disable ssl cert check
	 *
	 * @return void
	 */
	public static function change_handler( object $handler, string $base_url = null, array $headers = null, bool $verify = null ): void {
		if ( ! $handler ) {
			die( 'Metis_Restclient > change_handler > no handler given' );
		}

		self::$client = new Client( [
			'handler'  => $handler,
			'base_uri' => $base_url === null ? self::$base_url : $base_url,
			'verify'   => $verify === null ? self::$base_url : $verify,
			'headers'  => $headers === null ? self::$headers : $headers
		] );
	}

	/**
	 * if a custom handler is set, you can return to the init rest client state with restore
	 *
	 * @return void
	 */
	public static function restore(): void {
		self::$client = new Client( [
			'base_uri' => self::$base_url,
			'verify'   => self::$verify,
			'headers'  => self::$headers
		] );
	}

	/**
	 * setter for the rest apis base url for all requests
	 *
	 * @param string $base_url
	 *
	 * @return void
	 */
	public static function setBaseUrl( string $base_url ): void {
		self::$base_url = $base_url;
	}

	/**
	 * setter for the rest api headers for all requests
	 *
	 * @param array $headers assoc array  of headers to apply to requests
	 *
	 * @return void
	 */
	public static function setHeaders( array $headers ): void {
		self::$headers = $headers;
	}

	/**
	 * sets the SSL check verify value, responsible for enabling or disabling the SSL cert checks
	 *
	 * @param bool $verify enable = true, disable = false
	 *
	 * @return void
	 */
	public static function setVerify( bool $verify ): void {
		self::$verify = $verify;
	}


	/**
	 * Returns the HTML of a post
	 *
	 * @param string $url $post url
	 *
	 * @return string | bool return post content as string or false on error
	 */
	public static function get_post_html( string $url ): string | bool {
		if ( ! $url ) {
			return false;
		}

		$client = new Client([
			'verify' => self::$verify,
			'headers' => ['Content-Type' => 'text/html']
		]);

		$request = new Request( "GET", $url );

		try {
			$response = Restclient::$client->send( $request );
			if ( $response->getStatusCode() == 200 ) {
				return $response->getBody()->getContents();
			} else {
				return false;
			}

		} catch ( \Exception $e ) {
			return false;
		}
	}

	public static function handle_http_error(\Exception $e) {
		$code = $e->getCode();

		$message = $e->getMessage();



		if ( $code === 401 ) {
			// Unauthorized
			Services::redirect_to_vgw_metis_page(null, 'wp_metis_rest_client_unauthorized');
		}

		// unknown error
		Services::redirect_to_vgw_metis_page(null, 'wp_metis_rest_client_unknown_error', $message);
	}

	/**
	 * Register notifications for the rest client (called in plugin bootstrap)
	 *
	 * @param Notifications $notifications
	 *
	 * @return void
	 */
	public static function register_notifications(Notifications &$notifications): void {
		$notifications->add_notice_by_key( 'wp_metis_rest_client_unauthorized', esc_html__( 'CSV-Import aus T.O.M. fehlgeschlagen, da der API-Key fehlt oder ungültig ist. Bitte geben Sie einen gültigen API-Key in den Einstellungen an!', 'vgw-metis' ), );
		$notifications->add_notice_by_key( 'wp_metis_rest_client_unknown_error', esc_html__( 'Unbekannter API Fehler.', 'vgw-metis' ) );

	}
}
