<?php

if ( ! function_exists( 'humanstxt_callback_ip' ) ) :
/**
 * Returns the IP address of the server under which
 * the current script is executing.
 *
 * @since 1.2
 *
 * @return string Value of $_SERVER['SERVER_ADDR'], or NULL
 */
function humanstxt_callback_ip() {
	return isset( $_SERVER['SERVER_ADDR'] ) ? $_SERVER['SERVER_ADDR'] : null;
}
endif;

if ( ! function_exists( 'humanstxt_callback_os' ) ) :
/**
 * Returns the server's operating system name.
 *
 * @since 1.2
 *
 * @return string Value of php_uname('s')
 */
function humanstxt_callback_os() {
	return php_uname( 's' ); // PHP_OS
}
endif;

if ( ! function_exists( 'humanstxt_callback_server' ) ) :
/**
 * Returns the server identification string,
 * given in the headers when responding to requests.
 * E.g.: Apache/2.2.17 (Unix) mod_ssl/2.2.17 DAV/2 PHP/5.3.6
 *
 * @since 1.2
 *
 * @return string Value of $_SERVER['SERVER_SOFTWARE']
 */
function humanstxt_callback_server() {
	return isset( $_SERVER['SERVER_SOFTWARE'] ) ? $_SERVER['SERVER_SOFTWARE'] : null;
}
endif;

if ( ! function_exists( 'humanstxt_callback_phpversion' ) ) :
/**
 * Returns the server's PHP version.
 *
 * @return string Value of phpversion()
 */
function humanstxt_callback_phpversion() {
	return phpversion();
}
endif;

if ( ! function_exists( 'humanstxt_callback_zendversion' ) ) :
/**
 * Returns the PHP's Zend engine version.
 *
 * @since 1.2
 *
 * @return string Value of zend_version()
 */
function humanstxt_callback_zendversion() {
	return zend_version();
}
endif;

if ( ! function_exists( 'humanstxt_callback_mysqlversion' ) ) :
/**
 * Returns the server's MySQL version.
 *
 * @since 1.2
 *
 * @return string MySQL version.
 */
function humanstxt_callback_mysqlversion() {
	global $wpdb;
	return $wpdb->db_version();
}
endif;

if ( ! function_exists( 'humanstxt_callback_timezone' ) ) :
/**
 * Returns the server's timezone, as user-friendly as possible.
 * Something like: "US/Central (-05:00)", "Asia/Bangkok (+07:00)"
 * or "+01:00".
 *
 * @since 1.2
 *
 * @return string Server timezone.
 */
function humanstxt_callback_timezone() {
	$offset = date( 'Z' );
	$offset = sprintf( '%s%02d:%02d', ($offset < 0 ? '-' : '+'), abs( $offset / 3600 ), abs( $offset % 3600 ) / 60 );
	$timezone = function_exists( 'date_default_timezone_get' ) ? date_default_timezone_get() : null;
	return empty( $timezone ) || $timezone == 'UTC' ? $offset : $timezone . ' (' . $offset . ')';
}
endif;

if ( ! function_exists( 'humanstxt_callback_wpversion' ) ) :
/**
 * Returns the WordPress version.
 *
 * @return string WordPress version.
 */
function humanstxt_callback_wpversion() {
	return get_bloginfo( 'version' );
}
endif;

if ( ! function_exists( 'humanstxt_callback_wpblogname' ) ) :
/**
 * Returns the site/blog title.
 *
 * @since 1.0.5
 *
 * @return string Site/blog name.
 */
function humanstxt_callback_wpblogname() {
	return get_bloginfo( 'name' );
}
endif;

if ( ! function_exists( 'humanstxt_callback_wptagline' ) ) :
/**
 * Returns the site/blog description (tagline).
 *
 * @since 1.0.5
 *
 * @return string Site/blog description.
 */
function humanstxt_callback_wptagline() {
	return get_bloginfo( 'description' );
}
endif;

if ( ! function_exists( 'humanstxt_callback_wpcharset' ) ) :
/**
 * Returns the encoding used for pages and feeds.
 *
 * @since 1.0.5
 *
 * @return string Site/blog encoding.
 */
function humanstxt_callback_wpcharset() {
	return get_bloginfo( 'charset' );
}
endif;

if ( ! function_exists( 'humanstxt_callback_wptimezone' ) ) :
/**
 * Returns the timezone WordPress uses, as user-friendly as possible.
 * Something like: "US/Central (-05:00)", "Asia/Singapore (+08:00)"
 * or "+02:00".
 *
 * @since 1.2
 *
 * @return string WordPress timezone.
 */
function humanstxt_callback_wptimezone() {
	$offset = get_option( 'gmt_offset' );
	$offset = sprintf( '%s%02d:%02d', ( $offset < 0 ? '-' : '+' ), abs( $offset ), abs( ( $offset * 3600 ) % 3600 ) / 60 );
	$timezone = get_option( 'timezone_string' );
	return empty( $timezone ) ? $offset : $timezone . ' (' . $offset . ')';
}
endif;

if ( ! function_exists( 'humanstxt_callback_wpposts' ) ) :
/**
 * Returns count of posts that are published. Can be
 * modified using the 'humanstxt_postcount' filter.
 *
 * @since 1.0.4
 *
 * @return string Number of published posts
 */
function humanstxt_callback_wpposts() {
	$postcounts = wp_count_posts();
	return apply_filters( 'humanstxt_postcount', $postcounts->publish );
}
endif;

if ( ! function_exists( 'humanstxt_callback_wppages' ) ) :
/**
 * Returns count of pages that are published. Can be
 * modified using the 'humanstxt_pagecount' filter.
 *
 * @since 1.0.4
 *
 * @return string Number of published pages
 */
function humanstxt_callback_wppages() {
	$pagecounts = wp_count_posts( 'page' );
	return apply_filters( 'humanstxt_pagecount', $pagecounts->publish );
}
endif;

if ( ! function_exists( 'humanstxt_callback_wplanguage' ) ) :
/**
 * Returns user-friendly language of WordPress.
 * Supports WPML, qTranslate and xili-language.
 *
 * @global $sitepress
 * @global $q_config
 * @global $xili_language
 *
 * @return string Name(s) of language(s).
 */
function humanstxt_callback_wplanguage() {

	global $sitepress, $q_config, $xili_language;

	require_once ABSPATH . 'wp-admin/includes/ms.php';

	$separator = apply_filters( 'humanstxt_separator', ', ' );
	$separator = apply_filters( 'humanstxt_languages_separator', $separator );

	if ( defined( 'ICL_SITEPRESS_VERSION' ) ) { // is WPML/SitePress active?

		$languages = $sitepress->get_active_languages();
		foreach ( $languages as $code => $information ) {
			$languages[ $code ] = $information[ 'display_name' ];
		}

		$active_languages = implode( $separator, $languages );

	} else if ( function_exists( 'qtrans_getSortedLanguages' ) ) { // is qTranslate active?

		$languages = qtrans_getSortedLanguages();
		foreach ( $languages as $key => $language ) {
			// try to get internatinal language name
			$languages[ $key ] = isset( $q_config[ 'locale' ][ $language ]) ? format_code_lang( $language ) : qtrans_getLanguageName( $language );
		}

		$active_languages = implode( $separator, $languages );

	} else if (defined('XILILANGUAGE_VER')) { // is xili-language active?

		$languages = $xili_language->get_listlanguages();
		foreach ( $languages as $key => $language ) {
			$languages[ $key ] = $language->description;
		}

		$active_languages = implode( $separator, $languages );

	} else {

		// just return the standard WordPress language...
		$active_languages = format_code_lang( get_bloginfo( 'language' ) );

	}

	return apply_filters( 'humanstxt_languages', $active_languages );

}
endif;

if ( ! function_exists( 'humanstxt_callback_lastupdate' ) ) :
/**
 * Returns YYYY/MM/DD timestamp of the latest modified post/page which is published.
 * The date format can be modified with the 'humanstxt_lastupdate_format' filter.
 * The final funtion result can be modified with the 'humanstxt_lastupdate' filter.
 *
 * @global $wpdb
 * @return string $last_edit Timestamp of last modified post/page.
 */
function humanstxt_callback_lastupdate() {
	global $wpdb;
	$last_edit = $wpdb->get_var( 'SELECT post_modified FROM '.$wpdb->posts.' WHERE post_status = "publish" AND (post_type = "page" OR post_type = "post") ORDER BY post_modified DESC LIMIT 1' );
	if ( !empty( $last_edit ) ) {
		$last_edit = date(apply_filters( 'humanstxt_lastupdate_format', 'Y/m/d' ), strtotime( $last_edit ));
	}
	return apply_filters( 'humanstxt_lastupdate', $last_edit );
}
endif;

if ( ! function_exists( 'humanstxt_callback_wpauthors' ) ) :
/**
 * Returns all authors with a least 1 post. The format can be adjusted
 * with the 'humanstxt_authors_format' filter and the returned list
 * can be modified with the 'humanstxt_authors' filter.
 *
 * @since 1.1.0
 *
 * @global $wpdb
 *
 * @return string|null A list of active authors
 */
function humanstxt_callback_wpauthors() {
	global $wpdb;
	$authors = null;
	// 3.1's get_users() is neat, but let's keep it downwards compatible...
	$users = (array) $wpdb->get_results( 'SELECT ID, display_name, user_email, user_url FROM ' . $wpdb->users . ' INNER JOIN ' . _get_meta_table('user') . ' ON ID = user_id WHERE meta_key = "' . $wpdb->get_blog_prefix() . 'user_level" AND CAST(meta_value AS CHAR) != 0 ORDER BY display_name ASC' );
	if ( !empty( $users ) ) {
		foreach ( $users as $user) $author_ids[] = $user->ID;
		$authors_posts = count_many_users_posts( $author_ids );
		$format = apply_filters( 'humanstxt_authors_format', "\t" . '%1$s: %2$s' . "\n\n");
		foreach ( $users as $user ) {
			if ( $authors_posts[ $user->ID ] > 0 && !empty( $user->display_name ) ) {
				$contact = empty( $user->user_url ) ? $user->user_email : $user->user_url;
				$authors .= sprintf( $format, $user->display_name, $contact );
			}
		}
	}
	return apply_filters( 'humanstxt_authors', ltrim( $authors ) );
}
endif;

if ( ! function_exists( 'humanstxt_callback_wpplugins' ) ) :
/**
 * Returns a comma separated list of all active WordPress plugins.
 * Uses the 'humanstxt_separator' filter which is ', ' (comma + space) by
 * which is rewritable with the 'humanstxt_plugins_separator' filter.
 * Final function result can be modified with the 'humanstxt_plugins' filter.
 *
 * @return string|null $active_plugins List of active WP plugins.
 */
function humanstxt_callback_wpplugins() {
	$active_plugins = get_option( 'active_plugins', array() );
	if ( is_array( $active_plugins ) && !empty( $active_plugins ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		foreach ( $active_plugins as $key => $file ) {
			$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $file, false );
			$active_plugins[ $key ] = $plugin_data['Name'];
		}
		$separator = apply_filters( 'humanstxt_separator', ', ' );
		$separator = apply_filters( 'humanstxt_plugins_separator', $separator );
		$active_plugins = apply_filters( 'humanstxt_plugins', $active_plugins );
		return implode( $separator, $active_plugins );
	}
	return null;
}
endif;

if ( ! function_exists( 'humanstxt_callback_wptheme' ) ) :
/**
 * Returns a summary of the active WordPress theme:
 * "Theme-Name (Version) by Author (Author-Link)"
 * Function result can be modified with the 'humanstxt_wptheme' filter.
 *
 * @return string|null The theme's author name.
 */
function humanstxt_callback_wptheme() {
	$theme = wp_get_theme();
	$output = null;
	if ( !$theme->errors() ) {
		$name = htmlspecialchars_decode( strip_tags( $theme->display( 'Name', false ) ) );
		$version = htmlspecialchars_decode( strip_tags( $theme->display( 'Version', false ) ) );
		$author = htmlspecialchars_decode( strip_tags( $theme->display( 'Author', false ) ) );
		$link = htmlspecialchars_decode( strip_tags( $theme->display( 'AuthorURI', false ) ) );

		$output = $name;
		if ( !empty( $version ) )
			$output .= ' (' . $version . ')';
		if ( !empty( $author ) )
			$output .= ' by ' . $author;
		if ( !empty( $link ) )
			$output .= ' (' . $link . ')';

	}
	return apply_filters( 'humanstxt_wptheme', $output);
}
endif;

if ( ! function_exists( 'humanstxt_callback_wptheme_name' ) ) :
/**
 * Returns the theme name or NULL if n/a.
 *
 * @return string|null The theme name.
 */
function humanstxt_callback_wptheme_name() {
	$theme = wp_get_theme();
	$name = htmlspecialchars_decode( strip_tags( $theme->display( 'Name', false ) ) );
	return empty( $name ) ? null : $name;
}
endif;

if ( ! function_exists( 'humanstxt_callback_wptheme_version' ) ) :
/**
 * Returns the theme's version or NULL if n/a.
 *
 * @return string|null The theme's version name.
 */
function humanstxt_callback_wptheme_version() {
	$theme = wp_get_theme();
	$version = htmlspecialchars_decode( strip_tags( $theme->display( 'Version', false ) ) );
	return empty( $version ) ? null : $version;
}
endif;

if ( ! function_exists( 'humanstxt_callback_wptheme_author' ) ) :
/**
 * Returns the theme's author name or NULL if n/a.
 *
 * @return string|null The theme's author name.
 */
function humanstxt_callback_wptheme_author() {
	$theme = wp_get_theme();
	$author = htmlspecialchars_decode( strip_tags( $theme->display( 'Author', false ) ) );
	return empty( $author ) ? null : $author;
}
endif;

if ( ! function_exists( 'humanstxt_callback_wptheme_author_link' ) ) :
/**
 * Returns the theme's author link or NULL if n/a.
 *
 * @return string|null The theme's author URI.
 */
function humanstxt_callback_wptheme_author_link() {
	$theme = wp_get_theme();
	$link = htmlspecialchars_decode( strip_tags( $theme->display( 'AuthorURI', false ) ) );
	return empty( $link ) ? null : $link;
}
endif;
