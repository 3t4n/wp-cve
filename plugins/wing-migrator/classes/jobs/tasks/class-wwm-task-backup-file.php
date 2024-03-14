<?php

class Wwm_Task_Backup_File extends Wwm_Backup_Task_Base {
	public static $task_name = 'backup_file';
	public static $next_task_name = 'backup_finish';

	public function execute() {

		if ( $this->wwm_info->get_backup_type() === 'phar' ) {
			// compress type tar
			if ( $this->wwm_info->get_status() !== WWM_MIGRATION_STATUS_BACKUP_COMPRESS_TAR ) {
				$this->logger->info( '===========  start compress files  ===========' );
				$this->logger->info( 'backup type is tar.' );
				$this->wwm_info->set_status( WWM_MIGRATION_STATUS_BACKUP_COMPRESS_TAR );
				$this->wwm_info->update();
			}
			$archive_method = new Wwm_Archive_Tar();
		} else {
			// compress type zip
			if ( $this->wwm_info->get_status() !== WWM_MIGRATION_STATUS_BACKUP_COMPRESS_ZIP ) {
				$this->logger->info( '===========  start compress files  ===========' );
				$this->logger->info( 'backup type is zip.' );
				$this->wwm_info->set_status( WWM_MIGRATION_STATUS_BACKUP_COMPRESS_ZIP );
				$this->wwm_info->update();
			}
			$archive_method = new Wwm_Archive_Zip();
		}
		$archive_file = $this->wwm_info->get_backup_file_path();
		$archive_compress = new Wwm_Archive_Compress( $archive_method, $archive_file, $this->wwm_info );

		$finish_all = false;
		if ( $this->job_info->fetch_current_task_detail( 'file', 'add_common_file' ) === false ) {
			$archive_compress->add_file( $this->wwm_info->get_backup_db_file_path(), $this->wwm_info->get_backup_db_file_name() );
			$backup_info = $this->wwm_info->to_array();
			unset( $backup_info[ 'backup_key' ] );
			$archive_compress->add_from_string( 'backup.json', json_encode( $backup_info, JSON_FORCE_OBJECT ) );
			$this->job_info->update_current_task_detail( 'file', 'add_common_file', true );
		} elseif ( $this->job_info->fetch_current_task_detail( 'file', 'add_theme_file' ) === false ) {
			$finish_compress = $archive_compress->add_wp_content_dir( 'themes' );
			if ( $finish_compress ) {
				$this->job_info->update_current_task_detail( 'file', 'add_theme_file', true );
				$this->job_info->set_retry_count( 0 );
				$this->wwm_info->update_job_info( $this->job_info );
			}
		} elseif ( $this->job_info->fetch_current_task_detail( 'file', 'add_plugin_file' ) === false ) {
			$finish_compress = $archive_compress->add_wp_content_dir( 'plugins' );
			if ( $finish_compress ) {
				$this->job_info->update_current_task_detail( 'file', 'add_plugin_file', true );
				$this->job_info->set_retry_count( 0 );
				$this->wwm_info->update_job_info( $this->job_info );
			}
		} elseif ( $this->job_info->fetch_current_task_detail( 'file', 'add_upload_file' ) === false ) {
			$finish_compress = $archive_compress->add_wp_content_dir( 'uploads' );
			if ( $finish_compress ) {
				$this->job_info->update_current_task_detail( 'file', 'add_upload_file', true );
				$this->job_info->set_retry_count( 0 );
				$this->wwm_info->update_job_info( $this->job_info );
			}
		} else {
			$finish_compress = $archive_compress->add_wp_content_dir( null ); // other directories
			if ( $finish_compress ) {
				$finish_all = true;
			}
		}
		$archive_compress->close();
		if ( $finish_all ) {
			$this->logger->info( '===========  complete compress files  ===========' );
		}
		return $finish_all;
	}

}