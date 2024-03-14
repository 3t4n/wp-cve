<?php
defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

if ( ! function_exists( 'set_url_scheme' ) ) :
	/**
	 * Sets the scheme for a URL.
	 *
	 * @since WP 3.4.0
	 * @since WP 4.4.0 The 'rest' scheme was added.
	 *
	 * @param (string)      $url    Absolute URL that includes a scheme.
	 * @param (string|null) $scheme Optional. Scheme to give $url. Currently 'http', 'https', 'login',
	 *                              'login_post', 'admin', 'relative', 'rest', 'rpc', or null. Default null.
	 * @return (string) $url URL with chosen scheme.
	 */
	function set_url_scheme( $url, $scheme = null ) {
		$orig_scheme = $scheme;

		if ( ! $scheme ) {
			$scheme = is_ssl() ? 'https' : 'http';
		} elseif ( 'admin' === $scheme || 'login' === $scheme || 'login_post' === $scheme || 'rpc' === $scheme ) {
			$scheme = is_ssl() || force_ssl_admin() ? 'https' : 'http';
		} elseif ( 'http' !== $scheme && 'https' !== $scheme && 'relative' !== $scheme ) {
			$scheme = is_ssl() ? 'https' : 'http';
		}

		$url = trim( $url );

		if ( substr( $url, 0, 2 ) === '//' ) {
			$url = 'http:' . $url;
		}

		if ( 'relative' === $scheme ) {
			$url = ltrim( preg_replace( '#^\w+://[^/]*#', '', $url ) );

			if ( '' !== $url && '/' === $url[0] ) {
				$url = '/' . ltrim( $url , "/ \t\n\r\0\x0B" );
			}
		} else {
			$url = preg_replace( '#^\w+://#', $scheme . '://', $url );
		}

		/**
		 * Filters the resulting URL after setting the scheme.
		 *
		 * @since WP 3.4.0
		 *
		 * @param (string)      $url         The complete URL including scheme and path.
		 * @param (string)      $scheme      Scheme applied to the URL. One of 'http', 'https', or 'relative'.
		 * @param (string|null) $orig_scheme Scheme requested for the URL. One of 'http', 'https', 'login',
		 *                                   'login_post', 'admin', 'relative', 'rest', 'rpc', or null.
		 */
		return apply_filters( 'set_url_scheme', $url, $scheme, $orig_scheme );
	}
endif;


if ( ! function_exists( 'wp_is_writable' ) ) :
	/**
	 * Determine if a directory is writable.
	 *
	 * This function is used to work around certain ACL issues in PHP primarily affecting Windows Servers.
	 *
	 * @since WP 3.6.0
	 * @see win_is_writable()
	 *
	 * @param (string) $path Path to check for write-ability.
	 *
	 * @return (bool) Whether the path is writable.
	 */
	function wp_is_writable( $path ) {
		if ( 'WIN' === strtoupper( substr( PHP_OS, 0, 3 ) ) ) {
			return win_is_writable( $path );
		}
		return @call_user_func( 'is_writable', $path );
	}
endif;


if ( ! function_exists( 'wp_parse_url' ) ) :
	/**
	 * A wrapper for PHP's parse_url() function that handles consistency in the return
	 * values across PHP versions.
	 *
	 * PHP 5.4.7 expanded parse_url()'s ability to handle non-absolute url's, including
	 * schemeless and relative url's with :// in the path. This function works around
	 * those limitations providing a standard output on PHP 5.2~5.4+.
	 *
	 * Secondly, across various PHP versions, schemeless URLs starting containing a ":"
	 * in the query are being handled inconsistently. This function works around those
	 * differences as well.
	 *
	 * Error suppression is used as prior to PHP 5.3.3, an E_WARNING would be generated
	 * when URL parsing failed.
	 *
	 * @since 2.5.1
	 * @since WP 4.4.0
	 * @since WP 4.7.0 The $component parameter was added for parity with PHP's parse_url().
	 *
	 * @param (string) $url       The URL to parse.
	 * @param (int)    $component The specific component to retrieve. Use one of the PHP
	 *                            predefined constants to specify which one.
	 *                            Defaults to -1 (= return all parts as an array).
	 *                            @see http://php.net/manual/en/function.parse-url.php
	 *
	 * @return (mixed) False on parse failure; Array of URL components on success;
	 *                 When a specific component has been requested: null if the component
	 *                 doesn't exist in the given URL; a sting or - in the case of
	 *                 PHP_URL_PORT - integer when it does. See parse_url()'s return values.
	 */
	function wp_parse_url( $url, $component = -1 ) {
		$to_unset = array();
		$url = strval( $url );

		if ( '//' === substr( $url, 0, 2 ) ) {
			$to_unset[] = 'scheme';
			$url = 'placeholder:' . $url;
		} elseif ( '/' === substr( $url, 0, 1 ) ) {
			$to_unset[] = 'scheme';
			$to_unset[] = 'host';
			$url = 'placeholder://placeholder' . $url;
		}

		$parts = @call_user_func( 'parse_url', $url );

		if ( false === $parts ) {
			// Parsing failure.
			return $parts;
		}

		// Remove the placeholder values.
		if ( $to_unset ) {
			foreach ( $to_unset as $key ) {
				unset( $parts[ $key ] );
			}
		}

		return _get_component_from_parsed_url_array( $parts, $component );
	}
endif;


if ( ! function_exists( '_get_component_from_parsed_url_array' ) ) :
	/**
	 * Retrieve a specific component from a parsed URL array.
	 *
	 * @since 2.5.1
	 * @since WP 4.7.0
	 *
	 * @param (array|false) $url_parts The parsed URL. Can be false if the URL failed to parse.
	 * @param (int)         $component The specific component to retrieve. Use one of the PHP
	 *                                 predefined constants to specify which one.
	 *                                 Defaults to -1 (= return all parts as an array).
	 * @see http://php.net/manual/en/function.parse-url.php
	 *
	 * @return (mixed) False on parse failure; Array of URL components on success;
	 *                 When a specific component has been requested: null if the component
	 *                 doesn't exist in the given URL; a sting or - in the case of
	 *                 PHP_URL_PORT - integer when it does. See parse_url()'s return values.
	 */
	function _get_component_from_parsed_url_array( $url_parts, $component = -1 ) {
		if ( -1 === $component ) {
			return $url_parts;
		}

		$key = _wp_translate_php_url_constant_to_key( $component );

		if ( false !== $key && is_array( $url_parts ) && isset( $url_parts[ $key ] ) ) {
			return $url_parts[ $key ];
		} else {
			return null;
		}
	}
endif;


if ( ! function_exists( '_wp_translate_php_url_constant_to_key' ) ) :
	/**
	 * Translate a PHP_URL_* constant to the named array keys PHP uses.
	 *
	 * @since 2.5.1
	 * @since WP 4.7.0
	 * @see   http://php.net/manual/en/url.constants.php
	 *
	 * @param (int) $constant PHP_URL_* constant.
	 *
	 * @return (string|bool) The named key or false.
	 */
	function _wp_translate_php_url_constant_to_key( $constant ) {
		$translation = array(
			PHP_URL_SCHEME   => 'scheme',
			PHP_URL_HOST     => 'host',
			PHP_URL_PORT     => 'port',
			PHP_URL_USER     => 'user',
			PHP_URL_PASS     => 'pass',
			PHP_URL_PATH     => 'path',
			PHP_URL_QUERY    => 'query',
			PHP_URL_FRAGMENT => 'fragment',
		);

		if ( isset( $translation[ $constant ] ) ) {
			return $translation[ $constant ];
		} else {
			return false;
		}
	}
endif;


if ( ! function_exists( 'do_action_deprecated' ) ) :
	/**
	 * Fires functions attached to a deprecated action hook.
	 *
	 * When an action hook is deprecated, the do_action() call is replaced with do_action_deprecated(), which triggers a deprecation notice and then fires the original hook.
	 *
	 * @since 2.5.2
	 * @since WP 4.6.0
	 *
	 * @see _deprecated_hook()
	 *
	 * @param (string) $tag         The name of the action hook.
	 * @param (array)  $args        Array of additional function arguments to be passed to do_action().
	 * @param (string) $version     The version of WordPress that deprecated the hook.
	 * @param (string) $replacement Optional. The hook that should have been used.
	 * @param (string) $message     Optional. A message regarding the change.
	 */
	function do_action_deprecated( $tag, $args, $version, $replacement = false, $message = null ) {
		if ( ! has_action( $tag ) ) {
			return;
		}

		_deprecated_hook( $tag, $version, $replacement, $message );

		do_action_ref_array( $tag, $args );
	}
endif;


if ( ! function_exists( '_deprecated_hook' ) ) :
	/**
	 * Marks a deprecated action or filter hook as deprecated and throws a notice.
	 *
	 * Use the {@see 'deprecated_hook_run'} action to get the backtrace describing where the deprecated hook was called.
	 *
	 * Default behavior is to trigger a user error if `WP_DEBUG` is true.
	 *
	 * This function is called by the do_action_deprecated() and apply_filters_deprecated() functions, and so generally does not need to be called directly.
	 *
	 * @since 2.5.2
	 * @since WP 4.6.0
	 *
	 * @param (string) $hook        The hook that was used.
	 * @param (string) $version     The version of WordPress that deprecated the hook.
	 * @param (string) $replacement Optional. The hook that should have been used.
	 * @param (string) $message     Optional. A message regarding the change.
	 */
	function _deprecated_hook( $hook, $version, $replacement = null, $message = null ) {
		/**
		 * Fires when a deprecated hook is called.
		 *
		 * @since 2.5.2
		 * @since WP 4.6.0
		 *
		 * @param (string) $hook        The hook that was called.
		 * @param (string) $replacement The hook that should be used as a replacement.
		 * @param (string) $version     The version of WordPress that deprecated the argument used.
		 * @param (string) $message     A message regarding the change.
		 */
		do_action( 'deprecated_hook_run', $hook, $replacement, $version, $message );

		/**
		 * Filters whether to trigger deprecated hook errors.
		 *
		 * @since 2.5.2
		 * @since WP 4.6.0
		 *
		 * @param (bool) $trigger Whether to trigger deprecated hook errors. Requires `WP_DEBUG` to be defined true.
		 */
		if ( WP_DEBUG && apply_filters( 'deprecated_hook_trigger_error', true ) ) {
			$message = empty( $message ) ? '' : ' ' . $message;
			if ( ! is_null( $replacement ) ) {
				/* Translators: 1: WordPress hook name, 2: version number, 3: alternative hook name. */
				trigger_error( sprintf( __( '%1$s is <strong>deprecated</strong> since version %2$s! Use %3$s instead.', 'sf-move-login' ), $hook, $version, $replacement ) . $message );
			} else {
				/* Translators: 1: WordPress hook name, 2: version number. */
				trigger_error( sprintf( __( '%1$s is <strong>deprecated</strong> since version %2$s with no alternative available.', 'sf-move-login' ), $hook, $version ) . $message );
			}
		}
	}
endif;
