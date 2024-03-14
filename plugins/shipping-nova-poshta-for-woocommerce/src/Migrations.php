<?php
/**
 * Migrations class
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta;

/**
 * Class Migrations
 *
 * @package NovaPoshta
 */
class Migrations {

	/**
	 * Option name for plugin version.
	 */
	const OPTION_NAME = 'shipping_nova_poshta_for_woocommerce_version';

	/**
	 * Migration end successfully.
	 *
	 * @var bool
	 */
	private $is_migrated = false;

	/**
	 * Load hooks.
	 */
	public function hooks() {

		add_action( 'admin_init', [ $this, 'migration_run' ] );
	}

	/**
	 * Run migration.
	 */
	public function migration_run() {

		$version = $this->get_version();

		$this->migration( $version );

		if ( $this->is_migrated ) {
			update_option( self::OPTION_NAME, Main::VERSION );
		}
	}

	/**
	 * Migration process.
	 *
	 * @param string $version Version.
	 */
	private function migration( string $version ) {

		if ( version_compare( $version, '1.5.0', '<' ) ) {
			$this->v150_migrate();
		}
	}

	/**
	 * Migration for v1.5.0.
	 */
	private function v150_migrate() {

		global $wpdb;

		$wpdb->query( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			'DELETE FROM ' . $wpdb->order_itemmeta . ' WHERE meta_key = "internet_document" AND meta_value LIKE "%O:%"'
		);

		$this->is_migrated = true;
	}

	/**
	 * Get current option.
	 *
	 * @return string
	 */
	private function get_version(): string {

		$version = get_option( self::OPTION_NAME, '0.0.1' );

		return is_string( $version ) ? $version : '0.0.1';
	}
}
