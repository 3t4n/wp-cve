<?php

class Owm_Restore_Job extends Owm_Job_Base {

	public static $job_name = 'owm_restore_job';
	/** @var Owm_Restore_Info */
	protected $owm_info;

	protected static $tasks = array(
		'Owm_Task_Restore_Download',
		'Owm_Task_Restore_File',
		'Owm_Task_Restore_Database',
		'Owm_Task_Restore_Finish'
	);

	protected function init() {
		$owm_info = new Owm_Restore_Info();
		$this->owm_info = $owm_info;
		$this->logger = $owm_info->get_logger();
	}

	protected function get_sync_first_task() {
		return Owm_Task_Restore_Download::$task_name;
	}
}
