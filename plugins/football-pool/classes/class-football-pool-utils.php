<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

class Football_Pool_Utils {
	/**
	 * Echo JSON header and response and then die().
	 *
	 * @param array $params array of values that you want to return as JSON response
	 */
	public static function ajax_response( array $params )
	{
		// return the result
		header( 'Content-Type: application/json' );
		echo json_encode( $params );
		// always die when doing ajax responses
		wp_die();
	}

	/**
	 * Converts a string for XSS safe outputting
	 * https://www.owasp.org/index.php/PHP_Security_Cheat_Sheet#XSS_Cheat_Sheet
	 *
	 * @param mixed       $data
	 * @param string|null $encoding
	 * @param bool|null   $allow_overwrite
	 * @return string
	 */
	public static function xssafe( $data, ?string $encoding = 'UTF-8',
	                               ?bool $allow_overwrite = FOOTBALLPOOL_ALLOW_HTML ): string
	{
		if ( ! $allow_overwrite ) {
			if ( is_null( $data ) ) $data = '';
			$data = htmlspecialchars( $data, ENT_QUOTES | ENT_HTML401, $encoding );
		}
		
		return $data;
	}

	/**
	 * Converts and echoes a string for XSS safe outputting
	 * https://www.owasp.org/index.php/PHP_Security_Cheat_Sheet#XSS_Cheat_Sheet
	 *
	 * @param mixed       $data
	 * @param string|null $encoding
	 * @param bool|null $allow_overwrite
	 * @return void
	 */
	public static function xecho( $data, ?string $encoding = 'UTF-8',
	                              ?bool $allow_overwrite = FOOTBALLPOOL_ALLOW_HTML ) {
		if ( ! $allow_overwrite ) {
			$data = self::xssafe( $data, $encoding );
		}
		
		echo $data;
	}

	/**
	 * Returns a javascript-escaped string.
	 * https://defuse.ca/blog/escaping-string-literals-for-javascript-in-php.html
	 *
	 * @param mixed $data
	 * @return string
	 */
	public static function js_string_escape( $data ): string
	{
		$safe = "";
		for ( $i = 0; $i < strlen( $data ); $i++ ) {
			if ( ctype_alnum( $data[$i] ) ) {
				$safe .= $data[$i];
			} else {
				$safe .= sprintf( "\\x%02X", ord( $data[$i] ) );
			}
		}
		return $safe;
	}

	/**
	 * Search for needle in haystack and insert a text after it. If needle is not found, the original string
	 * is returned.
	 *
	 * @param string $haystack  source string
	 * @param string $needle    text to search for in the string
	 * @param string $text      text to insert after needle
	 *
	 * @return string|string[]
	 */
	public static function str_insert( string $haystack, string $needle, string $text ) {
		$index = strpos( $haystack, $needle );
		if ( $index === false ) {
			return $haystack;
		}
		return substr_replace( $haystack, $needle.$text, $index, strlen( $needle ) );
	}

	/**
	 * Gets a single value from the user meta set.
	 *
	 * @param array $user_meta
	 * @param string $key
	 * @param string|null $default
	 *
	 * @return mixed|string
	 */
	public static function get_user_meta( array $user_meta, string $key, ?string $default = '' ) {
		$output = $default;
		if ( is_array( $user_meta ) && isset( $user_meta[$key] ) 
			&& is_array( $user_meta[$key] ) && isset( $user_meta[$key][0] ) ) $output = $user_meta[$key][0];
		
		return $output;
	}

	/**
	 * Checks if the date is a valid date in the "Y-m-d H:i" format (or other format if the format is given).
	 *
	 * @param string $date the date to check
	 * @param string|null $format a date format string
	 *
	 * @return bool
	 */
	public static function is_valid_mysql_date( string $date, ?string $format = 'Y-m-d H:i' ): bool
	{
		$d = DateTime::createFromFormat( $format, $date );
		return $d && $d->format( $format ) === $date;
	}


	/**
	 * Helper function to include CSS files in the plugin.
	 *
	 * @param string $file
	 * @param string $handle
	 * @param array|null $deps
	 * @param bool $forced_exit
	 * @param string|null $custom_path
	 * @param mixed|null $external
	 * @param array|null $pages
	 * @param string|null $version
	 *
	 * @return void
	 */
	public static function include_css( string $file, string $handle, ?array $deps = null, ?bool $forced_exit = true,
	                                    ?string $custom_path = '', $external = false, ?array $pages = null,
	                                    ?string $version = FOOTBALLPOOL_DB_VERSION )
	{
		$external = ( $external === 'external' );
		if ( $external || $custom_path != '' ) {
			$url = $external ? esc_url_raw( $file ) : $file;
			$dir = $custom_path;
		} else {
			$url = FOOTBALLPOOL_PLUGIN_URL . $file;
			$dir = FOOTBALLPOOL_PLUGIN_DIR . $file;
		}
		
		if ( $external || file_exists( $dir ) ) {
			wp_register_style( $handle, $url, $deps, $version );
			wp_enqueue_style( $handle );
		} else {
            if ( $forced_exit ) wp_die( $dir . ' not found' );
		}
	}

	/**
	 * Helper function to include JS files in the plugin.
	 *
	 * @param string $file
	 * @param string $handle
	 * @param array|null $deps
	 * @param bool $forced_exit
	 * @param string|null $custom_path
	 * @param mixed|null $external
	 * @param array|null $pages
	 * @param string|null $version
	 *
	 * @return void
	 */
	public static function include_js( string $file, string $handle, ?array $deps = null, ?bool $forced_exit = true,
	                                   ?string $custom_path = '', $external = false, ?array $pages = null,
	                                   ?string $version = FOOTBALLPOOL_DB_VERSION ) {
		$external = ( $external === 'external' );
		if ( $external || $custom_path != '' ) {
			$url = $external ? esc_url_raw( $file ) : $file;
			$dir = $custom_path;
		} else {
			$url = FOOTBALLPOOL_PLUGIN_URL . $file;
			$dir = FOOTBALLPOOL_PLUGIN_DIR . $file;
		}
		
		if ( $external || file_exists( $dir ) ) {
			wp_register_script( $handle, $url, $deps, $version );
			wp_enqueue_script( $handle );
		} else {
            if ( $forced_exit ) wp_die( $dir . ' not found' );
		}
	}

	/**
	 * Checks if WordPress is at least at version $version
	 *
	 * @param string $version
	 *
	 * @return bool
	 */
	public static function wordpress_is_at_least_version( string $version ): bool
	{
		$wp_ver = get_bloginfo( 'version' );

		return version_compare( $wp_ver, $version, '>=' );
	}

	/**
	 * Returns or echoes a string in a code block using the highlight_string() function.
	 *
	 * @param string $str
	 * @param bool|null $return
	 * @param string|null $class
	 *
	 * @return string|void
	 */
	public static function highlight_string( string $str, ?bool $return = false, ?string $class = 'block' ) {
		$highlight = highlight_string( $str, true );
		$highlight = str_ireplace( '<code>', "<code class='{$class}'>", $highlight );
		
		if ( $return === true ) {
			return $highlight;
		} else {
			echo $highlight;
		}
	}

	/**
	 * Replaces all placeholders in a string with a value.
	 *
	 * @param string $input string in which placeholders surrounded with %% should be replaved
	 * @param array|null $params format array( 'placeholder' => 'text', 'placeholder2' => 'text2', ... )
	 * @param string|null $placeholder_start the char that identifies the start of a placeholder, defaults to '%'
	 * @param string|null $placeholder_end the char that identifies the end of a placeholder, defaults to '%'
	 *
	 * @return string|string[]
	 */
	public static function placeholder_replace( string $input, ?array $params = [],
	                                            ?string $placeholder_start = FOOTBALLPOOL_TEMPLATE_PARAM_DELIMITER,
	                                            ?string $placeholder_end = FOOTBALLPOOL_TEMPLATE_PARAM_DELIMITER ) {

		if ( count( $params ) > 0 ) {
			foreach ( $params as $key => $val ) {
				if ( is_null( $val ) ) $val = '';
				$input = str_replace( "{$placeholder_start}{$key}{$placeholder_end}", $val, $input );
			}
		}
		
		return $input;
	}

	/**
	 * Returns HTML for a select box with options.
	 *
	 * @param string $id
	 * @param array|null $options
	 * @param string|int $selected_val
	 * @param string|null $name
	 * @param string|null $css_class
	 *
	 * @return string
	 */
	public static function select( string $id, ?array $options, $selected_val, ?string $name = '',
	                               ?string $css_class = '' ): string
	{
		if ( $name === '' || $name === null ) $name = $id;
		
		$output = sprintf( '<select name="%s" id="%s" class="%s">', $name, $id, $css_class );
		foreach ( $options as $val => $text ) {
			$output .= sprintf('<option value="%s"%s>%s</option>'
								, $val
								, ( $val == $selected_val ) ? ' selected="selected"' : ''
								, $text
						);
		}
		$output .= '</select>';
		
		return $output;
	}
	
	/**
	 * Extract ids from a string ("x", "x-z", "x,y,z").
	 *
	 * We convert everything to integers, so strings will also end up as integer (e.g. "a" will become 0),
	 * but this is in general not a problem as we are looking for DB id's.
	 *
	 * @param string $input
	 *
	 * @return array result is an array of integers
	 */
	public static function extract_ids( string $input ): array
	{
		$ids = [];
		// remove all spaces and tabs
		$input = str_replace( [" ", "\t"], "", $input );
		// split for single numbers
		$input = explode( ',', $input );
		foreach ( $input as $part ) {
			// split in case of ranges
			$range = explode( '-', $part );
			if ( count( $range ) === 2 ) {
				// a range: x-y
				$x = (int) $range[0];
				$y = (int) $range[1];
				// always include low number of the range
				$ids[] = $x++;
				// if x is bigger than y (e.g. 5-2) it's not a valid range and we ignore the rest
				// if not, we add every number until we get to the upper boundary y of the range
				while ( $x <= $y ) {
					$ids[] = $x++;
				}
			} else {
				// a single number x
				// or a malformed range like x--y (which will be treated as a single number, returning only x).
				$ids[] = (int) $range[0];
			}
		}
		
		// remove duplicates and return
		return array_keys( array_flip( $ids ) );
	}

	/**
	 * Returns an int and stores the value + 1 in the WP cache.
	 *
	 * @param string $cache_key
	 *
	 * @return bool|int|mixed
	 */
	public static function get_counter_value( string $cache_key ) {
		$id = wp_cache_get( $cache_key, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		if ( $id === false ) {
			$id = 1;
		}
		wp_cache_set( $cache_key, $id + 1, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		
		return $id;
	}

	/**
	 * Accepts a date in "Y-m-d H:i" format and changes it to UTC.
	 *
	 * @param string $date_string
	 * @param string|null $date_format
	 *
	 * @return string
	 */
	public static function gmt_from_date( string $date_string, ?string $date_format = 'Y-m-d H:i' ): string
	{
		if ( strlen( $date_string ) === strlen( '0000-00-00 00:00' ) ) $date_string .= ':00';
		return $date_string !== '' ? get_gmt_from_date( $date_string, $date_format ) : '';
	}

	/**
	 * Accepts a date in "Y-m-d H:i" format and changes it to local time according to WP's timezone setting.
	 *
	 * @param string $date_string
	 * @param string|null $date_format
	 *
	 * @return string
	 */
	public static function date_from_gmt( string $date_string, ?string $date_format = 'Y-m-d H:i' ): string
	{
		if ( strlen( $date_string ) === strlen( '0000-00-00 00:00' ) ) $date_string .= ':00';
		return $date_string !== '' ? get_date_from_gmt( $date_string, $date_format ) : '';
	}

	/**
	 * Returns the full url of the current script.
	 *
	 * @return string
	 */
	public static function full_url(): string
	{
		$s = empty( $_SERVER['HTTPS'] ) ? '' : ( ( $_SERVER['HTTPS'] === 'on' ) ? 's' : '' );
		$protocol = substr( strtolower( $_SERVER['SERVER_PROTOCOL'] ), 0, strpos( strtolower( $_SERVER['SERVER_PROTOCOL'] ), '/' ) ) . $s;
		$port = ( $_SERVER['SERVER_PORT'] == '80' ) ? '' : ( ':' . $_SERVER['SERVER_PORT'] );
		return $protocol . '://' . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
	}

	/**
	 * Updates a value in the football pool options array that's stored in wp_options.
	 *
	 * @param string $key
	 * @param mixed $value
	 *
	 * @return void
	 */
	public static function set_fp_option( string $key, $value ) {
		self::update_fp_option( $key, $value );
	}

	/**
	 * Updates a value in the football pool options array that's stored in wp_options.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param mixed $overwrite defaults to overwriting the value for an existing key
	 *
	 * @return void
	 */
	public static function update_fp_option( string $key, $value, $overwrite = 'overwrite' ) {
		$options = get_option( FOOTBALLPOOL_OPTIONS, [] );
		if ( ! isset( $options[$key] ) || $overwrite === 'overwrite' ) {
			$options[$key] = $value;
			update_option( FOOTBALLPOOL_OPTIONS, $options );
		}
	}

	/**
	 * Returns a value from the football pool options array that's stored in wp_options.
	 *
	 * @param string $key
	 * @param mixed|null $default
	 * @param string|null $type
	 *
	 * @return int|string
	 */
	public static function get_fp_option( string $key, $default = '', ?string $type = 'text' )
	{
		$options = get_option( FOOTBALLPOOL_OPTIONS, [] );
		$value = $options[$key] ?? $default;
		if ( $type === 'int' || $type === 'integer' ) {
			if ( ! is_numeric( $value ) ) $value = $default;
			$value = (int) $value;
		}
		return $value;
	}
	
	/**
	 * @param array|mixed|string $value
	 *
	 * @return array|mixed|string
	 */
	public static function safe_stripslashes( $value )
	{
		// damn you, magic quotes!
		// and damn you, WP, for not telling me about wp_magic_quotes()!
		// http://kovshenin.com/2010/wordpress-and-magic-quotes/

		// return get_magic_quotes_gpc() ? stripslashes( $value ) : $value;
		if ( is_array( $value) )
			return stripslashes_deep( $value );
		else
			return stripslashes( $value );
	}

	/**
	 * @param array|mixed|string $value
	 *
	 * @return array|mixed|string
	 */
	public static function safe_stripslashes_deep( $value )
	{
		// return get_magic_quotes_gpc() ? stripslashes_deep( $value ) : $value;
		return stripslashes_deep( $value );
	}

	/**
	 * @param string $key
	 * @param string|null $default
	 *
	 * @return string
	 */
	public static function request_str( string $key, ?string $default = '' ): string
	{
		return self::request_string( $key, $default );
	}

	/**
	 * @param string $key
	 * @param string|null $default
	 *
	 * @return string
	 */
	public static function request_string( string $key, ?string $default = '' ): string
	{
		return ( $_POST ? self::post_string( $key, $default ) : self::get_string( $key, $default ) );
	}

	/**
	 * @param string $key
	 * @param string|null $default
	 *
	 * @return string
	 */
	public static function get_str( string $key, ?string $default = '' ): string
	{
		return self::get_string( $key, $default );
	}

	/**
	 * @param string $key
	 * @param string|null $default
	 *
	 * @return string
	 */
	public static function get_string( string $key, ?string $default = '' ): string
	{
		return ( isset( $_GET[$key] ) ? self::safe_stripslashes( $_GET[$key] ) : $default );
	}

	/**
	 * Get the value from the GET and check if it is one of the allowed values. If not, return the default.
	 *
	 * @param string $key
	 * @param array $allowed_values
	 * @param string|null $default
	 *
	 * @return mixed|string
	 */
	public static function get_enum( string $key, array $allowed_values, ?string $default = '' ) {
		$value = isset( $_GET[$key] ) ? self::safe_stripslashes( $_GET[$key] ) : $default;
		if ( ! in_array( $value, $allowed_values ) ) {
			$value = $default;
		}
		return $value;
	}

	/**
	 * Get the value from the POST and check if it is one of the allowed values. If not, return the default.
	 *
	 * @param string $key
	 * @param array $allowed_values
	 * @param string|null $default
	 *
	 * @return mixed|string
	 */
	public static function post_enum( string $key, array $allowed_values, ?string $default = '' )
	{
		$value = isset( $_POST[$key] ) ? self::safe_stripslashes( $_POST[$key] ) : $default;
		if ( ! in_array( $value, $allowed_values ) ) {
			$value = $default;
		}
		return $value;
	}

	/**
	 * @param string $key
	 * @param string|null $default
	 *
	 * @return string
	 */
	public static function post_str( string $key, ?string $default = '' ): string
	{
		return self::post_string( $key, $default );
	}

	/**
	 * @param string $key
	 * @param string|null $default
	 *
	 * @return string
	 */
	public static function post_string( string $key, ?string $default = '' ): string
	{
		return ( isset( $_POST[$key] ) ? self::safe_stripslashes( $_POST[$key] ) : $default );
	}

	/**
	 * @param string $key
	 * @param int|null $default
	 *
	 * @return int
	 */
	public static function request_int( string $key, ?int $default = 0 ): int
	{
		return self::request_integer( $key, $default );
	}

	/**
	 * @param string $key
	 * @param int|null $default
	 *
	 * @return int
	 */
	public static function request_integer( string $key, ?int $default = 0): int
	{
		return ( $_POST ? self::post_integer( $key, $default ) : self::get_integer( $key, $default ) );
	}

	/**
	 * @param string $key
	 * @param int|null $default
	 *
	 * @return int
	 */
	public static function get_integer( string $key, ?int $default = 0 ): int
	{
		return ( isset( $_GET[$key] ) && is_numeric( $_GET[$key] )? (int)$_GET[$key] : $default );
	}

	/**
	 * @param string $key
	 * @param int|null $default
	 *
	 * @return int
	 */
	public static function get_int( string $key, ?int $default = 0 ) {
		return self::get_integer( $key, $default );
	}

	/**
	 * @param string $key
	 * @param int|null $default
	 *
	 * @return int
	 */
	public static function post_integer( string $key, ?int $default = 0 ): int
	{
		return ( isset( $_POST[$key] ) && is_numeric( $_POST[$key] )? (int)$_POST[$key] : $default );
	}

	/**
	 * @param string $key
	 * @param int|null $default
	 *
	 * @return int
	 */
	public static function post_int( string $key, ?int $default = 0 ): int
	{
		return self::post_integer( $key, $default );
	}

	/**
	 * @param string $key
	 * @param array|null $default
	 *
	 * @return array
	 */
	public static function request_int_array( string $key, ?array $default = [] ): array
	{
		return self::request_integer_array( $key, $default );
	}

	/**
	 * @param string $key
	 * @param array|null $default
	 *
	 * @return array
	 */
	public static function request_integer_array( string $key, ?array $default = [] ): array
	{
		if ( $_POST ) {
			return self::post_integer_array( $key, $default );
		} else {
			return self::get_integer_array( $key, $default );
		}
	}

	/**
	 * @param string $key
	 * @param array|null $default
	 *
	 * @return array
	 */
	public static function get_intArray( string $key, ?array $default = [] ): array
	{
		return self::get_integer_array( $key, $default );
	}

	/**
	 * @param string $key
	 * @param array|null $default
	 *
	 * @return array
	 */
	public static function get_integer_array( string $key, ?array $default = [] ): array
	{
		$arr = array();
		if ( isset( $_GET[$key] ) && is_array( $_GET[$key] ) ) {
			$get = $_GET[$key];
			foreach ( $get as $str ) $arr[] = (int)$str;
		} else {
			$arr = $default;
		}
		
		return $arr;
	}

	/**
	 * @param string $key
	 * @param array|null $default
	 *
	 * @return array
	 */
	public static function post_int_array( string $key, ?array $default = array() ): array
	{
		return self::post_integer_array( $key, $default );
	}

	/**
	 * @param string $key
	 * @param array|null $default
	 *
	 * @return array
	 */
	public static function post_integer_array( string $key, ?array $default = array() ): array
	{
		$arr = array();
		if ( isset( $_POST[$key] ) && is_array( $_POST[$key] ) ) {
			$post = $_POST[$key];
			foreach ( $post as $str ) $arr[] = (int)$str;
		} else {
			$arr = $default;
		}
		
		return $arr;
	}

	/**
	 * @param string $key
	 * @param array|null $default
	 *
	 * @return array
	 */
	public static function request_str_array( string $key, ?array $default = [] ): array
	{
		return self::request_string_array( $key, $default );
	}

	/**
	 * @param string $key
	 * @param array|null $default
	 *
	 * @return array
	 */
	public static function request_string_array( string $key, ?array $default = [] ): array
	{
		return ( $_POST ? self::post_string_array( $key, $default ) : self::get_string_array( $key, $default ) );
	}

	/**
	 * @param string $key
	 * @param array|null $default
	 *
	 * @return array
	 */
	public static function get_str_array( string $key, ?array $default = [] ): array
	{
		return self::get_string_array( $key, $default );
	}

	/**
	 * @param string $key
	 * @param array|null $default
	 *
	 * @return array
	 */
	public static function get_string_array( string $key, ?array $default = [] ): array
	{
		return (
			isset( $_GET[$key] ) && is_array( $_GET[$key] ) ? self::safe_stripslashes_deep( $_GET[$key] ) : $default
		);
	}

	/**
	 * @param string $key
	 * @param array|null $default
	 *
	 * @return array
	 */
	public static function post_str_array( string $key, ?array $default = [] ): array
	{
		return self::post_string_array( $key, $default );
	}

	/**
	 * @param string $key
	 * @param array|null $default
	 *
	 * @return array
	 */
	public static function post_string_array( string $key, ?array $default = [] ): array
	{
		return (
			isset( $_POST[$key] ) && is_array( $_POST[$key] ) ?
				self::safe_stripslashes_deep( $_POST[$key] ) : $default
		);
	}

	/**
	 * Debug function, but defaults to file log instead of echoing the debug info.
	 *
	 * @param mixed $var
	 * @param string|null $type
	 * @param int|null $sleep
	 */
	public static function debugf( $var, ?string $type = 'file', ?int $sleep = 0 ) {
		self::debug( $var, $type, $sleep );
	}

	/**
	 * Debug function, but defaults to mail log instead of echoing the debug info and pauses by
	 * default for 1000 micro seconds.
	 *
	 * @param mixed $var
	 * @param string|null $type
	 * @param int|null $sleep
	 */
	public static function debugm( $var, ?string $type = 'mail', ?int $sleep = 1000 ) {
		self::debug( $var, $type, $sleep );
	}

	/**
	 * Print information about a variable in a human-readable way.
	 * If argument 'sleep' is set, the execution will halt after the debug for the given amount of micro seconds
	 * (one micro second = one millionth of a second).
	 *
	 * @param mixed $var
	 * @param string|null $type
	 * @param int|null $sleep
	 *
	 * @return string|void
	 */
	public static function debug( $var, ?string $type = 'echo', ?int $sleep = 0 ) {
		if ( defined( 'FOOTBALLPOOL_DEBUG_FORCE' ) ) {
			$type = FOOTBALLPOOL_DEBUG_FORCE;
		} else {
			if ( ! FOOTBALLPOOL_ENABLE_DEBUG ) return;
		}
		
		$type = str_replace( array( 'only', 'just', ' ', '-' ), '', $type );
		
		if ( $type === 'once' || ( is_array( $type ) && $type[0] === 'once' ) ) {
			$type = $type[1] ?? 'echo';
			
			$cache_key = 'fp_debug';
			$i = wp_cache_get( $cache_key, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
			if ( false === $i ) {
				$i = 1;
				wp_cache_set( $cache_key, $i, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
			} else {
				$i++;
			}
			
			if ( $i > 1 ) return;
		}
		
		$pre  = "<pre style='border: 1px solid;'>";
		$pre .= "<div style='padding:2px;color:#fff;background-color:#000;'>debug</div><div style='padding:2px;'>";
		$post = "</div></pre>";
		switch ( $type ) {
			case 'mail':
			case 'email':
			case 'e-mail':
				$subject = date('D d/M/Y H:i P') . ': error log';
				$message = var_export( $var, true );
				wp_mail( FOOTBALLPOOL_DEBUG_EMAIL, $subject, $message );
				break;
			case 'log':
			case 'file':
				$pre  = "[" . date('D d/M/Y H:i P') . "]\n";
				$post = "\n-----------------------------------------------\n";
				if ( defined( 'FOOTBALLPOOL_ERROR_LOG' ) ) {
					if ( ! file_exists( FOOTBALLPOOL_ERROR_LOG ) ) {
						file_put_contents( FOOTBALLPOOL_ERROR_LOG, "{$pre}errorlog created{$post}" );
					}
					error_log( $pre . var_export( $var, true ) . $post, 3, FOOTBALLPOOL_ERROR_LOG );
				}
				break;
			case 'output':
			case 'return':
				return $pre . var_export( $var, true ) . $post;
			case 'echo':
			default:
				echo $pre;
				var_dump( $var );
				echo $post;
		}
		
		if ( $sleep > 0 ) usleep( $sleep );
	}

	/**
	 * Logs a message in the predictions audit log.
	 *
	 * @param int $user_id ID of the user that makes the save.
	 * @param int $type 0 for matches and 1 for questions (FOOTBALLPOOL_TYPE_MATCH and FOOTBALLPOOL_TYPE_QUESTION).
	 * @param int $source_id ID of the match or question.
	 * @param int $result_code 0 = error, 1 = successful save.
	 * @param string $log_value Describes the action.
	 * @return bool
	 */
	public static function log_message( int $user_id, int $type, int $source_id, int $result_code, string $log_value ): bool
	{
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;

		$sql = $wpdb->prepare(
			"INSERT INTO {$prefix}predictions_audit_log 
				( log_date, user_id, type, source_id, result_code, log_value ) 
			VALUES 
			    ( %s, %d, %d, %d, %d, %s )",
			current_time( 'mysql', true ), $user_id, $type, $source_id, $result_code, $log_value
		);
		return ( $wpdb->query( $sql ) !== false );
	}

	/**
	 * Logs a generic error message in the predictions audit log.
	 *
	 * @param int $user_id ID of the user that makes the save.
	 * @param int $type 0 for matches and 1 for questions (FOOTBALLPOOL_TYPE_MATCH and FOOTBALLPOOL_TYPE_QUESTION).
	 * @param int $source_id ID of the match or question.
	 * @return bool
	 */
	public static function log_error_message( int $user_id, int $type, int $source_id ): bool
	{
		$types = ['match', 'question'];
		return self::log_message( $user_id, $type, $source_id, 0,
			sprintf(
				'Something went wrong while saving the prediction for %s with ID %d',
				$types[$type],
				$source_id
			)
		);
	}

}
