<?php

/**
 * class UserHook
 *
 * @link       https://appcheap.io
 * @author     ngocdt
 * @since      1.4.0
 *
 */

namespace AppBuilder\Hooks;

use AppBuilder\Token;
use WP_User;

defined( 'ABSPATH' ) || exit;

class UserHook {

	private Token $tokenObj;

	public function __construct() {

		$this->tokenObj = new Token();

		add_filter( 'app_builder_prepare_userdata', array( $this, 'prepare_user_data' ) );
		/**
		 * Add token to URL after login redirect
		 */
		add_filter( 'login_redirect', array( $this, 'login_redirect' ), 100, 3 );
		add_filter( 'woocommerce_login_redirect', array( $this, 'woocommerce_login_redirect' ), 100, 2 );

		/**
		 * Handle redirect URL after user checkout (Paid memberships pro) plugin
		 */
		add_filter( 'pmpro_confirmation_url', array( $this, 'pmpro_confirmation_url' ), 10, 3 );
	}

	/**
	 * This filter changes the URL to redirect to on confirmation.
	 *
	 * @param $url
	 * @param $user_id
	 * @param $pmpro_level
	 *
	 * @return mixed|string
	 */
	public function pmpro_confirmation_url( $url, $user_id, $pmpro_level ) {
		$user = wp_get_current_user();

		if ( 0 != $user->ID ) {
			return $this->woocommerce_login_redirect( $url, $user );
		}

		return $url;
	}

	/**
	 * Add token to URL after WP login redirect
	 *
	 * @param $url
	 * @param $request
	 * @param $user
	 *
	 * @return mixed|string
	 */
	function login_redirect( $url, $request, $user ) {
		return $this->woocommerce_login_redirect( $url, $user );
	}

	/**
	 * Add token to URL after WC login redirect
	 *
	 * @param $url
	 * @param $user
	 *
	 * @return mixed|string
	 */
	public function woocommerce_login_redirect( $url, $user ) {
		if ( str_contains( $_SERVER["HTTP_USER_AGENT"], 'Cirilla' ) && is_object( $user ) && is_a( $user, 'WP_User' ) ) {
			$q = str_contains( $url, '?' ) ? '&' : '?';

			return $url . $q . http_build_query( [ 'cirilla-token' => $this->tokenObj->sign_token( $user->ID ) ] );
		}

		return $url;
	}

	/**
	 *
	 * Prepare user data before response via rest api
	 *
	 * @param $user \WP_User
	 *
	 * @return mixed
	 * @since 1.0.0
	 * @author ngocdt
	 *
	 */
	public function prepare_user_data( $user ) {

		// Extra user data
		$user_data = $user->data;

		// First name and LastName
		$user_data->first_name = $user->first_name;
		$user_data->last_name  = $user->last_name;
		$user_data->user_pass  = '';

		// Avatar
		$avatar = apply_filters( 'app_builder_prepare_avatar_data', $user_data->ID, [] );
		$sizes  = rest_get_avatar_sizes();

		$user_data->avatar_urls = $avatar;
		$user_data->avatar      = $avatar[ end( $sizes ) ];

		// Get current user location save from app
		$user_data->location = get_user_meta( $user->ID, 'app_builder_location', true );
		// Get roles
		$user_data->roles = array_values( $user->roles );
		// Social Avatar
		$user_data->social_avatar = get_user_meta( $user->ID, 'app_builder_login_avatar', true );
		$user_data->login_type    = get_user_meta( $user->ID, 'app_builder_login_type', true );

		/**
		 * User options
		 */
		$options            = [
			"hideAds" => apply_filters( 'app_builder_prepare_hide_ads', false, $user ),
		];
		$user_data->options = apply_filters( 'app_builder_prepare_user_options', $options, $user );

		return $user_data;
	}
}