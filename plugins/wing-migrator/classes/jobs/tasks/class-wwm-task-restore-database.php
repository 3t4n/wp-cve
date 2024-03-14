<?php

class Wwm_Task_Restore_Database extends Wwm_Restore_Task_Base {
	public static $task_name = 'restore_database';
	public static $next_task_name = 'restore_finish';

	public function execute() {
		if ( $this->wwm_info->get_status() !== WWM_MIGRATION_STATUS_RESTORE_DB ) {
			$this->logger->info( '===========  start import database  ===========' );
			$this->wwm_info->set_status( WWM_MIGRATION_STATUS_RESTORE_DB );
			$this->wwm_info->update();
		}
		$finished_query_count = $this->job_info->fetch_current_task_detail( 'database', 'finished_query_count' );

		$mysql_restore = new Wwm_Mysql_Query_Restore( $this->wwm_info );
		$result = $mysql_restore->restore( $this->wwm_info->get_restore_db_file_path() );

		$this->wwm_info->force_update();

		if ( $finished_query_count === 0 ) {
			$mysql_restore->update_site_url( $this->wwm_info->get_site_url() );
		} elseif ( $result === Wwm_Mysql_Query_Restore::$RESULT_SUCCESS ) {
			$this->logger->info( '===========  complete import database  ===========' );
			return true;
		}
		return false;
	}
}