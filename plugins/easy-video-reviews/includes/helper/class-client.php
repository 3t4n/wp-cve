<?php
/**
 * Handles the client side of the API.
 *
 * @since 1.3.8
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews\Helper;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . '\Client' ) ) {

	/**
	 * Handles the client side of the API.
	 */
	class Client extends \EasyVideoReviews\Base\Controller {

		// Use Utilities trait.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Singleton instance
		 *
		 * @var self
		 */
		private static $instance;

		/**
		 * Access token
		 *
		 * @var [type]
		 */
		protected $access_token = null;

		/**
		 * REST API suffix for version
		 *
		 * @var string
		 */
		protected $rest_api_suffix = 'wp-json/evr/v2/';

		/**
		 * Returns the singleton instance
		 *
		 * @return self
		 */
		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
		/**
		 * Returns the access token.
		 *
		 * @return mixed
		 */
		public function get_access_token() {
			$access_token = get_option( 'evr_access_token', false );
			return apply_filters( 'evr_access_token', $access_token );
		}

		/**
		 * Set the access token
		 *
		 * @param string $token user authentication token.
		 * @return void
		 */
		public function setAccessToken( $token ) {
			$this->access_token = $token;
		}

		/**
		 * Get url
		 *
		 * @param string $route Server action url.
		 * @return SaaS server url with route
		 */
		protected function get_url( $route = '' ) {
			return $this->get_server() . $this->rest_api_suffix . $route;
		}

		/**
		 * Checks if the Client has a valid access token.
		 *
		 * @return bool
		 */
		public function has_valid_token() {
			return true === wp_validate_boolean( $this->get_access_token() ) && ! empty( $this->get_access_token() ) && strlen( $this->get_access_token() ) > 10;
		}

		/**
		 * Checks if the Client has premium access.
		 *
		 * @return bool
		 */
		public function has_premium_access() {
			/**
			 * Manipulating this option will NOT allow you to test the premium features.
			 * All premium features are tested on a separate server.
			 * This option is only used to hide the premium features from the free version temporarily.
			 * This section of code will be reset to the original state when you connect to the server.
			 */
			return true === wp_validate_boolean( $this->option()->get('is_pro') );
		}

		/**
		 * Returns the client host.
		 *
		 * @return string
		 */
		public function get_host() {
			return apply_filters( 'evr_client_host', isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '' );
		}

		/**
		 * Returns the server url.
		 *
		 * @return string
		 */
		public function get_server() {
			return apply_filters( 'evr_server', 'https://evr.wppool.dev/' );
		}

		/**
		 * Checks if the Client is locally hosted.
		 *
		 * @return bool
		 */
		public function is_local() {
			$whitelist = [ '127.0.0.1', '::1' ];
			if ( ! isset( $_SERVER['REMOTE_ADDR'] ) ) {
				return false;
			}

			$remote_addr = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
			if ( in_array( $remote_addr, $whitelist) ) {
				return true;
			}
			return false;
		}

		/**
		 * Get folders from remote url
		 *
		 * @return array|false
		 */
		public function folders() {

			global $evr_folders;

			if ( ! empty( $evr_folders ) ) {
				return $evr_folders;
			}

			$remote  = new \EasyVideoReviews\Remote();
			$folders = $remote->get_folders();

			if ( ! empty( $folders ) ) {
				$evr_folders = $folders;
				return $folders;
			}

			return [];
		}

		/**
		 * Get reviews from remote url
		 *
		 * @return array|false
		 */
		public function reviews() {

			global $evr_reviews;

			if ( ! empty( $evr_reviews ) ) {
				return $evr_reviews;
			}

			$remote  = new \EasyVideoReviews\Remote();
			$reviews = $remote->get_reviews();

			if ( ! empty( $reviews ) ) {
				$evr_reviews = $reviews;
				return $reviews;
			}

			return [];
		}

		/**
		 * Post request
		 *
		 * @param string $route Server url action.
		 * @param array  $data Data for url action.
		 * @return object
		 */
		public function post( $route = '', $data = [] ) {
			return $this->make_request('POST', $route, $data);
		}

		/**
		 * Make Reauest to Send
		 *
		 * @param string $method Request type.
		 * @param string $route Server url action.
		 * @param array  $data Data for url action.
		 * @throws \Exception Error message.
		 * @return object
		 */
		protected function make_request( $method = 'GET', $route = '', $data = [] ) {
			$url = $this->get_url($route);
			$args = [
				'method' => $method,
				'headers' => [
					'Authorization' => 'Bearer ' . $this->access_token,
					'Content-Type' => 'application/json',
				],
			];

			if ( 'POST' === $method ) {
				$args['body'] = wp_json_encode($data);
			}

			if ( 'GET' === $method ) {
				$url = add_query_arg($data, $url);
			}

			$response = wp_remote_request($url, $args);

			if ( is_wp_error($response) ) {
				throw new \Exception( esc_html( $response->get_error_message() ) );
			}

			$body = wp_remote_retrieve_body($response);

			$body = json_decode($body);

			// Response code.
			$response_code = wp_remote_retrieve_response_code($response);

			// If server down.
			if ( $response_code >= 500 ) {
				return 'Easy Video Reviews server is down. Please try again later.';
			}

			return $body;
		}
	}
}
