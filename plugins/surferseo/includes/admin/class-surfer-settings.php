<?php
/**
 * Object that contains settings.
 *
 * @package SurferSEO
 */

namespace SurferSEO\Admin;

use SurferSEO\Surfer\Content_Parsers\Parsers_Controller;

/**
 * Object that stores settings
 */
class Surfer_Settings {

	/**
	 * Prefix for WordPress settings.
	 *
	 * @var string.
	 */
	private $prefix = 'surfer_';

	/**
	 * Class construction
	 */
	public function __construct() {
	}

	/**
	 * Get plugin settings.
	 *
	 * @param string $module  - name of the module of settings.
	 * @param string $option  - option name.
	 * @param mixed  $default - default value to return.
	 * @return mixed
	 */
	public function get_option( $module, $option, $default ) {

		$module_options = get_option( $this->prefix . $module, false );

		if ( false === $module_options ) {
			return $default;
		}

		if ( isset( $module_options[ $option ] ) ) {
			return $module_options[ $option ];
		}

		return $default;
	}

	/**
	 * Get plugin settings.
	 *
	 * @param string $module - name of the module of settings.
	 * @return mixed
	 */
	public function get_options( $module ) {

		$module_options = get_option( $this->prefix . $module, false );

		if ( false === $module_options ) {
			$module_options = $this->get_default_values( $module );
		}

		return $module_options;
	}

	/**
	 * Saves plugin settings.
	 *
	 * @param string $module - name of the module of settings.
	 * @param string $option - option name.
	 * @param mixed  $value  - value to save for provided option.
	 * @return bool
	 */
	public function save_option( $module, $option, $value ) {

		$module_options = get_option( $this->prefix . $module, false );

		if ( false === $module_options ) {
			$module_options = $this->get_default_values( $module );
		}

		$module_options[ $option ] = $value;

		return update_option( $this->prefix . $module, $module_options );
	}

	/**
	 * Saves multiple options at oce as array.
	 *
	 * @param string $module - name of the module of settings.
	 * @param array  $values - array of pairs option => value.
	 * @return bool
	 */
	public function save_options( $module, $values ) {

		$module_options = get_option( $this->prefix . $module, false );

		if ( false === $module_options ) {
			$module_options = $this->get_default_values( $module );
		}

		foreach ( $values as $option => $value ) {
			$module_options[ $option ] = $value;
		}

		return update_option( $this->prefix . $module, $module_options );
	}

	/**
	 * Returns default settings for provided module.
	 *
	 * @param string $module - Name of the module to get defaults.
	 * @return array
	 */
	private function get_default_values( $module ) {

		$defaults = array();

		switch ( $module ) {
			case 'core':
				$defaults = $this->return_default_core_settings();
				break;
			case 'content-importer':
				$defaults = $this->return_default_content_importer_settings();
				break;
		}

		return $defaults;
	}

	/**
	 * Default settings for core configuration.
	 *
	 * @return array
	 */
	private function return_default_core_settings() {

		$defaults = array(
			'surfer_api_public_key' => '',
		);

		return $defaults;
	}

	/**
	 * Default settings for Content Importer
	 *
	 * @return array
	 */
	private function return_default_content_importer_settings() {

		$args  = array(
			'role'    => 'Administrator',
			'orderby' => 'user_nicename',
			'order'   => 'ASC',
		);
		$users = get_users( $args );

		$defaults = array(
			'surfer_url'                          => 'https://app.surferseo.com',
			'surfer_api_url'                      => 'https://app.surferseo.com/api/v1/wordpress/',
			'default_content_editor'              => Parsers_Controller::GUTENBERG,
			'default_post_author'                 => $users[0]->ID,
			'default_post_status'                 => 'draft',
			'default_category'                    => false,
			'default_tags'                        => false,
			'surfer_gsc_meta_script'              => false,
			'surfer_gsc_data_collection_interval' => 7,
		);

		return $defaults;
	}
}
