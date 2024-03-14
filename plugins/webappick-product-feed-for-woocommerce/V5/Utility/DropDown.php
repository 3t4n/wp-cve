<?php

namespace CTXFeed\V5\Utility;
class DropDown {
	/**
	 * @var string $options Hold Options
	 */
	private static $options;

	public function __construct() {
		self::$options = '';
	}

	/**
	 * Create Dropdown from array.
	 *
	 * @param array        $data      Array to Create Dropdown.
	 * @param string       $cache_key Cache Key if dropdown need to be cached or get the dropdown from cache.
	 * @param string|array $selected  Option value that need to be selected.
	 * @param bool         $cache     Cache Status.
	 *
	 * @return array|false|mixed|string|string[]
	 */
	public static function Create( $data, $selected, $cache_key = null, $cache = false ) {
		//TODO: Option to set disabled options.

		self::$options = "";

		if ( empty( $data ) || ! is_array( $data ) ) {
			return "<option class='disabled' selected>No data available.</option>";
		}

		// If $cache true then return cached data.
		if ( $cache ) {
			self::$options = Cache::get( $cache_key );
			if ( self::$options ) {
				if ( $selected !== '' && is_string( $selected ) ) {
					$selected      = esc_attr( $selected );
					self::$options = str_replace( "value=\"$selected\"", "value=\"$selected\" selected", self::$options );
				} elseif ( $selected !== '' && is_array( $selected ) ) {
					foreach ( $selected as $selectedValue ) {
						$selectedValue = esc_attr( $selectedValue );
						self::$options = str_replace( "value=\"$selectedValue\"", "value=\"$selectedValue\" selected", self::$options );
					}
				}

				return self::$options;
			}
		}

		if ( count($data) !== count($data, COUNT_RECURSIVE) ) {

			foreach ( $data as $value ) {
				if ( isset( $value['optionGroup'] ) ) {
					self::$options .= "<optgroup label=\"{$value['optionGroup']}\">";
				}

				if ( isset( $value['options'] ) && ! empty( $value['options'] ) ) {
					foreach ( $value['options'] as $optionKey => $option ) {
						self::$options .= sprintf( '<option value="%s">%s</option>', $optionKey, $option );
					}

					self::$options .= isset( $value['optionGroup'] ) ? '</optgroup>' : '';
				}
			}
		} else {
			foreach ( $data as $optionKey => $option ) {
				self::$options .= sprintf( '<option value="%s">%s</option>', $optionKey, $option );
			}
		}

		// If $cache true then set cache.
		if ( $cache ) {
			Cache::set( $cache_key, self::$options );
		}

		if ( $selected !== '' && is_string( $selected ) ) {
			//$selected      = esc_attr( $selected );
			self::$options = str_replace( "value=\"$selected\"", "value=\"$selected\" selected", self::$options );
		} elseif ( $selected !== '' && is_array( $selected ) ) {
			foreach ( $selected as $selectedValue ) {
				$selectedValue = esc_attr( $selectedValue );
				self::$options = str_replace( "value=\"$selectedValue\"", "value=\"$selectedValue\" selected", self::$options );
			}
		}

		return self::$options;
	}
}
