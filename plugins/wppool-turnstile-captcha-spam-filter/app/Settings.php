<?php
/**
 * Main plugin class.
 *
 * @since   1.0.0
 * @package EasyCloudflareTurnstile
 */

namespace EasyCloudflareTurnstile;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Manages general turnstile integrations settings.
 *
 * @since 1.0.1
 */
class Settings {

	/**
	 * Contains default settings of the plugin.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_all()
	{
		$defaults = [
			'site_key'      => '',
			'secret_key'    => '',
			'login_form'    => '',
			'theme'         => 'light',
			'button_access' => false,
			'error_msg'     => '',
		];

		$settings = get_option( EASY_CLOUDFLARE_TURNSTILE_PREFIX . 'settings', [] );
		$settings = is_array( $settings ) ? $settings : json_decode( $settings, true );
		$settings = wp_parse_args( $settings, $defaults );

		return $settings;
	}

	/**
	 * Retrieve setting value by the key.
	 *
	 * @param string $key The settings key.
	 * @param mixed  $default The default value.
	 *
	 * @since  1.0.0
	 * @return mixed
	 */
	public function get( $key, $default = null )
	{
		$settings = $this->get_all();

		if ( $default ) {
			return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
		}

		return isset( $settings[ $key ] ) ? $settings[ $key ] : false;
	}

	/**
	 * Save settings.
	 *
	 * @param array $settings The settings to save.
	 * @since  1.0.0
	 * @return array
	 */
	public function save( array $settings )
	{
		$settings = wp_json_encode( wp_parse_args( $settings, $this->get_all() ) );

		return update_option( EASY_CLOUDFLARE_TURNSTILE_PREFIX . 'settings', $settings );
	}
}