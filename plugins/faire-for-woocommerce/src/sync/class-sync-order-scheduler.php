<?php
/**
 * Sync Order Scheduler class.
 *
 * @package  FAIRE
 */

namespace Faire\Wc\Sync;

use ActionScheduler_Store;
use Faire\Wc\Admin\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Sync Order Scheduler class.
 */
class Sync_Order_Scheduler extends Sync_Scheduler {

	/**
	 * Name of the sync action.
	 *
	 * /@var string
	 */
	private const SYNC_ACTION_NAME = 'sync_orders';

	/**
	 * Instance of class Faire\Wc\Admin\Settings.
	 *
	 * @var Settings
	 */
	private Settings $settings;

	/**
	 * Class constructor.
	 *
	 * @param callable $action   Action to execute on schedule.
	 * @param Settings $settings Instance of class Faire\Wc\Admin\Settings.
	 */
	public function __construct( callable $action, Settings $settings ) {
		$this->settings = $settings;
		$this->action   = $action;

		parent::__construct(
			self::SYNC_ACTION_NAME,
			$action,
			array()
		);

		add_filter(
			'woocommerce_settings_api_sanitized_fields_faire_wc_integration',
			array( $this, 'manage_scheduled_sync' )
		);
	}

	/**
	 * Manages scheduled sync job attending to plugin settings.
	 *
	 * @param array|null $settings The plugin settings.
	 *
	 * @return array|null The plugin settings.
	 */
	public function manage_scheduled_sync( ?array $settings ): ?array {
		if ( ! $settings ) {
			return $settings;
		}
		if ( isset( $settings['order_sync_mode'] ) && 'sync_scheduled' !== $settings['order_sync_mode'] ) {
			$this->maybe_cancel_job();
			return $settings;
		}
		if ( isset( $settings['order_sync_schedule_num'] ) && isset( $settings['order_sync_schedule_time'] ) ) {
			$this->maybe_setup_job(
				$this->calculate_interval_seconds(
					$settings['order_sync_schedule_num'],
					$settings['order_sync_schedule_time']
				)
			);
			return $settings;
		}

		return $settings;
	}

	/**
	 * Calculates a time interval in seconds
	 *
	 * @param float  $number    Number of time units.
	 * @param string $time_unit Name of a time unit.
	 *   Possible values: 'none', 'hours', 'daily'.
	 *
	 * @return int Time interval in seconds.
	 */
	private function calculate_interval_seconds(
		float $number,
		string $time_unit
	): int {
		return $number * $this->settings->time_to_seconds( $time_unit );
	}

	/**
	 * Sets up a scheduled job if it doesn't exists.
	 *
	 * @param int $interval Scheduled time in seconds.
	 */
	public function maybe_setup_job( int $interval = 0 ) {
		if ( 0 === $interval || $this->check_job_exists( $interval ) ) {
			return;
		}
		$this->start_recurrent_job( $interval );
	}

	/**
	 * Cancels a scheduled job if it exists.
	 */
	public function maybe_cancel_job() {
		if ( $this->check_job_exists() ) {
			$this->cancel_job();
		}
	}

	/**
	 * Checks if a scheduled sync job exists.
	 *
	 * @param int $interval Scheduled time in seconds.
	 *
	 * @return bool True if job exists.
	 */
	public function check_job_exists( int $interval = 0 ): bool {
		$filters = array(
			'hook'   => 'faire_scheduler_hook_' . self::SYNC_ACTION_NAME,
			'status' => ActionScheduler_Store::STATUS_PENDING,
			'group'  => self::FAIRE_SCHEDULER_ACTIONS_GROUP . 'recurrent',
		);
		$jobs    = $this->get_jobs( $filters );

		if ( ! $jobs ) {
			$filters['status'] = ActionScheduler_Store::STATUS_RUNNING;
			$jobs              = $this->get_jobs( $filters );
		}

		if ( ! $interval ) {
			return (bool) $jobs;
		}

		foreach ( $jobs as $job ) {
			if ( $interval === $this->get_job_interval( $job ) ) {
				return true;
			}
		}

		return false;
	}

}
