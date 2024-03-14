<?php

namespace WPDesk\FlexibleWishlist\Service;

use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FlexibleWishlistVendor\WPDesk_Plugin_Info;
use WPDesk\FlexibleWishlist\Exception\InvalidSettingsOptionKey;
use WPDesk\FlexibleWishlist\Migration\MigrationsManager;
use WPDesk\FlexibleWishlist\Repository\SettingsRepository;
use WPDesk\FlexibleWishlist\Settings\Option\SettingsClearDataOption;

/**
 * Removes records from the database created by the plugin.
 */
class PluginDataCleaner implements Hookable {

	/**
	 * @var WPDesk_Plugin_Info
	 */
	private $plugin_info;

	public function __construct( WPDesk_Plugin_Info $plugin_info ) {
		$this->plugin_info = $plugin_info;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hooks() {
		$plugin_folder = $this->plugin_info->get_plugin_dir() . '/' . basename( $this->plugin_info->get_plugin_file_name() );
		register_uninstall_hook( $plugin_folder, [ self::class, 'delete_plugin_data' ] );
	}

	/**
	 * @return void
	 * @throws InvalidSettingsOptionKey
	 */
	public static function delete_plugin_data() {
		$settings_repository = new SettingsRepository();
		if ( ! $settings_repository->get_value( SettingsClearDataOption::FIELD_NAME ) ) {
			return;
		}

		MigrationsManager::reset_all_migrations();
		delete_option( SettingsRepository::PLUGIN_SETTINGS_OPTION_NAME );
	}
}
