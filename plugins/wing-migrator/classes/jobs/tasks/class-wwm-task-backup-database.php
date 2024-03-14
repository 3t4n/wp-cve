<?php

class Wwm_Task_Backup_Database extends Wwm_Backup_Task_Base {
	public static $task_name = 'backup_database';
	public static $next_task_name = 'backup_file';

	public function execute() {
		if ( $this->wwm_info->get_status() !== WWM_MIGRATION_STATUS_BACKUP_DUMP_DB ) {
			$this->logger->info( '===========  start dump database  ===========' );
			Wwm_Server_Info::logging_info( $this->logger );
			$this->wwm_info->set_status( WWM_MIGRATION_STATUS_BACKUP_DUMP_DB );
			$this->wwm_info->update();
		} else {
			$this->logger->info( 'continue dump task' );
		}

		$mysql_backup = new Wwm_Mysql_Query_Backup(
			$this->wwm_info->get_backup_dir_path(),
			$this->wwm_info->get_backup_db_file_name(),
			$this->wwm_info
		);

		$prefix = $this->wwm_info->get_prefix();

		$finish_all = false;
		if ( $this->job_info->fetch_current_task_detail( 'database', 'dump_header' ) === false ) {
			$mysql_backup->dump_header( $prefix );
			$mysql_backup->dump_create_table( $prefix );
			$this->job_info->update_current_task_detail( 'database', 'dump_header', true );
			$this->wwm_info->update_job_info( $this->job_info );
		} elseif ( $this->job_info->fetch_current_task_detail( 'database', 'create_table' ) === false ) {
			$finish_dump = $mysql_backup->dump_data( $prefix );
			if ( $finish_dump ) {
				$this->job_info->update_current_task_detail( 'database', 'create_table', true );
				$this->wwm_info->update_job_info( $this->job_info );
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