<?php
/**
 * Handles the Remote HTTP requests for the API.
 *
 * @since 1.3.8
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . '\Remote' ) ) {

	/**
	 * Handles the Remote HTTP requests for the API.
	 */
	class Remote extends \EasyVideoReviews\Base\Controller {

		// Use Utilities trait.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Access token
		 *
		 * @var string
		 */
		protected $access_token;

		/**
		 * REST API suffix
		 *
		 * @var string
		 */
		protected $rest_api_suffix = 'wp-json/evr/v2/';

		/**
		 * Returns the remote server endpoint.
		 *
		 * @param string $route The route to append to the server endpoint.
		 * @return string
		 */
		protected function get_server_endpoint( $route = '' ) {
			return wp_sprintf( '%s%s%s', $this->client()->get_server(), $this->rest_api_suffix, trim( $route, '/' ) );
		}

		/**
		 * Makes a request to the remote server.
		 *
		 * @param string $method The request method.
		 * @param string $route The route to append to the server endpoint.
		 * @param array  $data The data to send.
		 * @return mixed
		 * @throws \Exception If the request fails.
		 */
		protected function make_request( $method = 'GET', $route = '', $data = [] ) {
			$url  = $this->get_server_endpoint( $route );
			$args = [
				'method'  => $method,
				'headers' => [
					'Authorization' => 'Bearer ' . $this->client()->get_access_token(),
					'Content-Type'  => 'application/json',
				],
			];

			// Add data to the request.
			$data['website'] = $this->client()->get_host();

			if ( 'POST' === $method ) {
				$args['body'] = wp_json_encode( $data );
			} else {
				$url = add_query_arg( $data, $url );
			}

			$response = wp_remote_request( $url, $args );

			if ( is_wp_error( $response ) ) {
				$this->print_error_notice( $response->get_error_message() );
				return false;
			}

			$body = wp_remote_retrieve_body( $response );
			$body = json_decode( $body );

			// Response code.
			$response_code = wp_remote_retrieve_response_code( $response );

			// Is server down.
			if ( $response_code >= 500 ) {
				$this->print_error_notice( 'The Easy Video Reviews server is down. Please try again later.' );
				return false;
			}

			return $body;
		}

		/**
		 * Performs a GET request.
		 *
		 * @param string $route The route to append to the server endpoint.
		 * @param array  $data The data to send.
		 * @return mixed
		 */
		public function get( $route = '', $data = [] ) {
			return $this->make_request( 'GET', $route, $data );
		}

		/**
		 * Performs a POST request.
		 *
		 * @param string $route The route to append to the server endpoint.
		 * @param array  $data The data to send.
		 * @return mixed
		 */
		public function post( $route = '', $data = [] ) {
			return $this->make_request( 'POST', $route, $data );
		}

		/**
		 * Prints an error notice.
		 *
		 * @param string $message The message to print.
		 * @return void
		 */
		protected function print_error_notice( $message ) {
			add_action( 'admin_notices', function () use ( $message ) {
				?>
				<div class="notice notice-error">
					<p><?php echo esc_html( $message ); ?></p>
				</div>
				<?php
			} );
		}

		/**
		 * Additional methods.
		 */

		/**
		 * Get folders.
		 *
		 * @return array
		 */
		public function get_folders() {

			$app_information = $this->get( 'app' );

			// Return empty array if there is no app information.
			if ( ! $app_information || ! is_object( $app_information ) || ! isset( $app_information->success ) ) {
				return [];
			}

			if ( ! isset( $app_information->data ) || ! isset( $app_information->data->folders ) ) {
				return [];
			}

			return $app_information->data->folders;
		}

		/**
		 * Get reviews.
		 *
		 * @return array
		 */
		public function get_reviews() {

			$all_reviews = $this->get( 'reviews' );

			// Return empty array if there is no app information.
			if ( ! $all_reviews || ! is_object( $all_reviews ) || ! isset( $all_reviews->success ) ) {
				return [];
			}

			if ( ! isset( $all_reviews->data ) || ! isset( $all_reviews->data->reviews ) ) {
				return [];
			}

			return $all_reviews->data->reviews;
		}

		/**
		 * Get review by Slug.
		 *
		 * @param string $slug The review slug.
		 * @return mixed
		 */
		public function get_review_by_slug( $slug = '' ) {

			// Return empty array if there is no slug.
			if ( empty( $slug ) ) {
				return false;
			}

			$review = $this->get( 'public/reviews', [ 'slug' => $slug ] );

			// Return empty array if there is no review.
			if ( ! $review || ! is_object( $review ) || ! isset( $review->success ) ) {
				return false;
			}

			if ( ! isset( $review->data ) ) {
				return false;
			}

			return $review->data;
		}
	}
}
