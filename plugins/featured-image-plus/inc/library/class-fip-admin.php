<?php
/**
 * [Short Description]
 *
 * @package    DEVRY\FIP
 * @copyright  Copyright (c) 2024, Developry Ltd.
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since      1.4
 */

namespace DEVRY\FIP;

! defined( ABSPATH ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'FIP_Admin' ) ) {

	class FIP_Admin {
		/**
		 * Consturtor.
		 */
		public function __construct() {
		}

		/**
		 * Initializor.
		 */
		public function init() {
			add_action( 'wp_loaded', array( $this, 'on_loaded' ) );
		}

		/**
		 * Plugin loaded.
		 */
		public function on_loaded() {}

		/**
		 * Return a response message in JSON format and exit.
		 */
		public function print_json_message( $status, $message, $values_arr = array() ) {
			echo json_encode(
				array(
					array(
						'status'  => $status,
						'message' => vsprintf(
							wp_kses(
								$message,
								json_decode( FIP_PLUGIN_ALLOWED_HTML_ARR )
							),
							$values_arr
						),
					),
				),
			);
			exit;
		}

		/**
		 * Check the validity of the nonce token for the plugin's AJAX requests.
		 */
		public function check_nonce_token() {
			if ( ! check_ajax_referer( 'fip-ajax-nonce', 'security', false ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Check if the nonce token is invalid; if so, print an
		 * error message with a support email link.
		 */
		public function get_invalid_nonce_token() {
			/* translators: %1$s is replaced with Invalid security toke */
			/* translators: %2$s is replaced with link to Support email */
			$message    = esc_html__( '%1$s! Contact us @ %2$s.', 'featured-image-plus' );
			$values_arr = array(
				'<strong>' . __( 'Invalid security token', 'featured-image-plus' ) . '</strong>',
				'<a href="mailto:contact@' . FIP_PLUGIN_DOMAIN . '">contact@' . FIP_PLUGIN_DOMAIN . '</a>',
			);

			if ( ! $this->check_nonce_token() ) {
				$this->print_json_message(
					0,
					$message,
					$values_arr
				);
			}
		}

		/**
		 * Check if the current user has the necessary capability, typically for
		 * administrative tasks in the plugin.
		 */
		public function check_user_cap() {
			if ( ! current_user_can( 'administrator' ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Check if the current user has the necessary capabilities;
		 * otherwise, print an error message.
		 */
		public function get_invalid_user_cap() {
			/* translators: %1$s is replaced with Access denied */
			$message    = esc_html__( '%1$s! Current user does not have the capabilities to access this function.', 'featured-image-plus' );
			$values_arr = array( '<strong>' . __( 'Access denied', 'featured-image-plus' ) . '</strong>' );

			if ( ! $this->check_user_cap() ) {
				$this->print_json_message(
					0,
					$message,
					$values_arr
				);
			}
		}
	}
}
