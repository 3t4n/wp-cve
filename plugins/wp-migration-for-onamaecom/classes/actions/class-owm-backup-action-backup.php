<?php

class Owm_Backup_Action_Backup extends Owm_Migration_Action {
	public static $action_key = 'backup';

	/** @var Owm_Logger */
	private $logger;
	/** @var Owm_Backup_Info */
	private $owm_info;

	public function do_action() {

		$this->owm_info = new Owm_Backup_Info();
		$task_name = $this->get_parameter( 'task_name', false );
		$archive_type_param = $this->get_parameter( 'archive_type', false );

		if ( $this->owm_info->is_info_exists() && $task_name !== null ) {
			// -- continue job --
			$job_info = $this->owm_info->get_job_info();
			if ( $job_info !== null ) {
				$job_info->set_retry_count( 0 );
				$this->owm_info->set_job_info( $job_info );
			}
		} else {
			// -- new job --
			$this->owm_info->delete();
			$archive_type = $this->get_archive_type( $archive_type_param );
			$this->backup_init( $archive_type );
			$task_name = Owm_Task_Backup_Database::$task_name;

			$this->owm_info->set_cron_setting();
			$this->owm_info->set_object_cache_setting();
		}

		$this->logger = $this->owm_info->get_logger();
		$exclude_table_param = $this->get_parameter( 'exclude_table', false );
		if ( $exclude_table_param !== null ) {
			$this->logger->info( 'set exclude table: ' . $_GET[ 'exclude_table' ] );

			$exclude_tables = explode( ',', $_GET[ 'exclude_table' ] );
			$this->owm_info->set_exclude_db_tables( $exclude_tables );
		}
		$this->owm_info->update();

		$this->logger->info( 'migration start at ' . $task_name );
		if ( $this->owm_info->is_cron_disabled() === false
			|| $this->owm_info->is_object_cache_enabled() === false ) {
			$errors = Owm_Backup_Job::schedule_job( $task_name );
			if ( $errors !== null ) {
				$this->logger->warning( 'schedule_job error' );
				$this->logger->warning( var_export( $errors, true ) );
				$this->logger->warning( 'execute_sync' );
				$job = new Owm_Backup_Job();
				$job->execute_sync();

			}
			sleep( 90 );
		} else {
			$this->logger->info( 'cron disabled mode.' );
			$job = new Owm_Backup_Job();
			$job->execute_sync();
		}

		return array(
			'status' => $this->owm_info->get_status(),
			'backup_key' => $this->owm_info->get_backup_key(),
			'site_url' => $this->owm_info->get_site_url(),
			'backup_url' => $this->owm_info->get_backup_url(),
			'backup_type' => $this->owm_info->get_backup_type(),
			'start_datetime' => $this->owm_info->get_start_datetime(),
			'finish_datetime' => $this->owm_info->get_finish_datetime()
		);
	}

	private function get_archive_type( $archive_type ) {
		if ( $archive_type !== null ) {
			return $archive_type;
		}
		if ( ! extension_loaded( 'zip' ) ) {
			return 'phar';
		}
		return 'zip';
	}

	private function backup_init( $archive_type ) {
		$backup_dir_name = uniqid( 'bk_' . date( 'YmdHis' ) . '_' );
		$backup_dir_path = $this->make_dir( $backup_dir_name );
		$this->owm_info->set_status( OWM_MIGRATION_STATUS_BACKUP_START );
		$this->owm_info->set_backup_dir_path( $backup_dir_path );
		$this->logger = $this->owm_info->get_logger();
		$this->logger->info( '===========  start owm-backup  ===========' );
		Owm_Server_Info::logging_info( $this->logger );

		$prefix = "wp_";
		if ( isset( $_GET[ 'prefix' ] ) && $_GET[ 'prefix' ] !== '' ) {
			$prefix = $_GET[ 'prefix' ];
		}
		$this->owm_info->set_prefix( $prefix );

		if ( $archive_type !== 'zip' ) {
			$this->owm_info->set_backup_file_name( uniqid() . '.tar' );
			$this->owm_info->set_backup_type( 'phar' );
		} else {
			$this->owm_info->set_backup_file_name( uniqid() . '.zip' );
			$this->owm_info->set_backup_type( 'zip' );
		}
		$this->owm_info->set_backup_db_file_name( uniqid() . '.sql' );
		$this->owm_info->set_backup_url( $this->make_url( $backup_dir_name ) . '/' . $this->owm_info->get_backup_file_name() );
		$this->owm_info->set_start_datetime( date( DATE_ATOM, time() ) );
		$this->owm_info->set_start_timestamp( time() );
		$this->owm_info->set_site_url( get_site_url() );
		$this->owm_info->set_backup_key( uniqid() );
	}

	private function make_dir( $path ) {
		$upload_dir = wp_upload_dir();
		$target_dir = $upload_dir[ 'basedir' ] . DIRECTORY_SEPARATOR . $path;
		if ( ! file_exists( $target_dir ) ) {
			wp_mkdir_p( $target_dir );
		}
		Owm_File_Utils::add_index_php( $target_dir );
		return $target_dir;
	}

	private function make_url( $path ) {
		$upload_dir = wp_upload_dir();
		$target_url = $upload_dir[ 'baseurl' ] . DIRECTORY_SEPARATOR . $path;
		return $target_url;
	}

}