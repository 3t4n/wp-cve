<?php
/**
 * Plausible Analytics | Module.
 * @since      1.3.0
 * @package    WordPress
 * @subpackage Plausible Analytics
 */

namespace Plausible\Analytics\WP;

use Plausible\Analytics\WP\Admin\Messages;
use Plausible\Analytics\WP\Client\ApiException;

defined( 'ABSPATH' ) || exit;

class Ajax {
	/**
	 * Build class.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Action and filter hooks.
	 * @return void
	 */
	private function init() {
		add_action( 'wp_ajax_plausible_analytics_quit_wizard', [ $this, 'quit_wizard' ] );
		add_action( 'wp_ajax_plausible_analytics_show_wizard', [ $this, 'show_wizard' ] );
		add_action( 'wp_ajax_plausible_analytics_toggle_option', [ $this, 'toggle_option' ] );
		add_action( 'wp_ajax_plausible_analytics_save_options', [ $this, 'save_options' ] );
	}

	/**
	 * Mark the wizard as finished, so it won't appear again.
	 * @return void
	 */
	public function quit_wizard() {
		$request_data = $this->clean( $_REQUEST );

		if ( ! current_user_can( 'manage_options' ) || wp_verify_nonce( $request_data[ '_nonce' ], 'plausible_analytics_quit_wizard' ) < 1 ) {
			wp_send_json_error( __( 'Not allowed.', 'plausible-analytics' ), 403 );
		}

		update_option( 'plausible_analytics_wizard_done', true );

		if ( ! empty( $request_data[ 'redirect' ] ) ) {
			$url = admin_url( 'options-general.php?page=plausible_analytics' );

			// Redirect param points to a specific option.
			if ( $request_data[ 'redirect' ] !== '1' ) {
				$url .= '#' . $request_data[ 'redirect' ];
			}

			wp_redirect( $url );

			exit;
		}

		wp_send_json_success();
	}

	/**
	 * Clean variables using `sanitize_text_field`.
	 * Arrays are cleaned recursively. Non-scalar values are ignored.
	 * @since  1.3.0
	 * @access public
	 *
	 * @param string|array $var Sanitize the variable.
	 *
	 * @return string|array
	 */
	private function clean( $var ) {
		// If the variable is an array, recursively apply the function to each element of the array.
		if ( is_array( $var ) ) {
			return array_map( [ $this, 'clean' ], $var );
		}

		// If the variable is a scalar value (string, integer, float, boolean).
		if ( is_scalar( $var ) ) {
			// Parse the variable using the wp_parse_url function.
			$parsed = wp_parse_url( $var );
			// If the variable has a scheme (e.g. http:// or https://), sanitize the variable using the esc_url_raw function.
			if ( isset( $parsed[ 'scheme' ] ) ) {
				return esc_url_raw( wp_unslash( $var ), [ $parsed[ 'scheme' ] ] );
			}

			// If the variable does not have a scheme, sanitize the variable using the sanitize_text_field function.
			return sanitize_text_field( wp_unslash( $var ) );
		}

		// If the variable is not an array or a scalar value, return the variable unchanged.
		return $var;
	}

	/**
	 * Removes the plausible_analytics_wizard_done row from the wp_options table, effectively displaying the wizard on next page load.
	 * @return void
	 */
	public function show_wizard() {
		$post_data = $this->clean( $_POST );

		if ( ! current_user_can( 'manage_options' ) || wp_verify_nonce( $post_data[ '_nonce' ], 'plausible_analytics_show_wizard' ) < 1 ) {
			wp_send_json_error( __( 'Not allowed.', 'plausible-analytics' ), 403 );
		}

		delete_option( 'plausible_analytics_wizard_done' );

		wp_send_json_success();
	}

	/**
	 * Save Admin Settings
	 * @since 1.0.0
	 * @return void
	 */
	public function toggle_option() {
		// Sanitize all the post data before using.
		$post_data = $this->clean( $_POST );
		$settings  = Helpers::get_settings();

		if ( ! current_user_can( 'manage_options' ) || wp_verify_nonce( $post_data[ '_nonce' ], 'plausible_analytics_toggle_option' ) < 1 ) {
			wp_send_json_error( __( 'Not allowed.', 'plausible-analytics' ), 403 );
		}

		if ( $post_data[ 'is_list' ] ) {
			/**
			 * Toggle lists.
			 */
			if ( $post_data[ 'toggle_status' ] === 'on' ) {
				if ( ! in_array( $post_data[ 'option_value' ], $settings[ $post_data[ 'option_name' ] ] ) ) {
					$settings[ $post_data[ 'option_name' ] ][] = $post_data[ 'option_value' ];
				}
			} else {
				if ( ( $key = array_search( $post_data[ 'option_value' ], $settings[ $post_data[ 'option_name' ] ] ) ) !== false ) {
					unset( $settings[ $post_data[ 'option_name' ] ][ $key ] );
				}
			}
		} else {
			/**
			 * Single toggles.
			 */
			$settings[ $post_data[ 'option_name' ] ] = $post_data[ 'toggle_status' ];
		}

		// Update all the options to plausible settings.
		update_option( 'plausible_analytics_settings', $settings );

		do_action( 'plausible_analytics_settings_saved' );

		$option_label  = $post_data[ 'option_label' ];
		$toggle_status = $post_data[ 'toggle_status' ] === 'on' ? __( 'enabled', 'plausible-analytics' ) : __( 'disabled', 'plausible-analytics' );

		wp_send_json_success( sprintf( '%s %s.', $option_label, $toggle_status ), 200 );
	}

	/**
	 * Save Options
	 * @return void
	 * @throws ApiException
	 */
	public function save_options() {
		// Sanitize all the post data before using.
		$post_data = $this->clean( $_POST );
		$settings  = Helpers::get_settings();

		if ( ! current_user_can( 'manage_options' ) || wp_verify_nonce( $post_data[ '_nonce' ], 'plausible_analytics_toggle_option' ) < 1 ) {
			wp_send_json_error( __( 'Forbidden', 'plausible-analytics' ), 403 );
		}

		$options = json_decode( $post_data[ 'options' ] );

		if ( empty( $options ) ) {
			wp_send_json_error( __( 'No options found to save.', 'plausible-analytics' ), 400 );
		}

		foreach ( $options as $option ) {
			// Clean spaces
			$settings[ $option->name ] = trim( $option->value );

			// Validate API token, if this is the API token field.
			if ( $option->name === 'api_token' && empty( $settings[ 'self_hosted_domain' ] ) ) {
				$this->validate_api_token( $option->value );
			}
		}

		update_option( 'plausible_analytics_settings', $settings );

		wp_send_json_success( __( 'Settings saved.', 'plausible-analytics' ), 200 );
	}

	/**
	 * Validate the entered API token, before storing it to the DB. wp_send_json_error() ensures that code execution stops.
	 *
	 * @param string $token
	 *
	 * @return void
	 * @throws ApiException
	 */
	private function validate_api_token( $token = '' ) {
		$client = new Client( $token );

		if ( ! $client->validate_api_token() ) {
			Messages::set_error(
				sprintf(
					__(
						'Oops! The API token you used is invalid. Please <a class="plausible-create-api-token hover:cursor-pointer underline">click here</a> to generate a new token.',
						'plausible-analytics'
					),
					''
				)
			);

			wp_send_json_error( __( 'Invalid API token.', 'plausible-analytics' ) );
		}
	}
}
