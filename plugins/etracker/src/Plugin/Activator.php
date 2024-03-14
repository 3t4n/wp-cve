<?php
/**
 * Fired during plugin activation
 *
 * @link       https://etracker.com
 * @since      1.0.0
 *
 * @package    Etracker
 * @subpackage Etracker/includes
 */

namespace Etracker\Plugin;

use Etracker\Database\LoggingTable;
use Etracker\Database\ReportingDataTable;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 *
 * @package    Etracker
 * @subpackage Etracker/includes
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class Activator {
	/**
	 * Plugin activator.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// phpcs:disable WordPressVIPMinimum.Functions.RestrictedFunctions.flush_rewrite_rules_flush_rewrite_rules
		flush_rewrite_rules();
		// phpcs:enable WordPressVIPMinimum.Functions.RestrictedFunctions.flush_rewrite_rules_flush_rewrite_rules
		self::manage_plugin_db_tables();
		self::enable_cron();
	}

	/**
	 * Manage plugin database tables.
	 *
	 * @return void
	 */
	public static function manage_plugin_db_tables() {
		$reporting_data_table = new ReportingDataTable();
		$reporting_data_table->activate();

		$logging_table = new LoggingTable();
		$logging_table->activate();

		update_option( 'etracker_db_version', ETRACKER_VERSION );
	}

	/**
	 * Register etracker cron events.
	 *
	 * @return void
	 */
	public static function enable_cron() {
		if ( ! wp_next_scheduled( 'etracker_cron_fetch_reports' ) ) {
			/**
			 * Action `etracker_cron_fetch_reports` to fetch reports twice per day.
			 */
			wp_schedule_event( time(), 'twicedaily', 'etracker_cron_fetch_reports' );
		}

		if ( ! wp_next_scheduled( 'etracker_cron_cleanup_logging' ) ) {
			/**
			 * Action `etracker_cron_cleanup_logging` to cleanup logs once a day.
			 */
			wp_schedule_event( time(), 'daily', 'etracker_cron_cleanup_logging' );
		}
	}

	/**
	 * Action for `plugins_loaded` hook to force database updates.
	 *
	 * @see https://codex.wordpress.org/Creating_Tables_with_Plugins
	 *
	 * @return void
	 */
	public static function update_db_check() {
		if ( ETRACKER_VERSION != get_option( 'etracker_db_version' ) ) {
			self::activate();
		}
	}
}
