<?php

namespace WPDesk\FlexibleWishlist\Settings;

use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk\FlexibleWishlist\Repository\SettingsRepository;
use WPDesk\FlexibleWishlist\Settings\Option\MenuSelectedOption;

/**
 * Manage modification of plugin settings after saving changes to menu items.
 */
class MenuSettingsUpdater implements Hookable {

	/**
	 * @var SettingsRepository
	 */
	private $settings_repository;

	public function __construct( SettingsRepository $settings_repository ) {
		$this->settings_repository = $settings_repository;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hooks() {
		add_action( 'wp_update_nav_menu_item', [ $this, 'update_menu_settings' ] );
	}

	/**
	 * Removes a menu from the list of selected menus after saving changes to that menu.
	 *
	 * @param int $menu_id .
	 *
	 * @return void
	 */
	public function update_menu_settings( int $menu_id ) {
		$settings    = $this->settings_repository->get_values();
		$value_index = array_search( $menu_id, $settings[ MenuSelectedOption::FIELD_NAME ], false );
		if ( $value_index === false ) {
			return;
		}

		unset( $settings[ MenuSelectedOption::FIELD_NAME ][ $value_index ] );
		$this->settings_repository->save_values( $settings );
	}
}
