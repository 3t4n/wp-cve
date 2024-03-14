<?php

class Owm_Backup_Job extends Owm_Job_Base {
	public static $job_name = 'owm_backup_job';
	/** @var Owm_Backup_Info */
	protected $owm_info;

	protected static $tasks = array(
		'Owm_Task_Backup_Database',
		'Owm_Task_Backup_File',
		'Owm_Task_Backup_Finish'
	);

	protected function init() {
		$owm_info = new Owm_Backup_Info();
		$this->owm_info = $owm_info;
		$this->logger = $owm_info->get_logger();
	}

	protected function get_sync_first_task() {
		return Owm_Task_Backup_Database::$task_name;
	}
}
