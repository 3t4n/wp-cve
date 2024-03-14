<?php
defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );


/**
 * When a logged out user tries to access the admin area, deny access.
 * Does nothing if the user is logged in.
 * `admin-post.php` and `admin-ajax.php` are white listed.
 *
 * @since 2.5 Deprecated. Was hooked on 'after_setup_theme'.
 */
function sfml_maybe_deny_admin_redirect() {
	global $pagenow;

	_deprecated_function( __FUNCTION__, '2.5', 'sfml_maybe_deny_login_redirect' );

	// If it's not the administration area, or if it's an ajax call, no need to go further.
	if ( ! ( is_admin() && ! ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( 'admin-post.php' === $pagenow && ! empty( $_REQUEST['action'] ) ) ) ) ) { // WPCS: CSRF ok.
		return;
	}

	if ( is_user_admin() ) {
		$scheme = 'logged_in';
	} else {
		/** This filter is documented in wp-includes/pluggable.php */
		$scheme = apply_filters( 'auth_redirect_scheme', '' );
	}

	if ( wp_validate_auth_cookie( '', $scheme ) ) {
		return;
	}

	// Nice try. But no.
	sfml_deny_login_redirect();
}


/**
 * When a logged out user tries to access `wp-signup.php` or `wp-register.php`, deny access.
 * Does nothing if the user is logged in.
 * Does nothing in multisite.
 *
 * @since 2.5 Deprecated. Was hooked on 'register_url'.
 *
 * @param (string) $url The URL.
 *
 * @return (string) The same URL.
 */
function sfml_maybe_die_on_signup_page( $url ) {
	_deprecated_function( __FUNCTION__, '2.5', 'sfml_maybe_deny_login_redirect' );

	if ( empty( $_SERVER['REQUEST_URI'] ) ) {
		return $url;
	}
	if ( false === strpos( $_SERVER['REQUEST_URI'], '/wp-signup.php' ) && false === strpos( $_SERVER['REQUEST_URI'], '/wp-register.php' ) ) {
		return $url;
	}
	if ( is_multisite() || is_user_logged_in() ) {
		return $url;
	}

	// Nope!
	sfml_deny_login_redirect();
}


/**
 * Perform the action set for redirections to login page: die or redirect.
 *
 * @since 2.5 Deprecated.
 */
function sfml_deny_login_redirect() {
	_deprecated_function( __FUNCTION__, '2.5', 'sfml_maybe_deny_login_redirect' );

	/**
	 * If you want to trigger a custom action (redirect, message, die...), add it here.
	 * Don't forget to exit/die.
	 */
	do_action( 'sfml_wp_admin_error' );

	$do = sfml_get_deny_admin_access();

	switch ( $do ) {
		case 1:
			wp_die( __( 'Cheatin&#8217; uh?' ), __( 'Nope :)', 'sf-move-login' ), array( 'response' => 403 ) );
		case 2:
			$redirect = $GLOBALS['wp_rewrite']->using_permalinks() ? home_url( '404' ) : add_query_arg( 'p', '404', home_url() );
			/** This filter is documented in inc/redirections-and-dies.php */
			$redirect = apply_filters( 'sfml_404_error_page', $redirect );
			wp_redirect( esc_url_raw( user_trailingslashit( $redirect ) ) );
			exit;
		case 3:
			wp_redirect( esc_url_raw( user_trailingslashit( home_url() ) ) );
			exit;
	}
}
