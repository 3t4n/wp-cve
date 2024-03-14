<?php

class Owm_Task_Restore_File extends Owm_Restore_Task_Base {
	public static $task_name = 'restore_file';
	public static $next_task_name = 'restore_database';

	public function execute() {
		$this->logger->info( '===========  start extract files  ===========' );
		if ( $this->owm_info->get_backup_type() === 'zip' ) {
			$this->owm_info->set_status( OWM_MIGRATION_STATUS_RESTORE_EXTRACT_ZIP );
			$archive_method = new Owm_Archive_Zip();
		} else {
			$this->owm_info->set_status( OWM_MIGRATION_STATUS_RESTORE_EXTRACT_TAR );
			$archive_method = new Owm_Archive_Tar();

		}
		$archive_file_path = $this->owm_info->get_restore_file_path();
		$archive_extract = new Owm_Archive_Extract( $archive_method, $archive_file_path, $this->owm_info );

		if ( ! $this->job_info->fetch_current_task_detail( 'file', 'extract_sql_file' ) ) {
			$backup_json = $archive_extract->extract_and_get_content( $this->owm_info->get_restore_dir_path(), 'backup.json' );
			$backup_json = json_decode( $backup_json, true );
			$this->logger->info( 'backup data:' . var_export( $backup_json, true ) );
			$sql_file_name = $backup_json[ 'backup_db_file_name' ];
			$this->owm_info->set_restore_db_file_name( $sql_file_name );

			$archive_extract->extract_to( $this->owm_info->get_restore_dir_path(), $sql_file_name );
			$this->job_info->update_current_task_detail( 'file', 'extract_sql_file', true );

			$this->owm_info->set_job_info( $this->job_info );
			$this->owm_info->update();

		} else {
			$sql_file_name = $this->owm_info->get_restore_db_file_name();
		}

		$finish_all = $archive_extract->extract_wp_content_dir( array( $sql_file_name ) );

		if ( $finish_all ) {
			$this->logger->info( '===========  complete extract files  ===========' );
			return true;
		}

		return false;
	}
}