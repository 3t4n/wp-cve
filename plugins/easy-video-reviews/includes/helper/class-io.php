<?php
/**
 * Input Output Helper Class
 * Handles all input and output for Easy Video Reviews
 *
 * @since 1.3.8
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews\Helper;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . '\IO' ) ) {

	/**
	 * Input Output Helper Class
	 */
	class IO {

		/**
		 * Singleton instance
		 *
		 * @var self
		 */
		private static $instance;

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
		 * Returns all input
		 *
		 * @return array
		 */
		public function get_inputs() {
			$input = file_get_contents( 'php://input' );
			$input = json_decode( sanitize_text_field( $input ), true );

			if ( ! $input || empty($input) ) {
				$input = $_REQUEST;
			}

			return $this->sanitize( $input );
		}


		/**
		 * Sanitizes recursively all input
		 *
		 * @param array|string $data Data.
		 * @return array|string
		 */
		public function sanitize( $data ) {
			if ( is_array( $data ) ) {
				foreach ( $data as $key => $value ) {
					$data[ $key ] = $this->sanitize( $value );
				}
			} else {
				$data = sanitize_text_field( $data );
			}

			return $data;
		}

		/**
		 * Returns a specific input
		 *
		 * @param string $key The key to get.
		 * @param mixed  $default The default value.
		 * @return mixed
		 */
		public function get_input( $key, $default = null ) {
			$input = $this->get_inputs();

			if ( isset( $input[ $key ] ) ) {
				return $input[ $key ];
			}

			return $default;
		}

		/**
		 * Get URL Parameter
		 *
		 * @param string $key The key to get.
		 * @param mixed  $default The default value.
		 * @return mixed
		 */
		public function get_url_param( $key, $default = null ) {
			$param = filter_input( INPUT_GET, $key );

			if ( ! empty( $param ) ) {
				return $this->sanitize( $param );
			}

			return $default;
		}

		/**
		 * Sends a JSON response
		 *
		 * @param mixed $success Success.
		 * @param mixed $message Message.
		 * @param mixed $data Data.
		 */
		public function send_json( $success, $message = null, $data = null ) {
			$payload = [
				'success' => boolval( $success ),
				'message' => '',
				'data'    => '',
			];

			if ( is_array( $data ) || is_object( $data ) ) {
				$payload['data'] = $data;
			} elseif ( ! is_null( $data ) ) {
				$payload['message'] = $data;
			}

			if ( $message ) {
				$payload['message'] = $message;
			}

			wp_send_json( $payload );
			wp_die();
		}
	}
}
