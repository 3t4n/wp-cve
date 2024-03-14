<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'bwf_options_get' ) ) {
	/**
	 * Get value of any column based on column key
	 *
	 * @param $key
	 * @param $column - default value column
	 *
	 * @return mixed|string|null
	 */
	function bwf_options_get( $key = '', $column = 'value' ) {
		global $wpdb;

		if ( empty( $key ) ) {
			return '';
		}

		$query = "SELECT `{$column}` FROM {$wpdb->prefix}bwf_options WHERE `key` = %s";

		$value = $wpdb->get_var( $wpdb->prepare( $query, $key ) );
		if ( empty( $value ) ) {
			return '';
		}

		if ( bwf_is_json( $value ) ) {
			$value = json_decode( $value, true );

			return $value;
		}

		return maybe_unserialize( $value );
	}
}

if ( ! function_exists( 'bwf_options_update' ) ) {
	/**
	 * Update value based on given key
	 *
	 * @param $key
	 * @param $value
	 *
	 * @return bool|int|mysqli_result|resource|null
	 */
	function bwf_options_update( $key, $value ) {
		global $wpdb;

		/** If value is array */
		$value = is_array( $value ) ? json_encode( $value ) : $value;

		$p_key = bwf_options_get( $key, 'id' );
		if ( empty( $p_key ) ) {
			$data = [
				'key'   => $key,
				'value' => $value
			];

			return $wpdb->insert( "{$wpdb->prefix}bwf_options", $data );
		}

		$data = [
			'value' => $value
		];

		return $wpdb->update( $wpdb->prefix . 'bwf_options', $data, array( 'id' => $p_key ) );
	}
}

if ( ! function_exists( 'bwf_options_delete' ) ) {
	/**
	 * Delete an option key
	 *
	 * @param $key
	 *
	 * @return bool|int|mysqli_result|resource|null
	 */
	function bwf_options_delete( $key = '' ) {
		global $wpdb;

		if ( empty( $key ) ) {
			return 0;
		}

		$p_key = bwf_options_get( $key, 'id' );
		if ( empty( $p_key ) ) {
			return 0;
		}

		return $wpdb->delete( $wpdb->prefix . 'bwf_options', [ 'id' => $p_key ], [ '%d' ] );
	}
}

if ( ! function_exists( 'bwf_is_json' ) ) {
	/**
	 * Check if given string is json
	 *
	 * @param $string
	 *
	 * @return bool
	 */
	function bwf_is_json( $string ) {
		if ( ! is_string( $string ) ) {
			return false;
		}
		json_decode( $string );

		return ( json_last_error() === JSON_ERROR_NONE );
	}
}
