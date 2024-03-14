<?php

/**
 * Various utility functions
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_KB_Core_Utilities {

	const KB_CONFIG_PREFIX =  'ep'.'kb_config_';
	const KB_POST_TYPE_PREFIX = 'ep'.'kb_post_type_';  // changing this requires db update
	const KB_POST_TYPE_PREFIX_SHORT = 'ep'.'kb_post_type';  // changing this requires db update
	const KB_CATEGORY_TAXONOMY_SUFFIX = '_category';  // changing this requires db update; do not translate
	const KB_TAG_TAXONOMY_SUFFIX = '_tag'; // changing this requires db update; do not translate
	const KB_ARTICLES_SEQUENCE = 'ep'.'kb_articles_sequence';
	const DEFAULT_KB_ID = 1;

	public static function is_kb_or_amag_enabled() {
		return defined( 'EP' . 'KB_PLUGIN_NAME' ) || defined( 'AMAG_PLUGIN_NAME' );
	}

	/**
	 * Retrieve KB post type name e.g. ep kb_post_type_1
	 *
	 * @param $kb_id - assumed valid id
	 *
	 * @return string
	 */
	public static function get_post_type( $kb_id ) {
		$kb_id = EPHD_Utilities::sanitize_int( $kb_id, self::DEFAULT_KB_ID );
		return self::KB_POST_TYPE_PREFIX . $kb_id;
	}

	/**
	 * Retrieve KB id by post type name e.g. ep kb_post_type_1
	 *
	 * @param $post_type
	 *
	 * @return int
	 */
	public static function get_kb_id_by_post_type( $post_type ) {
		$kb_id = 0;
		if ( ! empty( self::is_kb_post_type( $post_type ) ) ) {
			$kb_id = EPHD_Utilities::sanitize_int( str_replace( self::KB_POST_TYPE_PREFIX, '', $post_type ) );
		}
		return $kb_id;
	}

	/**
	 * Is this KB post type?
	 *
	 * @param $post_type
	 * @return bool
	 */
	public static function is_kb_post_type( $post_type ) {
		if ( empty($post_type) || ! is_string($post_type)) {
			return false;
		}
		// we are only interested in KB articles
		return strncmp($post_type, self::KB_POST_TYPE_PREFIX, strlen(self::KB_POST_TYPE_PREFIX)) == 0;
	}

	/**
	 * Return category name e.g. ep kb_post_type_1_category
	 *
	 * @param $kb_id - assumed valid id
	 *
	 * @return string
	 */
	public static function get_category_taxonomy_name( $kb_id ) {
		return self::get_post_type( $kb_id ) . self::KB_CATEGORY_TAXONOMY_SUFFIX;
	}

	/**
	 * Return tag name e.g. ep kb_post_type_1_tag
	 *
	 * @param $kb_id - assumed valid id
	 *
	 * @return string
	 */
	public static function get_tag_taxonomy_name( $kb_id ) {
		return self::get_post_type( $kb_id ) . self::KB_TAG_TAXONOMY_SUFFIX;
	}
	/**
	 * Get KB-SPECIFIC option. Function adds KB ID suffix. Prefix represent core or ADD-ON prefix.
	 *
	 * @param $kb_id - assuming it is a valid ID
	 * @param $option_name - without kb suffix
	 * @param $default - use if KB option not found
	 * @param bool $is_array - ensure returned value is an array, otherwise return default
	 * @return string|array|null or default
	 */
	public static function get_kb_option( $kb_id, $option_name, $default, $is_array=false ) {
		$full_option_name = $option_name . '_' . $kb_id;
		return EPHD_Utilities::get_wp_option( $full_option_name, $default, $is_array );
	}

	public static function get_kb_config( $kb_id ) {

		$all_kb_configs = self::get_kb_configs();
		if ( empty( $all_kb_configs ) ) {
			return null;
		}


		foreach ( $all_kb_configs as $one_kb_config ) {
			if ( $one_kb_config['id'] == $kb_id ) {
				return $one_kb_config['id'];
			}
		}

		if ( ! empty( $one_kb_config[self::DEFAULT_KB_ID] ) ) {
			return $one_kb_config[self::DEFAULT_KB_ID];
		}

		return null;
	}

	public static function get_kb_configs() {
		/** @var $wpdb Wpdb */
		global $wpdb;

		// retrieve all KB options for existing knowledge bases from WP Options table
		$kb_configs = $wpdb->get_results( "SELECT option_value FROM $wpdb->options WHERE option_name LIKE '" . EPHD_KB_Core_Utilities::KB_CONFIG_PREFIX . "%'", ARRAY_A );
		if ( empty( $kb_configs ) || ! is_array( $kb_configs ) ) {
			EPHD_Logging::add_log( "Did not retrieve any kb config. Using defaults (22). Last error: " . $wpdb->last_error, $kb_configs );
			return null;
		}

		// unserialize options and use defaults if necessary
		$kb_options_checked = array();
		foreach ( $kb_configs as $ix => $row ) {

			if ( empty( $row['option_value'] ) ) {
				return null;
			}

			$config = maybe_unserialize( $row['option_value'] );
			if ( $config === false ) {
				EPHD_Logging::add_log( "Could not unserialize configuration: ", EPHD_Utilities::get_variable_string( $row['option_value'] ) );
				return null;
			}

			if ( empty( $config ) || ! is_array( $config ) ) {
				EPHD_Logging::add_log( "Did not fisnd configuration" );
				return null;
			}

			if ( empty( $config['id'] ) ) {
				EPHD_Logging::add_log( "Found invalid configuration", $config );
				return null;
			}

			$kb_id = ( $config['id'] === self::DEFAULT_KB_ID ) ? $config['id'] : EPHD_Utilities::sanitize_get_id( $config['id'] );
			if ( is_wp_error( $kb_id ) ) {
				return null;
			}

			$kb_options_checked[$kb_id] = $config;
		}

		return $kb_options_checked;
	}

	/**
	 * Is WPML enabled? Only for KB CORE. ADD-ONs to call this function in core
	 *
	 * @param $kb_id
	 *
	 * @return bool
	 */
	public static function is_wpml_enabled_addon( $kb_id ) {

		if ( EPHD_Utilities::is_positive_int( $kb_id ) ) {
			$kb_config = self::get_kb_config( $kb_id );
			if ( is_wp_error( $kb_config ) ) {
				return false;
			}
		} else {
			return false;
		}

		return EPHD_Utilities::is_wpml_enabled( $kb_config );
	}
}