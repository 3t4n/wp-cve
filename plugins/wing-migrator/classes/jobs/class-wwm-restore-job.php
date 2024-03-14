<?php

class Wwm_Restore_Job extends Wwm_Job_Base {

	public static $job_name = 'wwm_restore_job';
	/** @var Wwm_Restore_Info */
	protected $wwm_info;

	protected static $tasks = array(
		'Wwm_Task_Restore_Download',
		'Wwm_Task_Restore_File',
		'Wwm_Task_Restore_Database',
		'Wwm_Task_Restore_Finish'
	);

	protected function init() {
		$wwm_info = new Wwm_Restore_Info();
		$this->wwm_info = $wwm_info;
		$this->logger = $wwm_info->get_logger();
	}

	protected function get_sync_first_task() {
		return Wwm_Task_Restore_Download::$task_name;
	}
}
