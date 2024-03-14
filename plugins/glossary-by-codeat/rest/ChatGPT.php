<?php
/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */

namespace Glossary\Rest;

use Glossary\Engine;
use Orhanerday\OpenAi\OpenAi;

/**
 * ChatGPT endpoints REST
 */
class ChatGPT extends Engine\Base {

	/**
	 * Initialize the class.
	 *
	 * @return bool
	 */
	public function initialize() {
		parent::initialize();

		\add_action( 'rest_api_init', array( $this, 'add_routes' ) );

		return true;
	}

	/**
	 * Our Rest endpoint
	 *
	 * @return void
	 */
	public function add_routes() {
		\register_rest_route(
			'wp/v2',
			'glossary/generate',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'openai_generate' ),
				'args'                => array(
					'prompt' => array(
						'default'  => 0,
						'required' => true,
					),
				),
				'permission_callback' => function () {
					return current_user_can( 'edit_glossaries' ); // phpcs:ignore WordPress.WP.Capabilities.Unknown
				},
			)
		);
	}

	/**
	 * Generate the term content with OpenAI
	 *
	 * @param \WP_REST_Request $request Values.
	 * @return \WP_REST_Response|\WP_REST_Request|\WP_Error
	 */
	public function openai_generate( \WP_REST_Request $request ) { // phpcs:ignore
		$settings = \gl_get_settings_extra();

		if ( !\wp_verify_nonce( \strval( $request['nonce'] ), 'generate_nonce' ) ) {
			$response = \rest_ensure_response( __( 'Invalid nonce', GT_TEXTDOMAIN ) );

			if ( \is_wp_error( $response ) ) {
				return $response;
			}

			$response->set_status( 500 );

			return $response;
		}

		$error = '';

		if ( isset( $settings['openai_key'] ) && !empty( $request[ 'prompt' ] ) ) {
			$open_ai = new OpenAi( $settings['openai_key'] );
			$message = $open_ai->chat(
				array(
					'model'             => $settings['openai_model'],
					'messages'          => array(
						array(
							'role'    => 'user',
							'content' => $request[ 'prompt' ],
						),
					),
					'temperature'       => \intval( $settings['openai_temperature'] ),
					'max_tokens'        => 4000,
					'frequency_penalty' => 0,
					'presence_penalty'  => 0,
				)
			);
			$message = json_decode( \strval( $message ) );

			if ( is_object( $message ) ) {
				if ( isset( $message->error ) ) {
					$error = $message->error->message;
				}

				if ( is_array( $message->choices ) ) {
					return \rest_ensure_response( $message->choices[0]->message->content );
				}
			}
		}

		$response = \rest_ensure_response( __( 'Request not accepted: ', GT_TEXTDOMAIN ) . $error );

		if ( \is_wp_error( $response ) ) {
			return $response;
		}

		$response->set_status( 500 );

		return $response;
	}

}
