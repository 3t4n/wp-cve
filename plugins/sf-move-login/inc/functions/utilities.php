<?php
defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

/*------------------------------------------------------------------------------------------------*/
/* !OPTIONS ===================================================================================== */
/*------------------------------------------------------------------------------------------------*/

/**
 * Get all options.
 *
 * @return (array) All plugin options.
 */
function sfml_get_options() {
	return SFML_Options::get_instance()->get_options();
}


/**
 * Get default options.
 *
 * @return (array) Plugin default options.
 */
function sfml_get_default_options() {
	return SFML_Options::get_instance()->get_default_options();
}


/**
 * Get the slugs.
 *
 * @return (array) All slugs.
 */
function sfml_get_slugs() {
	return SFML_Options::get_instance()->get_slugs();
}


/**
 * Access to `wp-login.php`: what the user set.
 *
 * @return (int) 1: error message, 2: 404, 3: home.
 */
function sfml_get_deny_wp_login_access() {
	return SFML_Options::get_instance()->get_option( 'deny_wp_login_access' );
}


/**
 * Access to the administration area: what the user set.
 *
 * @return (int) 0: nothing, 1: error message, 2: 404, 3: home.
 */
function sfml_get_deny_admin_access() {
	return SFML_Options::get_instance()->get_option( 'deny_admin_access' );
}


/*------------------------------------------------------------------------------------------------*/
/* !GENERIC TOOLS =============================================================================== */
/*------------------------------------------------------------------------------------------------*/

/**
 * Store, get or delete static data.
 * Getter:   no need to provide a second parameter.
 * Setter:   provide a second parameter for the value.
 * Deletter: provide null as second parameter to remove the previous value.
 *
 * @param (string) $key An identifier key.
 *
 * @return (mixed) The stored data or null.
 */
function sfml_cache_data( $key ) {
	static $data = array();

	$func_get_args = func_get_args();

	if ( array_key_exists( 1, $func_get_args ) ) {
		if ( null === $func_get_args[1] ) {
			unset( $data[ $key ] );
		} else {
			$data[ $key ] = $func_get_args[1];
		}
	}

	return isset( $data[ $key ] ) ? $data[ $key ] : null;
}


/**
 * Tell if the server runs Apache.
 *
 * @since 2.5.2
 *
 * @return (bool) True if the server runs Apache. False otherwize.
 */
function sfml_is_apache() {
	global $is_apache;
	static $is;

	if ( isset( $is ) ) {
		return $is;
	}

	if ( isset( $is_apache ) ) {
		$is = (bool) $is_apache;
	} else {
		$is = ! empty( $_SERVER['SERVER_SOFTWARE'] ) && ( strpos( $_SERVER['SERVER_SOFTWARE'], 'Apache' ) !== false || strpos( $_SERVER['SERVER_SOFTWARE'], 'LiteSpeed' ) !== false );
	}

	/**
	 * Filters the value returned by `sfml_is_apache()`.
	 *
	 * @since 2.5.2
	 *
	 * @param (bool) $is True if the server runs Apache. False otherwize.
	 */
	$is = apply_filters( 'sfml_is_apache', $is );

	// `$is` must be set before being returned (aka don't return the filter result directly or the static var won't keep the right value).
	return $is;
}


/**
 * Tell if the server runs IIS7.
 *
 * @since 2.5.2
 *
 * @return (bool) True if the server runs IIS7. False otherwize.
 */
function sfml_is_iis7() {
	global $is_iis7;
	static $is;

	if ( isset( $is ) ) {
		return $is;
	}

	if ( isset( $is_iis7 ) ) {
		$is = (bool) $is_iis7;
	} else {
		$is = ! sfml_is_apache() && ! empty( $_SERVER['SERVER_SOFTWARE'] ) && ( strpos( $_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS' ) !== false || strpos( $_SERVER['SERVER_SOFTWARE'], 'ExpressionDevServer' ) !== false );
		$is = $is && intval( substr( $_SERVER['SERVER_SOFTWARE'], strpos( $_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS/' ) + 14 ) ) >= 7;
	}

	/**
	 * Filters the value returned by `sfml_is_iis7()`.
	 *
	 * @since 2.5.2
	 *
	 * @param (bool) $is True if the server runs IIS7. False otherwize.
	 */
	$is = apply_filters( 'sfml_is_iis7', $is );

	// `$is` must be set before being returned (aka don't return the filter result directly or the static var won't keep the right value).
	return $is;
}


/**
 * Tell if the server runs Nginx.
 *
 * @return (bool) True if the server runs Nginx. False otherwize.
 */
function sfml_is_nginx() {
	global $is_nginx;
	static $is;

	if ( isset( $is ) ) {
		return $is;
	}

	if ( isset( $is_nginx ) ) {
		$is = (bool) $is_nginx;
	} else {
		$is = ! empty( $_SERVER['SERVER_SOFTWARE'] ) && strpos( $_SERVER['SERVER_SOFTWARE'], 'nginx' ) !== false;
	}

	/**
	 * Filters the value returned by `sfml_is_nginx()`.
	 *
	 * @since 2.5.2
	 *
	 * @param (bool) $is True if the server runs Nginx. False otherwize.
	 */
	$is = apply_filters( 'sfml_is_nginx', $is );

	// `$is` must be set before being returned (aka don't return the filter result directly or the static var won't keep the right value).
	return $is;
}


/**
 * Get the main blog ID.
 *
 * @return (int)
 */
function sfml_get_main_blog_id() {
	static $blog_id;

	if ( isset( $blog_id ) ) {
		return $blog_id;
	}

	if ( ! is_multisite() ) {
		$blog_id = 1;
	} elseif ( ! empty( $GLOBALS['current_site']->blog_id ) ) {
		$blog_id = absint( $GLOBALS['current_site']->blog_id );
	} elseif ( defined( 'BLOG_ID_CURRENT_SITE' ) ) {
		$blog_id = absint( BLOG_ID_CURRENT_SITE );
	} elseif ( defined( 'BLOGID_CURRENT_SITE' ) ) {
		// Deprecated.
		$blog_id = absint( BLOGID_CURRENT_SITE );
	}
	$blog_id = ! empty( $blog_id ) ? $blog_id : 1;

	return $blog_id;
}


/**
 * Return the current URL.
 *
 * @param (string) $mode What to return: raw (all), base (before '?'), uri (before '?', without the domain).
 *
 * @return (string)
 */
function sfml_get_current_url( $mode = 'base' ) {
	$mode = (string) $mode;
	$port = (int) $_SERVER['SERVER_PORT'];
	$port = 80 !== $port && 443 !== $port ? ( ':' . $port ) : '';
	$url  = ! empty( $GLOBALS['HTTP_SERVER_VARS']['REQUEST_URI'] ) ? $GLOBALS['HTTP_SERVER_VARS']['REQUEST_URI'] : ( ! empty( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '' );
	$url  = 'http' . ( is_ssl() ? 's' : '' ) . '://' . $_SERVER['HTTP_HOST'] . $port . $url;

	switch ( $mode ) :
		case 'raw' :
			return $url;
		case 'uri' :
			$home = set_url_scheme( home_url() );
			$url  = explode( '?', $url, 2 );
			$url  = reset( $url );
			$url  = str_replace( $home, '', $url );
			return trim( $url, '/' );
		default:
			$url  = explode( '?', $url, 2 );
			return reset( $url );
	endswitch;
}


/**
 * Get the absolute filesystem path to the root of the WordPress installation.
 * This is a clone of `get_home_path()`. We don't use the real one because of a bug in old versions.
 *
 * @return (string) Full filesystem path to the root of the WordPress installation
 */
function sfml_get_home_path() {
	$home    = set_url_scheme( get_option( 'home' ), 'http' );
	$siteurl = set_url_scheme( get_option( 'siteurl' ), 'http' );

	if ( ! empty( $home ) && 0 !== strcasecmp( $home, $siteurl ) ) {
		$wp_path_rel_to_home = str_ireplace( $home, '', $siteurl ); /* $siteurl - $home */
		$pos = strripos( str_replace( '\\', '/', $_SERVER['SCRIPT_FILENAME'] ), trailingslashit( $wp_path_rel_to_home ) );
		$home_path = substr( $_SERVER['SCRIPT_FILENAME'], 0, $pos );
		$home_path = trailingslashit( $home_path );
	} else {
		$home_path = ABSPATH;
	}

	return str_replace( '\\', '/', $home_path );
}


/**
 * Format a path with no heading slash and a trailing slash.
 * If the path is empty, it returns an empty string, not a lonely slash.
 * Example: foo/bar/
 *
 * @param (string) $slug A path.
 *
 * @return (string) The path with no heading slash and a trailing slash.
 */
function sfml_trailingslash_only( $slug ) {
	return ltrim( trim( $slug, '/' ) . '/', '/' );
}


/**
 * Has WP its own directory?
 *
 * @see http://codex.wordpress.org/Giving_WordPress_Its_Own_Directory
 *
 * @return (string) The directory containing WP.
 */
function sfml_get_wp_directory() {
	static $wp_siteurl_subdir;

	if ( isset( $wp_siteurl_subdir ) ) {
		return $wp_siteurl_subdir;
	}

	$wp_siteurl_subdir = '';

	$home    = set_url_scheme( rtrim( get_option( 'home' ), '/' ), 'http' );
	$siteurl = set_url_scheme( rtrim( get_option( 'siteurl' ), '/' ), 'http' );

	if ( $home && 0 !== strcasecmp( $home, $siteurl ) ) {
		$wp_siteurl_subdir = str_ireplace( $home, '', $siteurl ); /* $siteurl - $home */
		$wp_siteurl_subdir = sfml_trailingslash_only( $wp_siteurl_subdir );
	}

	return $wp_siteurl_subdir;
}


/**
 * Is WP a MultiSite and a subfolder install?
 *
 * @since 2.5 Moved to global scope.
 *
 * @return (bool)
 */
function sfml_is_subfolder_install() {
	global $wpdb;
	static $subfolder_install;

	if ( ! isset( $subfolder_install ) ) {
		if ( is_multisite() ) {
			$subfolder_install = ! is_subdomain_install();
		} elseif ( ! is_null( $wpdb->sitemeta ) ) {
			$subfolder_install = ! $wpdb->get_var( "SELECT meta_value FROM $wpdb->sitemeta WHERE site_id = 1 AND meta_key = 'subdomain_install'" );
		} else {
			$subfolder_install = false;
		}
	}

	return $subfolder_install;
}


/**
 * Get the site main URL. Will be the same for any site of a network, and for any lang of a multilang site.
 *
 * @since 2.5
 *
 * @return (string) The URL.
 */
function sfml_get_main_url() {
	$current_network = false;

	if ( function_exists( 'get_network' ) ) {
		$current_network = get_network();
	} elseif ( function_exists( 'get_current_site' ) ) {
		$current_network = get_current_site();
	}

	if ( ! $current_network ) {
		return get_option( 'siteurl' );
	}

	$scheme   = is_ssl() ? 'https' : 'http';
	$main_url = set_url_scheme( 'http://' . $current_network->domain . $current_network->path, $scheme );

	return untrailingslashit( $main_url );
}


/**
 * Build a string for html attributes (means: separated by a space).
 * Example:
 *     array( 'width' => '200', 'height' => '150', 'yolo' => 'foo' )
 *     ==>
 *     ' width="200" height="150" yolo="foo"'
 *
 * @param (array)  $attributes An array of attributes.
 * @param (string) $quote      Quote style to use.
 *
 * @return (string)
 */
function sfml_build_html_atts( $attributes, $quote = '"' ) {
	$out = '';

	if ( ! $attributes || ! is_array( $attributes ) ) {
		return '';
	}

	foreach ( $attributes as $att_name => $att_value ) {
		$out .= ' ' . esc_attr( $att_name ) . '=' . $quote . esc_attr( $att_value ) . $quote;
	}

	return $out;
}


/**
 * Remove rewrite rules and then recreate rewrite rules.
 * Waits until the 'init' action to do so.
 */
function sfml_maybe_flush_rewrite_rules() {
	static $done = false;

	if ( did_action( 'init' ) ) {
		flush_rewrite_rules();
	} elseif ( ! $done ) {
		$done = true;
		add_action( 'init', 'flush_rewrite_rules', PHP_INT_MAX );
	}
}


/*------------------------------------------------------------------------------------------------*/
/* !VARIOUS ===================================================================================== */
/*------------------------------------------------------------------------------------------------*/

/**
 * Small helper to (maybe) include `rewrite.php` file.
 */
function sfml_include_rewrite_file() {
	include_once( SFML_PLUGIN_DIR . 'inc/functions/rewrite.php' );
}


/**
 * Tell if the `.htaccess` or `web.config` file is writable.
 * If the file does not exist (uh?), check if the parent folder is writable.
 *
 * @return (bool) True if the file is writable. False otherwize.
 */
function sfml_can_write_file() {
	$home_path = sfml_get_home_path();

	// Apache.
	if ( sfml_is_apache() ) {
		if ( ! got_mod_rewrite() ) {
			return false;
		}
		if ( wp_is_writable( $home_path . '.htaccess' ) ) {
			return true;
		}
		return ! file_exists( $home_path . '.htaccess' ) && wp_is_writable( $home_path );
	}

	// IIS7.
	if ( sfml_is_iis7() ) {
		if ( ! iis7_supports_permalinks() ) {
			return false;
		}
		if ( wp_is_writable( $home_path . 'web.config' ) ) {
			return true;
		}
		return ! file_exists( $home_path . 'web.config' ) && wp_is_writable( $home_path );
	}

	return false;
}
