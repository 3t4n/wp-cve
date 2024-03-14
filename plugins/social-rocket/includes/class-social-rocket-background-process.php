<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) { exit; }

class Social_Rocket_Background_Process {
	
	/**
	 * Action
	 *
	 * (default value: 'async_request')
	 *
	 * @var string
	 * @access protected
	 */
	protected $action = 'social_rocket_background_process';
	
	/**
	 * Cron_hook_identifier
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $cron_hook_identifier;

	/**
	 * Cron_interval_identifier
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $cron_interval_identifier;
	
	/**
	 * Data
	 *
	 * (default value: array())
	 *
	 * @var array
	 * @access protected
	 */
	protected $data = array();
	
	/**
	 * Handle() initialized
	 *
	 * @var bool
	 * @access protected
	 */
	protected $handle_init = false;
	
	/**
	 * Identifier
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $identifier;
	
	/**
	 * Prefix
	 *
	 * (default value: 'wp')
	 *
	 * @var string
	 * @access protected
	 */
	protected $prefix = 'wp';
	
	/**
	 * Start time of current process.
	 *
	 * (default value: 0)
	 *
	 * @var int
	 * @access protected
	 */
	protected $start_time = 0;
	
	
	/**
	 * Initiate new background process
	 */
	public function __construct() {
		
		$this->identifier = $this->prefix . '_' . $this->action;
		$this->cron_hook_identifier     = $this->identifier . '_cron';
		$this->cron_interval_identifier = $this->identifier . '_cron_interval';

		add_action( 'wp_ajax_' . $this->identifier, array( $this, 'maybe_handle' ) );
		add_action( 'wp_ajax_nopriv_' . $this->identifier, array( $this, 'maybe_handle' ) );
		add_action( 'shutdown', array( $this, 'dispatch_queue' ) );
		add_action( $this->cron_hook_identifier, array( $this, 'handle_cron_healthcheck' ) );
		add_filter( 'cron_schedules', array( $this, 'schedule_cron_healthcheck' ) );
		
	}

	
	/**
	 * Cancel Process
	 *
	 * Stop processing queue items, clear cronjob and delete batch.
	 *
	 * Not used, may remove in the future.
	 *
	 */
	public function cancel_process() {
		wp_clear_scheduled_hook( $this->cron_hook_identifier );
	}
	
	
	/**
	 * Clear scheduled event
	 */
	protected function clear_scheduled_event() {
		$timestamp = wp_next_scheduled( $this->cron_hook_identifier );

		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, $this->cron_hook_identifier );
		}
	}
	
	
	/**
	 * Complete.
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	protected function complete() {
		// Unschedule the cron healthcheck.
		$this->clear_scheduled_event();
	}
	
	
	/**
	 * Set data used during the request
	 *
	 * Not used, may remove in the future.
	 *
	 * @param array $data Data.
	 *
	 * @return $this
	 */
	public function data( $data ) {
		$this->data = $data;

		return $this;
	}
	
	
	/**
	 * Delete queue item
	 *
	 * @param string $key Key.
	 *
	 * @return $this
	 */
	public function delete( $key ) {
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'social_rocket_count_queue';
		$result = $wpdb->delete(
			$table_name,
			array(
				'id' => $key,
			)
		);

		return $result;
	}
	
	
	/**
	 * Dispatch the async request
	 *
	 * @return array|WP_Error
	 */
	public function dispatch() {
		// Schedule the cron healthcheck.
		$this->schedule_event();
		
		$url  = add_query_arg( $this->get_query_args(), $this->get_query_url() );
		$args = $this->get_post_args();

		return wp_remote_post( esc_url_raw( $url ), $args );
	}
	
	
	/**
	 * Save and run queue.
	 */
	public function dispatch_queue() {
		if ( ! empty( $this->data ) ) {
			$this->save()->dispatch();
		}
	}
	
	
	/**
	 * Get batch
	 *
	 * @return stdClass Return the first batch from the queue
	 */
	protected function get_batch() {
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'social_rocket_count_queue';
		$results = $wpdb->get_results(
			"SELECT * FROM $table_name
			ORDER BY request_time ASC
			LIMIT 20",
			ARRAY_A 
		);
		
		$batch       = new stdClass();
		$batch->data = array();
		
		foreach ( $results as $result ) {
			$key   = $result['id'];
			$value = unserialize( $result['data'] );
			$batch->data[$key] = $value;
		}

		return $batch;
	}
	
	
	/**
	 * Get memory limit
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

		if ( ! $memory_limit || -1 === intval( $memory_limit ) ) {
			// Unlimited, set to 32GB.
			$memory_limit = '32000M';
		}

		return intval( $memory_limit ) * 1024 * 1024;
	}
	
	
	/**
	 * Get post args
	 *
	 * @return array
	 */
	protected function get_post_args() {
		if ( property_exists( $this, 'post_args' ) ) {
			return $this->post_args;
		}

		return array(
			'timeout'   => 0.01,
			'blocking'  => false,
			'body'      => $this->data,
			'cookies'   => $_COOKIE,
			'sslverify' => apply_filters( 'https_local_ssl_verify', false ),
		);
	}
	
	
	/**
	 * Get query args
	 *
	 * @return array
	 */
	protected function get_query_args() {
		if ( property_exists( $this, 'query_args' ) ) {
			return $this->query_args;
		}

		return array(
			'action' => $this->identifier,
			'nonce'  => wp_create_nonce( $this->identifier ),
		);
	}
	
	
	/**
	 * Get query URL
	 *
	 * @return string
	 */
	protected function get_query_url() {
		if ( property_exists( $this, 'query_url' ) ) {
			return $this->query_url;
		}

		return admin_url( 'admin-ajax.php' );
	}
	
	
	/**
	 * Get time limit
	 *
	 * @return int
	 */
	protected function get_time_limit() {
		if ( function_exists( 'ini_get' ) ) {
			$time_limit = ini_get( 'max_execution_time' );
		} else {
			// Sensible default.
			$time_limit = '30';
		}

		if ( ! $time_limit || -1 === intval( $time_limit ) ) {
			// Unlimited, set to 300.
			$time_limit = '300';
		}

		return apply_filters( $this->identifier . '_default_time_limit', $time_limit );
	}
	
	
	/**
	 * Handle
	 *
	 * Pass each queue item to the task handler, while remaining
	 * within server memory and time limit constraints.
	 */
	protected function handle() {
		$this->lock_process();
		$this->set_limits();
		$this->handle_init = true;
		
		do {
			$batch = $this->get_batch();

			foreach ( $batch->data as $key => $value ) {
				$task = $this->task( $value );

				if ( $task === false ) {
					// task completed, we can delete it from the queue.
					$this->delete( $key );
				}

				if ( $this->time_exceeded() || $this->memory_exceeded() ) {
					// Batch limits reached.
					break;
				}
			}
			
		} while ( ! $this->time_exceeded() && ! $this->memory_exceeded() && ! $this->is_queue_empty() );

		$this->unlock_process();

		// Start next batch or complete process.
		if ( ! $this->is_queue_empty() ) {
			$this->dispatch();
		} else {
			$this->complete();
		}

		wp_die();
	}
	
	
	/**
	 * Handle cron healthcheck
	 *
	 * Restart the background process if not already running
	 * and data exists in the queue.
	 */
	public function handle_cron_healthcheck() {
		if ( $this->is_process_running() ) {
			// Background process already running.
			exit;
		}

		if ( $this->is_queue_empty() ) {
			// No data to process.
			$this->clear_scheduled_event();
			exit;
		}

		$this->handle();

		exit;
	}
	
	
	/**
	 * Are we idle? (i.e. is queue empty)
	 */
	public function is_idle() {
		return $this->is_queue_empty();
	}
	
	
	/**
	 * Is process running
	 *
	 * Check whether the current process is already running
	 * in a background process.
	 */
	public function is_process_running() {
		
		$pid = get_site_transient( $this->identifier . '_process_lock' );
		
		if ( $pid === 'unknown' ) {
			// Can't check PID, so assume process is already running.
			return true;
		}
		
		if ( $pid && $pid !== 'unknown' ) {
			
			// If we are on *nix and posix functions are not disabled, we can do a quick
			// check to make sure the process hasn't died:
			if ( function_exists( 'posix_getsid' ) ) {
				if ( posix_getsid( $pid ) === false ) {
					// process has died!
					return false;
				}
			}
			
			// If we are on Windows, then we're S.O.L. because doing this would require
			// an exec call, which would raise false security alarms, and it just isn't
			// worth the hassle.
			
			// Process is already running.
			return true;
		}

		return false;
	}
	
	
	/**
	 * Is queue empty
	 *
	 * @return bool
	 */
	protected function is_queue_empty() {
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'social_rocket_count_queue';
		
		$count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );

		return ( $count > 0 ) ? false : true;
	}
	
	
	/**
	 * Lock process
	 *
	 * Lock the process so that multiple instances can't run simultaneously.
	 * Override if applicable, but the duration should be greater than that
	 * defined in the time_exceeded() method.
	 */
	protected function lock_process() {
		$this->start_time = time(); // Set start time of current process.

		$lock_duration = ( property_exists( $this, 'queue_lock_time' ) ) ? $this->queue_lock_time : 3600; // 60 minutes
		$lock_duration = apply_filters( $this->identifier . '_queue_lock_time', $lock_duration );

		$pid = false;
		if ( function_exists( 'getmypid' ) ) { // some shitty shared hosts disable gitmypid() for no good reason
			$pid = getmypid();
		}
		if ( ! $pid ) {
			$pid = 'unknown';
		}
		
		set_site_transient( $this->identifier . '_process_lock', $pid, $lock_duration );
	}
	
	
	/**
	 * Maybe process queue
	 *
	 * Checks whether data exists within the queue and that
	 * the process is not already running.
	 */
	public function maybe_handle() {
		// Don't lock up other requests while processing
		session_write_close();

		if ( $this->is_process_running() ) {
			// Background process already running.
			wp_die();
		}

		if ( $this->is_queue_empty() ) {
			// No data to process.
			wp_die();
		}

		check_ajax_referer( $this->identifier, 'nonce' );

		$this->handle();

		wp_die();
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
		$memory_limit   = $this->get_memory_limit() * 0.9; // 90% of max memory
		$current_memory = memory_get_usage( true );
		$return         = false;

		if ( $current_memory >= $memory_limit ) {
			$return = true;
		}

		return apply_filters( $this->identifier . '_memory_exceeded', $return );
	}
	
	
	/**
	 * Push to queue
	 */
	public function push_to_queue( $hash, $data = false ) {
		
		// Begin compatibility for SR Pro < 1.2.5
		if ( ! $data ) {
			$data = $hash;
			$hash = md5( $data['url'] . '|' . $data['network'] );
		}
		// End compatibility for SR Pro < 1.2.5
		
		$this->data[ $hash ] = $data;
		return $this;
	}
	
	
	/**
	 * Save queue
	 *
	 * @return $this
	 */
	public function save() {
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'social_rocket_count_queue';
		
		if ( ! empty( $this->data ) ) {
			
			$query  = "INSERT IGNORE INTO $table_name (hash, data) VALUES ";
			$values = array();
			$rows   = count( $this->data );
			
			$i = 0;
			foreach ( $this->data as $hash => $data ) {
				$i++;
				
				$query .= "(%s, %s)";
				$query .= ( $i < $rows ? "," : '' );
				
				$values[] = $hash;
				$values[] = serialize( $data );
			}
			
			$result = $wpdb->query(
				$wpdb->prepare( $query, $values	)
			);
		}
		
		return $this;
	}
	

	/**
	 * Schedule cron healthcheck
	 *
	 * @access public
	 * @param mixed $schedules Schedules.
	 * @return mixed
	 */
	public function schedule_cron_healthcheck( $schedules ) {
		$interval = apply_filters( $this->identifier . '_cron_interval', 5 );

		if ( property_exists( $this, 'cron_interval' ) ) {
			$interval = apply_filters( $this->identifier . '_cron_interval', $this->cron_interval );
		}

		// Adds every 5 minutes to the existing schedules.
		$schedules[ $this->identifier . '_cron_interval' ] = array(
			'interval' => MINUTE_IN_SECONDS * $interval,
			'display'  => sprintf( __( 'Every %d Minutes' ), $interval ),
		);

		return $schedules;
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
	 * Try to give ourselves reasonable limits.
	 *
	 * May not always work, but worth a try.
	 */
	protected function set_limits() {
		
		if ( $this->handle_init ) {
			return;
		}
		
		if ( ! function_exists( 'ini_set' ) ) {
			return;
		}
		
		$memory_limit = $this->get_memory_limit();
		if ( $memory_limit < ( 256 * 1024 * 1024 ) ) {
			ini_set( 'memory_limit', '256M' );
		}
		
		$time_limit = $this->get_time_limit();
		if ( $time_limit < 300 ) {
			ini_set( 'max_execution_time', 300 );
		}
		
	}
	
	
	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param mixed $item Queue item to iterate over
	 *
	 * @return mixed
	 */
	protected function task( $item ) {
		
		if ( ! class_exists( 'Social_Rocket' ) ) {
			return false;
		}
		
		$SR = Social_Rocket::get_instance();
		
		$master_throttle = isset( $SR->settings['master_throttle'] ) ? $SR->settings['master_throttle'] : 1;
		$master_throttle = apply_filters( 'social_rocket_master_throttle', $master_throttle );
		sleep( $master_throttle );
		
		// Actions to perform
		switch ( $item['task'] ) {
		
			case 'update_share_count':
				
				$network = isset( $item['network'] ) ? $item['network'] : '';
				$id      = isset( $item['id'] )      ? $item['id']      : '';
				$type    = isset( $item['type'] )    ? $item['type']    : '';
				$url     = isset( $item['url'] )     ? $item['url']     : '';
				$force   = isset( $item['force'] )   ? $item['force']   : false;
				
				// validate we have the bare minimum required, in case we are given something screwy
				if (
					( $type === 'url' && $id > '' ) ||
					( $type !== 'url' && intval( $id ) > 0 )
				) {
					// okay
				} else {
					// we don't have a valid ID. Dump the task.
					return false;
				}
				if ( ! $network > '' ) {
					// we don't have a valid network. Dump the task.
					return false;
				}
				
				// api throttling
				if ( class_exists( 'Social_Rocket_'.ucfirst($network) ) ) {
					// make sure the network is loaded, so we get correct throttle value
					$SRN = call_user_func( array( 'Social_Rocket_'.ucfirst($network), 'get_instance' ) );
				}
				$throttle = apply_filters( 'social_rocket_'.$network.'_throttle', 1 );
				$last = get_option( '_social_rocket_'.$network.'_last_call' );
				$now = time();
				if ( $now - $last < $throttle ) {
					// too soon. save this for later
					return $item;
				}
				
				// do it
				update_option( '_social_rocket_'.$network.'_last_call', time() ); // works as a lock to prevent race conditions
				
				$success = $SR->update_share_count( $network, $id, $type, $url, $force );
				
				if ( $success ) {
					// finished, remove the item from the queue
					$item = false;
				} else {
					// something went wrong. try again later
					return $item;
				}
				
				break;
		
		}
		
		$item = apply_filters( 'social_rocket_background_process_task', $item );
		
		return $item;
	}
	
	
	/**
	 * Time exceeded.
	 *
	 * Ensures the batch never exceeds a sensible time limit.
	 * A timeout limit of 30s is common on shared hosting.
	 *
	 * @return bool
	 */
	protected function time_exceeded() {
		$time_limit = $this->get_time_limit();
		
		$finish = $this->start_time + $time_limit - ( $time_limit > 10 ? 10 : 1 ); // try not to go within 10 seconds of limit
		$return = false;

		if ( time() >= $finish ) {
			$return = true;
		}

		return apply_filters( $this->identifier . '_time_exceeded', $return );
	}
	
	
	/**
	 * Unlock process
	 *
	 * Unlock the process so that other instances can spawn.
	 *
	 * @return $this
	 */
	protected function unlock_process() {
		delete_site_transient( $this->identifier . '_process_lock' );

		return $this;
	}
	
	
	/**
	 * Update queue
	 *
	 * Not used, may remove in the future.
	 *
	 * @param string $key Key.
	 * @param array  $data Data.
	 *
	 * @return $this
	 */
	public function update( $key, $data ) {
		return $this;
	}

}
