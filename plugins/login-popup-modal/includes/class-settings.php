<?php
/**
 * Login modal box Settings.
 *
 * @since   0.0.0
 * @package Login_Modal_Box
 */

/**
 * Login modal box Settings.
 *
 * @since 0.0.0
 */
class LMB_Settings {
	/**
	 * Parent plugin class.
	 *
	 * @since 0.0.0
	 *
	 * @var   Login_Modal_Box
	 */
	protected $plugin = null;

	/**
	 * Property settings array
	 *
	 * @var    string
	 * @since  1.2
	 */
	const SETTINGS = array(
		'header-title'  => NULL,
		'menu-location'	=> NULL,
		'login-id'      => 0,
		'logout-id'     => 0,		
		'site-url' 		=> '/',
	);

	/**
	 * Property settings array
	 *
	 * @var    string
	 * @since  0.0.0
	 */
	protected $settings = array();

	/**
	 * Constructor.
	 *
	 * @since  0.0.0
	 *
	 * @param  Login_Modal_Box $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  0.0.0
	 */
	public function hooks() {
		$this->init_settings();
	}


	/**
	 * Initiate settings.
	 *
	 * @since  0.0.0
	 */
	public function init_settings() {

		// Get options.
		$this->settings = self::SETTINGS;

		$network_id = null;
		if ( is_multisite() ) {
			$network_id = get_current_blog_id();
		}

		$options = get_network_option( $network_id, sprintf( '%s_%s', 'login-modal-box', 'settings' ) );
		if ( $options ) {
			foreach ( $options as $name => $value ) {
				$this->settings[ $name ] = $value;
			}
		}

		// Setup site url.
		$this->settings['site-url'] = get_site_url();
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
		if ( update_network_option( $network_id, sprintf( '%s_%s', 'login-modal-box', 'settings' ), $this->settings ) ) {
			return true;
		}
		return false;
	}

}
