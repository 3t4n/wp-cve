<?php
/**
 * Background Process
 *
 * @version 1.0.0
 */


/**
 * WFFN_Background_Process Class.
 * Based on WP_Background_Process concept
 */
if ( ! class_exists( 'WFFN_Background_Process' ) ) {
	class WFFN_Background_Process extends WP_Background_Process {


		/**
		 * Initiate new background process.
		 * WFFN_Background_Process constructor.
		 */
		public function __construct() {
			// Uses unique prefix per blog so each blog has separate queue.
			$this->prefix = 'wffn_' . get_current_blog_id();
			parent::__construct();

		}

		/**
		 * Is queue empty.
		 *
		 * @return bool
		 */
		protected function is_queue_empty() {
			global $wpdb;

			$table  = $wpdb->options;
			$column = 'option_name';

			if ( is_multisite() ) {
				$table  = $wpdb->sitemeta;
				$column = 'meta_key';
			}

			$key   = $wpdb->esc_like( $this->identifier . '_batch_' ) . '%';
			$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE {$column} LIKE %s", $key ) ); // @codingStandardsIgnoreLine.

			return ! ( $count > 0 );
		}

		/**
		 * Get batch.
		 *
		 * @return stdClass Return the first batch from the queue.
		 */
		protected function get_batch() {
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

			$key   = $wpdb->esc_like( $this->identifier . '_batch_' ) . '%';
			$query = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE {$column} LIKE %s ORDER BY {$key_column} ASC LIMIT 1", $key ) ); // @codingStandardsIgnoreLine.
			if ( empty( $query ) ) {
				return;
			}
			$batch       = new stdClass();
			$batch->key  = $query->$column;
			$batch->data = array_filter( (array) maybe_unserialize( $query->$value_column ) );

			return $batch;
		}

		/**
		 * See if the batch limit has been exceeded.
		 *
		 * @return bool
		 */
		protected function batch_limit_exceeded() {
			return $this->time_exceeded() || $this->memory_exceeded();
		}

		/**
		 * Handle.
		 *
		 * Pass each queue item to the task handler, while remaining
		 * within server memory and time limit constraints.
		 */
		protected function handle() {
			$this->lock_process();
			if ( empty( $this->get_batch() ) ) {
				return;
			}

			do {
				$batch = $this->get_batch();

				foreach ( $batch->data as $key => $value ) {
					$task = $this->task( $value );

					if ( false !== $task ) {
						$batch->data[ $key ] = $task;
					} else {
						unset( $batch->data[ $key ] );
					}

					if ( $this->batch_limit_exceeded() ) {
						// Batch limits reached.
						break;
					}
				}

				// Update or delete current batch.
				if ( ! empty( $batch->data ) ) {
					$this->update( $batch->key, $batch->data );
				} else {
					$this->delete( $batch->key );
				}
			} while ( ! $this->batch_limit_exceeded() && ! $this->is_queue_empty() );

			$this->unlock_process();

			// Start next batch or complete process.
			if ( ! $this->is_queue_empty() ) {
				$this->dispatch();
			} else {
				$this->complete();
			}
		}

		public function trigger() {
			$this->handle();
		}

		/**
		 * Get memory limit.
		 *
		 * @return int
		 */
		protected function get_memory_limit() {
			if ( function_exists( 'ini_get' ) ) {
				$memory_limit = ini_get( 'memory_limit' );
			} else {
				// Sensible default.
				$memory_limit = '128M';
			}

			if ( ! $memory_limit || - 1 === intval( $memory_limit ) ) {
				// Unlimited, set to 32GB.
				$memory_limit = '32G';
			}

			return wp_convert_hr_to_bytes( $memory_limit );
		}

		/**
		 * Schedule cron healthcheck.
		 *
		 * @param array $schedules Schedules.
		 *
		 * @return array
		 */
		public function schedule_cron_healthcheck( $schedules ) {
			$interval = apply_filters( $this->identifier . '_cron_interval', 5 );

			if ( property_exists( $this, 'cron_interval' ) ) {
				$interval = apply_filters( $this->identifier . '_cron_interval', $this->cron_interval );
			}

			// Adds every 5 minutes to the existing schedules.
			$schedules[ $this->identifier . '_cron_interval' ] = array(
				'interval' => MINUTE_IN_SECONDS * $interval,
				/* translators: %d: interval */
				'display'  => sprintf( __( 'Every %d minutes', 'funnel-builder' ), $interval ),
			);

			return $schedules;
		}


		/**
		 * Overriding parent protected function publically to use outside this class
		 * @return bool
		 */
		public function is_process_running() {
			return parent::is_process_running();
		}


		/**
		 * Is the updater running?
		 *
		 * @return boolean
		 */
		public function is_updating() {
			return false === $this->is_queue_empty();
		}


		/**
		 * Schedule fallback event.
		 */
		protected function schedule_event() {
			if ( ! wp_next_scheduled( $this->cron_hook_identifier ) ) {
				wp_schedule_event( time() + 10, $this->cron_interval_identifier, $this->cron_hook_identifier );
			}
		}

		/**
		 * Memory exceeded
		 *
		 * Ensures the batch process never exceeds 90%
		 * of the maximum WordPress memory.
		 *
		 * @return bool
		 */
		protected function memory_exceeded() {
			$memory_limit   = $this->get_memory_limit() * 0.8; // 80% of max memory
			$current_memory = memory_get_usage( true );
			$return         = false;

			if ( $current_memory >= $memory_limit ) {
				$return = true;
			}

			return apply_filters( $this->identifier . '_memory_exceeded', $return );
		}

		/**
		 * Task
		 *
		 * Override this method to perform any actions required on each
		 * queue item. Return the modified item for further processing
		 * in the next pass through. Or, return false to remove the
		 * item from the queue.
		 *
		 * @param string $callback Update callback function.
		 *
		 * @return string|bool
		 * @SuppressWarnings(PHPMD.DevelopmentCodeFragment)
		 * @SuppressWarnings(PHPMD.ElseExpression)
		 */
		protected function task( $callback ) {

			$result = false;
			if ( is_callable( $callback ) ) {
				$result = (bool) call_user_func( $callback );

			}

			return $result ? $callback : false;
		}

		/**
		 * Complete
		 *
		 * Override if applicable, but ensure that the below actions are
		 * performed, or, call parent::complete().
		 */
		protected function complete() {
			parent::complete();
		}
	}
}
