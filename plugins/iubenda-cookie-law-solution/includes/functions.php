<?php
/**
 * Common functions.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'iub_array_get' ) ) {
	/**
	 * Iubenda array get
	 *
	 * @param   array  $target_array   An array from which we want to retrieve some information.
	 * @param   string $key            A string separated by . of keys describing the path with which to retrieve information.
	 * @param   mixed  $default_value  Optional. The return value if the path does not exist within the array.
	 *
	 * @return array|ArrayAccess|mixed|null
	 */
	function iub_array_get( $target_array, $key, $default_value = null ) {
		if ( ! ( is_array( $target_array ) || $target_array instanceof ArrayAccess ) ) {
			return $default_value instanceof Closure ? $default_value() : $default_value;
		}

		if ( is_null( $key ) ) {
			return $target_array;
		}

		if ( array_key_exists( $key, $target_array ) ) {
			return $target_array[ $key ];
		}

		if ( strpos( $key, '.' ) === false ) {
			return $target_array[ $key ] ?? ( $default_value instanceof Closure ? $default_value() : $default_value );
		}

		foreach ( explode( '.', $key ) as $segment ) {
			if ( ( is_array( $target_array ) || $target_array instanceof ArrayAccess ) && ( array_key_exists( $segment, $target_array ) ) ) {
				$target_array = $target_array[ $segment ];
			} else {
				return $default_value instanceof Closure ? $default_value() : $default_value;
			}
		}

		return $target_array;
	}
}

if ( ! function_exists( 'iub_array_only' ) ) {
	/**
	 * Return only intersects keys with the array
	 *
	 * @param   array        $target_array  Array.
	 * @param   array|string $keys          Keys.
	 *
	 * @return array
	 */
	function iub_array_only( array $target_array, $keys ) {
		return array_intersect_key( $target_array, array_flip( (array) $keys ) );
	}
}

if ( ! function_exists( '__iub_trans' ) ) {
	/**
	 * Translate a specific string into a specific language.
	 *
	 * @param string $target_string  specific string.
	 * @param string $locale         specific language.
	 * @param string $text_domain    Optional. as default iubenda.
	 *
	 * @return string|void
	 */
	function __iub_trans( $target_string, $locale, $text_domain = 'iubenda' ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionDoubleUnderscore,PHPCompatibility.FunctionNameRestrictions.ReservedFunctionNames.FunctionDoubleUnderscore

		$mo     = new MO();
		$mofile = IUBENDA_PLUGIN_PATH . 'languages/' . $text_domain . '-' . $locale . '.mo';
		if ( file_exists( $mofile ) ) {
			$mo->import_from_file( $mofile );
			// phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText, WordPress.WP.I18n.NonSingularStringLiteralDomain
			$target_string = esc_html__( $mo->translate( $target_string ) );
		}

		// phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText, WordPress.WP.I18n.NonSingularStringLiteralDomain
		return $target_string;
	}
}

if ( ! function_exists( 'iub_verify_ajax_request' ) ) {
	/**
	 * Custom ajax verification request with nonce check and permission check.
	 *
	 * @param   int|string   $action      Action nonce.
	 * @param   false|string $query_arg   Optional. Key to check for the nonce in `$_REQUEST` (since 2.5). If false,
	 *                                   `$_REQUEST` values will be evaluated for '_ajax_nonce', and '_wpnonce'
	 *                                   (in that order). Default false.
	 * @param   string       $capability  Capability name.
	 * @param   bool         $should_die  Optional. Whether to die early when the nonce cannot be verified.
	 *                                   Default true.
	 *
	 * @return void
	 */
	function iub_verify_ajax_request( $action, $query_arg = false, $capability = 'manage_options', $should_die = false ) {
		if (
			! check_ajax_referer( $action, $query_arg, $should_die ) ||
			! current_user_can( apply_filters( 'iubenda_cookie_law_cap', $capability ) )
		) {
			wp_die( esc_html__( 'Sorry, you are not authorized to perform this action.' ), 403 );
		}
	}
}

if ( ! function_exists( 'iub_verify_user_capability' ) ) {
	/**
	 * Check if the current user have any capability or not.
	 *
	 * @param   array $capabilities  Capability names.
	 *
	 * @return void
	 */
	function iub_verify_user_capability( array $capabilities = array() ) {
		// If running in WP-CLI context, no need to check capabilities, so return.
		if ( iubenda::is_wp_cli() ) {
			return;
		}

		if ( ! $capabilities ) {
			// Set default capability.
			$capabilities = array( 'manage_options' );
		}

		foreach ( $capabilities as $capability ) {
			if ( current_user_can( apply_filters( 'iubenda_cookie_law_cap', $capability ) ) ) {
				return;
			}
		}

		wp_die( esc_html__( 'Sorry, you are not authorized to perform this action.' ), 403 );
	}
}

if ( ! function_exists( 'iub_verify_postback_request' ) ) {
	/**
	 * Custom postback verification request with nonce check and permission check.
	 *
	 * @param   int|string   $action      Action nonce.
	 * @param   false|string $query_arg   Optional. Key to check for the nonce in `$_REQUEST` (since 2.5). If false,
	 *                                    `$_REQUEST` values will be evaluated for '_ajax_nonce', and '_wpnonce'
	 *                                    (in that order). Default false.
	 * @param   string       $capability  Capability name.
	 *
	 * @return void
	 */
	function iub_verify_postback_request( $action, $query_arg = '_wpnonce', $capability = 'manage_options' ) {
		if (
			! check_admin_referer( $action, $query_arg ) ||
			! current_user_can( apply_filters( 'iubenda_cookie_law_cap', $capability ) )
		) {
			wp_die( esc_html__( 'Sorry, you are not authorized to perform this action.' ), 403 );
		}
	}
}

if ( ! function_exists( 'iub_get_request_parameter' ) ) {
	/**
	 * Gets the request parameter.
	 *
	 * @param   string $key            The query parameter.
	 * @param   string $default_value  The default value to return if not found.
	 * @param   string $with_sanitize  With sanitize. Default true.
	 *
	 * @return     string  The request parameter.
	 */
	function iub_get_request_parameter( string $key, $default_value = '', $with_sanitize = true ) {
		// If key not exist or empty return default.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_REQUEST[ $key ] ) || empty( $_REQUEST[ $key ] ) ) {
			return $default_value;
		}

		if ( $with_sanitize ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return sanitize_key( $_REQUEST[ $key ] );
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		return $_REQUEST[ $key ];
	}
}

if ( ! function_exists( 'iub_is_polylang_active' ) ) {
	/**
	 * Check if Polylang plugin installed and activated.
	 *
	 * @return bool
	 */
	function iub_is_polylang_active() {
		$result = false;
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$polylang_plugin_status = is_plugin_active( 'polylang/polylang.php' ) || is_plugin_active( 'polylang-pro/polylang.php' );

		if ( $polylang_plugin_status && function_exists( 'PLL' ) ) {
			$result = true;
		}
		return $result;
	}
}

if ( ! function_exists( 'iub_is_wpml_active' ) ) {
	/**
	 * Check if WPML plugin installed and activated.
	 *
	 * @return bool
	 */
	function iub_is_wpml_active() {
		$result = false;
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$sitepress_plugin_status = is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) || is_plugin_active( 'wpml-multilingual-cms/sitepress.php' );

		if ( $sitepress_plugin_status && class_exists( 'SitePress' ) ) {
			$result = true;
		}
		return $result;
	}
}

if ( ! function_exists( 'iub_caught_exception' ) ) {
	/**
	 * When catching an exception, this allows us to log it if unexpected.
	 *
	 * @param   Exception|Error $e The exception object.
	 */
	function iub_caught_exception( $e ) {
		$message = 'Exception (' . get_class( $e ) . ') occurred during enqueuing embed code. Exception Message: ' . $e->getMessage() . ' (Code: ' . $e->getCode() . ', line ' . $e->getLine() . ' in ' . $e->getFile() . ')';

		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		error_log( "Exception caught. $message." );
	}
}

if ( ! function_exists( 'can_use_dom_document_class' ) ) {

	/**
	 * Check if Dom document not exists or there is outdated modules.
	 *
	 * @return bool
	 */
	function can_use_dom_document_class() {
		if ( ! class_exists( 'DOMDocument' ) ) {
			return false;
		}

		if ( defined( 'LIBXML_DOTTED_VERSION' ) && extension_loaded( 'libxml' ) ) {
			return ! version_compare( LIBXML_DOTTED_VERSION, '2.7.8', '<' );
		}

		return true;
	}
}
