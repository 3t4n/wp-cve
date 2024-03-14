<?php

namespace NativeRent;

use NativeRent\Admin\Cache_Actions;

use function add_action;
use function defined;
use function esc_sql;
use function get_option;
use function is_array;
use function maybe_unserialize;
use function update_option;
use function version_compare;

use const NATIVERENT_PLUGIN_VERSION;

defined( 'ABSPATH' ) || exit;

/**
 * Class Options
 */
class Migrations {

	/**
	 * Options name
	 *
	 * @var string
	 */
	private static $old_option_name = 'nativerent_options';

	/**
	 * Init.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'check_version' ), 5 );
	}

	/**
	 * Check version and run the update if required
	 *
	 * @return void
	 */
	public static function check_version() {
		$version_in_db = Options::get_version();
		if ( version_compare( $version_in_db, NATIVERENT_PLUGIN_VERSION, '<' ) ) {
			self::migrate( $version_in_db );
			Options::setup_version();
			if ( Options::authenticated() ) {
				Cache_Actions::need_to_clear_cache();
				API::send_state();
			}
		}
	}

	/**
	 * Migration for options
	 *
	 * @param  string $version_in_db  Version plugin is updated from.
	 *
	 * @return void
	 */
	public static function migrate( $version_in_db = '' ) {
		if ( version_compare( $version_in_db, '1.3', '<' ) ) {
			self::migrate_from_1_3();
		}

		if ( version_compare( $version_in_db, '1.4', '<' ) ) {
			self::migrate_from_1_4();
		}

		if ( version_compare( $version_in_db, '1.4.4', '<' ) ) {
			self::migrate_to_1_4_4();
		}

		if ( version_compare( $version_in_db, '1.7.0', '<' ) ) {
			self::migrate_to_1_7_0();
		}

		/**
		 * We also have to repeat this migration when upgrading from 1.8.0 to 1.8.1
		 * due to the addition of site moderation status in the monetizations API response structure.
		 */
		if ( version_compare( $version_in_db, '1.8.0', '<' ) || version_compare( $version_in_db, '1.8.1', '<' ) ) {
			self::migrate_to_1_8_0();
		}

		if ( version_compare( $version_in_db, '1.8.5', '<' ) ) {
			self::migrate_to_1_8_5();
		}
	}

	/**
	 * Migrate from 1.3.x
	 *
	 * @return void
	 */
	private static function migrate_from_1_3() {
		// migrate from < 1.3.x .
		global $wpdb;
		$table_name  = $wpdb->prefix . self::$old_option_name;
		$found_table = $wpdb->get_var(
			$wpdb->prepare(
				'SHOW TABLES LIKE %s',
				$wpdb->esc_like( $table_name )
			)
		);
		if ( $found_table === $table_name ) {
			$old_options = $wpdb->get_results( 'SELECT * FROM ' . esc_sql( $table_name ) );
			if ( is_array( $old_options ) ) {
				$nativerent_options = get_option( 'nativerent_options', array() );
				foreach ( $old_options as $old_option ) {
					$opt_name = maybe_unserialize( $old_option->name );
					if ( isset( $nativerent_options[ $opt_name ] ) ) {
						continue;
					}
					$nativerent_options[ $opt_name ] = maybe_unserialize( $old_option->value );
				}

				update_option( self::$old_option_name, $nativerent_options );
			}
		}
	}

	/**
	 * Migrate from 1.3.x
	 *
	 * @return void
	 */
	private static function migrate_from_1_4() {
		// migrate from 1.4.1 .
		$old_options = get_option( self::$old_option_name, array() );
		if ( ! empty( $old_options ) ) {
			foreach ( $old_options as $opt => $v ) {
				Options::set( $opt, $v );
			}
		}

		// delete old and unnecessary data....
		Options::delete( 'nativerent_options' );
		Options::delete( 'jsURL' );
		Options::delete( 'cssURL' );

		global $wpdb;
		$wpdb->query( 'DROP TABLE IF EXISTS ' . esc_sql( $wpdb->prefix . self::$old_option_name ) );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . esc_sql( $wpdb->prefix . 'nativerent_adv' ) );
	}

	/**
	 * Migrate to 1.4.4
	 *
	 * @return void
	 */
	private static function migrate_to_1_4_4() {
		// revert configs patches from old versions.
		Migrations_Autoconfig::uninstall();
	}

	/**
	 * Migrate to 1.7.0
	 *
	 * @return void
	 */
	private static function migrate_to_1_7_0() {
		Settings::instance()->uninstall();
		Connect_Handler::remove_connect_file_from_config();
	}

	/**
	 * Migrate to 1.8.0
	 * Setup monetizations.
	 *
	 * @return void
	 */
	private static function migrate_to_1_8_0() {
		// Getting actual monetizations if plugin is authorized.
		if ( Options::authenticated() ) {
			API::load_monetizations();
		}
		Options::delete( 'selfCheckReport' );
	}

	/**
	 * Migrate to 1.8.5
	 * Changes to adUnitsConfig struct.
	 * Multiple NTGB configurations.
	 *
	 * @return void
	 */
	private static function migrate_to_1_8_5() {
		$old_config_json = get_option( 'nativerent.adUnitsConfig', '{}' );
		$old_config      = json_decode( $old_config_json, true );
		if ( ! empty( $old_config ) ) {
			$ntgb_conf = array();
			if ( isset( $old_config['ntgb'] ) ) {
				$ntgb_conf = array( '1' => $old_config['ntgb'] );
				unset( $old_config['ntgb'] );
			}

			$new_config = Options::create_adunits_config_map(
				array(
					'regular' => $old_config,
					'ntgb'    => $ntgb_conf,
				),
				false
			);
			Options::update_adunits_config( $new_config );
		}
	}
}
