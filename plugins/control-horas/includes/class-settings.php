<?php
/**
 * Control Horas Settings.
 *
 * @since   1.0.2
 * @package 
 */

/**
 * Control Horas Settings.
 *
 * @since 0.1.0
 */
class CH_Settings {
	/**
	 * Parent plugin class.
	 *
	 * @since 1.0.2
	 *
	 * @var   
	 */
	protected $plugin = null;

	/**
	 * Property settings array
	 *
	 * @var    string
	 * @since  1.0.1
	 */
	protected $key = "control-horas";

	/**
	 * Property settings array
	 *
	 * @var    string
	 * @since  1.0.1
	 */
	protected $settings = array(
		'guardar-ip'     => 0,
		);

	/**
	 * Constructor.
	 *
	 * @since  1.0.2
	 *
	 * @param   $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->init_settings();
	}


	/**
	 * Init settings.
	 *
	 * @since  1.0.2
	 */
	public function init_settings() {		
		$network_id = null;
		if ( is_multisite() ) {
			$network_id = get_current_blog_id();
		}

		// get all settings.
		$options = get_network_option( $network_id, sprintf( '%s_%s', $this->key, 'settings' ) );
		if ( $options ) {
			foreach ( $options as $name => $value ) {
				$this->settings[ $name ] = $value;
			}
		}
	}


	/**
	 * Returns all settings
	 *
	 * @return array
	 */
	public function get_settings() {
		return $this->settings;
	}

	/**
	 * Returns the value of given setting key, based on if network settings are enabled or not
	 *
	 * @param string $name Setting to fetch.
	 * @param string $default Default Value.
	 *
	 * @return bool|mixed|void
	 */
	public function get_setting( $name = '', $default = false ) {

		if ( empty( $name ) ) {
			return false;
		}
		return $this->settings[ $name ];
	}


	/**
	 * Update value for given setting key
	 *
	 * @param string $name Key.
	 * @param string $value Value.
	 *
	 * @return bool If the setting was updated or not
	 */
	public function update_setting( $name = '', $value = '' ) {

		if ( empty( $name ) ) {
			return false;
		}

		$network_id = null;
		if ( is_multisite() ) {
			$network_id = get_current_blog_id();
		}

		$value = trim( sanitize_text_field( $value ) );

		$this->settings[ $name ] = $value;

		if ( update_network_option( $network_id, sprintf( '%s_%s', $this->key, 'settings' ), $this->settings ) ) {
			return true;
		}
		return false;
	}

}
