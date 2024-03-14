<?php

namespace WPRuby_CAA\Core\Features;

use WPRuby_CAA\Core\Constants;
use WPRuby_CAA\Core\Dto\Settings;
use WPRuby_CAA\Core\Dto\User;

class Login_Controller {

	protected static $_instance = null;

	public static function boot() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		add_action( 'wp_login', [ $this, 'check_expired_user_on_login' ], 20, 2 );
		add_action( 'wp_login', [ $this, 'user_last_login' ], 30, 2 );
		add_action( 'login_message', [ $this, 'user_login_message' ], 10, 2 );
		add_action( 'clear_auth_cookie', [ $this, 'set_user_is_logged_in' ] );
		add_action( 'admin_init', [ $this, 'log_user_is_logged_in' ] );
		add_filter( 'login_redirect', [ $this, 'redirect_to_first_accessible_page' ], 10, 3 );
	}

	public function user_last_login( $user_login, $user ) {
		update_user_meta( $user->ID, Constants::USER_LAST_LOGIN, time() );
		update_user_meta( $user->ID, Constants::USER_IS_LOGGED_IN, true );
	}

	public function log_user_is_logged_in() {
		update_user_meta( get_current_user_id(), Constants::USER_IS_LOGGED_IN, true );
	}

	public function check_expired_user_on_login( $user_login, $user = null ) {
		if ( ! $user ) {
			$user = get_user_by( 'login', $user_login );
		}


		if ( ! $user ) {
			return;
		}

		$caa_user = new User( $user->ID );

		if ( ! $caa_user->isCaaAccount() ) {
			return;
		}


		if ( $caa_user->isDeactivated() ) {
			$this->logUserOut();
		}

		if ( $caa_user->isExpired() ) {
			$this->logUserOut( 'expired' );
		}

	}


	public function user_login_message( $message ) {

		if ( isset( $_GET['disabled'] ) && $_GET['disabled'] == 1 ) {
			return '<div id="login_error">' . __( 'Account disabled', 'controlled-admin-access' ) . '</div>';
		}

		if ( isset( $_GET['expired'] ) && $_GET['expired'] == 1 ) {
			return '<div id="login_error">' . __( 'Account expired', 'controlled-admin-access' ) . '</div>';
		}

		return $message;
	}

	public function set_user_is_logged_in() {
		update_user_meta( get_current_user_id(), Constants::USER_IS_LOGGED_IN, false );
	}

	public function redirect_to_first_accessible_page( $redirect_to, $request, $user ) {

		if ( ! $user instanceof \WP_User ) {
			return $redirect_to;
		}

		$user = new User($user->ID);

		$not_allowed_pages = $user->getRestrictedMenu();

		if ( count( $not_allowed_pages ) === 0 ) {
			return $redirect_to;
		}

		$settings = new Settings();
		$redirect_to_setting = $settings->get_redirect_after_login_to();

		if (!in_array($redirect_to_setting, $not_allowed_pages)) {
			return admin_url($redirect_to_setting);
		}

		$all_pages = get_option( Constants::CAA_ALL_MENU_SLUGS, [] );

		if ( empty( $all_pages ) ) {
			return $redirect_to;
		}

		$all_pages_slugs = array_map(function ($page) {
			return $page['slug'];
		}, $all_pages);

		$allowed_pages = array_values( array_diff( $all_pages_slugs, $not_allowed_pages ) );

		if ( empty( $allowed_pages ) ) {
			return $redirect_to;
		}

		$first_page = $allowed_pages[0];
		if ( strpos( strtolower( $first_page ), '.php' ) === false ) {
			return admin_url( '?page=' . $first_page );
		}

		return admin_url( $allowed_pages[0] );

	}

	private function logUserOut( $key = 'disabled' ) {
		wp_clear_auth_cookie();
		// Build login URL and then redirect
		$login_url = site_url( 'wp-login.php', 'login' );
		$login_url = add_query_arg( $key, '1', $login_url );
		wp_redirect( $login_url );
		exit;
	}
}
