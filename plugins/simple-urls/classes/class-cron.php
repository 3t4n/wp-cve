<?php
/**
 * Declare class Config
 *
 * @package Config
 */

namespace LassoLite\Classes;

use LassoLite\Classes\Processes\Amazon;
use LassoLite\Classes\Processes\Import_All;
use LassoLite\Classes\Processes\Revert_All;

/**
 * Config
 */
class Cron {

	const CRONS = array(
		'lasso_lite_update_amazon'           => 'lasso_lite_15_minutes',
		'lasso_lite_import_all'              => 'lasso_lite_15_minutes',
		'lasso_lite_revert_all'              => 'lasso_lite_15_minutes',
		'lasso_lite_tracking_support_status' => 'daily',
	);

	/**
	 * Cron constructor.
	 */
	public function __construct() {
		add_filter( 'cron_schedules', array( $this, 'add_lasso_cron' ) );
		add_action( 'lasso_lite_tracking_support_status', array( $this, 'lasso_lite_tracking_support_status' ) );
		add_action( 'lasso_lite_import_all', array( $this, 'lasso_import_all' ) );
		add_action( 'lasso_lite_revert_all', array( $this, 'lasso_revert_all' ) );
		add_action( 'lasso_lite_update_amazon', array( $this, 'lasso_lite_update_amazon' ) );

		$this->lasso_create_schedule_hook();
	}

	/**
	 * Add a custom cron to WordPress
	 *
	 * @param array $schedules An array of non-default cron schedules. Default empty.
	 */
	public function add_lasso_cron( $schedules ) {
		$schedules['lasso_lite_15_minutes'] = array(
			'interval' => 15 * MINUTE_IN_SECONDS, // ? 15 minutes in seconds
			'display'  => __( '15 minutes' ),
		);

		return $schedules;
	}

	/**
	 * Create hook for the new cron
	 */
	public function lasso_create_schedule_hook() {
		$crons       = self::CRONS;
		$events      = array();
		$crons_array = _get_cron_array();

		if ( ! is_array( $crons_array ) ) {
			return;
		}

		foreach ( $crons_array as $time => $cron ) {
			foreach ( $cron as $hook => $dings ) {
				if ( strpos( $hook, 'lasso_lite_' ) === false ) {
					continue;
				}

				foreach ( $dings as $sig => $data ) {
					$interval = $data['interval'] ?? HOUR_IN_SECONDS;

					// ? get the cron that is less than the existing one
					if ( isset( $events[ $hook ] ) && $interval >= $events[ $hook ]->interval ) {
						continue;
					}

					$events[ $hook ] = (object) array(
						'hook'     => $hook,
						'time'     => $time, // ? UTC
						'schedule' => $data['schedule'],
						'interval' => $interval,
					);

				}
			}
		}

		foreach ( $crons as $cron_name => $interval ) {
			if ( ! wp_next_scheduled( $cron_name ) ) {
				wp_schedule_event( time(), $interval, $cron_name );
			}
		}
	}

	/**
	 * Tracking support status
	 */
	public function lasso_lite_tracking_support_status() {
		$settings = Setting::get_settings();
		if ( boolval( $settings[ Enum::SUPPORT_ENABLED ] ) ) {
			Setting::save_support( false );
		}
	}

	/**
	 * Import all
	 */
	public function lasso_import_all() {
		$allow_import_all = get_option( Import_All::OPTION, '0' );
		if ( 1 === intval( $allow_import_all ) ) {
			$lasso_import_all = new Import_All();
			$lasso_import_all->import();
		}
	}

	/**
	 * Revert all
	 */
	public function lasso_revert_all() {
		$allow_revert_all = get_option( Revert_All::OPTION, '0' );
		if ( 1 === intval( $allow_revert_all ) ) {
			$lasso_import_all = new Revert_All();
			$lasso_import_all->revert();
		}
	}

	/**
	 * Revert all
	 */
	public function lasso_lite_update_amazon() {
		$settings = Setting::get_settings();
		if ( boolval( $settings['amazon_pricing_daily'] ) ) {
			$lasso_amazon = new Amazon();
			$lasso_amazon->run();
		}
	}
}
