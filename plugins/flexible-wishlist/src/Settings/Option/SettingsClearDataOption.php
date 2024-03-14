<?php

namespace WPDesk\FlexibleWishlist\Settings\Option;

/**
 * {@inheritdoc}
 */
class SettingsClearDataOption extends OptionBase {

	const FIELD_NAME = 'settings_clear_data';

	/**
	 * {@inheritdoc}
	 */
	public function get_name(): string {
		return self::FIELD_NAME;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_label(): string {
		return __( 'Clear plugin data when uninstalling', 'flexible-wishlist' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_description() {
		return __( 'When uninstalling a plugin, all plugin data and created wishlists will be deleted.', 'flexible-wishlist' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type(): string {
		return OptionBase::FIELD_TYPE_TOGGLE;
	}
}
