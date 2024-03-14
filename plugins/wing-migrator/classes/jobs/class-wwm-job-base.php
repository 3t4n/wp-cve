<?php

abstract class Wwm_Job_Base {

	public static $job_name = null;
	/** @var Wwm_Logger */
	protected $logger;
	/** @var Wwm_Info */
	protected $wwm_info;
	protected static $tasks = array();

	public static function schedule_job( $task_name ) {

		static::clear_hook();

		if ( static::is_backup_job() || WWM_RESTORE_CRON_REQUEST_LOOPBACK === false ) {
			$site_url = site_url( 'wp-cron.php' );
			$headers = array(
				'Cache-Control' => 'no-cache',
			);
		} else {
			// execute request by loopback address
			$parse_site_url = parse_url( site_url() );
			$search_host = $parse_site_url[ 'scheme' ] . '://' . $parse_site_url[ 'host' ];
			$replace_host = $parse_site_url[ 'scheme' ] . '://127.0.0.1';
			$site_url = str_replace( $search_host, $replace_host, site_url( 'wp-cron.php' ) );
			$headers = array(
				'Cache-Control' => 'no-cache',
				'Host' => $parse_site_url[ 'host' ]
			);
		}

		$now = time();
		wp_schedule_single_event( $now, static::$job_name, array( $task_name ) );
		usleep( mt_rand( 100, 10000 ) );
		$doing_wp_cron = sprintf( '%.22F', microtime( true ) );
		$query_args = array(
			'doing_wp_cron' => $doing_wp_cron
		);
		$cron_request = apply_filters( 'cron_request', array(
			'url' => add_query_arg( $query_args, $site_url ),
			'key' => $doing_wp_cron,
			'args' => array(
				'headers' => $headers,
				'user-agent' => WWM_MIGRATION_HTTP_USER_AGENT,
				'timeout' => 1,
				'blocking' => true,
				'sslverify' => false
			)
		) );
		set_transient( 'doing_cron', $doing_wp_cron );
		$response = wp_remote_post( $cron_request[ 'url' ], $cron_request[ 'args' ] );

		if ( $response instanceof WP_Error === false ) {
			return null;
		}
		$errors = $response->errors;
		if ( array_key_exists( 'http_request_failed', $errors ) ) {
			$request_failed_error = $errors[ 'http_request_failed' ];
			if ( count( $request_failed_error ) === 1
				&& strpos( $request_failed_error[ 0 ], 'Operation timed out' ) !== false ) {
				return null;
			}
		}
		// schedule_job error
		wp_unschedule_event( $now, static::$job_name, array( $task_name ) );
		return $response->errors;
	}

	public static function clear_hook() {
		delete_transient( 'doing_cron' );
		$cron_jobs = get_option( 'cron' );

		foreach ( $cron_jobs as $timestamp => $cron_job ) {
			if ( ! is_array( $cron_job ) ) {
				continue;
			}
			if ( array_key_exists( self::$job_name, $cron_job ) ) {
				foreach ( $cron_job[ self::$job_name ] as $job_uid => $job_data ) {
					wp_clear_scheduled_hook( static::$job_name, $job_data[ 'args' ] );
				}
			}
		}
	}

	public function add_action() {
		add_action( static::$job_name, array( $this, 'execute' ), 999, 2 );
	}

	/**
	 * whether request user agent contains "migration" string or not
	 * @return bool
	 */
	protected function check_user_agent() {
		$user_agent = $_SERVER[ 'HTTP_USER_AGENT' ];
		return strpos( $user_agent, 'migration' ) !== false;
	}

	private static function is_backup_job() {
		if (static::$job_name === 'wwm_restore_job') {
			return false;
		}
		return true;
	}

	private function get_complete_status() {
		if ( $this->is_backup_job() ) {
			return WWM_MIGRATION_STATUS_BACKUP_COMPLETE;
		}
		return WWM_MIGRATION_STATUS_RESTORE_COMPLETE;
	}

	protected function can_execute( $task_name ) {

		if ( ! $this->wwm_info->is_info_exists() ) {
			error_log( 'wing-migration:  migration info dose not exist' );
			return false;
		}

		if ( $this->wwm_info->is_force_stop() ) {
			$this->logger->warning( 'forced stop' );
			$this->wwm_info->set_status( $this->get_complete_status() );
			$this->wwm_info->update();
			return false;
		}

		if ( $this->wwm_info->is_running() ) {
			$this->logger->warning( 'job runnning' );
			return false;
		}

		if ( $this->wwm_info->get_start_timestamp() !== null ) {
			$time_limit = $this->wwm_info->get_start_timestamp() + WWM_EXECUTE_TIME_LIMIT;
			if ( $time_limit < time() ) {
				$this->logger->warning( 'execute time limit exceeded.' );
				return false;
			}
		}

		if ( ! $this->check_user_agent() ) {
			$this->clear_hook();
			$errors = $this->schedule_job( $task_name );
			if ( $errors !== null ) {
				$this->logger->warning( 'schedule_job error' );
				$this->logger->warning( var_export( $errors, true ) );
			}
			return false;
		}
		return true;
	}

	/**
	 * Execute task
	 * @param $target_task
	 * @return array
	 *          job_finished bool
	 *          next_task str|null
	 */
	protected function execute_task( $target_task ) {
		try {
			ini_set( 'memory_limit', '1024M' );
			@set_time_limit( 0 );

			$next_task = null;

			foreach ( static::$tasks as $task ) {
				if ( $task::$task_name !== $target_task ) {
					continue;
				}
				/** @var Wwm_Task_Base $task */
				$task = new $task( $this->wwm_info );
				$is_finish = $task->execute();
				/** @var Wwm_Job_Info $job_info */
				$job_info = $task->get_job_info();
				if ( $is_finish ) {
					$next_task = $task::$next_task_name;
					$job_info->set_retry_count( 0 );
					if ( $next_task === null ) {
						// job finished
						$this->wwm_info->update_job_info( null );
						$this->clear_hook();
						return array( true, null );
					}
				}
			}
			return array( false, $next_task );
		} catch ( Exception $e ) {
			// continue task
			$this->logger->exception( 'task error', $e );
			sleep( 20 );
			return array( false, null );
		}
	}

	protected function execute_task_sync() {
		foreach ( static::$tasks as $backup_task ) {
			/** @var Wwm_Task_Base $task */
			$task = new $backup_task( $this->wwm_info );
			$job_info = $this->get_job_info();
			$continue = true;
			$count = 0;
			while ( $continue ) {
				if ( $count > 100 ) {
					$this->logger->error( 'too many continue task' );
					Wwm_Migration_Response::create_error_response( 'too many continue task', 400 );
					break;
				}
				if ( $this->wwm_info->is_info_exists() === false || $this->wwm_info->is_force_stop() ) {
					$this->logger->error( 'job force stopped' );
					Wwm_Migration_Response::create_error_response( 'job force stopped', 400 );
					break;
				}
				$this->logger->info( $backup_task::$task_name );
				$is_finish = $task->execute();
				$continue = ! $is_finish;
				$job_info->set_current_task_started_time( time() );
				$this->wwm_info->update_job_info( $job_info );
				$count++;
			}
		}
	}

	protected function get_job_info() {
		if ( $this->wwm_info->get_job_info() === null ) {
			$job_info = new Wwm_Job_Info();
			if ( $this->wwm_info instanceof Wwm_Backup_Info ) {
				$job_info->init_backup_task_detail();
			} else {
				$job_info->init_restore_task_detail();
			}
		} else {
			$job_info = $this->wwm_info->get_job_info();
		}
		return $job_info;
	}

	/**
	 * Execute Job
	 * wp-cron entry point
	 * @param $task_name
	 */
	public function execute( $task_name ) {
		$this->init();

		if ( ! $this->can_execute( $task_name ) ) {
			return;
		}

		$this->wwm_info->set_running( true );
		$this->wwm_info->update();

		$job_info = $this->get_job_info();
		$job_info->set_current_task_started_time( time() );
		$job_info->set_current_task( $task_name );
		$this->wwm_info->update_job_info( $job_info );

		$current_task = $job_info->get_current_task();
		$this->logger->info( '[Task] ' . $current_task . ' ++++++++++++++++++++++++' );

		$result = $this->execute_task( $current_task );
		$is_job_finished = $result[ 0 ];
		$next_task = $result[ 1 ];
		if ( $is_job_finished ) {
			return;
		}
		$this->wwm_info->set_running( false );
		$this->wwm_info->update();

		if ( $next_task !== null ) {
			// run next task
		} elseif ( $job_info->is_max_retry_exceeded() ) {
			$this->logger->error( 'stop job because of reaching max retry count' );
			$this->wwm_info->update_job_info( null );
			$this->wwm_info->update();
			$this->clear_hook();
			return;
		} else {
			$job_info->increment_retry_count();
			$next_task = $current_task;
			$this->logger->info( '+ retry this task (' . $job_info->get_retry_count() . 'times) +' );
		}

		// execute task
		$this->wwm_info->update_job_info( $job_info );
		$this->clear_hook();
		$errors = $this->schedule_job( $next_task );
		if ( $errors !== null ) {
			$this->logger->warning( 'schedule_job error' );
			$this->logger->warning( var_export( $errors, true ) );
		}
	}

	/**
	 * Execute job in series
	 * for disable wp-cron
	 */
	public function execute_sync() {
		$this->init();

		$job_info = new Wwm_Job_Info();
		$job_info->init_backup_task_detail();

		$job_info->set_current_task_started_time( time() );
		$job_info->set_current_task( $this->get_sync_first_task() );
		$this->wwm_info->update_job_info( $job_info );

		$this->execute_task_sync();

		$this->wwm_info->set_status( $this->get_complete_status() );
		$this->wwm_info->update();

	}

	protected abstract function init();

	protected abstract function get_sync_first_task();
}
