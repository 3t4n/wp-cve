<?php //phpcs:ignore
/**
 * Utilities trait.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since 1.2.2
 */

// Namespace.
namespace StockSyncWithGoogleSheetForWooCommerce;

// Exit if accessed directly.
defined('ABSPATH') || exit;


if ( ! trait_exists('\StockSyncWithGoogleSheetForWooCommerce\Utilities') ) {

	/**
	 * Utilities trait.
	 */
	trait Utilities {
		/**
		 * Checks if development mode is enabled
		 *
		 * @return bool
		 */
		public function is_development_mode() { //phpcs:ignore
			return defined('WP_DEBUG') && WP_DEBUG;
		}

		/**
		 * Sends json response.
		 *
		 * @param bool   $success Whether the request was successful or not.
		 * @param string $data   The data to send.
		 * @return void
		 */
		 public function send_json( $success, $data = null ) { //phpcs:ignore
			$response = [
				'success' => $success,
			];

			if ( $data ) {
				if ( is_array($data) || is_object($data) ) {
					$response['data'] = $data;
				} else {
					$response['message'] = $data;
				}
			}

			wp_send_json($response, 200);
			wp_die();
		}

		/**
		 * Sanitize array recursively.
		 *
		 * @param mixed $array The array to sanitize.
		 * @return mixed
		 */
		public function sanitize( $array ) { //phpcs:ignore
			foreach ( $array as $key => $value ) {
				if ( is_array($value) ) {
					$array[ $key ] = $this->sanitize($value);
				} else {
					$array[ $key ] = sanitize_text_field($value);
				}
			}

			return $array;
		}

		/**
		 * Gets the request body.
		 *
		 * @return object
		 */
		public function get_body() { //phpcs:ignore
			$inputs = json_decode(file_get_contents('php://input'), true);

			if ( is_array($inputs) ) {
				return (object) $this->sanitize($inputs);
			}

			return (object) [];
		}


		/**
		 * Loads template file.
		 *
		 * @param string $template The template file name.
		 * @param array  $data     The data to pass to the template.
		 * @return void
		 */
		public function load_template( $template, $data = [] ) { // phpcs:ignore
			if ( file_exists( SSGSW_TEMPLATES . $template . '.php' ) ) {
				include SSGSW_TEMPLATES . $template . '.php';
			}
		}
	}
}//phpcs:ignore