<?php
/**
 * Core class for handling this plugin transient.
 *
 * @package Omnipress
 */

namespace Omnipress;

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Transient {

	/**
	 * Transient key prefix.
	 *
	 * @var string
	 */
	protected $prefix = '_omnipress_';

	/**
	 * Transient key suffix
	 *
	 * @var string
	 */
	protected $suffix = '';

	/**
	 * Transient key.
	 *
	 * @var string
	 */
	protected $transient = '';

	/**
	 * Class construct.
	 */
	public function __construct( $suffix = '' ) {
		$this->suffix    = $suffix;
		$this->transient = $this->prefix . $this->suffix;
	}

	/**
	 * Set transient.
	 */
	public function set( $value, $expiration = 0 ) {
		return set_transient( $this->transient, $value, $expiration );
	}

	/**
	 * Get transient.
	 */
	public function get() {
		return get_transient( $this->transient );
	}

	/**
	 * Delete transient.
	 */
	public function delete() {
		return delete_transient( $this->transient );
	}

	/**
	 * Delete all transients created by omnipress.
	 *
	 * @return void
	 */
	public function delete_all() {

		global $wpdb;

		$like_pattern = '%' . $wpdb->esc_like( "_{$this->prefix}" ) . '%';

		$option_names = $wpdb->get_col( $wpdb->prepare(
			"SELECT option_name
			FROM {$wpdb->options}
			WHERE option_name LIKE %s",
			$like_pattern
		));

		if ( is_array( $option_names ) && ! empty( $option_names ) ) {
			foreach ( $option_names as $transient_key ) {
				delete_option( $transient_key );
			}
		}
	}
}