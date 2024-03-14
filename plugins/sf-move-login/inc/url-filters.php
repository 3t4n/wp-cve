<?php
defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

/*------------------------------------------------------------------------------------------------*/
/* !FILTER URLS ================================================================================= */
/*------------------------------------------------------------------------------------------------*/

add_filter( 'site_url', 'sfml_site_url', 10, 4 );
/**
 * Filter the site URL.
 *
 * @param (string)      $url     The complete site URL including scheme and path.
 * @param (string)      $path    Path relative to the site URL. Blank string if no path is specified.
 * @param (string|null) $scheme  Scheme to give the site URL context. Accepts 'http', 'https', 'login',
 *                               'login_post', 'admin', 'relative' or null.
 * @param (int|null)    $blog_id Blog ID, or null for the current blog.
 *
 * @return (string) The site URL.
 */
function sfml_site_url( $url, $path, $scheme, $blog_id = null ) {
	if ( ! empty( $path ) && is_string( $path ) && false === strpos( $path, '..' ) && 0 === strpos( ltrim( $path, '/' ), 'wp-login.php' ) ) {
		$blog_id = (int) $blog_id;

		// Base url.
		if ( empty( $blog_id ) || get_current_blog_id() === $blog_id || ! is_multisite() ) {
			$url = get_option( 'siteurl' );
		} else {
			$url = get_blog_option( $blog_id, 'siteurl' );
		}

		$url = set_url_scheme( $url, $scheme );
		return rtrim( $url, '/' ) . sfml_set_path( $path );
	}

	return $url;
}


add_filter( 'network_site_url', 'sfml_network_site_url', 10, 3 );
/**
 * Filter the network site URL: don't use `network_site_url()` for the login URL ffs!
 *
 * @param (string)      $url    The complete network site URL including scheme and path.
 * @param (string)      $path   Path relative to the network site URL. Blank string if
 *                              no path is specified.
 * @param (string|null) $scheme Scheme to give the URL context. Accepts 'http', 'https',
 *                              'relative' or null.
 *
 * @return (string) The network site URL.
 */
function sfml_network_site_url( $url, $path, $scheme ) {
	if ( ! empty( $path ) && is_string( $path ) && false === strpos( $path, '..' ) && 0 === strpos( ltrim( $path, '/' ), 'wp-login.php' ) ) {
		return site_url( $path, $scheme );
	}

	return $url;
}


add_filter( 'logout_url', 'sfml_logout_url', 1 );
/**
 * Filter the logout URL: `wp_logout_url()` add the action param after using `site_url()`.
 *
 * @since 1.0
 *
 * @param (string) $logout_url The Log Out URL.
 *
 * @return (string) The Log Out URL.
 */
function sfml_logout_url( $logout_url ) {
	return sfml_login_to_action( $logout_url, 'logout' );
}


add_filter( 'lostpassword_url', 'sfml_lostpassword_url', 1 );
/**
 * Filter the Lost Password URL: `wp_lostpassword_url()` add the action param after using `network_site_url()`.
 *
 * @since 1.0
 *
 * @param (string) $lostpassword_url The lost password page URL.
 *
 * @return (string) The lost password page URL.
 */
function sfml_lostpassword_url( $lostpassword_url ) {
	return sfml_login_to_action( $lostpassword_url, 'lostpassword' );
}


add_filter( 'wp_redirect', 'sfml_redirect' );
/**
 * Filter the redirect location: some redirections are hard-coded.
 *
 * @since 1.0
 *
 * @param (string) $location The path to redirect to.
 *
 * @return (string) The path to redirect to.
 */
function sfml_redirect( $location ) {
	$base_uri = explode( '?', $location );
	$base_uri = reset( $base_uri );

	if ( site_url( $base_uri ) === site_url( 'wp-login.php' ) ) {
		return sfml_site_url( $location, $location, 'login', get_current_blog_id() );
	}

	return $location;
}


add_filter( 'update_welcome_email', 'sfml_update_welcome_email', 10, 2 );
/**
 * Multisite: filter the content of the welcome email after site activation.
 *
 * @since 1.0
 *
 * @param (string) $welcome_email Message body of the email.
 * @param (int)    $blog_id       Blog ID.
 *
 * @return (string) Message body of the email.
 */
function sfml_update_welcome_email( $welcome_email, $blog_id ) {
	if ( false === strpos( $welcome_email, 'wp-login.php' ) ) {
		return $welcome_email;
	}

	$url = get_blogaddress_by_id( $blog_id );

	switch_to_blog( $blog_id );
	$login_url = wp_login_url();
	restore_current_blog();

	return str_replace( $url . 'wp-login.php', $login_url, $welcome_email );
}


/*------------------------------------------------------------------------------------------------*/
/* !UTILITIES =================================================================================== */
/*------------------------------------------------------------------------------------------------*/

/**
 * Set the relative path: `wp-login.php?action=register -> /register`.
 *
 * @param (string) $path Path relative to the site URL.
 *
 * @return (string) The new path relative to the site URL, with our custom slug.
 */
function sfml_set_path( $path ) {
	$slugs = sfml_get_slugs();
	$other = SFML_Options::get_instance()->get_other_actions();

	// Get the action.
	$parsed_path = wp_parse_url( $path );

	if ( ! empty( $parsed_path['query'] ) ) {
		wp_parse_str( $parsed_path['query'], $params );
		$action = ! empty( $params['action'] ) ? $params['action'] : 'login';

		if ( isset( $params['key'] ) ) {
			$action = 'resetpass';
		}

		if ( ! isset( $slugs[ $action ] ) && ! isset( $other[ $action ] ) && false === has_filter( 'login_form_' . $action ) ) {
			$action = 'login';
		}
	} else {
		$action = 'login';
	}

	// Set the path.
	if ( isset( $slugs[ $action ] ) ) {
		$path = str_replace( 'wp-login.php', $slugs[ $action ], $path );
		$path = remove_query_arg( 'action', $path );
	} else {
		// In case of a custom action.
		$path = str_replace( 'wp-login.php', $slugs['login'], $path );
		$path = add_query_arg( 'action', $action, $path );
	}

	return '/' . ltrim( $path, '/' );
}


/**
 * Set the URL: `login?action=logout -> /logout`.
 * If the action is not present when we try to build the new URL, we fallback to `/login`. Then we can use this function after the action is added.
 *
 * @param (string) $link The URL.
 * @param (string) $action The action.
 *
 * @return (string) The new URL, with our custom slug.
 */
function sfml_login_to_action( $link, $action ) {
	$slugs = sfml_get_slugs();
	$need_action_param = false;

	if ( isset( $slugs[ $action ] ) ) {
		$slug = $slugs[ $action ];
	} else {
		// Shouldn't happen, because this function is not used in this case.
		$slug = $slugs['login'];

		if ( false === has_filter( 'login_form_' . $action ) ) {
			$action = 'login';
		} else {
			// In case of a custom action.
			$need_action_param = true;
		}
	}

	if ( $link && false === strpos( $link, '/' . $slug ) ) {

		$link = str_replace( array( '/' . $slugs['login'], '&amp;', '?amp;', '&' ), array( '/' . $slug, '&', '?', '&amp;' ), remove_query_arg( 'action', $link ) );

		if ( $need_action_param ) {
			// In case of a custom action, shouldn't happen.
			$link = add_query_arg( 'action', $action, $link );
		}
	}

	return $link;
}
