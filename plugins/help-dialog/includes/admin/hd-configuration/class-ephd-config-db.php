<?php

/**
 * Manage plugin configuration (plugin-wide ) in the database.
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Config_DB {

	// Prefix for WP option name that stores settings
	const EPHD_GLOBAL_CONFIG_NAME = 'ephd_global_config';
	const EPHD_NOTIFICATION_RULES_CONFIG_NAME = 'ephd_notification_rules_config';

	private $option_name;
	private $cached_settings = null;
	private $is_single_array;
	private $default_config;
	private $fields_specification;

	public function __construct( $option_name ) {
		$this->option_name = $option_name;
		$this->is_single_array = in_array( $this->option_name, [ self::EPHD_GLOBAL_CONFIG_NAME ] );
		$this->default_config = EPHD_Config_Specs::get_default_hd_config( $this->option_name );
		$this->fields_specification = EPHD_Config_Specs::get_fields_specification( $this->option_name );
	}

	/**
	 * Get settings from the WP Options table.
	 * If settings are missing then use defaults.
	 *
	 * For single array config returns array - default is specs config
	 * For non-single config returns array of arrays - default is array with one specs config as the first element
	 *
	 * @param bool $return_error
	 * @return array|WP_Error return current HD configuration
	 */
	public function get_config( $return_error=false ) {

		// retrieve config if already cached
		if ( is_array( $this->cached_settings ) ) {
			$config = [];

			$config_array = $this->is_single_array ? [ $this->cached_settings ] : $this->cached_settings;
			foreach ( $config_array as $instance_id => $cached_setting ) {
				$config[$instance_id] = wp_parse_args( $cached_setting, $this->default_config );
			}

			return $this->is_single_array ? $config[0] : $config;
		}

		// retrieve Plugin config
		$config = get_option( $this->option_name, [] );
		if ( empty( $config ) || ! is_array( $config ) || ( ! $this->is_single_array && empty( $config[EPHD_Config_Specs::DEFAULT_ID] ) ) ) {

			// write error to log
			EPHD_Logging::add_log( "Did not find HD configuration (DB231)." );

			// return WP_Error if specified by parameter
			if ( $return_error ) {
				return new WP_Error( 'DB231', __( "Did not find Help Dialog configuration.", 'help-dialog' ) . ' (E80) ' );
			}

			// return default config
			if ( $this->is_single_array ) {
				return $this->default_config;
			} else {
				$config[EPHD_Config_Specs::DEFAULT_ID] = $this->default_config;
				return $config;
			}
		}

		$config_array = $this->is_single_array ? [ $config ] : $config;
		foreach ( $config_array as $instance_id => $setting ) {
			$config[$instance_id] = wp_parse_args( $setting, $this->default_config );
		}
		$config = $this->is_single_array ? $config[0] : $config;

		// cached the config for future use
		$this->cached_settings = $config;

		return $config;
	}

	/**
	 * Return specific value from the plugin settings values. Values are automatically trimmed.
	 *
	 * @param $setting_name
	 *
	 * @param string $default
	 * @return string with value or empty string if this settings not found
	 */
	public function get_value( $setting_name, $default='' ) {

		if ( empty( $setting_name ) ) {
			return $default;
		}

		$hd_config = $this->get_config();
		if ( isset( $hd_config[$setting_name] ) ) {
			return $hd_config[$setting_name];
		}

		$default_settings = $this->default_config;

		return isset( $default_settings[$setting_name] ) ? $default_settings[$setting_name] : $default;
	}

	/**
	 * Set specific value in HD Configuration
	 *
	 * @param $key
	 * @param $value
	 * @return array|WP_Error
	 */
	public function set_value( $key, $value ) {

		$hd_config = $this->get_config( true );
		if ( is_wp_error( $hd_config ) ) {
			return $hd_config;
		}

		$hd_config[$key] = $value;

		return $this->update_config( $hd_config );
    }

	/**
	 * Update HD Configuration. Use default if config missing.
	 *
	 * @param array $config contains HD configuration or empty if adding default configuration
	 *
	 * @return array|WP_Error configuration that was updated
	 */
	public function update_config( $config ) {

		// only single array Config cannot be empty, the rest can be empty array
		if ( ! is_array( $config ) || ( empty( $config ) && $this->is_single_array ) ) {
			return new WP_Error( 'update_config', 'Configuration is empty' );
		}

		$input_filter = new EPHD_Input_Filter();

		$config_array = $this->is_single_array ? [ $config ] : $config;
		$sanitized_config = [];
		foreach ( $config_array as $instance_id => $setting ) {

			// first sanitize and validate input
			$new_settings = $input_filter->validate_and_sanitize_specs( $setting, $this->fields_specification );
			if ( is_wp_error( $new_settings ) ) {
				EPHD_Logging::add_log( 'Failed to sanitize Plugin settings', $new_settings );
				return $new_settings;
			}

			// use defaults for missing configuration
			$sanitized_config[$instance_id] = wp_parse_args( $new_settings, $this->default_config );
		}
		$sanitized_config = $this->is_single_array ? $sanitized_config[0] : $sanitized_config;

		return $this->save_config( $sanitized_config );
	}

	/**
	 * Insert or update HD configuration
	 *
	 * @param array $sanitized_config
	 * @return array|WP_Error if configuration is missing or cannot be serialized
	 */
	private function save_config( $sanitized_config ) {

		// check if config can be properly serialized
		$serialized_config = maybe_serialize( $sanitized_config );
		if ( empty( $serialized_config ) ) {
			EPHD_Logging::add_log( 'Failed to serialize HD config', 'Config: ' . $this->option_name );
			return new WP_Error( 'serialize_config', 'Failed to serialize HD config. Config: ' . $this->option_name );
		}

		// run update only if new and old configs are different; otherwise, 'update_option()' will not update config and return 'false', so catch it earlier
		$old_config = get_option( $this->option_name, [] );
		if ( $serialized_config === maybe_serialize( $old_config ) ) {
			return $sanitized_config;
		}

		// update configuration or return error
		$result = update_option( $this->option_name, $sanitized_config );
		if ( $result === false ) {
			EPHD_Logging::add_log( 'Failed to update HD config', 'Config: ' . $this->option_name );
			return new WP_Error( 'save_config', 'Failed to update HD config. Config: ' . $this->option_name );
		}

		// cached the settings for future use
		$this->cached_settings = $sanitized_config;

		wp_cache_flush();

		return $sanitized_config;
	}
}