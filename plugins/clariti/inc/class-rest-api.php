<?php
/**
 * REST API integration touch-points.
 *
 * @package Clariti
 */

namespace Clariti;

/**
 * REST API integration touch-points.
 */
class REST_API {
	/**
	 * Set up our public REST routes.
	 *
	 * @return void
	 */
	public static function register_routes() {
		register_rest_route(
			'clariti/v1',
			'/verify',
			array(
				'methods'             => \WP_REST_Server::ALLMETHODS,
				'callback'            => array( 'Clariti\REST_API', 'verify_plugin_install' ),
				'permission_callback' => array( 'Clariti\REST_API', 'private_data_permission_check' ),
			)
		);
	}

	/**
	 * Verify we have an open and public API endpoint
	 *
	 * @return true
	 */
	public static function private_data_permission_check() {
		return true;
	}

	/**
	 * Filters the REST API index to include our own data.
	 *
	 * @param WP_REST_Response $response Existing response object.
	 * @return object
	 */
	public static function filter_rest_index( $response ) {
		$types = array();
		foreach ( clariti_get_supported_post_types() as $type ) {
			$post_type = get_post_type_object( $type );
			if ( ! $post_type ) {
				continue;
			}
			$namespace = ! empty( $post_type->rest_namespace ) ? $post_type->rest_namespace : 'wp/v2';
			$rest_base = ! empty( $post_type->rest_base ) ? $post_type->rest_base : $post_type->name;
			$types[]   = array(
				'name'     => $post_type->labels->singular_name,
				'slug'     => $post_type->name,
				'comments' => post_type_supports( $post_type->name, 'comments' ),
				'route'    => '/' . $namespace . '/' . $rest_base,
			);
		}

		$response->data['clariti'] = array(
			'types' => $types,
		);
		return $response;
	}

	/**
	 * API Endpoint for verifying our install
	 *
	 * @param \WP_REST_Request $request WP Request object.
	 *
	 * @return array|\WP_Error
	 * @throws \Exception Bubbles up from the create_jwt call to get the signature.
	 */
	public static function verify_plugin_install( \WP_REST_Request $request ) {

		if ( 'POST' !== $request->get_method() ) {
			wp_send_json(
				array(
					'ok'      => false,
					'error'   => array(
						'message' => 'Method not allowed',
						'code'    => 405,
					),
					'version' => clariti_get_plugin_version(),
				),
				405
			);
		}

		$jwt = $request->get_body();

		// Split the token.
		$token_parts = explode( '.', $jwt );

		if ( count( $token_parts ) !== 3 ) {
			wp_send_json(
				array(
					'ok'      => false,
					'error'   => array(
						'message' => 'Invalid request body',
						'code'    => 400,
					),
					'version' => clariti_get_plugin_version(),
				),
				400
			);
		}

		$header             = base64_decode( $token_parts[0] );
		$payload            = base64_decode( $token_parts[1] );
		$signature_provided = $token_parts[2];

		// Build a signature based on the header and payload using the secret.
		$base_64_url_signature = Notifier::create_jwt( $payload, $header, true );

		// Verify it matches the signature provided in the token.
		$signature_valid = ( $base_64_url_signature === $signature_provided );

		if ( ! $signature_valid ) {
			wp_send_json(
				array(
					'ok'      => false,
					'error'   => array(
						'message' => 'Invalid signature',
						'code'    => 400,
					),
					'version' => clariti_get_plugin_version(),
				),
				400
			);
		}

		$data = json_decode( $payload, true );

		if ( ! is_array( $data ) ) {
			wp_send_json(
				array(
					'ok'      => false,
					'error'   => array(
						'message' => 'Invalid request payload',
						'code'    => 400,
					),
					'version' => clariti_get_plugin_version(),
				),
				400
			);
		}

		if ( empty( $data['key'] ) ) {
			wp_send_json(
				array(
					'ok'      => false,
					'error'   => array(
						'message' => 'API key is missing',
						'code'    => 400,
					),
					'version' => clariti_get_plugin_version(),
				),
				400
			);
		}

		$key = admin::get_api_key();

		return array(
			'ok'      => $key === $data['key'],
			'version' => clariti_get_plugin_version(),
		);
	}
}
