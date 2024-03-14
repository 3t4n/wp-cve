<?php

namespace AppBuilder;

/**
 * Class Setting
 * @author ngocdt@rnlab.io
 * @since 1.0.0
 */
class Setting {

	/**
	 * Option key store in database
	 */
	public $option_key = 'app_builder_settings';

	/**
	 * Get all the settings
	 *
	 * @return array
	 */
	public function settings(): array {
		$default = apply_filters( $this->option_key, [
			'jwt'      => [
				'secret_key' => defined('AUTH_KEY') ? AUTH_KEY : home_url('/app'),
				'exp'        => 30,
			],
			'facebook' => [
				'app_id'     => '',
				'app_secret' => '',
			],
			'google'   => [
				'key' => '',
			],
		] );

		$settings = get_option( $this->option_key, array() );

		return wp_parse_args( $settings, $default );
	}

	/**
	 *
	 * Get setting by key
	 *
	 * @param string $key
	 *
	 * @return false|mixed
	 */
	public function get( string $key ) {
		$settings = $this->settings();

		if ( isset( $settings[ $key ] ) ) {
			return $settings[ $key ];
		}

		return false;
	}

	/**
	 *
	 * Update or Create settings
	 *
	 * @param array $value
	 *
	 * @return bool
	 */
	public function set( array $value ): bool {
		if ( get_option( $this->option_key ) != false ) {
			return update_option( $this->option_key, $value, false );
		} else {
			return add_option( $this->option_key, $value, false );
		}
	}
}
