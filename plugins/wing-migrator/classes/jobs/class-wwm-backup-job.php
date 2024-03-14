<?php

class Wwm_Backup_Job extends Wwm_Job_Base {
	public static $job_name = 'wwm_backup_job';
	/** @var Wwm_Backup_Info */
	protected $wwm_info;

	protected static $tasks = array(
		'Wwm_Task_Backup_Database',
		'Wwm_Task_Backup_File',
		'Wwm_Task_Backup_Finish'
	);

	protected function init() {
		$wwm_info = new Wwm_Backup_Info();
		$this->wwm_info = $wwm_info;
		$this->logger = $wwm_info->get_logger();
	}

	protected function get_sync_first_task() {
		return Wwm_Task_Backup_Database::$task_name;
	}
}
