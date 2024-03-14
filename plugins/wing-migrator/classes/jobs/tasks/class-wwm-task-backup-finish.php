<?php

class Wwm_Task_Backup_Finish extends Wwm_Backup_Task_Base {
	public static $task_name = 'backup_finish';
	public static $next_task_name = null;

	public function execute() {
		$mysql_backup = new Wwm_Mysql_Query_Backup(
			$this->wwm_info->get_backup_dir_path(),
			$this->wwm_info->get_backup_db_file_name(),
			$this->wwm_info
		);
		$mysql_backup->file_delete();

		// delete hidden file
		$compressed_dir = new Wwm_Archived_Dir( $this->wwm_info->get_backup_dir_path() );
		$compressed_dir->delete();

		$this->wwm_info->set_status( WWM_MIGRATION_STATUS_BACKUP_COMPLETE );
		$this->wwm_info->set_finish_datetime( date( DATE_ATOM, time() ) );
		$this->wwm_info->update();
		$this->logger->info( '===========  complete wwm-backup  ===========' );

		return true;
	}
}