<?php

namespace Codemanas\InactiveLogout;

class LogoutHandler {

	protected $settings = false;

	protected function __construct() {
		if ( ! wp_doing_ajax() ) {
			add_filter( 'logout_redirect', [ $this, 'logout_redirect' ], 999999999, 3 );
			add_action( 'wp_logout', [ $this, 'wp_logout' ], 999999999 );
		}

		$this->settings = Helpers::getInactiveSettingsData();
	}


	/**
	 * Trigger for frontend logouts
	 *
	 * @param $user_id
	 *
	 * @return void
	 */
	public function wp_logout( $user_id ) {
		$url = $this->logout_redirect( '', '', get_userdata( $user_id ) );
		if ( ! empty( $url ) ) {
			nocache_headers();
			wp_redirect( $url );
			exit;
		}
	}

	/**
	 * Trigger for backend logouts
	 *
	 * @param $redirect_to
	 * @param $requested_redirect_to
	 * @param $current_user
	 *
	 * @return false|mixed|string
	 */
	public function logout_redirect( $redirect_to, $requested_redirect_to, $current_user ) {
		if ( ! empty( $this->settings ) ) {
			$redirect_to = Helpers::getLogoutRedirectPage( $this->settings );
		}

		return $redirect_to;
	}

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

}