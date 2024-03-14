<?php

namespace WPDesk\FlexibleWishlist\Settings\Group;

use WPDesk\FlexibleWishlist\Settings\Option\SettingsClearDataOption;
use WPDesk\FlexibleWishlist\Settings\Option\SettingsResetEnabledOption;

/**
 * {@inheritdoc}
 */
class AdminGroup implements Group {

	/**
	 * {@inheritdoc}
	 */
	public function get_label(): string {
		return __( 'Other settings', 'flexible-wishlist' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_fields(): array {
		return [
			new SettingsResetEnabledOption(),
			new SettingsClearDataOption(),
		];
	}
}
