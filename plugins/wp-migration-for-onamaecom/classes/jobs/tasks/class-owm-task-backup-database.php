<?php

class Owm_Task_Backup_Database extends Owm_Backup_Task_Base {
	public static $task_name = 'backup_database';
	public static $next_task_name = 'backup_file';

	public function execute() {
		if ( $this->owm_info->get_status() !== OWM_MIGRATION_STATUS_BACKUP_DUMP_DB ) {
			$this->logger->info( '===========  start dump database  ===========' );
			Owm_Server_Info::logging_info( $this->logger );
			$this->owm_info->set_status( OWM_MIGRATION_STATUS_BACKUP_DUMP_DB );
			$this->owm_info->update();
		} else {
			$this->logger->info( 'continue dump task' );
		}

		$mysql_backup = new Owm_Mysql_Query_Backup(
			$this->owm_info->get_backup_dir_path(),
			$this->owm_info->get_backup_db_file_name(),
			$this->owm_info
		);

		$prefix = $this->owm_info->get_prefix();

		$finish_all = false;
		if ( $this->job_info->fetch_current_task_detail( 'database', 'dump_header' ) === false ) {
			$mysql_backup->dump_header( $prefix );
			$mysql_backup->dump_create_table( $prefix );
			$this->job_info->update_current_task_detail( 'database', 'dump_header', true );
			$this->owm_info->update_job_info( $this->job_info );
		} elseif ( $this->job_info->fetch_current_task_detail( 'database', 'create_table' ) === false ) {
			$finish_dump = $mysql_backup->dump_data( $prefix );
			if ( $finish_dump ) {
				$this->job_info->update_current_task_detail( 'database', 'create_table', true );
				$this->owm_info->update_job_info( $this->job_info );
				$finish_all = true;
			}
		}
		if ( $finish_all ) {
			$mysql_backup->dump_footer( $prefix );
			$this->logger->info( '===========  complete dump database  ===========' );
		}
		return $finish_all;
	}
}