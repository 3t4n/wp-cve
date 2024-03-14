<?php
/**
 * Owly API class
 *
 * @package WP_To_Social_Pro
 * @author  WP Zinc
 */

/**
 * Provides functions for sending statuses and querying Hootsuite's ow.ly API.
 *
 * @package WP_To_Social_Pro
 * @author  WP Zinc
 * @version 3.0.0
 */
class WP_To_Social_Pro_Owly_API {

	/**
	 * Holds the base class object.
	 *
	 * @since   1.0.0
	 *
	 * @var     object
	 */
	public $base;

	/**
	 * Holds the API endpoint
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	private $api_endpoint = 'https://www.wpzinc.com/?api=owly';

	/**
	 * Constructor
	 *
	 * @since   1.0.0
	 *
	 * @param   object $base    Base Plugin Class.
	 */
	public function __construct( $base ) {

		// Store base class.
		$this->base = $base;

	}

	/**
	 * Uploads a photo to ow.ly
	 *
	 * @since   1.0.0
	 *
	 * @param   string $image_url  Image URL.
	 * @return  mixed   WP_Error | Update object
	 */
	public function photo_upload( $image_url ) {

		// Send request.
		return $this->post(
			'photo/upload',
			array(
				'image_url' => $image_url,
			)
		);

	}

	/**
	 * Private function to perform a GET request
	 *
	 * @since  1.0.0
	 *
	 * @param  string $cmd        Command (required).
	 * @param  array  $params     Params (optional).
	 * @return mixed               WP_Error | object
	 */
	private function get( $cmd, $params = array() ) {

		return $this->request( $cmd, 'get', $params );

	}

	/**
	 * Private function to perform a POST request
	 *
	 * @since  1.0.0
	 *
	 * @param  string $cmd        Command (required).
	 * @param  array  $params     Params (optional).
	 * @return mixed               WP_Error | object
	 */
	private function post( $cmd, $params = array() ) {

		return $this->request( $cmd, 'post', $params );

	}

	/**
	 * Main function which handles sending requests to the ow.ly API
	 *
	 * @since   1.0.0
	 *
	 * @param   string $cmd        Command.
	 * @param   string $method     Method (get|post).
	 * @param   array  $params     Parameters (optional).
	 * @return  mixed               WP_Error | object
	 */
	private function request( $cmd, $method = 'get', $params = array() ) {

		// Build endpoint URL.
		$url = $this->api_endpoint . '&action=' . $cmd;

		// Define the timeout.
		$timeout = 10;

		/**
		 * Defines the number of seconds before timing out a request to the Owly API.
		 *
		 * @since   3.0.0
		 *
		 * @param   int     $timeout    Timeout, in seconds
		 */
		$timeout = apply_filters( $this->base->plugin->filter_name . '_api_request', $timeout );

		// Request via WordPress functions.
		$result = $this->request_wordpress( $url, $method, $params, $timeout );

		// Result will be WP_Error or the data we expect.
		return $result;

	}

	/**
	 * Performs POST and GET requests through WordPress wp_remote_post() and
	 * wp_remote_get() functions
	 *
	 * @since   1.0.0
	 *
	 * @param   string $url        URL.
	 * @param   string $method     Method (post|get).
	 * @param   array  $params     Parameters.
	 * @param   int    $timeout    Timeout, in seconds (default: 10).
	 * @return  mixed               WP_Error | object
	 */
	private function request_wordpress( $url, $method, $params, $timeout = 10 ) {

		// Send request.
		switch ( $method ) {
			/**
			 * GET
			 */
			case 'get':
				$response = wp_remote_get(
					$url,
					array(
						'body'    => ( ! empty( $params ) ? $params : '' ),
						'timeout' => $timeout,
					)
				);
				break;

			/**
			 * POST
			 */
			case 'post':
				$response = wp_remote_post(
					$url,
					array(
						'body'    => ( ! empty( $params ) ? $params : '' ),
						'timeout' => $timeout,
					)
				);
				break;
		}

		// If an error occured, return it now.
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		// Fetch HTTP code and body.
		$http_code = wp_remote_retrieve_response_code( $response );
		$response  = wp_remote_retrieve_body( $response );

		// Parse the response, to return the JSON data or an WP_Error object.
		return $this->parse_response( $response, $http_code, $params );

	}

	/**
	 * Parses the response body and HTTP code, returning either
	 * a WP_Error object or the JSON decoded response body
	 *
	 * @since   1.7.3
	 *
	 * @param   string $response   Response Body.
	 * @param   int    $http_code  HTTP Code.
	 * @param   array  $params     Request Parameters.
	 * @return  mixed               WP_Error | object
	 */
	private function parse_response( $response, $http_code, $params ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter

		// Decode response.
		$body = json_decode( $response );

		// Return body if response is successful.
		if ( $body->success ) {
			return $body->data;
		}

		// Return basic WP_Error if we don't have any more information.
		if ( is_null( $body ) ) {
			return new WP_Error(
				$http_code,
				sprintf(
					/* translators: HTTP Error Code */
					__( 'Hootsuite API Error: HTTP Code %s. Sorry, we don\'t have any more information about this error. Please try again.', 'wp-to-hootsuite' ),
					$http_code
				)
			);
		}

		// Return detailed WP_Error.
		// Define the error message.
		$message = array();
		if ( isset( $body->data ) ) {
			$message[] = $body->data;
		}

		// For certain error codes, we can provide better error messages to the user, detailing
		// the steps they should take to resolve the issue.
		if ( strpos( $body->data, 'Picture upload failed' ) !== false ) {
			if ( $this->is_local_host() ) {
				$message = array(
					sprintf(
						/* translators: HTTP Error Message */
						__( '%s, because your site is running on a local host and not web accessible. Please run the Plugin on a publicly accessible domain.', 'wp-to-hootsuite' ),
						$body->data
					),
				);
			} else {
				$message = array(
					sprintf(
						/* translators: HTTP Error Message */
						__( '%s. Ensure your web host, firewall and any WAF Plugins are not preventing Hootsuite from accessing this image.', 'wp-to-hootsuite' ),
						$body->data
					),
				);
			}
		}

		// Return WP_Error.
		return new WP_Error(
			$http_code,
			sprintf(
				/* translators: %1$s: HTTP Error Code, %2$s: Error Message */
				__( 'Hootsuite API Error: #%1$s: %2$s', 'wp-to-hootsuite' ),
				$http_code,
				implode( "\n", $message )
			)
		);

	}

	/**
	 * Determines if the WordPress URL is a local, non-web accessible URL.
	 *
	 * @since   1.7.3
	 *
	 * @return  bool    Locally Hosted Site
	 */
	private function is_local_host() {

		// Get URL of site and its information.
		$url = wp_parse_url( get_bloginfo( 'url' ) );

		// Iterate through local host addresses to check if they exist
		// in part of the site's URL host.
		foreach ( $this->get_local_hosts() as $local_host ) {
			if ( strpos( $url['host'], $local_host ) !== false ) {
				return true;
			}
		}

		// If here, we're not on a local host.
		return false;

	}

	/**
	 * Returns an array of domains and IP addresses that are non-web accessible
	 *
	 * @since   1.7.3
	 *
	 * @return  array   Non-web accessible Domains and IP addresses
	 */
	private function get_local_hosts() {

		// If domain is 127.0.0.1, localhost or .dev, don't count it towards the domain limit
		// The user has a valid license key if they're here, so that's enough
		// See: https://www.sqa.org.uk/e-learning/WebTech01CD/page_12.htm.
		$local_hosts = array(
			'localhost',
			'127.0.0.1',
			'10.0.',
			'192.168.',
			'.dev',
			'.local',
			'.localhost',
			'.test',
		);

		// Add 172.16.0.* to 172.16.31.*.
		for ( $i = 0; $i <= 31; $i++ ) {
			$local_hosts[] = '172.16.' . $i . '.';
		}

		return $local_hosts;

	}

}
