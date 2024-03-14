<?php

class Wwm_Backup_Action_Log extends Wwm_Migration_Action {
	public static $action_key = 'backup_log';

	public function do_action() {
		$info = new Wwm_Backup_Info();
		$log_data = null;
		if ( $info->get_backup_dir_path() !== null ) {
			$log_data = file_get_contents( $info->get_backup_dir_path() . DIRECTORY_SEPARATOR . 'backup.log' );
			if ( ! $log_data ) {
				$log_data = null;
			}
		}
		return array(
			'data' => $log_data
		);
	}
}
