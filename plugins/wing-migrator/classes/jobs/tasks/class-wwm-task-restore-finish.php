<?php

class Wwm_Task_Restore_Finish extends Wwm_Restore_Task_Base {
	public static $task_name = 'restore_finish';
	public static $next_task_name = null;

	public function execute() {
		@unlink( $this->wwm_info->get_restore_db_file_path() );
		@unlink( $this->wwm_info->get_restore_file_path() );

		$this->wwm_info->set_status( WWM_MIGRATION_STATUS_RESTORE_COMPLETE );
		$this->wwm_info->set_finish_datetime( date( DATE_ATOM, time() ) );
		$this->wwm_info->update();
		$this->logger->info( '===========  complete wwm-restore  ===========' );

		return true;
	}
}