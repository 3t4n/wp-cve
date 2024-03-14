<?php
/**
 * Main plugin class.
 *
 * @since   1.0.0
 * @package EasyCloudflareTurnstile
 */

namespace EasyCloudflareTurnstile;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Manages general turnstile integrations.
 *
 * @since 1.0.1
 */
class Helpers {

	/**
	 * Validates turnstile
	 *
	 * @param string $token   The generated turnstile token.
	 *
	 * @return array|WP_Error
	 */
	public function validate_turnstile( $token )
	{
		$secret_key = wp_turnstile()->settings->get( 'secret_key' );

		$response = wp_remote_post(
			wp_turnstile()->verification_url,
			[
				'body' => [
					'secret'   => $secret_key,
					'response' => $token,
				],
			],
		);

		return json_decode( wp_remote_retrieve_body( $response ), true );
	}

	/**
	 * Activate plugin by slug name.
	 *
	 * @param string $slug Plugin slug name.
	 * @return mixed
	 */
	public function activate_plugin( $slug )
	{
		if ( ! function_exists( 'activate_plugin' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}

		if ( ! is_plugin_active( $slug ) ) {
			activate_plugin( $slug );

			wp_send_json_success([
				'message' => __( 'Plugin activated successfully', 'wppool-turnstile' ),
			]);
		}

		wp_send_json_error([
			'message' => __( 'Plugin could not active, error occurred.', 'wppool-turnstile' ),
		]);
	}

	/**
	 * Accept all tags.
	 *
	 * @param string $allowed Allowed HTML tag.
	 * @return mixed
	 */
	public function allow_all_tags( $allowed )
	{
		$allowed['*'] = true;
		return $allowed;
	}
}