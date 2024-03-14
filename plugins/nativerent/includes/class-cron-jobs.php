<?php

namespace NativeRent;

use function add_action;
use function add_filter;
use function time;
use function wp_next_scheduled;
use function wp_schedule_event;
use function wp_unschedule_hook;

/**
 * Cron Jobs
 *
 * @package nativerent
 */
class Cron_Jobs {
	/**
	 * Init status.
	 *
	 * @var bool
	 */
	protected static $init = false;

	/**
	 * Prefix for name of jobs and intervals.
	 *
	 * @var string
	 */
	protected static $prefix = 'ntrnt_';

	/**
	 * Custom intervals.
	 * [name, duration in sec, display name].
	 *
	 * @var array<array-key, array<string, int, ?string>>
	 */
	protected static $intervals = array(
		array(
			'cron_update_monetizations_internal',
			NATIVERENT_UPDATE_MONETIZATIONS_INTERVAL,
			'every ' . NATIVERENT_UPDATE_MONETIZATIONS_INTERVAL . ' seconds',
		),
	);

	/**
	 * Jobs list.
	 * [name, interval name, action].
	 *
	 * @var array<array-key, array<string, string, array|string>
	 */
	protected static $jobs = array(
		array(
			'cron_update_monetizations',
			'cron_update_monetizations_internal',
			array( API::class, 'load_monetizations' ),
		),
	);

	/**
	 * Init function.
	 *
	 * @return void
	 */
	public static function init() {
		if ( self::$init ) {
			return;
		}

		// Register intervals.
		foreach ( self::$intervals as $interval ) {
			self::register_custom_interval( $interval[0], $interval[1], isset( $interval[2] ) ? $interval[2] : '' );
		}

		// Register jobs.
		foreach ( self::$jobs as $job ) {
			self::register_cron_job( $job[0], $job[1], $job[2] );
		}

		self::$init = true;
	}

	/**
	 * Unregister jobs.
	 *
	 * @return void
	 */
	public static function unregister() {
		foreach ( self::$jobs as $job ) {
			self::unregister_cron_job( $job[0] );
		}
	}

	/**
	 * Get name with prefix.
	 *
	 * @param string $name Name of task or interval.
	 *
	 * @return string
	 */
	private static function get_name( $name ) {
		return self::$prefix . $name;
	}

	/**
	 * Register custom execution interval
	 *
	 * @param string $name Name of interval.
	 * @param int    $seconds Interval in seconds.
	 * @param string $display Display name.
	 *
	 * @return void
	 */
	private static function register_custom_interval( $name, $seconds, $display = '' ) {
		add_filter(
			'cron_schedules',
			function ( $recurrence ) use ( $name, $seconds, $display ) {
				$recurrence[ self::get_name( $name ) ] = array(
					'interval' => $seconds,
					'display'  => $display,
				);

				return $recurrence;
			}
		);
	}

	/**
	 * Register cron job.
	 *
	 * @param string       $name Task name.
	 * @param string       $interval Interval name.
	 * @param array|string $action Action.
	 *
	 * @return void
	 */
	private static function register_cron_job( $name, $interval, $action ) {
		$task_name = self::get_name( $name );
		if ( ! wp_next_scheduled( $task_name ) ) {
			wp_schedule_event( time(), self::get_name( $interval ), $task_name );
		}
		add_action( $task_name, $action );
	}

	/**
	 * Unregister job.
	 *
	 * @param string $name Task name.
	 *
	 * @return void
	 */
	private static function unregister_cron_job( $name ) {
		wp_unschedule_hook( self::get_name( $name ) );
	}
}
