<?php

abstract class Owm_Task_Base {
	public static $task_name = null;
	public static $next_task_name = null;

	/** @var Owm_Job_Info */
	protected $job_info;
	/** @var Owm_Logger */
	protected $logger;

	public function get_job_info() {
		return $this->job_info;
	}

	abstract public function execute();

}

abstract class Owm_Backup_Task_Base extends Owm_Task_Base {
	/** @var Owm_Backup_Info */
	protected $owm_info;
	/** @var string */
	protected $backup_dir_path;
	/** @var string */
	protected $backup_file_name;

	/**
	 * Owm_Backup_Task_Base constructor.
	 * @param Owm_Backup_Info $info
	 */
	public function __construct( $info ) {
		$this->owm_info = $info;
		$this->job_info = $info->get_job_info();
		$this->logger = $info->get_logger();
		$this->backup_file_name = $this->owm_info->get_backup_file_name();
		$this->backup_dir_path = $this->owm_info->get_backup_dir_path();
	}

}


abstract class Owm_Restore_Task_Base extends Owm_Task_Base {
	/** @var Owm_Restore_Info */
	protected $owm_info;

	/**
	 * Owm_Restore_Task_Base constructor.
	 * @param Owm_Restore_Info $info
	 */
	public function __construct( $info ) {
		$this->owm_info = $info;
		$this->job_info = $info->get_job_info();
		$this->logger = $info->get_logger();
	}
}
