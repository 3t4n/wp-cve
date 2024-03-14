<?php

if ( ! function_exists( 'bwf_get_remote_rest_args' ) ) {
	/**
	 * Get wp remote post arguments
	 *
	 * @param $data
	 * @param $method
	 *
	 * @return mixed|void
	 */
	function bwf_get_remote_rest_args( $data = '', $method = 'POST' ) {
		return apply_filters( 'bwf_get_remote_rest_args', [
			'method'    => $method,
			'body'      => $data,
			'timeout'   => 0.01,
			'sslverify' => false,
		] );
	}
}

if ( ! function_exists( 'bwf_clean' ) ) {
	/**
	 * Sanitize the given string or array
	 *
	 * @param $var
	 *
	 * @return array|mixed|string
	 */
	function bwf_clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'bwf_clean', $var );
		}

		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}

if ( ! function_exists( 'bwf_get_states' ) ) {
	/**
	 * Get states nice name from country and state slugs
	 *
	 * @param $country
	 * @param $state
	 *
	 * @return mixed|string
	 */
	function bwf_get_states( $country = '', $state = '' ) {
		$country_states = apply_filters( 'bwf_get_states', include WooFunnel_Loader::$ultimate_path . 'helpers/states.php' );

		if ( empty( $state ) ) {
			return '';
		}
		if ( empty( $country ) ) {
			return $state;
		}
		if ( ! isset( $country_states[ $country ] ) ) {
			return $state;
		}
		if ( ! isset( $country_states[ $country ][ $state ] ) ) {
			return $state;
		}

		return $country_states[ $country ][ $state ];
	}
}

if ( ! function_exists( 'bwf_get_fonts_list' ) ) {
	/**
	 * get the list of all the registered fonts
	 * we have 3 modes here, 'standard', 'name_only','name_key' and 'all'
	 *
	 * @param string $mode
	 *
	 * @return array|int[]|mixed|string[]
	 */
	function bwf_get_fonts_list( $mode = 'standard' ) {
		$fonts        = [];
		$font_path    = WooFunnel_Loader::$ultimate_path . '/helpers/fonts.json';
		$google_fonts = json_decode( file_get_contents( $font_path ), true );     //phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown
		$web_fonts    = ( $mode !== 'all' ) ? array_keys( $google_fonts ) : $google_fonts;

		if ( $mode === 'all' || $mode === 'name_only' ) {
			return $web_fonts;
		}

		/**
		 * if the name_key mode
		 */
		if ( $mode === 'name_key' ) {
			foreach ( $web_fonts as $web_font_family ) {
				if ( $web_font_family !== 'Open Sans' ) {
					$fonts[ $web_font_family ] = $web_font_family;
				}
			}

			return $fonts;
		}

		/**
		 * if standard mode
		 */
		$fonts[] = array(
			'id'   => 'default',
			'name' => __( 'Default', 'funnel-builder' )
		);
		foreach ( $web_fonts as $web_font_family ) {
			if ( $web_font_family !== 'Open Sans' ) {
				$fonts[] = array(
					'id'   => $web_font_family,
					'name' => $web_font_family,
				);
			}
		}

		return $fonts;
	}
}

/**
 * Converts a string (e.g. 'yes' or 'no' , 'true') to a bool.
 *
 * @param $string
 *
 * @return bool
 */
if ( ! function_exists( 'bwf_string_to_bool' ) ) {
	function bwf_string_to_bool( $string ) {
		return is_bool( $string ) ? $string : ( 'yes' === strtolower( $string ) || 1 === $string || 'true' === strtolower( $string ) || '1' === $string );
	}
}

if ( ! function_exists( 'bwf_clear_queries' ) ) {
	/**
	 * Dev
	 * Make WPDB queries empty
	 * @return void
	 */
	function bwf_clear_queries() {
		global $wpdb;
		$wpdb->queries = [];
	}
}

if ( ! function_exists( 'bwf_save_queries' ) ) {
	/**
	 * Dev
	 * Save DB calls from WPDB class object
	 *
	 * @param $file_name
	 * @param $folder_name
	 * @param $reference
	 *
	 * @return void
	 */
	function bwf_save_queries( $file_name = 'general', $folder_name = 'funnelkit', $reference = 'DB Call' ) {
		global $wpdb;

		if ( empty( $wpdb->queries ) || ! is_array( $wpdb->queries ) ) {
			return;
		}

		$queries = [];
		foreach ( $wpdb->queries as $q ) {
			$queries[] = [ $q[0], $q[2] ];
		}

		$message = print_r( $queries, true );

		$file_name  = sanitize_title( $file_name );
		$logger_obj = BWF_Logger::get_instance();

		add_filter( 'bwf_logs_allowed', 'bwf_return_true', 99999 );
		if ( ! empty( $reference ) ) {
			$logger_obj->log( $reference, $file_name, $folder_name );
		}
		$logger_obj->log( $message, $file_name, $folder_name );
		remove_filter( 'bwf_logs_allowed', 'bwf_return_true', 99999 );
	}
}

if ( ! function_exists( 'bwf_return_true' ) ) {
	/**
	 * @return bool
	 */
	function bwf_return_true() {
		return true;
	}
}