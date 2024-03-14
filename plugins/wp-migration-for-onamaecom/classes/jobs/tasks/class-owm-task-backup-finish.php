<?php

class Owm_Task_Backup_Finish extends Owm_Backup_Task_Base {
	public static $task_name = 'backup_finish';
	public static $next_task_name = null;

	public function execute() {
		$mysql_backup = new Owm_Mysql_Query_Backup(
			$this->owm_info->get_backup_dir_path(),
			$this->owm_info->get_backup_db_file_name(),
			$this->owm_info
		);
		$mysql_backup->file_delete();

		// delete hidden file
		$compressed_dir = new Owm_Archived_Dir( $this->owm_info->get_backup_dir_path() );
		$compressed_dir->delete();

		$this->owm_info->set_status( OWM_MIGRATION_STATUS_BACKUP_COMPLETE );
		$this->owm_info->set_finish_datetime( date( DATE_ATOM, time() ) );
		$this->owm_info->update();
		$this->logger->info( '===========  complete owm-backup  ===========' );

		return true;
	}
}