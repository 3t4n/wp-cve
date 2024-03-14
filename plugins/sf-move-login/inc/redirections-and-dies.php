<?php
defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

/*------------------------------------------------------------------------------------------------*/
/* !REMOVE DEFAULT WORDPRESS REDIRECTIONS TO LOGIN AND ADMIN AREAS ============================== */
/*------------------------------------------------------------------------------------------------*/

/**
 * WordPress redirects some URLs (`wp-admin`, `dashboard`, `admin`) to the administration area,
 * and some others (`wp-login.php`, `login`) to the login page.
 * We don't want that, so we remove the hook.
 */
remove_action( 'template_redirect', 'wp_redirect_admin_locations', 1000 );


add_filter( 'rewrite_rules_array', 'sfml_remove_rewrite_rules' );
/**
 * Filter the full set of generated rewrite rules.
 *
 * @since 2.4
 *
 * @param (array) $rules The compiled array of rewrite rules.
 *
 * @return (array)
 */
function sfml_remove_rewrite_rules( $rules ) {
	if ( ! is_multisite() ) {
		unset( $rules['.*wp-register.php$'] );
	}
	return $rules;
}


/*------------------------------------------------------------------------------------------------*/
/* !DENY ACCESS TO THE FORM ===================================================================== */
/*------------------------------------------------------------------------------------------------*/

add_action( 'login_init', 'sfml_maybe_deny_login_page', 0 );
/**
 * When displaying the login page, if the URL does not matches those in our settings, deny access.
 * Does nothing if the user is logged in.
 */
function sfml_maybe_deny_login_page() {
	// If the user is logged in, do nothing, lets WP redirect this user to the administration area.
	if ( is_user_logged_in() ) {
		return;
	}

	$uri    = sfml_get_current_url( 'uri' );
	$wp_dir = sfml_get_wp_directory();
	$slugs  = sfml_get_slugs();

	if ( $wp_dir ) {
		foreach ( $slugs as $action => $slug ) {
			$slugs[ $action ] = $wp_dir . $slug;
		}
	}

	/**
	 * If you want to display the login form somewhere outside wp-login.php, add your URIs here.
	 *
	 * @param (array)  $new_slugs An array of action => URIs (WP directory + slugs).
	 * @param (string) $uri    The current URI.
	 * @param (string) $wp_dir Path to WordPress.
	 * @param (array)  $slugs  URIs already in use.
	 */
	$new_slugs = apply_filters( 'sfml_slugs_not_to_kill', array(), $uri, $wp_dir, $slugs );
	$slugs     = is_array( $new_slugs ) && ! empty( $new_slugs ) ? array_merge( $new_slugs, $slugs ) : $slugs;
	$slugs     = array_flip( $slugs );

	if ( isset( $slugs[ $uri ] ) ) {
		// Display the login page.
		if ( ! defined( 'DONOTCACHEPAGE' ) ) {
			// Tell cache plugins not to cache the login page.
			define( 'DONOTCACHEPAGE', true );
		}
		return;
	}

	// You shall not pass!
	sfml_deny_login_access();
}


/**
 * Perform the action set for the login page: die or redirect.
 */
function sfml_deny_login_access() {
	/**
	 * If you want to trigger a custom action (redirect, message, die...), add it here.
	 * Don't forget to exit/die.
	 */
	do_action( 'sfml_wp_login_error' );

	$do = sfml_get_deny_wp_login_access();

	switch ( $do ) {
		case 2:
			wp_redirect( esc_url_raw( sfml_get_404_error_url() ) );
			exit;
		case 3:
			wp_redirect( esc_url_raw( user_trailingslashit( home_url() ) ) );
			exit;
		case 4:
			sfml_maybe_trigger_404_error();
			// Fallback in case headers were already sent.
			wp_redirect( esc_url_raw( sfml_get_404_error_url() ) );
			exit;
		default:
			wp_die( __( 'No no no, the login form is not here.', 'sf-move-login' ), __( 'Nope :)', 'sf-move-login' ), array( 'response' => 403 ) );
	}
}


/*------------------------------------------------------------------------------------------------*/
/* !DO NOT REDIRECT TO THE NEW LOGIN PAGE ======================================================= */
/*------------------------------------------------------------------------------------------------*/

add_filter( 'wp_redirect', 'sfml_maybe_deny_login_redirect', 1 );
/**
 * Filters the redirect location.
 * When a logged out user is being redirected to the new login page, deny access.
 * Does nothing if the user is logged in.
 *
 * @since 2.5
 *
 * @param (string) $location The path to redirect to.
 *
 * @return (string)
 */
function sfml_maybe_deny_login_redirect( $location ) {
	global $pagenow;

	if ( 'wp-login.php' === $pagenow ) {
		return $location;
	}

	if ( is_user_logged_in() ) {
		return $location;
	}

	if ( wp_get_referer() === $location ) {
		return $location;
	}

	if ( sfml_cache_data( 'allow_redirection' ) ) {
		// This can be used by 3rd party plugins.
		sfml_cache_data( 'allow_redirection', null );
		return $location;
	}

	$slugs  = sfml_get_slugs();
	$wp_dir = sfml_get_wp_directory();

	if ( sfml_is_subfolder_install() ) {
		$base  = wp_parse_url( trailingslashit( sfml_get_main_url() ) );
		$base  = ltrim( $base['path'], '/' );
		$base .= $wp_dir ? '[_0-9a-zA-Z-]+/' : '([_0-9a-zA-Z-]+/)?';
	} else {
		$base  = wp_parse_url( trailingslashit( get_option( 'home' ) ) );
		$base  = ltrim( $base['path'], '/' );
		$base .= $wp_dir ? ltrim( $wp_dir, '/' ) : '';
	}

	$regex  = '^' . $base . '(' . implode( '|', $slugs ) . ')$';
	$parsed = wp_parse_url( $location );
	$parsed = ! empty( $parsed['path'] ) ? $parsed['path'] : '';
	$parsed = trim( $parsed, '/' );

	if ( ! preg_match( "@{$regex}@", $parsed ) ) {
		return $location;
	}

	$redirect = false;
	/**
	 * Filters the redirection location.
	 * You can also trigger a custom action, like a `wp_die()` with a custom message.
	 *
	 * @since 2.5
	 *
	 * @param (string|bool) $redirect A custom URL to redirect to. Default is false.
	 */
	$redirect = apply_filters( 'sfml_login_redirect_location', $redirect );

	if ( $redirect ) {
		return esc_url_raw( $redirect );
	}

	do_action_deprecated( 'sfml_wp_admin_error', array(), '2.5', 'sfml_login_redirect_location' );

	$do = sfml_get_deny_admin_access();

	switch ( $do ) {
		case 1:
			wp_die( __( 'Cheatin&#8217; uh?', 'sf-move-login' ), __( 'Nope :)', 'sf-move-login' ), array( 'response' => 403 ) );
		case 2:
			return esc_url_raw( sfml_get_404_error_url() );
		case 3:
			return esc_url_raw( user_trailingslashit( home_url() ) );
		case 4:
			sfml_maybe_trigger_404_error();
			// Fallback in case headers were already sent.
			return esc_url_raw( sfml_get_404_error_url() );
		default:
			return $location;
	}
}




/*------------------------------------------------------------------------------------------------*/
/* !TOOLS ======================================================================================= */
/*------------------------------------------------------------------------------------------------*/

/**
 * Trigger a 404 error if headers have not been sent yet.
 * Be aware that if the headers have been sent, the request won't be killed: provide a fallback!
 *
 * @since 2.5.2
 */
function sfml_maybe_trigger_404_error() {
	if ( headers_sent() ) {
		return;
	}

	status_header( 404 );

	$headers = wp_get_nocache_headers();
	$headers['Content-Type'] = get_option( 'html_type' ) . '; charset=' . get_option( 'blog_charset' );

	foreach ( $headers as $name => $field_value ) {
		@header( "{$name}: {$field_value}" );
	}

	exit;
}


/**
 * Get the URL of the "WordPress" 404 error page we'll redirect to.
 *
 * @since 2.5.2
 *
 * @return (string) A URL.
 */
function sfml_get_404_error_url() {
	global $wp_rewrite;

	if ( $wp_rewrite && $wp_rewrite->using_permalinks() ) {
		$redirect = user_trailingslashit( home_url( '404' ) );
	} else {
		$redirect = add_query_arg( 'p', '404', user_trailingslashit( home_url() ) );
	}
	/**
	 * Filter the 404 page URL.
	 *
	 * @param (string) $redirect An URL that leads to a 404 response.
	 */
	return apply_filters( 'sfml_404_error_page', $redirect );
}
