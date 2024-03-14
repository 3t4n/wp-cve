<?php

abstract class Wwm_Task_Base {
	public static $task_name = null;
	public static $next_task_name = null;

	/** @var Wwm_Job_Info */
	protected $job_info;
	/** @var Wwm_Logger */
	protected $logger;

	public function get_job_info() {
		return $this->job_info;
	}

	abstract public function execute();

}

abstract class Wwm_Backup_Task_Base extends Wwm_Task_Base {
	/** @var Wwm_Backup_Info */
	protected $wwm_info;
	/** @var string */
	protected $backup_dir_path;
	/** @var string */
	protected $backup_file_name;

	/**
	 * Wwm_Backup_Task_Base constructor.
	 * @param Wwm_Backup_Info $info
	 */
	public function __construct( $info ) {
		$this->wwm_info = $info;
		$this->job_info = $info->get_job_info();
		$this->logger = $info->get_logger();
		$this->backup_file_name = $this->wwm_info->get_backup_file_name();
		$this->backup_dir_path = $this->wwm_info->get_backup_dir_path();
	}

}


abstract class Wwm_Restore_Task_Base extends Wwm_Task_Base {
	/** @var Wwm_Restore_Info */
	protected $wwm_info;

	/**
	 * Wwm_Restore_Task_Base constructor.
	 * @param Wwm_Restore_Info $info
	 */
	public function __construct( $info ) {
		$this->wwm_info = $info;
		$this->job_info = $info->get_job_info();
		$this->logger = $info->get_logger();
	}
}
