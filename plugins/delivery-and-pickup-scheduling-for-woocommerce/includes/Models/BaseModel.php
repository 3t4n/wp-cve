<?php
/**
 * Base model class.
 *
 * Author:          Uriahs Victor
 * Created on:      25/11/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Models
 */

namespace Lpac_DPS\Models;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class BaseModel.
 *
 * @package Lpac_DPS\Models
 * @since 1.0.0
 */
class BaseModel {

	/**
	 * Days of the week.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected $days_of_the_week = array(
		'sunday', // Counting of days of the week starts on Sunday with an index of 0.
		'monday',
		'tuesday',
		'wednesday',
		'thursday',
		'friday',
		'saturday',
	);

	/**
	 * Get a specific settings option from settings array.
	 *
	 * @param string $option The option setting to retrieve from the settings array.
	 * @param string $default The value to return if no value exists.
	 * @return mixed
	 * @since 1.0.0
	 * @since 1.0.8 Changed the return method to better handle default values.
	 */
	public static function get_setting( string $option = '', $default = '' ) {
		$options = get_option( 'lpac_dps' );

		if ( ! isset( $options[ $option ] ) ) {
			return $default;
		}

		if ( '' === $options[ $option ] ) {
			return $default;
		}

		return $options[ $option ];
	}
}
