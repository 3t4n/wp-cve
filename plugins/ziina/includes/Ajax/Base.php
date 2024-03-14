<?php
/**
 * Base Ajax class
 *
 * @package ZiinaPayment
 */

namespace ZiinaPayment\Ajax;

defined( 'ABSPATH' ) || exit();

/**
 * Base Ajax
 *
 * @package ZiinaPayment
 * @since   1.0.0
 */
abstract class Base {
	/**
	 * Prefix for actions
	 *
	 * @var string
	 */
	const PREFIX = '';

	/**
	 * Actions for wc api (registration with prefix)
	 *
	 * @var array
	 */
	const ACTIONS = array();

	/**
	 * Ajax Account constructor
	 */
	public function __construct() {
		foreach ( static::ACTIONS as $action ) {
			add_action( 'woocommerce_api_' . static::PREFIX . '_' . $action, array( $this, $action ), 10, 0 );
		}
	}

	/**
	 * Returns wc api url by short action name
	 *
	 * @param string $short_name      short action name.
	 * @param array  $additional_args additional url arguments.
	 * @param bool   $add_nonce       add nonce to url.
	 *
	 * @return false|string
	 */
	public static function get_action_url( string $short_name, array $additional_args = array(), bool $add_nonce = true ) {
		$action = static::get_action_name( $short_name );

		if ( empty( $action ) ) {
			return false;
		}

		$request_url = WC()->api_request_url( $action );

		if ( ! empty( $additional_args ) ) {
			$request_url = add_query_arg(
				$additional_args,
				$request_url
			);
		}

		if ( $add_nonce ) {
			$request_url = add_query_arg(
				array(
					'_wpnonce' => wp_create_nonce( static::get_action_name( $short_name ) ),
				),
				$request_url
			);
		}

		return $request_url;
	}

	/**
	 * Returns wc api action name by short action name
	 *
	 * @param  string $short_name  short action name.
	 *
	 * @return false|string
	 */
	public static function get_action_name( string $short_name ) {
		if ( in_array( $short_name, static::ACTIONS, true ) ) {
			return static::PREFIX . '_' . $short_name;
		}

		return false;
	}

	/**
	 * Verify nonce in $_GET array
	 *
	 * @param  string $function_name  function (action) name to verify. Use __FUNCTION__ to get right function name.
	 *
	 * @return void
	 */
	public static function verify_nonce( string $function_name = '' ) {
		if ( ! is_user_logged_in() ) {
			wp_die( esc_html__( 'You must be logged in', 'ziina' ) );
		}

		$nonce = sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ?? '' ) );

		$verified = wp_verify_nonce( $nonce, static::get_action_name( $function_name ) );

		if ( ! $verified ) {
			wp_die( esc_html__( 'Action failed. Please try again.', 'ziina' ) );
		}
	}
}
