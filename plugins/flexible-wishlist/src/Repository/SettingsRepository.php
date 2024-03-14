<?php

namespace WPDesk\FlexibleWishlist\Repository;

use WPDesk\FlexibleWishlist\Exception\InvalidSettingsOptionKey;
use WPDesk\FlexibleWishlist\Settings\Group\AdminGroup;
use WPDesk\FlexibleWishlist\Settings\Group\Group;
use WPDesk\FlexibleWishlist\Settings\Group\MenuGroup;
use WPDesk\FlexibleWishlist\Settings\Group\PopupGroup;
use WPDesk\FlexibleWishlist\Settings\Group\TextGroup;
use WPDesk\FlexibleWishlist\Settings\Group\WishlistPageGroup;
use WPDesk\FlexibleWishlist\Settings\Option\Option;
use WPDesk\FlexibleWishlist\Settings\Option\OptionBase;
use WPDesk\FlexibleWishlist\Settings\Option\SettingsResetEnabledOption;

/**
 * Saves and reads plugin settings.
 */
class SettingsRepository {

	const PLUGIN_SETTINGS_OPTION_NAME = 'flexible_wishlist_settings';

	/**
	 * @var mixed[]|null
	 */
	private $cached_global_settings = null;

	/**
	 * @return Group[]
	 */
	public function get_options(): array {
		return [
			new MenuGroup(),
			new PopupGroup(),
			new TextGroup(),
			new WishlistPageGroup(),
			new AdminGroup(),
		];
	}

	/**
	 * @return mixed[]
	 */
	public function get_values(): array {
		if ( $this->cached_global_settings !== null ) {
			return $this->cached_global_settings;
		}

		$plugin_settings = get_option( self::PLUGIN_SETTINGS_OPTION_NAME, [] );
		$values          = [];
		foreach ( $this->get_options() as $group ) {
			foreach ( $group->get_fields() as $field ) {
				if ( isset( $plugin_settings[ $field->get_name() ] ) ) {
					$field_value                  = $this->validate_field_value( $field, $plugin_settings[ $field->get_name() ] );
					$values[ $field->get_name() ] = $field->parse_value(
						( $field_value !== null ) ? $field_value : $field->get_default_value()
					);
				} else {
					$values[ $field->get_name() ] = $field->parse_value(
						$field->get_default_value()
					);
				}
			}
		}
		return $values;
	}

	/**
	 * @param string $option_key .
	 *
	 * @return mixed
	 * @throws InvalidSettingsOptionKey
	 */
	public function get_value( string $option_key ) {
		$values = $this->get_values();
		if ( ! isset( $values[ $option_key ] ) ) {
			throw new InvalidSettingsOptionKey();
		}
		return $values[ $option_key ];
	}

	/**
	 * @param mixed[] $submitted_data .
	 *
	 * @return void
	 */
	public function save_values( array $submitted_data ) {
		$values = [];
		foreach ( $this->get_options() as $group ) {
			foreach ( $group->get_fields() as $field ) {
				$values[ $field->get_name() ] = $this->validate_field_value( $field, $submitted_data[ $field->get_name() ] ?? null );
			}
		}

		if ( $values[ SettingsResetEnabledOption::FIELD_NAME ] ?? false ) {
			$values = [];
		}

		update_option( self::PLUGIN_SETTINGS_OPTION_NAME, $values );
		$this->cached_global_settings = null;
	}

	/**
	 * @return void
	 */
	public function init_options_translations() {
		$plugin_settings = get_option( self::PLUGIN_SETTINGS_OPTION_NAME, [] );
		foreach ( $this->get_options() as $group ) {
			foreach ( $group->get_fields() as $field ) {
				$field_value = $this->validate_field_value( $field, $plugin_settings[ $field->get_name() ] ?? null );
				if ( $field_value === null ) {
					continue;
				}

				$field->init_translation( $field_value );
			}
		}
	}

	/**
	 * @param Option               $option .
	 * @param string|string[]|null $value  .
	 *
	 * @return string|string[]|null
	 */
	private function validate_field_value( Option $option, $value ) {
		switch ( $option->get_type() ) {
			case OptionBase::FIELD_TYPE_INPUT:
			case OptionBase::FIELD_TYPE_URL:
				if ( ( $value === null ) || is_array( $value ) ) {
					return $option->get_default_value();
				}
				return sanitize_text_field( $value );
			case OptionBase::FIELD_TYPE_RADIO:
			case OptionBase::FIELD_TYPE_RADIO_PREVIEW:
				if ( $value === null ) {
					return $option->get_default_value();
				}
				return ( in_array( $value, array_keys( $option->get_options() ), false ) ) ? $value : null;
			case OptionBase::FIELD_TYPE_TOGGLE:
				if ( ( $value === null ) || is_array( $value ) ) {
					return '';
				}
				return ( (string) $value === '1' ) ? '1' : '';
			case OptionBase::FIELD_TYPE_MULTI_CHECKBOX:
				if ( $value === null ) {
					return [];
				}
				foreach ( (array) $value as $value_key ) {
					if ( ! in_array( $value_key, array_keys( $option->get_options() ), false ) ) {
						return null;
					}
				}
				return $value;
		}
		return null;
	}
}
