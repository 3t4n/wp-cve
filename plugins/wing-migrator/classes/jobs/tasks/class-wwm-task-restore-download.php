<?php

class Wwm_Task_Restore_Download extends Wwm_Restore_Task_Base {
	public static $task_name = 'restore_download';
	public static $next_task_name = 'restore_file';
	public static $chunk_download_threshold = 524288000;  // 500MB
	public static $chunk_download_size = 524288000;  // 500MB
	public static $status_code_partial_content = 206;
	public static $status_code_success = 200;

	private function normal_download( $file_path, $backup_url ) {
		$this->logger->info( 'normal download' );
		$success = Wwm_File_Utils::file_download( $file_path, $backup_url );
		if ( $success === false ) {
			$this->logger->info( 'backup file cannot download' );
			return false;
		}
		return true;
	}

	private function chunk_download( $file_path, $backup_url, $content_length ) {
		$this->logger->info( 'chunk download' );

		$range_start = $this->job_info->fetch_current_task_detail( 'download', 'range_start' );
		if ( $range_start === null ) {
			$range_start = 0;
		}
		$range_end = $range_start + $this::$chunk_download_size;
		$append_mode = $range_start !== 0;
		$is_finish = false;

		if ( $range_end >= $content_length ) {
			$is_finish = true;
			$range_end = $content_length;
		}

		$this->logger->info( 'range :' . $range_start . '-' . $range_end );
		$result = Wwm_File_Utils::chunk_file_download( $file_path, $backup_url, $range_start, $range_end, $append_mode );
		$this->logger->info( 'response:' );
		$this->logger->info( print_r( $result, true ) );

		if ( $result === false ) {
			$this->logger->info( 'backup file cannot download' );
			return false;
		} elseif ( $result === $this::$status_code_success || $is_finish ) {
			return true;
		}

		$this->logger->info( 'backup continue' );
		$next_range_start = $range_end + 1;
		$this->job_info->update_current_task_detail( 'download', 'range_start', $next_range_start );
		$this->wwm_info->update_job_info( $this->job_info );
		return false;
	}

	public function execute() {

		if ( $this->wwm_info->get_status() !== WWM_MIGRATION_STATUS_RESTORE_DOWNLOAD_FILE ) {
			$this->logger->info( '===========  start download file  ===========' );
			$this->wwm_info->set_status( WWM_MIGRATION_STATUS_RESTORE_DOWNLOAD_FILE );
			$exploded_backup_url = explode( '/', $this->wwm_info->get_backup_file_url() );
			$file_name = end( $exploded_backup_url );
			$this->wwm_info->set_restore_file_name( $file_name );

			$ext = substr( $this->wwm_info->get_restore_file_path(), strrpos( $this->wwm_info->get_restore_file_path(), '.' ) + 1 );
			if ( $ext === 'zip' ) {
				$this->wwm_info->set_backup_type( 'zip' );
			} else {
				$this->wwm_info->set_backup_type( 'phar' );
			}
			$this->wwm_info->update();
		} else {
			$this->logger->info( 'continue dump task' );
		}

		$file_path = $this->wwm_info->get_restore_file_path();
		$backup_url = $this->wwm_info->get_backup_file_url();

		$file_info = Wwm_File_Utils::get_file_info( $backup_url );

		if ( $file_info[ 'file_exists' ] === false ) {
			$this->logger->info( 'backup file cannot download' );
			$this->logger->info( print_r( $file_info, true ) );
			return false;
		}
		$this->logger->info( 'DownloadFileInfo:' );
		$this->logger->info( $backup_url );
		$this->logger->info( print_r( $file_info, true ) );

		$content_length = $file_info[ 'content_length' ];

		if ( $content_length <= $this::$chunk_download_threshold || $file_info[ 'is_range_support' ] === false ) {
			$result = $this->normal_download( $file_path, $backup_url );
		} else {
			$result = $this->chunk_download( $file_path, $backup_url, $content_length );
		}

		if ( $result === false ) {
			return false;
		}

		$this->logger->info( 'filesize : ' . filesize( $this->wwm_info->get_restore_file_path() ) . ' bytes' );
		$this->logger->info( '===========  complete download file  ===========' );

		return true;
	}
}