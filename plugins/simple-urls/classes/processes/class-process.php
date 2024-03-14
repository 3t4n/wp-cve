<?php
/**
 * Declare class Process
 * Scan lasso link in posts/pages
 *
 * @package Process
 */

namespace LassoLite\Classes\Processes;

use LassoLite\Classes\Helper as Lasso_Helper;
use LassoLite\Classes\Lasso_DB;
use LassoLite\Classes\Setting as Lasso_Setting;
use LassoLite\Classes\Verbiage as Lasso_Verbiage;

use LassoLite\Libs\Background_Processes\Lasso_WP_Background_Process;

use stdClass;

/**
 * Load vendor-prefix for cron requests
 */
require_once SIMPLE_URLS_DIR . '/vendor-prefix/vendor/autoload.php';

/**
 * $key generated when save() event get fired
 * OR
 * $this->identifier = $this->prefix . '_' . $this->action;
 * $key = $wpdb->esc_like( $this->identifier . '_batch_' ) . '%';
 *
 * Reference URL
 * https://deliciousbrains.com/background-processing-wordpress/
 * https://github.com/A5hleyRich/wp-background-processing
 * https://github.com/A5hleyRich/wp-background-processing-example
 */
abstract class Process extends Lasso_WP_Background_Process {
	const EXCEEDED_TIME_LIMIT      = 10; // ? Exceeded time limit in seconds
	const AGE_OUT                  = 6; // ? Age out time after n hours
	const RESTART_ATTEMPTED_LIMIT  = 3;
	const OPTION_RESTART_ATTEMPTED = 'lasso_lite_process_restart_attempted'; // ? Age out time after n hours
	const COMPLETE_ACTION_KEY      = 'lasso_lite_process_complete';

	/**
	 * Action name
	 *
	 * @var string $action
	 */
	protected $action = 'lassolite_background_process';

	/**
	 * Log name
	 *
	 * @var string $log_name
	 */
	protected $log_name = 'lasso_lite_background_process';

	/**
	 * Process name
	 *
	 * @var string $process_name
	 */
	protected $process_name = 'Lasso Lite Background Process';

	/**
	 * Process constructor.
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'wp_ajax_lasso_lite_get_list_background_processing', array( $this, 'get_list_background_processing' ) );
		add_filter( $this->identifier . '_default_time_limit', array( $this, 'custom_exceeded_time_limit' ), 10, 1 );
	}

	/**
	 * Custom exceeded time limit.
	 *
	 * @param int $time_limit Exceeded time limit.
	 */
	public function custom_exceeded_time_limit( $time_limit ) {
		return self::EXCEEDED_TIME_LIMIT;
	}

	/**
	 * Set log name
	 *
	 * @param string $log_name Log name.
	 */
	public function set_log_file_name( $log_name ) {
		$this->log_name     = $log_name;
		$this->process_name = ucwords( preg_replace( '/[\-_]/', ' ', $log_name ) );
	}

	/**
	 * Write logs when task starts
	 */
	public function task_start_log() {
		// ? set process start time
		$this->set_process_start_time();
	}

	/**
	 * Write logs when task completed
	 */
	public function task_end_log() {

	}

	/**
	 * Set total idexes of process
	 *
	 * @param int $total_count Total.
	 */
	public function set_total( $total_count ) {
		$key = $this->get_key();

		if ( false === get_option( $key . '_total_count' ) ) {
			update_option( $key . '_total_count', $total_count, false );
		}
	}

	/**
	 * Get total idexes of process
	 */
	public function get_total() {
		$key = $this->get_key();
		return (int) get_option( $key . '_total_count' );
	}

	/**
	 * Get key of process
	 */
	public function get_key() {
		return $this->identifier;
	}

	/**
	 * Set start time of process
	 */
	public function set_process_start_time() {
		// ? SHOULD CONSIDER LATER
		// ? Only set "start time" for the first process of each background process type. The "start time" will auto delete when the process completed.
		/**
		// if ( ! $this->get_process_start_time() ) {
		// $key = $this->get_key();
		// update_option( $key . '_start_time', microtime( true ), false );
		// }
		*/

		$key = $this->get_key();
		update_option( $key . '_start_time', microtime( true ), false );
	}

	/**
	 * Get start time of process
	 */
	public function get_process_start_time() {
		$key = $this->get_key();
		return get_option( $key . '_start_time', 0 );
	}

	/**
	 * Set running time of process
	 */
	public function set_processing_runtime() {
		$key  = $this->get_key();
		$diff = microtime( true ) - $this->get_process_start_time();
		$diff = round( $diff, 2 );

		update_option( $key . '_process_running_time', $diff, false );
	}

	/**
	 * Get running time of process
	 */
	public function get_processing_runtime() {
		$key = $this->get_key();
		return get_option( $key . '_process_running_time', 0 );
	}

	/**
	 * Get ETA
	 */
	public function get_eta() {
		$total               = $this->get_total();
		$completed           = $this->get_total_completed();
		$completed           = 0 !== $completed ? $completed : 1;
		$unit_execution_time = $this->get_processing_runtime() / $completed;
		$eta                 = $unit_execution_time * ( $total - $completed );

		return $eta;
	}

	/**
	 * Check whether process is running or not
	 *
	 * @param bool $default Use default or custom logic. Default to false.
	 */
	public function is_process_running( $default = false ) {
		$this->remove_duplicated_processes();
		if ( $default ) {
			return parent::is_process_running();
		}

		$lasso_db = new Lasso_DB();
		$lasso_db->check_empty_process();
		$next_schedule = $this->next_schedule();

		return parent::is_process_running() && $this->get_total_remaining() > 0 && $next_schedule;
	}

	/**
	 * Remove duplicated processes
	 */
	private function remove_duplicated_processes() {
		$lasso_db = new Lasso_DB();

		$query = '
			DELETE 
			FROM ' . $lasso_db->options . ' 
			WHERE option_id not in (
				SELECT option_id
				FROM (
					SELECT min(option_id) AS option_id 
					FROM ' . $lasso_db->options . " 
					WHERE option_name LIKE '%" . $this->action . "%batch%'
				) AS wpo
			) AND option_name LIKE '%" . $this->action . "%batch%'
		";

		$lasso_db->query( $query );
	}

	/**
	 * Remove: remove a process
	 */
	public function remove_process() {
		$lasso_db = new Lasso_DB();

		$sql = '
			DELETE FROM ' . $lasso_db->options . "
			WHERE option_name LIKE '%" . $this->action . "%'
				AND option_name NOT LIKE '%" . $this->action . "_start_time%'
		";

		$lasso_db->query( $sql );
	}

	/**
	 * Check whether the process is age out
	 */
	public function is_process_age_out() {
		$start_time        = $this->get_process_start_time();
		$diff              = microtime( true ) - $start_time;
		$failed_times      = self::RESTART_ATTEMPTED_LIMIT;
		$option_name       = self::OPTION_RESTART_ATTEMPTED;
		$current_attempted = intval( get_option( $option_name, 0 ) );
		$hour_in_seconds   = self::AGE_OUT * HOUR_IN_SECONDS;

		if ( $current_attempted >= $failed_times && ! $this->is_queue_empty() ) {
			return true;
		}

		if ( $start_time > 0 && $diff > $hour_in_seconds ) { // ? the process is age out
			// ? restart_attempted reach the limit, remove the process
			$this->remove_process();

			// ? increase restart_attempted
			update_option( $option_name, ++$current_attempted );
		}

		return false;
	}

	/**
	 * Get next schedule
	 */
	public function next_schedule() {
		return wp_next_scheduled( $this->cron_hook_identifier );
	}

	/**
	 * Get total remaining items
	 */
	public function get_total_remaining() {
		global $wpdb;

		$lasso_db = new Lasso_DB();

		$table        = $wpdb->options;
		$column       = 'option_name';
		$key_column   = 'option_id';
		$value_column = 'option_value';

		if ( is_multisite() ) {
			$table        = $wpdb->sitemeta;
			$column       = 'meta_key';
			$key_column   = 'meta_id';
			$value_column = 'meta_value';
		}

		$key = $wpdb->esc_like( $this->identifier . '_batch_' ) . '%';

		// @codingStandardsIgnoreStart
		$sql = $wpdb->prepare(
			"
                        SELECT {$value_column}
                        FROM {$table}
                        WHERE {$column} LIKE %s
                        ORDER BY {$key_column} ASC
                        LIMIT 1
                    ",
			$key
		);
		// @codingStandardsIgnoreEnd

		$queue              = $lasso_db->get_var( $sql );   // ? We want to track SQL errors in Sentry
		$count              = 0;
		$unserialized_queue = maybe_unserialize( $queue );
		if ( is_array( $unserialized_queue ) ) {
			$count = count( $unserialized_queue );
		}

		return $count;
	}

	/**
	 * Get total items are completed
	 */
	public function get_total_completed() {
		$get_total      = $this->get_total();
		$total_remainig = $this->get_total_remaining();
		if ( $total_remainig && $total_remainig <= $get_total ) {
			return $get_total - $total_remainig;
		}

		return $get_total;
	}

	/**
	 * Do something when the process is completed
	 */
	public function set_completed() {
		$key = $this->get_key();
		delete_option( $key . '_total_count' );
		delete_option( $key . '_start_time' );
		delete_option( $key . '_process_running_time' );
	}

	/**
	 * Check whether the process is empty or not
	 */
	public function is_queue_empty() { // phpcs:ignore
		return parent::is_queue_empty();
	}

	/**
	 * Check whether the process is completed or not
	 */
	public function is_completed() {
		return $this->is_queue_empty();
	}

	/**
	 * Do something when an index completes
	 */
	public function complete() {
		parent::complete();
		$this->set_completed();
		$this->task_end_log();
		do_action( self::COMPLETE_ACTION_KEY, $this->action );
	}

	/**
	 * Check whether CPU exceeded or not
	 */
	public function cpu_exceeded() {
		$max_cpu_allow = Lasso_Setting::get_setting( 'cpu_threshold', 80 ); // ? percent
		$cpu_load      = Lasso_Helper::get_cpu_load();

		return $cpu_load >= $max_cpu_allow;
	}

	/**
	 * Handle
	 *
	 * Pass each queue item to the task handler, while remaining
	 * within server memory and time limit constraints.
	 */
	protected function handle() {
		$this->lock_process();

		do {
			$batch = $this->get_batch();

			$batch_data = $batch->data ?? array();
			foreach ( $batch_data as $key => $value ) {
				$task = $this->cpu_exceeded() ? true : $this->task( $value );

				if ( false !== $task ) {
					$batch->data[ $key ] = $task;
				} else {
					unset( $batch->data[ $key ] );
				}

				if ( $this->time_exceeded() || $this->memory_exceeded() ) {
					// ? Batch limits reached.
					break;
				}
			}

			// ? Update or delete current batch.
			$batch_key = $batch->key ?? '';
			if ( ! empty( $batch->data ) ) {
				$this->update( $batch_key, $batch->data );
			} else {
				$this->delete( $batch_key );
			}
		} while ( ! $this->time_exceeded() && ! $this->memory_exceeded() && ! $this->is_queue_empty() );

		$this->unlock_process();

		// ? Start next batch or complete process.
		if ( ! $this->is_queue_empty() ) {
			$this->dispatch();
		} else {
			$this->complete();
		}

		wp_die();
	}

	/**
	 * Handle manually
	 *
	 * Pass each queue item to the task handler, while remaining
	 * within server memory and time limit constraints.
	 */
	public function handle_manually() {
		$this->handle();
	}

	/**
	 * Get batch
	 *
	 * @return stdClass Return the first batch from the queue
	 */
	public function get_batch() {
		global $wpdb;

		$table        = $wpdb->options;
		$column       = 'option_name';
		$key_column   = 'option_id';
		$value_column = 'option_value';

		if ( is_multisite() ) {
			$table        = $wpdb->sitemeta;
			$column       = 'meta_key';
			$key_column   = 'meta_id';
			$value_column = 'meta_value';
		}

		$key = $wpdb->esc_like( $this->identifier . '_batch_' ) . '%';

		// @codingStandardsIgnoreStart
		$query = $wpdb->get_row(
			$wpdb->prepare(
				"
				SELECT *
				FROM {$table}
				WHERE {$column} LIKE %s
				ORDER BY {$key_column} ASC
				LIMIT 1
			",
				$key
			)
		);
		// @codingStandardsIgnoreEnd

		if ( is_null( $query ) ) {
			return array();
		}

		$batch       = new stdClass();
		$batch->key  = $query->$column;
		$batch->data = maybe_unserialize( $query->$value_column );

		return $batch;
	}

	/**
	 * Disable all processes exclude remove attribute process
	 */
	public static function are_all_processes_disabled() {
		$disable = get_option( 'lasso_lite_disable_processes', 0 );
		$disable = boolval( intval( $disable ) );

		return $disable;
	}

	/**
	 * Remove all processes (WP schedule)
	 */
	public function remove_all_processes() {
		$process_classes = Lasso_Verbiage::PROCESS_DESCRIPTION;
		foreach ( $process_classes as $process_class => $process_name ) {
			/** @var Process $process_class_obj */ // phpcs:ignore
			$process_class_obj = new $process_class();
			$process_class_obj->clear_scheduled_event();
			$process_class_obj->set_completed();
			wp_clear_scheduled_hook( $process_class_obj->cron_hook_identifier );
		}

		$lasso_db = new Lasso_DB();
		$lasso_db->remove_all_lasso_processes();
	}

	/**
	 * Get Background Process is running
	 */
	public function get_list_background_processing() {
		$result            = array(
			'running_total' => 0,
			'items'         => array(),
		);
		$restart_attempted = intval( get_option( self::OPTION_RESTART_ATTEMPTED, 0 ) );

		$process_classes = Lasso_Verbiage::PROCESS_DESCRIPTION;
		$process_classes = apply_filters( 'lasso_lite_all_processes', $process_classes );

		foreach ( $process_classes as $process_class => $process_name ) {
			/** @var Process $process_class_obj */ // phpcs:ignore
			$process_class_obj     = new $process_class();
			$process_next_schedule = $process_class_obj->next_schedule();

			$process_total_item = $process_class_obj->get_total();
			$process_completed  = $process_class_obj->get_total_completed();
			$process_remaining  = $process_class_obj->get_total_remaining();

			$trigger_manually = $restart_attempted >= self::RESTART_ATTEMPTED_LIMIT && ! $process_class_obj->is_process_running();
			$data             = array(
				'name'             => $process_name,
				'class'            => $process_class,
				'completed'        => $process_completed,
				'total'            => $process_total_item,
				'trigger_manually' => $trigger_manually,
			);

			if ( $process_next_schedule ) {
				if ( ! $process_total_item || ! $process_remaining ) {
					continue;
				}

				$result['running_total'] += 1;

				$result['items'][] = $data;
			} elseif ( ! $process_class_obj->is_queue_empty() && $trigger_manually ) {
				$result['items'][] = $data;
			}
		}

		wp_send_json_success( $result );
	}
}
