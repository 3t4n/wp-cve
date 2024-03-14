<?php

class Owm_Task_Restore_Finish extends Owm_Restore_Task_Base {
	public static $task_name = 'restore_finish';
	public static $next_task_name = null;

	public function execute() {
		@unlink( $this->owm_info->get_restore_db_file_path() );
		@unlink( $this->owm_info->get_restore_file_path() );

		$this->owm_info->set_status( OWM_MIGRATION_STATUS_RESTORE_COMPLETE );
		$this->owm_info->set_finish_datetime( date( DATE_ATOM, time() ) );
		$this->owm_info->update();
		$this->logger->info( '===========  complete owm-restore  ===========' );

		return true;
	}
}