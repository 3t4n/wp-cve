<?php

namespace AppBuilder;

/**
 * Class Addons
 * @author ngocdt@rnlab.io
 * @since 1.0.11
 */
class Addons {

	/**
	 * Option key store in database
	 */
	public $option_key = 'app_builder_addons';

	/**
	 * Get all the addons
	 *
	 * @return array
	 */
	public function addons(): array {
		$default = apply_filters( $this->option_key, array(
			'content-egg' => true
		) );

		$addons = get_option( $this->option_key, array() );

		return wp_parse_args( $addons, $default );
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
		$addons = $this->addons();

		if ( isset( $addons[ $key ] ) ) {
			return $addons[ $key ];
		}

		return false;
	}

	/**
	 *
	 * Update or Create addons
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
