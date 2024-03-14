<?php
/**
 * Searchanise Cron
 *
 * @package Searchanise/Cron
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

/**
 * Searchanise cron class
 */
class Cron {

	const CRON_RESYNC_EVENT = 'se_cron_resync';
	const CRON_INDEX_EVENT  = 'se_index_resync';

	/**
	 * Unregister cron jobs
	 */
	public static function unregister() {
		wp_clear_scheduled_hook( self::CRON_INDEX_EVENT );
		wp_clear_scheduled_hook( self::CRON_RESYNC_EVENT );
	}

	/**
	 * Adds custom intervals
	 *
	 * @param array $schedules Schedules list.
	 *
	 * @return array
	 */
	public static function add_intervals( $schedules ) {
		$schedules['every_minute'] = array(
			'interval' => 60,
			'display'  => 'Every minute',
		);

		return $schedules;
	}

	/**
	 * Register cron jobs
	 */
	public static function activate() {
		if ( ! wp_next_scheduled( self::CRON_INDEX_EVENT ) ) {
			wp_schedule_event( time(), Api::get_instance()->get_index_interval(), self::CRON_INDEX_EVENT );
		}

		if ( ! wp_next_scheduled( self::CRON_RESYNC_EVENT ) ) {
			wp_schedule_event( time(), Api::get_instance()->get_resync_interval(), self::CRON_RESYNC_EVENT );
		}
	}

	/**
	 * Indexer action
	 */
	public static function indexer() {
		if ( defined( 'DOING_CRON' ) && DOING_CRON && Api::get_instance()->check_cron_async_enabled() ) {
			if ( Api::get_instance()->check_start_async() ) {
				Async::get_instance()->async();
			}
		}
	}

	/**
	 * Re-importer action
	 */
	public static function reimporter() {
		if ( defined( 'DOING_CRON' ) && DOING_CRON && Api::get_instance()->is_periodic_sync_mode() ) {
			Api::get_instance()->queue_import( null, false );
		}
	}
}
