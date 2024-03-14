<?php
/**
 * Sync Product Scheduler class.
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
 * Sync Product Scheduler class.
 */
class Sync_Product_Scheduler extends Sync_Scheduler {

	/**
	 * Name of the sync action.
	 *
	 * /@var string
	 */
	private const SYNC_ACTION_NAME = 'faire_product_scheduled_event';

	/**
	 * Instance of class Faire\Wc\Admin\Settings.
	 *
	 * @var Settings
	 */
	private Settings $settings;

	// Option keys
	public const OPTION_FAIRE_PRODUCTS_PENDING_CREATE = '_faire_products_pending_create';
	public const OPTION_FAIRE_PRODUCTS_PENDING_UPDATE = '_faire_products_pending_update';
	public const OPTION_FAIRE_PRODUCTS_PENDING_DELETE = '_faire_products_pending_delete';

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

		// Setup scheduled sync on setting save.
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
		if ( ! $_POST ) {
			return $settings; // Avoids duplicate schedule, if checked when event is currently running
		}
		if ( isset( $settings['product_sync_mode'] ) && 'sync_scheduled' !== $settings['product_sync_mode'] ) {
			$this->manage_scheduled_product_sync( true );
			return $settings;
		}
		if ( isset( $settings['product_sync_schedule_num'] ) && isset( $settings['product_sync_schedule_time'] ) ) {
			$this->manage_scheduled_product_sync(
				false,
				$this->calculate_interval_seconds(
					$settings['product_sync_schedule_num'],
					$settings['product_sync_schedule_time']
				)
			);
			return $settings;
		}

		return $settings;
	}

	/**
	 * Add product to sync.
	 *
	 * @return string|false
	 */
	public function add_product_single_sync_queue( $id, $action = 'update', $future_seconds = 0 ) {
		if ( 'create' === $action ) {
			$action_type = 'create_faire_product_' . $id;
		} elseif ( 'update' === $action ) {
			$action_type = 'update_faire_product_' . $id;
		} elseif ( 'delete' === $action ) {
			$action_type = 'delete_faire_product_' . $id;
		} else {
			return false;
		}
		$scheduler_args = array(
			'action_type' => $action_type,
			// 'id' => $id
		);
		$this->action_args = $scheduler_args;

		// Check for the same job already scheduled.
		$existing_jobs = $this->get_jobs(
			array(
				'group'  => self::FAIRE_SCHEDULER_ACTIONS_GROUP . 'single_time',
				'status' => 'pending',
				'args'   => $scheduler_args,
			)
		);

		if ( ! $existing_jobs ) {
			$job_id = $this->start_once_job( $future_seconds );
			return $job_id;
		}
		return false;
	}

	/**
	 * Setup scheduled sync event.
	 *
	 * @return string|false
	 */
	public function manage_scheduled_product_sync( $cancel = false, $interval = 0 ) {

		// Define scheduler args, for adding and searching existing jobs.
		$scheduler_args    = array(
			'action_type' => 'sync_pending_faire_products',
		);
		$this->action_args = $scheduler_args;

		// Check for the same job already scheduled.
		$existing_jobs = $this->get_jobs(
			array(
				'group'  => self::FAIRE_SCHEDULER_ACTIONS_GROUP . 'recurrent',
				'status' => 'pending',
				'args'   => $scheduler_args,
			)
		);

		if ( true === $cancel ) {
			if ( $existing_jobs ) {
				// Cancel the jobs.
				foreach ( $existing_jobs as $job ) {
					$this->group = self::FAIRE_SCHEDULER_ACTIONS_GROUP . 'recurrent';
					$this->cancel_job();
				}
			}
		} else {

			// Cancel any jobs if job is not exact match or if dupicates are found.
			$cancel_duplicate = false;
			foreach ( $existing_jobs as $jobkey => $job ) {
				if ( $job->get_schedule()->get_recurrence() !== $interval ) {
					$this->group = self::FAIRE_SCHEDULER_ACTIONS_GROUP . 'recurrent';
					$this->cancel_job();
					unset( $existing_jobs[ $jobkey ] );
				} else {
					$cancel_duplicate = true;
				}
			}
			if ( ! $existing_jobs ) {
				$job_id = $this->start_recurrent_job(
					$interval
				);
				return $job_id;
			}
		}
		return false;
	}


	/**
	 * Get pending sync.
	 *
	 * @return array
	 */
	public function get_product_faire_pending_sync( $action = 'update' ): array {

		$meta_key = '';
		if ( 'create' === $action ) {
			$meta_key = self::OPTION_FAIRE_PRODUCTS_PENDING_CREATE;
		} elseif ( 'update' === $action ) {
			$meta_key = self::OPTION_FAIRE_PRODUCTS_PENDING_UPDATE;
		} elseif ( 'delete' === $action ) {
			$meta_key = self::OPTION_FAIRE_PRODUCTS_PENDING_DELETE;
		}

		$pending_ids = get_option( $meta_key, array() );
		if ( ! is_array( $pending_ids ) ) {
			$pending_ids = array();
		}
		return $pending_ids;
	}

	/**
	 * Add to pending sync.
	 *
	 * @return void
	 */
	public function add_product_faire_pending_sync( $ids, $action = 'update' ) {

		$meta_key = '';
		if ( 'create' === $action ) {
			$meta_key = self::OPTION_FAIRE_PRODUCTS_PENDING_CREATE;
		} elseif ( 'update' === $action ) {
			$meta_key = self::OPTION_FAIRE_PRODUCTS_PENDING_UPDATE;
		} elseif ( 'delete' === $action ) {
			$meta_key = self::OPTION_FAIRE_PRODUCTS_PENDING_DELETE;
		}

		$pending_ids = $this->get_product_faire_pending_sync( $action );
		$add_ids     = ( is_array( $ids ) ) ? $ids : array( $ids );
		$pending_ids = array_merge( $pending_ids, $add_ids );
		update_option( $meta_key, array_unique( $pending_ids ), false ); // Set autoload = false.
	}

	/**
	 * Remove from pending sync.
	 *
	 * @return void
	 */
	public function remove_product_faire_pending_sync( $ids, $action = 'update' ) {

		$meta_key = '';
		if ( 'create' === $action ) {
			$meta_key = self::OPTION_FAIRE_PRODUCTS_PENDING_CREATE;
		} elseif ( 'update' === $action ) {
			$meta_key = self::OPTION_FAIRE_PRODUCTS_PENDING_UPDATE;
		} elseif ( 'delete' === $action ) {
			$meta_key = self::OPTION_FAIRE_PRODUCTS_PENDING_DELETE;
		}

		$pending_ids = $this->get_product_faire_pending_sync( $action );
		$remove_ids  = ( is_array( $ids ) ) ? $ids : array( $ids );
		$pending_ids = array_diff( $pending_ids, $remove_ids );
		update_option( $meta_key, array_unique( $pending_ids ), false ); // Set autoload = false.
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
		return (int) ( $number * $this->settings->time_to_seconds( $time_unit ) );
	}

}
