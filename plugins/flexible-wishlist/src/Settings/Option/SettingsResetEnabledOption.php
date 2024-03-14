<?php

namespace WPDesk\FlexibleWishlist\Settings\Option;

/**
 * {@inheritdoc}
 */
class SettingsResetEnabledOption extends OptionBase {

	const FIELD_NAME = 'settings_reset_enabled';

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
		return __( 'Reset to default settings', 'flexible-wishlist' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_description() {
		return __( 'After saving changes, the plugin settings will be reset to default.', 'flexible-wishlist' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type(): string {
		return OptionBase::FIELD_TYPE_TOGGLE;
	}
}
