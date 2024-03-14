<?php

namespace WPDesk\FlexibleWishlist\Migration;

use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FlexibleWishlistVendor\WPDesk_Plugin_Info;

/**
 * Manage database table migration after plugin installation or update.
 */
class MigrationsManager implements Hookable {

	const PLUGIN_MIGRATION_OPTION_KEY = 'flexible_wishlist_migration_version';

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
		add_action( 'plugins_loaded', [ $this, 'make_migrations' ] );
	}

	/**
	 * @return Migration100[]
	 */
	private static function get_migrations(): array {
		return [
			new Migration100(),
		];
	}

	/**
	 * @return void
	 *
	 * @internal
	 */
	public function make_migrations() {
		$current_migration = get_option( self::PLUGIN_MIGRATION_OPTION_KEY, '0.0.0' );
		if ( $current_migration === $this->plugin_info->get_version() ) {
			return;
		}

		foreach ( self::get_migrations() as $migration ) {
			if ( $migration->get_version() > $this->plugin_info->get_version() ) {
				$migration->down();
			} elseif ( $migration->get_version() > $current_migration ) {
				$migration->up();
			}
		}

		update_option( self::PLUGIN_MIGRATION_OPTION_KEY, $this->plugin_info->get_version() );
	}

	/**
	 * @return void
	 */
	public static function reset_all_migrations() {
		foreach ( array_reverse( self::get_migrations() ) as $migration ) {
			$migration->down();
		}

		delete_option( self::PLUGIN_MIGRATION_OPTION_KEY );
	}
}
