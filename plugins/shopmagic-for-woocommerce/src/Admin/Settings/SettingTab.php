<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Admin\Settings;

use ShopMagicVendor\WPDesk\Forms\Field;
use ShopMagicVendor\WPDesk\Persistence\PersistentContainer;
use WPDesk\ShopMagic\Admin\Settings\SettingsTab as LegacySettingsTab;

interface SettingTab extends LegacySettingsTab {

	public static function get_settings_persistence(): PersistentContainer;

	/** @return Field[] */
	public function get_fields(): array;

}
