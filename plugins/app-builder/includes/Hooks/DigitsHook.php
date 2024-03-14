<?php

/**
 * class DigitsHook
 *
 * @link       https://appcheap.io
 * @author     ngocdt
 * @since      1.4.0
 *
 */

namespace AppBuilder\Hooks;

use AppBuilder\Token;
use WP_Error;

defined( 'ABSPATH' ) || exit;

class DigitsHook {

	private Token $tokenObj;

	public function __construct() {

		$this->tokenObj = new Token();

		/**
		 * Filter token digits
		 * @since 1.4.0
		 */
		add_filter( 'digits_rest_token_data', array( $this, 'digits_rest_token_data' ), 10, 2 );
	}

	/**
	 * Change the way encode token
	 *
	 * @author Ngoc Dang
	 * @since 1.4.0
	 */
	public function digits_rest_token_data( $_data, $user_id ) {
		// Get user info
		$user = get_user_by( 'id', $user_id );

		if ( $user ) {

			// Generate token
			$token = $this->tokenObj->sign_token( $user_id, [] );

			// Filter user data before response
			$pre_user_data = apply_filters( 'app_builder_prepare_userdata', $user );

			return array(
				'token' => $token,
				'user'  => $pre_user_data,
			);
		} else {
			return new WP_Error(
				404,
				__( 'Something wrong!.', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}
	}
}
