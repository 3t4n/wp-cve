<?php

/**
 * class Captcha
 *
 * @link       https://appcheap.io
 * @since      1.0.0
 * @author     ngocdt
 *
 */

namespace AppBuilder\Api;

defined( 'ABSPATH' ) || exit;

use Gregwar\Captcha\CaptchaBuilder;
use WP_Error;

class Captcha extends Base {

	public function __construct() {
		$this->namespace = constant( 'APP_BUILDER_REST_BASE' ) . '/v1';
		parent::__construct();
	}

	public function register_routes() {
		register_rest_route( $this->namespace, 'captcha/get', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( $this, 'get_item' ),
			'permission_callback' => '__return_true',
		) );

		register_rest_route( $this->namespace, 'captcha/validate', array(
			'methods'             => \WP_REST_Server::CREATABLE,
			'callback'            => array( $this, 'validate' ),
			'permission_callback' => '__return_true',
		) );
	}

	/**
	 *
	 * Get captcha
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function get_item( $request ) {
		$captcha = new CaptchaBuilder();
		$captcha->build();

		$cap_image = $captcha->inline();
		$phrase    = $captcha->getPhrase();
		$key       = uniqid();

		$captcha_store = get_option( 'app_builder_captcha', [] );

		// Clean expired captcha
		foreach ( $captcha_store as $k => $value ) {
			if ( (int) $value['time'] < time() ) {
				unset( $captcha_store[ $k ] );
			}
		}

		$captcha_store[ $key ] = [
			'phrase' => $phrase,
			'time'   => time() + 300, // Captcha expired in 5 Minute
		];

		update_option( 'app_builder_captcha', $captcha_store );

		return rest_ensure_response( [
			'phrase'  => $key,
			'captcha' => $cap_image,
		] );
	}

	/**
	 *
	 * Validate captcha
	 *
	 * @param $request
	 *
	 * @return WP_Error|bool
	 */
	public function validate( $request ) {

		$phrase  = $request->get_param( 'phrase' );
		$captcha = $request->get_param( 'captcha' );

		$validate = true;

		if ( empty( $captcha ) || empty( $phrase ) ) {
			$validate = new WP_Error(
				"app_builder_captcha",
				__( "Captcha or phrase not provider.", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		$captcha_store = get_option( 'app_builder_captcha', [] );

		if ( ! is_wp_error( $validate ) && ! isset( $captcha_store[ $phrase ] ) ) {
			$validate = new WP_Error(
				"app_builder_captcha",
				__( "Phrase not validate.", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		$captcha_data = $captcha_store[ $phrase ];

		if ( ! is_wp_error( $validate ) && strtolower($captcha_data['phrase']) != strtolower($captcha) ) {
			$validate = new WP_Error(
				"app_builder_captcha",
				__( "Captcha not validate.", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		if ( ! is_wp_error( $validate ) && $captcha_data['time'] < time() ) {
			$validate = new WP_Error(
				"app_builder_captcha",
				__( "Captcha expired.", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		if ( is_wp_error( $validate ) ) {
			unset( $captcha_store[ $phrase ] );
			update_option( 'app_builder_captcha', $captcha_store );
		}

		return $validate;
	}
}
