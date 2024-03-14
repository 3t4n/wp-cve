<?php

class Wwm_Restore_Action_Restore extends Wwm_Migration_Action {
	public static $action_key = 'restore';

	/** @var Wwm_Restore_Info */
	private $wwm_info;
	/** @var Wwm_Logger */
	private $logger;
	/** @var string */
	private $restore_dir_name;
	/** @var string */
	private $restore_dir_path;

	public function do_action() {
		$this->wwm_info = new Wwm_Restore_Info();
		$task_name = $this->get_parameter( 'task_name', false );

		if ( $this->wwm_info->is_info_exists() && $task_name !== null ) {
			// -- continue job --
			/** @var Wwm_Job_Info $job_info */
			$job_info = $this->wwm_info->get_job_info();
			if ( $job_info !== null ) {
				$job_info->set_retry_count( 0 );
				$this->wwm_info->set_job_info( $job_info );
			}
		} else {
			// -- new job --

			// get parameter
			$backup_file_url = $this->get_parameter( 'backup_file', true );
			$backup_key = $this->get_parameter( 'backup_key', true );
			$site_url = $this->get_parameter( 'site_url', false );
			$this->wwm_info->set_backup_file_url( $backup_file_url );
			$this->wwm_info->set_backup_key( $backup_key );
			$this->wwm_info->set_site_url( $site_url );

			// remove previous data
			$this->wwm_info->delete();
			if ( $this->wwm_info->get_restore_dir_path() !== null ) {
				Wwm_File_Utils::delete_dir( $this->wwm_info->get_restore_dir_path() );
			}
			$this->restore_init();
			$task_name = Wwm_Task_Restore_Download::$task_name;

			$this->wwm_info->set_cron_setting();

		}
		$this->logger = $this->wwm_info->get_logger();
		$this->logger->info( 'migration start at ' . $task_name );

		if ( ! $this->wwm_info->is_cron_disabled() ) {
			Wwm_Restore_Job::schedule_job( $task_name );
			sleep( 90 );
		} else {
			$this->logger->info( 'cron disabled mode.' );
			$job = new Wwm_Restore_Job();
			$job->execute_sync();
		}

		return array(
			'status' => $this->wwm_info->get_status(),
			'restore_dir_name' => $this->wwm_info->get_restore_dir_name(),
			'start_datetime' => $this->wwm_info->get_start_datetime(),
			'finish_datetime' => $this->wwm_info->get_finish_datetime()
		);
	}

	private function restore_init() {
		$this->restore_dir_name = uniqid( 'rs_' . date( 'YmdHis' ) . '_' );
		$this->restore_dir_path = $this->make_dir( $this->restore_dir_name );
		$this->wwm_info->set_restore_dir_name( $this->restore_dir_name );
		$this->wwm_info->set_restore_dir_path( $this->restore_dir_path );
		$this->wwm_info->set_status( WWM_MIGRATION_STATUS_RESTORE_START );
		$this->wwm_info->set_start_datetime( date( DATE_ATOM, time() ) );
		$this->wwm_info->update();

		$this->logger = $this->wwm_info->get_logger();
		$this->logger->info( '===========  start wwm-restore  ===========' );

		Wwm_Server_Info::logging_info( $this->logger );


	}

	private function make_dir( $path ) {
		$upload_dir = wp_upload_dir();
		$target_dir = $upload_dir[ 'basedir' ] . DIRECTORY_SEPARATOR . $path;
		if ( ! file_exists( $target_dir ) ) {
			wp_mkdir_p( $target_dir );
		}
		Wwm_File_Utils::add_index_php( $target_dir );
		return $target_dir;
	}

}
