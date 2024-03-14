<?php
/**
 * @author  CodeFlavors
 * @project codeflavors-vimeo-video-post-lite
 */

namespace Vimeotheque\Rest_Api\Endpoints\Plugin;

use Vimeotheque\Plugin;
use Vimeotheque\Rest_Api\Endpoints\Rest_Controller_Abstract;
use Vimeotheque\Rest_Api\Endpoints\Rest_Controller_Interface;
use Vimeotheque\Vimeo_Api\Vimeo_Oauth;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Rest_Store_Settings
	extends Rest_Controller_Abstract
	implements Rest_Controller_Interface {

	public function __construct(){
		parent::set_namespace( 'vimeotheque/v1');
		parent::set_rest_base( '/plugin/settings');
		$this->register_routes();
	}

	/**
	 * @inheritDoc
	 */
	public function get_response( \WP_REST_Request $request ) {
		$options = $this->process_options(
			Plugin::instance()->get_options_obj()->get_defaults(),
			Plugin::instance()->get_options_obj()->get_options(),
			$request->get_params()
		);
		// Update the options
		Plugin::instance()->get_options_obj()->update_options( $options );

		$player_options = $this->process_options(
			Plugin::instance()->get_embed_options_obj()->get_defaults(),
			Plugin::instance()->get_embed_options_obj()->get_options(),
			$request->get_params()
		);
		// Update the player embed options
		Plugin::instance()->get_embed_options_obj()->update_options( $player_options );

		// Refresh the options.
		$options = Plugin::instance()->get_options_obj()->get_options(true);

		$settings_page_url = sprintf(
			'edit.php?post_type=%s&page=%s',
			Plugin::instance()->get_cpt()->get_post_type(),
			'cvm_settings'
		);

		$oauth = new Vimeo_Oauth(
			$options['vimeo_consumer_key'],
			$options['vimeo_secret_key'],
			$options['oauth_token'],
			admin_url( $settings_page_url )
		);

		$success = false;
		$message = '';

		if( !empty( $options['vimeo_consumer_key'] ) && !empty( $options['vimeo_secret_key'] ) ){
			if( empty( $options['oauth_token'] ) || ( isset( $options['oauth_secret'] ) && empty( $options['oauth_secret'] ) ) ){
				$token = $oauth->get_unauth_token();

				if( !is_wp_error( $token ) ){
					$options['oauth_token'] = $token;
					$success = true;
					$message = __( 'Your Vimeo keys are successfully installed. You can now query public videos on Vimeo and import them as WordPress posts.', 'codeflavors-vimeo-video-post-lite' );
				}else{
					$options['vimeo_consumer_key'] = '';
					$options['vimeo_secret_key'] = '';
					$options['oauth_token'] = '';
					$message = $token->get_error_message();
				}

				Plugin::instance()->get_options_obj()->update_options( $options );
			}
		}else{
			$message = __( 'In order to be able to query Vimeo you must enter your Vimeo OAuth client and secret key.', 'codeflavors-vimeo-video-post-lite' );
		}

		wp_send_json([
			'success' => $success,
			'message' => $message
		]);
	}

	/**
	 * Process options.
	 *
	 * @param array $defaults       The default options.
	 * @param array $options        The existing options.
	 * @param array $new_options    The new options.
	 *
	 * @return array    The processed options.
	 */
	private function process_options( $defaults, $options, $new_options ){
		foreach ( $defaults as $index => $value ) {
			if( isset( $new_options[ $index ] ) ){
				switch( gettype( $value ) ){
					case 'boolean':
						$options[ $index ] = (bool) $new_options[ $index ];
					break;
					case 'integer':
						$options[ $index ] = (int) $new_options[ $index ];
					break;
					default:
						$options[ $index ] = trim( (string) $new_options[ $index ] );
					break;
				}
			}
		}

		return $options;
	}

	/**
	 * @inheritDoc
	 */
	public function register_routes() {
		register_rest_route(
			parent::get_namespace(),
			parent::get_rest_base(),
			[
				'methods' => \WP_REST_Server::CREATABLE,
				'callback' => [$this, 'get_response'],
				'permission_callback' => function(){
					return current_user_can( 'manage_options' );
				},
				'args' => [

				]
			]
		);
	}
}